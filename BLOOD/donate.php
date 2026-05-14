<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $blood_group = $_POST['blood_group'];
    $phone = $_POST['phone'];

    // Check if exact combination already exists
    $check_sql = "SELECT * FROM donors WHERE name = ? AND age = ? AND blood_group = ? AND phone = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("siss", $name, $age, $blood_group, $phone);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        $error = "This exact donor record already exists!";
    } else {
        // Insert new donor
        $sql = "INSERT INTO donors (name, age, blood_group, phone) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siss", $name, $age, $blood_group, $phone);

        if ($stmt->execute()) {
            header("Location: thanks.php");
            exit();
        } else {
            $error = "Error registering donor";
        }
        $stmt->close();
    }
    $check_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Registration</title>
    <style>
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(calc(-20px * var(--direction, 1)));
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
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
            min-height: 100dvh;
            background-image: url('images/blood-bg.jpg');
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
            animation: fadeIn 1s var(--animation-timing),
                       containerGlow 3s infinite;
            backdrop-filter: blur(var(--container-blur));
            -webkit-backdrop-filter: blur(var(--container-blur));
            box-shadow: 0 0 20px var(--shadow-color),
                       0 10px 20px -10px var(--shadow-color);
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

        .container::after {
            filter: blur(5px);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            position: relative;
            width: 100%;
        }

        .back-btn {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            padding: 10px 20px;
            font-size: 1rem;
            text-decoration: none;
            background-color: var(--primary-color);
            color: white;
            border-radius: 8px;
            transition: all 0.3s var(--animation-timing);
        }

        .back-btn:hover,
        .back-btn:focus-visible {
            background-color: var(--primary-dark);
            transform: translateY(calc(-50% - 2px));
            box-shadow: 0 6px 20px var(--glow-color),
                       0 0 0 2px var(--primary-color);
        }

        .back-btn:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px white,
                       0 0 0 6px var(--primary-color);
        }

        h1 {
            color: var(--text-color);
            margin-bottom: 0;
            animation: slideIn 1s var(--animation-timing);
            font-size: clamp(1.5rem, 3vw, 2rem);
            text-shadow: 2px 2px 4px var(--shadow-color);
            line-height: 1.4;
            letter-spacing: 0.5px;
            text-align: center;
            width: 100%;
            padding-right: 60px;
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
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid transparent;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s var(--animation-timing);
            background-color: var(--input-bg);
            color: var(--text-color);
        }

        input:hover, select:hover {
            border-color: var(--primary-color);
        }

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
            font-size: clamp(1rem, 2vw, 1.125rem);
            cursor: pointer;
            transition: all 0.3s var(--animation-timing);
            animation: fadeIn 1s var(--animation-timing);
            animation-delay: 1s;
            animation-fill-mode: both;
            box-shadow: 0 4px 15px var(--glow-color);
            width: 100%;
            max-width: 200px;
            margin: 20px auto 0;
            display: block;
            position: relative;
            overflow: hidden;
        }

        .btn:hover,
        .btn:focus-visible {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--glow-color),
                       0 0 0 2px var(--primary-color);
        }

        .btn:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px white,
                       0 0 0 6px var(--primary-color);
        }

        .btn::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transform: rotate(45deg);
            transition: 0.5s var(--animation-timing);
            opacity: 0;
        }

        .btn:hover::after {
            opacity: 1;
            transform: rotate(45deg) translate(50%, 50%);
        }

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

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }
            
            input, select {
                font-size: 16px; /* Prevents zoom on mobile */
            }
        }

        @media (forced-colors: active) {
            .btn {
                border: 2px solid ButtonText;
            }
            .btn:focus-visible {
                outline: 2px solid ButtonText;
            }
            input, select {
                border: 1px solid ButtonText;
            }
        }

        @media print {
            body {
                background: none;
            }
            .container {
                box-shadow: none;
                border: 1px solid #000;
            }
            .btn {
                border: 1px solid #000;
                color: #000;
            }
            input, select {
                border: 1px solid #000;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-container">
            <h1>DONOR REGISTRATION</h1>
            <a href="index.html" class="back-btn">BACK</a>
        </div>
        <?php if (isset($error)): ?>
            <div class="error" role="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="name">NAME</label>
                <input type="text" id="name" name="name" required 
                       pattern="[A-Za-z\s]+" title="Please enter a valid name (letters and spaces only)">
            </div>
            <div class="form-group">
                <label for="age">AGE</label>
                <input type="number" id="age" name="age" min="18" max="65" required
                       title="Age must be between 18 and 65">
            </div>
            <div class="form-group">
                <label for="blood_group">BLOOD GROUP</label>
                <select id="blood_group" name="blood_group" required>
                    <option value="">Select Blood Group</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                </select>
            </div>
            <div class="form-group">
                <label for="phone">PHONE</label>
                <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required
                       title="Please enter a 10-digit phone number">
            </div>
            <button type="submit" class="btn">REGISTER</button>
        </form>
    </div>

    <script>
        function validateForm() {
            var phone = document.getElementById('phone').value;
            var age = document.getElementById('age').value;
            var name = document.getElementById('name').value;

            if (!/^[A-Za-z\s]+$/.test(name)) {
                alert('Please enter a valid name (letters and spaces only)');
                return false;
            }

            if (phone.length !== 10 || isNaN(phone)) {
                alert('Please enter a valid 10-digit phone number');
                return false;
            }

            if (age < 18 || age > 65) {
                alert('Age must be between 18 and 65');
                return false;
            }

            return true;
        }
    </script>
</body>
</html> 