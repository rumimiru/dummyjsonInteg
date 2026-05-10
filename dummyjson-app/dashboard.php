<?php 
require_once 'includes/auth.php';
redirectIfNotLoggedIn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DummyJSON App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #000000, #3f1211);
            min-height: 100vh;
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            overflow-x: hidden; /* Prevent light from causing scroll */
            position: relative; /* Base for light centering */
        }

        /* Following Light Effect from index.php */
        #light {
            position: fixed;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.18) 0%, rgba(255,255,255,0) 65%);
            pointer-events: none;
            z-index: 1; /* Below the dashboard content */
            transform: translate(-50%, -50%); /* Centered on cursor */
            transition: all 0.08s ease-out; /* Smoother movement */
            mix-blend-mode: screen;
            opacity: 0.9;
        }

        /* Navbar scaling for larger screens */
        .navbar {
            background-color: #000000;
            padding: 25px 80px; 
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            position: relative;
            z-index: 2; /* Keeps nav above the light */
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 2.2rem; 
            color: white;
            text-decoration: none;
            pointer-events: none;
        }

        .nav-links {
            background-color: rgba(255, 255, 255, 0.08);
            padding: 12px 40px;
            display: flex;
            gap: 35px;
            border-radius: 4px;
        }

        .nav-links a {
            color: #adb5bd;
            text-decoration: none;
            font-size: 1.2rem;
            transition: 0.3s;
        }

        .nav-links a:hover { color: white; }
        .nav-links a.logout-link { color: #dc3545; }

        /* Main Dashboard scaling */
        .content-wrapper {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-bottom: 10vh; 
            position: relative;
            z-index: 2; /* Keeps content above the light */
        }

        .hero-section {
            text-align: center;
            max-width: 1200px;
            width: 90%;
        }

        .hero-section h1 {
            font-size: 5rem; 
            font-weight: 300;
            margin-bottom: 20px;
        }

        .hero-section h1 span {
            font-weight: 700;
        }

        .hero-section p {
            font-size: 2.8rem;
            color: #ffffff;
            margin-bottom: 70px;
            opacity: 0.9;
        }

        /* Scaled Pill Buttons */
        .btn-container {
            display: flex;
            justify-content: center;
            gap: 40px;
        }

        .btn-custom {
            background-color: #382c2b;
            color: white;
            border: none;
            padding: 18px 60px; 
            border-radius: 60px;
            font-weight: bold;
            font-size: 1.4rem;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(0,0,0,0.4);
        }

        .btn-custom:hover {
            background-color: #0f710f;
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.6);
        }
    </style>
</head>
<body>

    <div id="light"></div>

    <nav class="navbar">
        <a href="dashboard.php" class="navbar-brand">DummyJSON App</a>
        <div class="nav-links">
            <a href="products.php">Products</a>
            <a href="users.php">Users</a>
            <a href="posts.php">Posts</a>
            <a href="logout.php" class="logout-link">Logout</a>
        </div>
    </nav>

    <div class="content-wrapper">
        <div class="hero-section">
            <h1>Welcome, <span><?= htmlspecialchars($_SESSION['full_name']) ?>!</span></h1>
            <p>Explore DummyJSON API Data</p>

            <div class="btn-container">
                <a href="products.php" class="btn-custom">Products</a>
                <a href="users.php" class="btn-custom">Users & Carts</a>
                <a href="posts.php" class="btn-custom">Posts</a>
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
        
        // Start the animation loop
        animateLight();
    </script>

</body>
</html>