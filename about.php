<?php
// about.php - Dedicated Webpage for About Us
$pageTitle = "About Us";
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <a href="index.php">Home</a>
        <span class="breadcrumb-sep">/</span>
        <span>About Us</span>
    </nav>

    <!-- About Hero -->
    <div class="about-hero-box">
        <span class="section-tag">Our Little Bookstore</span>
        <h1>About NovelHaven</h1>
        <p style="font-size: 1.05rem; color: var(--text-muted); max-width: 650px; margin: 0 auto; line-height: 1.6;">
            We created NovelHaven with a simple thought: great novels shouldn't be hard to find. We focus on a curated shelf of timeless stories that every reader will cherish, delivering exceptional literature straight to your door.
        </p>
    </div>

    <!-- About Cards -->
    <div class="about-cards-row">
        <div class="about-card">
            <h3>📚 Curated Stories</h3>
            <p>
                From Axie Oh's enchanting fantasy in <em>The Floating World</em> to Stephen King's sprawling worlds in <em>Fairy Tale</em>, we celebrate novels that stand the test of time. Our collection is handpicked by passionate readers.
            </p>
        </div>

        <div class="about-card">
            <h3>🛡️ Quality Guaranteed</h3>
            <p>
                We partner directly with leading publishers to ensure that every book you purchase from NovelHaven is an authentic, high-quality edition that will look beautiful on your bookshelf.
            </p>
        </div>

        <div class="about-card">
            <h3>🚚 Fast Delivery</h3>
            <p>
                Once you place an order, our dedicated fulfillment team ensures your books are securely packaged and dispatched swiftly, bringing the magic of reading to your hands without delay.
            </p>
        </div>
    </div>

    <!-- Store Contact / Location -->
    <div style="text-align: center; margin-top: 3.5rem; font-size: 1rem; color: var(--dark); padding-top: 2rem; border-top: 1px solid var(--border);">
        <h3 style="margin-bottom: 1rem;">Contact Us</h3>
        <p style="font-size: 0.9rem;">
            <strong>Email:</strong> irfannn080@gmail.com &nbsp;&bull;&nbsp; <strong>Phone:</strong> 6001715756
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
