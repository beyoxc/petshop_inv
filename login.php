<?php
session_start();
include 'config/database.php';

$error = '';

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users 
              WHERE username='$username' 
              AND password='$password'";

    $result = mysqli_query($sales_conn, $query);

    if (mysqli_num_rows($result) > 0) {

        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();

    } else {
        $error = 'Invalid credentials. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Just 4 Paws</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('assets/images/image.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(3px);
            z-index: 1;
        }

        .logo-display {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
            padding: 40px;
        }

        .logo-display-container {
            text-align: center;
        }

        .logo-display svg {
            width: 200px;
            height: 200px;
            margin-bottom: 20px;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.3));
            animation: slideDown 0.8s ease-out;
        }

        .logo-image {
            max-width: 400px;
            width: 100%;
            height: auto;
            margin-bottom: 20px;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.3));
            animation: slideDown 0.8s ease-out;
        }

        .logo-display h2 {
            display: none;
        }

        .logo-display p {
            display: none;
        }

        .login-background {
            display: none;
        }

        .login-background::after {
            display: none;
        }

        .login-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 24px;
            padding: 40px;
            position: relative;
            z-index: 2;
            width: 100%;
            min-height: 100vh;
        }

        .login-card {
            width: 100%;
            max-width: 380px;
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            border: none;
            box-shadow: 0 0 40px rgba(209, 51, 138, 0.5), 0 0 80px rgba(209, 51, 138, 0.3), 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .logo-section {
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
            animation: slideDown 0.6s ease-out;
        }

        .logo-section img {
            width: 360px;
            max-width: 100%;
            height: auto;
            border-radius: 0;
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.18);
        }

        .logo-container {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #d1338a 0%, #a51d6e 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(209, 51, 138, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .logo-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 40px rgba(209, 51, 138, 0.35);
        }

        .logo-container svg {
            width: 100%;
            height: 100%;
        }

        .login-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .login-header p {
            font-size: 13px;
            color: #666;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 18px;
            animation: slideUp 0.6s ease-out both;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.15s; }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #d1338a;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            color: #1a1a1a;
            background: #f9f9f9;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input::placeholder {
            color: #999;
        }

        .form-input:hover {
            border-color: #d1338a;
            background: #fff;
        }

        .form-input:focus {
            border-color: #d1338a;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(209, 51, 138, 0.08);
        }

        .error-message {
            display: none;
            padding: 10px 12px;
            background: rgba(220, 38, 38, 0.08);
            border: 1px solid rgba(220, 38, 38, 0.2);
            border-radius: 6px;
            color: #dc2626;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
            animation: shake 0.4s ease-in-out;
        }

        .error-message.show {
            display: block;
        }

        .login-button {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #d1338a 0%, #a51d6e 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 16px rgba(209, 51, 138, 0.28);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            animation: slideUp 0.6s ease-out 0.25s both;
            margin-top: 20px;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(209, 51, 138, 0.38);
        }

        .login-button:active {
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(209, 51, 138, 0.28);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        @media (max-width: 1024px) {
            body {
                background-image: none;
                background: linear-gradient(135deg, #d1338a 0%, #a51d6e 100%);
                justify-content: center;
            }

            body::before {
                backdrop-filter: blur(0);
                background: transparent;
            }

            .logo-display {
                display: none;
            }
        }

        @media (min-width: 1024px) {
            .login-background {
                display: none;
            }
        }

        @media (max-width: 640px) {
            .login-header h1 {
                font-size: 20px;
            }

            .login-button {
                padding: 11px;
                font-size: 13px;
            }

            .login-wrapper {
                padding: 24px;
            }

            .login-card {
                max-width: 100%;
                padding: 30px;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="logo-section">
            <img src="assets/images/image%20copy%202.png" alt="Just 4 Paws Logo">
        </div>
        <div class="login-card">
            <div class="login-header">
                <h1>Sign In</h1>
                <p>Enter your credentials</p>
            </div>

            <form method="POST" onsubmit="handleSubmit(event)">
                <?php if ($error): ?>
                    <div class="error-message show"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text"
                           name="username"
                           class="form-input"
                           placeholder="Enter username"
                           required
                           autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password"
                           name="password"
                           class="form-input"
                           placeholder="Enter password"
                           required>
                </div>

                <button type="submit"
                        name="login"
                        class="login-button">
                    Sign In
                </button>
            </form>
        </div>
    </div>

    <script>
        function handleSubmit(event) {
            const username = document.querySelector('input[name="username"]').value;
            const password = document.querySelector('input[name="password"]').value;
            
            if (!username || !password) {
                event.preventDefault();
            }
        }
    </script>
</body>
</html>