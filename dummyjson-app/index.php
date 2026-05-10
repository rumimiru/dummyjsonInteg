<?php

require_once 'includes/auth.php';

if (isLoggedIn()) {

    header("Location: dashboard.php");

    exit();

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DummyJSON API Web App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/style.css">

    <style>

        .main-container {

            background-color: #f5f0e6;

            border-radius: 20px;

            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);

            padding: 70px 60px;           /* More space */

            text-align: center;

            max-width: 580px;

            width: 100%;

        }

        .school-logos img {

            height: 50px;

            margin-bottom: 5px;

        }

        h1 {

            font-size: 2.9rem;

        }

        .btn-login, .btn-register {

            padding: 16px 50px;

            font-size: 1.25rem;

        }

    </style>

</head>

<body>



    <!-- Following Light -->

    <div id="light"></div>



    <div class="main-container">

        <!-- School Logo -->

        <div class="school-logos mb-4">

            <img src="Logo1.png" alt="School Logo">

        </div>



        <h5 class="text-uppercase mb-4" style="letter-spacing: 2px;">

            SCHOOL OF INFORMATION TECHNOLOGY EDUCATION

        </h5>



        <hr class="my-4">



        <h1>DummyJSON API Web App</h1>

        <p class="subtitle">PHP + MySQL + API Integration System</p>

        <h4 class="mb-5">Final Project</h4>



        <!-- Buttons -->

        <div class="d-flex justify-content-center gap-4 flex-wrap">

            <a href="login.php" class="btn btn-login" id="loginBtn">LOGIN</a>

            <a href="register.php" class="btn btn-register">REGISTER</a>

        </div>



        <!-- Developers -->
        <div class="devs mt-5">
            <strong>Devs:</strong><br>
            Mharx Nevin D. Malbog<br>
            Francine Nicole De Guzman
        </div>
    </div>



    <script>
        // Smooth Mouse Following Light
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

