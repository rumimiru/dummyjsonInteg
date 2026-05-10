<?php 
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

// Fetch posts
$posts_data = json_decode(file_get_contents('https://dummyjson.com/posts'), true);
$posts = $posts_data['posts'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts - DummyJSON App</title>
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

        /* Post Card Container */
        .post-card {
            background-color: #ffffff;
            color: #000;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }

        .post-card:hover {
            transform: translateY(-5px);
        }

        .post-card h5 {
            font-weight: 800;
            font-size: 1.6rem;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .post-body {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #444;
            margin-bottom: 25px;
        }

        .post-meta {
            font-size: 0.95rem;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }

        .tag-badge {
            background-color: #382c2b;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-right: 5px;
            display: inline-block;
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
            <a href="users.php">Users</a>
            <a href="logout.php" class="logout-link">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2 class="page-title">POSTS</h2>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php foreach($posts as $post): ?>
                    <div class="post-card">
                        <h5><?= htmlspecialchars($post['title']) ?></h5>
                        <p class="post-body"><?= htmlspecialchars($post['body']) ?></p>
                        
                        <div class="post-meta">
                            <div class="mb-2">
                                <strong>Tags:</strong> 
                                <?php if(!empty($post['tags'])): ?>
                                    <?php foreach($post['tags'] as $tag): ?>
                                        <span class="tag-badge"><?= htmlspecialchars($tag) ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted">No tags</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-2">
                                <strong>Reactions:</strong> 
                                <span class="text-success fw-bold">👍 <?= $post['reactions']['likes'] ?? 0 ?></span> 
                                <span class="mx-2">|</span>
                                <span class="text-danger fw-bold">👎 <?= $post['reactions']['dislikes'] ?? 0 ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($posts)): ?>
                    <div class="alert alert-light text-center py-5">
                        <h4>No posts found.</h4>
                    </div>
                <?php endif; ?>
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