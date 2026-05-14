<?php
require_once 'config.php';

$donors = [];
if (isset($_POST['blood_group']) && !empty($_POST['blood_group'])) {
    $blood_group = $_POST['blood_group'];
    $sql = "SELECT * FROM donors WHERE blood_group = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $blood_group);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $donors[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Blood</title>
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
            --table-header-bg: #ff0000;
            --table-row-hover: rgba(255, 255, 255, 0.9);
            --table-bg: rgba(255, 255, 255, 0.8);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --text-color: #fff;
                --container-bg: rgba(0, 0, 0, 0.6);
                --shadow-color: rgba(255, 255, 255, 0.1);
                --table-bg: rgba(0, 0, 0, 0.3);
                --table-row-hover: rgba(0, 0, 0, 0.5);
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

        @keyframes tableRowFade {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
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
            width: min(90%, 800px);
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
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
            position: relative;
            width: 100%;
        }

        .back-btn {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            padding: 10px 20px;
            font-size: 1rem;
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
            padding: 0 60px;
        }

        select {
            width: 200px;
            padding: 12px;
            border: 2px solid transparent;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s var(--animation-timing);
            background-color: var(--table-bg);
            color: var(--text-color);
            margin-bottom: 20px;
            cursor: pointer;
        }

        select:hover {
            border-color: var(--primary-color);
        }

        select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--glow-color);
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
            margin: 10px;
            text-decoration: none;
            display: inline-block;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: var(--table-bg);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px var(--shadow-color);
        }

        th, td {
            padding: 12px;
            text-align: left;
            color: var(--text-color);
        }

        th {
            background-color: var(--table-header-bg);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        tr {
            transition: background-color 0.3s var(--animation-timing);
        }

        tr:nth-child(even) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        tr:hover {
            background-color: var(--table-row-hover);
        }

        tbody tr {
            animation: tableRowFade 0.5s var(--animation-timing) backwards;
        }

        tbody tr:nth-child(1) { animation-delay: 0.1s; }
        tbody tr:nth-child(2) { animation-delay: 0.2s; }
        tbody tr:nth-child(3) { animation-delay: 0.3s; }
        tbody tr:nth-child(4) { animation-delay: 0.4s; }
        tbody tr:nth-child(5) { animation-delay: 0.5s; }

        @media (max-width: 600px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            th, td {
                padding: 8px;
                font-size: 0.9rem;
            }
        }

        @media (forced-colors: active) {
            .btn {
                border: 2px solid ButtonText;
            }
            .btn:focus-visible {
                outline: 2px solid ButtonText;
            }
            select {
                border: 1px solid ButtonText;
            }
            table {
                border: 1px solid ButtonText;
            }
            th, td {
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
            select {
                border: 1px solid #000;
            }
            table {
                border: 1px solid #000;
            }
            th {
                background-color: #fff;
                color: #000;
                border: 1px solid #000;
            }
            td {
                border: 1px solid #000;
            }
        }

        .search-container {
            display: flex;
            gap: 15px;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
        }

        select {
            margin-bottom: 0;
        }

        @media (max-width: 600px) {
            .header-container {
                margin-bottom: 20px;
            }

            .search-container {
                flex-direction: column;
                gap: 10px;
            }

            .back-btn {
                padding: 8px 16px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-container">
            <a href="index.html" class="btn back-btn">🏠</a>
            <h1>REQUEST BLOOD</h1>
        </div>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="search-container">
                <select name="blood_group" required>
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
                <button type="submit" class="btn">SEARCH</button>
            </div>
        </form>

        <?php if (!empty($donors)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Blood Group</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($donors as $donor): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($donor['name']); ?></td>
                            <td><?php echo htmlspecialchars($donor['age']); ?></td>
                            <td><?php echo htmlspecialchars($donor['blood_group']); ?></td>
                            <td><?php echo htmlspecialchars($donor['phone']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (isset($_POST['blood_group'])): ?>
            <p style="text-align: center; color: var(--text-color); margin-top: 20px;">
                No donors found for the selected blood group.
            </p>
        <?php endif; ?>
    </div>
</body>
</html> 