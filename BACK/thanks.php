<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
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
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --text-color: #fff;
                --container-bg: rgba(0, 0, 0, 0.6);
                --shadow-color: rgba(255, 255, 255, 0.1);
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

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
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
            text-align: center;
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

        h1 {
            color: var(--text-color);
            margin-bottom: clamp(20px, 5vw, 30px);
            animation: slideIn 1s var(--animation-timing);
            font-size: clamp(1.5rem, 3vw, 2rem);
            text-shadow: 2px 2px 4px var(--shadow-color);
            line-height: 1.4;
            letter-spacing: 0.5px;
        }

        p {
            color: var(--text-color);
            margin-bottom: 30px;
            animation: fadeIn 1.5s var(--animation-timing);
            font-size: clamp(1rem, 2vw, 1.125rem);
            line-height: 1.6;
        }

        .heart-icon {
            color: var(--primary-color);
            font-size: 2em;
            display: inline-block;
            margin: 20px 0;
            animation: heartbeat 1.5s infinite;
        }

        .btn {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: clamp(1rem, 2vw, 1.125rem);
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s var(--animation-timing);
            animation: fadeIn 2s var(--animation-timing);
            box-shadow: 0 4px 15px var(--glow-color);
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

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }
        }

        @media (forced-colors: active) {
            .btn {
                border: 2px solid ButtonText;
            }
            .btn:focus-visible {
                outline: 2px solid ButtonText;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thanks for Donating</h1>
        <div class="heart-icon">❤</div>
        <p>Your contribution will help save lives!</p>
        <a href="index.html" class="btn" role="button">BACK</a>
    </div>
</body>
</html> 