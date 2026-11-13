<?php
// index.php - Bookstore Homepage
$pageTitle = "Home - Welcome to NovelHaven";
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section with Books/Reading Background -->
<section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="hero-container">
        <h1 class="hero-title">Find Your Next Great Novel to Read</h1>
        <p class="hero-desc">
            Welcome to NovelHaven. We have hand-selected a collection of beloved novels &mdash; from classic English romance and dystopian fiction to sweeping fantasies and detective mysteries.
        </p>
        <div class="hero-actions">
            <!-- Center Prominent Button taking users to the separate Browse Novels webpage -->
            <a href="browse.php" class="btn btn-accent btn-lg">
                📚 Browse Novels
            </a>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
