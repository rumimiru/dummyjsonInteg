<?php 
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

// Fetch products from API
$api_url = 'https://dummyjson.com/products';
$response = file_get_contents($api_url);
$products = json_decode($response, true)['products'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - DummyJSON App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #000000, #3f1211);
            min-height: 100vh;
            color: white;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            overflow-y: auto;
            position: relative;
        }

        /* Following Light Effect */
        #light {
            position: fixed;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255,255,255,0) 65%);
            pointer-events: none;
            z-index: 1;
            transform: translate(-50%, -50%);
            transition: all 0.08s ease-out;
            mix-blend-mode: screen;
        }

        /* Navbar: Styled to match dashboard.php exactly */
        .navbar {
            background-color: #000000;
            padding: 25px 80px; 
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 2.2rem; 
            color: white !important;
            text-decoration: none;
        }

        /* The transparent box container for links */
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

        /* Content Scaling */
        .container-fluid {
            position: relative;
            z-index: 2;
        }

        .page-title {
            text-align: center;
            font-size: 4rem;
            font-weight: 800;
            margin: 60px 0;
            text-transform: uppercase;
        }

        .products-grid {
            padding: 0 180px 180px 180px;
        }

        .product-card {
            background-color: #453333;
            color: #ffffff;
            border-radius: 12px;
            padding: 35px;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 35px rgba(78, 60, 60, 0.4);
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-10px);
        }

        .product-image {
            height: 280px;
            object-fit: contain;
            margin-bottom: 20px;
        }

        .product-info h5 {
            font-weight: 750;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .product-info p {
            font-size: 1.1rem;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>

    <div id="light"></div>

    <nav class="navbar">
        <a href="dashboard.php" class="navbar-brand">DummyJSON App</a>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="users.php">Users</a>
            <a href="posts.php">Posts</a>
            <a href="logout.php" class="logout-link">Logout</a>
        </div>
    </nav>

    <div class="container-fluid">
        <h2 class="page-title">PRODUCTS</h2>

        <div class="products-grid">
            <div class="row row-cols-1 row-cols-md-3 g-5">
                <?php foreach($products as $p): ?>
                <div class="col">
                    <div class="product-card">
                        <img src="<?= $p['thumbnail'] ?>" alt="<?= htmlspecialchars($p['title']) ?>" class="product-image">
                        <div class="product-info">
                            <h5><?= htmlspecialchars($p['title']) ?></h5>
                            <p><strong>Category:</strong> <?= ucfirst(htmlspecialchars($p['category'])) ?></p>
                            <p><strong>Price:</strong> $<?= number_format($p['price'], 2) ?></p>
                            <p><strong>Stock:</strong> <?= htmlspecialchars($p['stock']) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
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