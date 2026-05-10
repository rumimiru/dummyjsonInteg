<?php 
require_once 'includes/auth.php';
require_once 'config/database.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Just trim the input; prepared statements handle the security
    $login = trim($_POST['login']);
    $password = $_POST['password'];

    // Using MySQLi syntax with your $conn variable
    $query = "SELECT * FROM users WHERE email = ? OR username = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $login, $login);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid username/email or password.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "Database error: Could not prepare statement.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DummyJSON API Web App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #000000, #3f1211);
            min-height: 100vh;
            margin: 0;
            display: flex;
            font-family: 'Segoe UI', sans-serif;
            overflow: hidden;
            position: relative;
        }

        #light {
            position: fixed;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255,255,255,0) 70%);
            pointer-events: none;
            z-index: 1;
            transform: translate(-50%, -50%);
            transition: all 0.08s ease-out;
            mix-blend-mode: screen;
        }

        .split-wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
            z-index: 2;
        }

        /* LEFT SIDE - Larger focus */
        .split-left {
            width: 74%; 
            background-color: #000000;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background-color: #ffffff;
            padding: 50px 80px; /* Increased padding */
            width: 600px;       /* Made wider */
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }

        .login-card h2 {
            font-weight: 900;
            margin-bottom: 20px;
            font-size: 2.8rem;
            color: #000;
        }

        .form-group {
            text-align: left;
            margin-bottom: 10px;
        }

        .form-group label {
            font-family: "Segoe UI", sans-serif;
            font-size: 1.1rem;
            font-weight: bold;
            display: block;
            margin-bottom: 3px;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 3px solid #000; /* Bolder border */
            font-family: "Segoe UI", sans-serif;
            font-size: 1.1rem;
            outline: none;
            border-radius: 0;
        }

        .btn-login-submit {
            background-color: #382c2b;
            color: white;
            border: none;
            padding: 16px 55px;
            border-radius: 40px;
            font-weight: bold;
            font-size: 1.4rem;
            margin-top: 26px;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .btn-login-submit:hover {
            background-color: #138834;
            transform: scale(1.05);
        }

        /* RIGHT SIDE */
        .split-right {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .info-card {
            background-color: #f5f0e6;
            padding: 80px 50px;
            border-radius: 20px;
            text-align: center;
            max-width: 480px; 
            width: 100%;
            cursor: pointer;
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        .info-card:hover {
            transform: scale(1.08);
        }

        .school-logos img {
            height: 45px;
            margin-bottom: 5px;
        }

        .info-card h1 {
            font-size: 2.0rem;
            font-weight: bold;
            color: #2c2c2c;
        }

        .register-link a {
            color: #244aa2;
            font-weight: 900;
            text-decoration: underline;
            font-size: 1.5rem;
            display: block;
            margin-top: 1.2px;
        }
    </style>
</head>
<body>

    <div id="light"></div>

    <div class="split-wrapper">
        <div class="split-left">
            <div class="login-card">
                <h2>LOGIN</h2>

                <?php if ($error): ?>
                    <div class="alert alert-danger py-2 mb-4" style="border-radius: 0;"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>EMAIL</label>
                        <input type="text" name="login" placeholder="Type your email" required>
                    </div>
                    <div class="form-group">
                        <label>PASSWORD</label>
                        <input type="password" name="password" placeholder="Type your password" required>
                    </div>
                    <button type="submit" class="btn-login-submit">LOGIN</button>
                </form>

                <div class="register-link mt-5" style="font-family: 'Segoe UI' sans-serif;">
                    Don't have an account yet?<br>
                    <a href="register.php" style="font-family: 'Segoe UI', sans-serif;">REGISTER NOW</a>
                </div>
            </div>
        </div>

        <div class="split-right">
            <div class="info-card" onclick="window.location.href='index.php'">
                <div class="school-logos mb-3">
                    <img src="Logo1.png" alt="Logo">
                </div>
                <h6 class="text-uppercase mb-3" style="letter-spacing: 1px; font-size: 0.75rem; opacity: 0.8;">
                    SCHOOL OF INFORMATION TECHNOLOGY EDUCATION
                </h6>
                <hr class="my-4">
                <h1>DummyJSON API Web App</h1>
                <p style="color: #555; font-size: 0.9rem;">PHP + MySQL + API Integration System</p>
                <h5 class="mb-4">Final Project</h5>
                <div class="devs" style="font-size: 0.85rem;">
                    <strong>Devs:</strong><br>
                    Mharx Nevin D. Malbog<br>
                    Francine Nicole De Guzman<br><br>
                    <strong>Instructor:</strong><br>
                    Sir Pogi Jelson Lanto MIT
                </div>
            </div>
        </div>
    </div>

    <script>
        const light = document.getElementById('light');
        let currentX = 0;
        let currentY = 0;

        document.addEventListener('mousemove', (e) => {
            currentX = e.clientX;
            currentY = e.clientY;
        });

        function animateLight() {
            light.style.left = currentX + 'px';
            light.style.top = currentY + 'px';
            requestAnimationFrame(animateLight);
        }
        animateLight();
    </script>
</body>
</html>