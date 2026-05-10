<?php 
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

// Get user info
$all_users = json_decode(file_get_contents('https://dummyjson.com/users'), true)['users'];
$user = null;
foreach ($all_users as $u) {
    if ($u['id'] == $user_id) {
        $user = $u;
        break;
    }
}

// Get cart
$carts_data = json_decode(file_get_contents('https://dummyjson.com/carts'), true)['carts'];
$user_cart = null;
foreach ($carts_data as $cart) {
    if ($cart['userId'] == $user_id) {
        $user_cart = $cart;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Cart - DummyJSON App</title>
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

        /* Navbar identical to dashboard.php */
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

        /* Content Styling */
        .container {
            position: relative;
            z-index: 2;
            padding-top: 60px;
            padding-bottom: 80px;
        }

        .page-title {
            text-align: center;
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 50px;
            text-transform: uppercase;
        }

        /* Cart Card - Styled like product cards */
        .cart-card {
            background-color: #ffffff;
            color: #000;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
            max-width: 900px;
            margin: 0 auto;
        }

        .cart-card h3 {
            font-weight: 800;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .cart-summary p {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .item-list {
            margin-top: 30px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            font-size: 1.1rem;
        }

        .item-row:last-child { border-bottom: none; }

        .btn-back {
            background-color: #382c2b;
            color: white;
            border: none;
            padding: 12px 35px;
            border-radius: 50px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            margin-top: 30px;
            transition: 0.3s;
        }

        .btn-back:hover {
            background-color: #000;
            color: white;
            transform: translateX(-5px);
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

    <div class="container">
        <h2 class="page-title">User Cart</h2>

        <div class="cart-card">
            <h3>Cart for <?= $user ? htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) : 'User' ?></h3>

            <?php if ($user_cart): ?>
                <div class="cart-summary">
                    <p><strong>Cart ID:</strong> <?= $user_cart['id'] ?></p>
                    <p><strong>Total Items:</strong> <?= $user_cart['totalQuantity'] ?></p>
                    <p><strong>Total Amount:</strong> <span style="font-size: 1.5rem; font-weight: 800;">$<?= number_format($user_cart['total'], 2) ?></span></p>
                </div>

                <div class="item-list">
                    <h4 class="fw-bold mb-3">Items:</h4>
                    <?php foreach($user_cart['products'] as $item): ?>
                        <div class="item-row">
                            <span>
                                <strong><?= htmlspecialchars($item['title']) ?></strong> 
                                <small class="text-muted ms-2">× <?= $item['quantity'] ?></small>
                            </span>
                            <span class="fw-bold">$<?= number_format($item['total'], 2) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-dark text-center py-4">
                    <h4 class="mb-0">No cart found for this user.</h4>
                </div>
            <?php endif; ?>

            <a href="users.php" class="btn-back">← Back to Users</a>
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