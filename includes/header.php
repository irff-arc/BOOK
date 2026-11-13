<?php
// includes/header.php - Pure HTML/CSS Header (No JavaScript)
require_once __DIR__ . '/db.php';

$currentPage = basename($_SERVER['PHP_SELF']);
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NovelHaven Online Bookstore - Simple and elegant bookstore for reading and discovering novels.">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . " - NovelHaven" : "NovelHaven - Online Bookstore"; ?></title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📖</text></svg>">
</head>
<body>

    <!-- Header Navigation Bar -->
    <header class="site-header">
        <div class="container">
            <nav class="navbar">
                <!-- Bookstore Logo -->
                <a href="index.php" class="brand-logo">
                    <span class="brand-icon">📖</span>
                    <span class="brand-title">Novel<span>Haven</span></span>
                </a>

                <!-- Pure CSS Responsive Toggle Checkbox (No JS) -->
                <input type="checkbox" id="nav-toggle" class="nav-toggle-input" aria-label="Toggle navigation menu">
                <label for="nav-toggle" class="nav-toggle-label">&#9776;</label>

                <!-- Navigation Links -->
                <div class="nav-menu">
                    <a href="index.php" class="nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>">Home</a>
                    <a href="browse.php" class="nav-link <?php echo ($currentPage == 'browse.php') ? 'active' : ''; ?>">Browse Novels</a>
                    <a href="search.php" class="nav-link <?php echo ($currentPage == 'search.php') ? 'active' : ''; ?>">Search</a>
                    <a href="about.php" class="nav-link <?php echo ($currentPage == 'about.php') ? 'active' : ''; ?>">About Us</a>

                    <div class="nav-auth">
                        <?php if ($user): ?>
                            <a href="orders.php" class="user-badge" title="View my orders">
                                <span>👤</span> <?php echo htmlspecialchars($user['name']); ?>
                            </a>
                            <a href="orders.php" class="nav-link <?php echo ($currentPage == 'orders.php') ? 'active' : ''; ?>" style="font-size: 0.88rem;">My Orders</a>
                            <a href="logout.php" class="btn btn-outline btn-sm">Log Out</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary btn-sm">Login / Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <main>
