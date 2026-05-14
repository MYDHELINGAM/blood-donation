<?php
// donate.php

require_once 'config.php';  // Your DB connection setup in config.php

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $dob = $_POST['dob'];
    $blood_group = $_POST['blood_group'];
    $phone = trim($_POST['phone']);

    // Validate age server-side
    $dobDate = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($dobDate)->y;

    if ($age < 16 || $age > 65) {
        $error = "Your age must be between 16 and 65 to donate blood.";
    } else {
        // Check if exact donor exists
        $check_sql = "SELECT * FROM donors WHERE name = ? AND dob = ? AND blood_group = ? AND phone = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ssss", $name, $dob, $blood_group, $phone);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "This exact donor record already exists!";
        } else {
            // Insert new donor
            $sql = "INSERT INTO donors (name, dob, blood_group, phone) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $name, $dob, $blood_group, $phone);

            if ($stmt->execute()) {
                $success = "Thank you for registering as a blood donor!";
                // Clear form fields after success
                $name = $dob = $blood_group = $phone = "";
            } else {
                $error = "Error registering donor: " . $stmt->error;
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Blood Donation Registration</title>
<style>
/* Paste your CSS here, or link your stylesheet */
:root {
    --primary-color: #ff0000;
    --primary-dark: #cc0000;
    --text-color: #333;
    --container-bg: rgba(255, 255, 255, 0.6);
    --animation-timing: cubic-bezier(0.4, 0, 0.2, 1);
    --container-blur: 5px;
    --shadow-color: rgba(0, 0, 0, 0.1);
    --glow-color: rgba(255, 0, 0, 0.2);
    --input-bg: rgba(255, 255, 255, 0.9);
    --error-color: #ff0000;
    --error-bg: rgba(255, 0, 0, 0.1);
}
@media (prefers-color-scheme: dark) {
    :root {
        --text-color: #fff;
        --container-bg: rgba(0, 0, 0, 0.6);
        --shadow-color: rgba(255, 255, 255, 0.1);
        --input-bg: rgba(0, 0, 0, 0.3);
    }
}
* { margin: 0; padding: 0; box-sizing: border-box; }
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(calc(-20px * var(--direction, 1))); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes slideIn {
    from { transform: translateX(-100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
@keyframes containerGlow {
    0%, 100% { box-shadow: 0 0 10px var(--glow-color); }
    50% { box-shadow: 0 0 20px var(--glow-color); }
}
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
body {
    min-height: 100vh;
    background-image: url('back(2).jpeg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    display: grid;
    place-items: center;
    font-family: system-ui, -apple-system, sans-serif;
    overflow-x: hidden;
    position: relative;
    padding: 20px;
}
.container {
    background-color: var(--container-bg);
    padding: clamp(20px, 5vw, 40px);
    border-radius: 15px;
    width: min(90%, 500px);
    animation: fadeIn 1s var(--animation-timing), containerGlow 3s infinite;
    backdrop-filter: blur(var(--container-blur));
    -webkit-backdrop-filter: blur(var(--container-blur));
    box-shadow: 0 0 20px var(--shadow-color), 0 10px 20px -10px var(--shadow-color);
    isolation: isolate;
    position: relative;
}
.container::before,
.container::after {
    content: '';
    position: absolute;
    inset: -2px;
    border-radius: 17px;
    background: linear-gradient(45deg, var(--primary-color), transparent);
    z-index: -1;
    opacity: 0.3;
    animation: containerGlow 3s infinite alternate;
}
.container::after { filter: blur(5px); }
.back-btn {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 8px 15px;
    font-size: 0.9rem;
    text-decoration: none;
    background-color: var(--primary-color);
    color: #fff;
    border-radius: 6px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px var(--glow-color);
    z-index: 1;
}
.back-btn:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px var(--glow-color);
}
.back-btn:focus {
    outline: none;
    box-shadow: 0 0 0 3px white, 0 0 0 6px var(--primary-color);
}
h1 {
    color: var(--text-color);
    margin-bottom: 20px;
    animation: slideIn 1s var(--animation-timing);
    font-size: clamp(1.5rem, 3vw, 2rem);
    text-shadow: 2px 2px 4px var(--shadow-color);
    text-align: center;
}
.form-group {
    margin-bottom: 20px;
    text-align: left;
    animation: fadeIn 1s var(--animation-timing);
    animation-fill-mode: both;
}
.form-group:nth-child(1) { animation-delay: 0.2s; }
.form-group:nth-child(2) { animation-delay: 0.4s; }
.form-group:nth-child(3) { animation-delay: 0.6s; }
.form-group:nth-child(4) { animation-delay: 0.8s; }
label {
    display: block;
    margin-bottom: 8px;
    color: var(--text-color);
    font-weight: 600;
}
input, select {
    width: 100%;
    padding: 12px;
    border: 2px solid transparent;
    border-radius: 8px;
    background-color: var(--input-bg);
    color: var(--text-color);
    font-size: 1rem;
    transition: all 0.3s var(--animation-timing);
}
select { color: brown; background-color: #f5f0e6; }
select option { color: brown; background-color: #fff5e6; }
input:hover, select:hover { border-color: var(--primary-color); }
input:focus, select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px var(--glow-color);
}
input:invalid, select:invalid {
    border-color: var(--error-color);
    animation: shake 0.3s var(--animation-timing);
}
.btn {
    background-color: var(--primary-color);
    color: white;
    padding: 15px 30px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s var(--animation-timing);
    animation: fadeIn 1s var(--animation-timing);
    animation-delay: 1s;
    animation-fill-mode: both;
    box-shadow: 0 4px 15px var(--glow-color);
    width: 100%;
    margin-top: 20px;
    display: block;
    position: relative;
    overflow: hidden;
}
.btn:hover { background-color: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 6px 20px var(--glow-color), 0 0 0 2px var(--primary-color); }
.error {
    color: var(--error-color);
    margin: 20px 0;
    padding: 10px;
    border-radius: 8px;
    background-color: var(--error-bg);
    animation: fadeIn 0.5s var(--animation-timing);
    font-weight: 500;
    text-align: center;
}
.success {
    color: green;
    margin: 20px 0;
    padding: 10px;
    border-radius: 8px;
    background-color: #d4edda;
    animation: fadeIn 0.5s var(--animation-timing);
    font-weight: 500;
    text-align: center;
}
</style>
</head>
<body>
<div class="container">
    <a href="index.html" class="back-btn">Back</a>

    <h1>Blood Donation Registration</h1>

    <?php if (!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php elseif (!empty($success)): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="post" onsubmit="return validateForm();">
        <div class="form-group">
            <label for="name">NAME</label>
            <input type="text" id="name" name="name" pattern="[A-Za-z\s]+" title="Please enter letters only" required
                value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="dob">DATE OF BIRTH</label>
            <input type="date" id="dob" name="dob" required title="Please enter your date of birth"
                value="<?php echo isset($dob) ? htmlspecialchars($dob) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="blood_group">BLOOD GROUP</label>
            <select id="blood_group" name="blood_group" required>
                <option value="" disabled <?php echo !isset($blood_group) ? 'selected' : ''; ?>>Select Blood Group</option>
                <?php
                $groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                foreach ($groups as $group) {
                    $selected = (isset($blood_group) && $blood_group === $group) ? 'selected' : '';
                    echo "<option value=\"$group\" $selected>$group</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="phone">PHONE</label>
            <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" title="Enter a 10-digit phone number" required
                value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
        </div>
        <button type="submit" class="btn">REGISTER</button>
    </form>
</div>

<script>
function validateForm() {
    const dobInput = document.getElementById('dob').value;
    if (!dobInput) return false;

    const dob = new Date(dobInput);
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const m = today.getMonth() - dob.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
        age--;
    }

    if (age < 16 || age > 65) {
        alert("Your age must be between 16 and 65 to donate blood.");
        return false;
    }
    return true;
}
</script>

</body>
</html>
