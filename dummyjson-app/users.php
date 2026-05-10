<?php 
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

// Fetch users from API
$users = json_decode(file_get_contents('https://dummyjson.com/users'), true)['users'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - DummyJSON App</title>
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

        /* Navbar: Dashboard style */
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

        /* Content Container */
        .container {
            position: relative;
            z-index: 2;
            padding-top: 60px;
            padding-bottom: 80px;
        }

        .page-title {
            text-align: center;
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 60px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Table Card Container */
        .user-card {
            background-color: #ffffff;
            color: #000;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }

        .table {
            color: #333;
            vertical-align: middle;
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 15px;
            font-size: 1.1rem;
            text-transform: uppercase;
        }

        .table tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        .user-img {
            border: 2px solid #3f1211;
            padding: 2px;
        }

        .btn-view-cart {
            background-color: #382c2b;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-view-cart:hover {
            background-color: #000;
            color: white;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <div id="light"></div>

    <nav class="navbar">
        <a href="dashboard.php" class="navbar-brand">DummyJSON App</a>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="products.php">Products</a>
            <a href="posts.php">Posts</a>
            <a href="logout.php" class="logout-link">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2 class="page-title">USERS</h2>

        <div class="user-card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Age</th>
                            <th>Phone</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($users as $user): ?>
                        <tr>
                            <td>
                                <img src="<?= $user['image'] ?>" width="55" height="55" class="rounded-circle user-img shadow-sm">
                            </td>
                            <td class="fw-bold"><?= htmlspecialchars($user['firstName'].' '.$user['lastName']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= $user['age'] ?></td>
                            <td><?= htmlspecialchars($user['phone']) ?></td>
                            <td class="text-center">
                                <a href="carts.php?user_id=<?= $user['id'] ?>" class="btn btn-view-cart">View Cart</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
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