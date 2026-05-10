<?php 
require_once 'includes/auth.php';
require_once 'config/database.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $username  = trim($_POST['username']);
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];

    if (empty($full_name) || empty($email) || empty($username) || empty($password)) {
        $errors[] = "All fields are required.";
    } elseif ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    } else {
        $query = "SELECT id FROM users WHERE email = ? OR username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $email, $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors[] = "Email or Username already exists.";
        }
        mysqli_stmt_close($stmt);
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $insert_query = "INSERT INTO users (full_name, email, username, password) VALUES (?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "ssss", $full_name, $email, $username, $hash);
        
        if (mysqli_stmt_execute($insert_stmt)) {
            $success = "Registration successful. You may now login.";
        } else {
            $errors[] = "Failed to register. Please try again.";
        }
        mysqli_stmt_close($insert_stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DummyJSON API Web App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #000000, #3f1211);
            min-height: 100vh;
            margin: 0.5;
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

        /* LEFT SIDE - Matching Login (74% width) */
        .split-left {
            width: 74%; 
            background-color: #000000;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-card {
            background-color: #ffffff;
            padding: 40px 80px; /* Matched to Login padding */
            width: 600px;       /* Matched to Login width */
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            max-height: 95vh;
            overflow-y: auto;
            border-radius: 0;
        }

        /* Hide scrollbar for cleaner look but keep scroll functional */
        .register-card::-webkit-scrollbar { display: none; }

        .register-card h2 {
            font-weight: 820;
            margin-top: 50; 
            margin-bottom: 32px;
            font-size: 2.5rem;
            color: #000;
        }

        .form-group {
            text-align: left;
            margin-bottom: 12px;
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
            padding: 12px;
            border: 3px solid #000;
            font-family: "Segoe UI", sans-serif;
            font-size: 1rem;
            outline: none;
            border-radius: 0;
        }

        .btn-register-submit {
            background-color: #382c2b;
            color: white;
            border: none;
            padding: 16px 55px;
            border-radius: 40px;
            font-weight: bold;
            font-size: 1.4rem;
            margin-top: 20px;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .btn-register-submit:hover {
            background-color: #218838;
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

        .info-card:hover { transform: scale(1.08); }
        .school-logos img { height: 45px; margin-bottom: 5px; }
        .info-card h1 { font-size: 2.0rem; font-weight: bold; color: #2c2c2c; }

        .login-link a {
            color: #244aa2;
            font-weight: 900;
            text-decoration: underline;
            font-size: 1.5rem;
            display: block;
            margin-top: 0.8px;
        }
    </style>
</head>
<body>

    <div id="light"></div>

    <div class="split-wrapper">
        <div class="split-left">
            <div class="register-card">
                <h2>REGISTER</h2>

                <?php if ($success): ?>
                    <div class="alert alert-success py-2 mb-4" style="border-radius: 0;"><?= $success ?></div>
                    <a href="login.php" class="btn-register-submit d-inline-block text-decoration-none">GO TO LOGIN</a>
                <?php else: ?>
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger py-2 mb-3" style="border-radius: 0; font-size: 0.9rem;">
                            <?php foreach($errors as $err) echo $err . "<br>"; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="form-group">
                            <label>FULL NAME</label>
                            <input type="text" name="full_name" placeholder="Type your full name" required>
                        </div>
                        <div class="form-group">
                            <label>EMAIL</label>
                            <input type="email" name="email" placeholder="Type your email" required>
                        </div>
                        <div class="form-group">
                            <label>USERNAME</label>
                            <input type="text" name="username" placeholder="Type your username" required>
                        </div>
                        <div class="form-group">
                            <label>PASSWORD</label>
                            <input type="password" name="password" placeholder="Type your password" required>
                        </div>
                        <div class="form-group">
                            <label>CONFIRM PASSWORD</label>
                            <input type="password" name="confirm_password" placeholder="Re-type password" required>
                        </div>
                        <button type="submit" class="btn-register-submit">REGISTER</button>
                    </form>
                <?php endif; ?>

                <div class="login-link mt-5">
                    Already have an account?<br>
                    <a href="login.php">LOGIN</a>
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
        let currentX = 0; let currentY = 0;
        document.addEventListener('mousemove', (e) => {
            currentX = e.clientX; currentY = e.clientY;
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