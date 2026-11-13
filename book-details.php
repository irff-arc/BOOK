<?php
// book-details.php - Dedicated Webpage for Individual Book Details & Demo Payment
require_once __DIR__ . '/includes/db.php';

$isbn = isset($_GET['isbn']) ? (int)$_GET['isbn'] : 0;
$book = get_book_by_isbn($conn, $isbn);

if (!$book) {
    $pageTitle = "Book Not Found";
    require_once __DIR__ . '/includes/header.php';
    echo '<div class="container" style="padding: 4rem 1.25rem; text-align: center;">';
    echo '<div class="alert alert-error" style="max-width: 500px; margin: 0 auto 1.5rem auto;">Book not found or invalid ISBN.</div>';
    echo '<a href="browse.php" class="btn btn-primary">&larr; Return to Browse Novels</a>';
    echo '</div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$pageTitle = $book['title'] . " by " . $book['author'];
$currentUser = current_user();

// Handle Form Submission for Demo Purchase (Pure PHP - No JS)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'demo_purchase') {

    if ($currentUser) {
        $orderRes = record_order($conn, $currentUser['user_id'], $book['ISBN'], $book['price']);
        if ($orderRes['success']) {
            // Redirect to the dedicated order success webpage!
            header("Location: order-success.php?order_id=" . urlencode($orderRes['order_id']));
            exit;
        } else {
            $purchaseError = "Failed to process demo order: " . htmlspecialchars($orderRes['error']);
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding-top: 1.5rem; padding-bottom: 4rem;">
    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb">
        <a href="index.php">Home</a>
        <span class="breadcrumb-sep">/</span>
        <a href="browse.php">Browse Novels</a>
        <span class="breadcrumb-sep">/</span>
        <span><?php echo htmlspecialchars($book['title']); ?></span>
    </nav>

    <?php if (isset($purchaseError)): ?>
        <div class="alert alert-error"><?php echo $purchaseError; ?></div>
    <?php endif; ?>

    <div class="book-details-box">
        <!-- Cover Art -->
        <div class="book-details-cover">
            <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?> Cover">
        </div>

        <!-- Book Details & Purchase -->
        <div class="book-details-content">
            <div class="book-tag-row">
                <span class="pill-tag"><?php echo htmlspecialchars($book['category']); ?></span>
                <span class="pill-tag pill-tag-muted">ISBN: <?php echo htmlspecialchars($book['ISBN']); ?></span>
            </div>

            <h1 class="book-main-title"><?php echo htmlspecialchars($book['title']); ?></h1>
            <p class="book-main-author">by <strong><?php echo htmlspecialchars($book['author']); ?></strong></p>

            <!-- Price Panel -->
            <div class="book-price-panel">
                <div>
                    <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Price</span>
                    <div class="book-price-value">₹<?php echo number_format((float)$book['price'], 2); ?></div>
                </div>
                <div class="book-in-stock">&#10003; In Stock &bull; Available</div>
            </div>

            <!-- Synopsis -->
            <div class="book-synopsis">
                <h3>About This Novel</h3>
                <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
            </div>

            <!-- Book Specifications -->
            <div class="book-meta-grid">
                <div>
                    <span class="meta-field-label">Format</span>
                    <span class="meta-field-value">Hardcover Novel</span>
                </div>
                <div>
                    <span class="meta-field-label">Language</span>
                    <span class="meta-field-value">English</span>
                </div>
                <div>
                    <span class="meta-field-label">Category</span>
                    <span class="meta-field-value"><?php echo htmlspecialchars($book['category']); ?></span>
                </div>
                <div>
                    <span class="meta-field-label">Delivery</span>
                    <span class="meta-field-value">Instant Dispatch</span>
                </div>
            </div>

            <!-- Checkout Section (Pure HTML Form - No JS) -->
            <div class="checkout-box">
                <?php if ($currentUser): ?>
                    <!-- Standard Purchase for Logged-In User -->
                    <form method="POST" action="book-details.php?isbn=<?php echo urlencode($book['ISBN']); ?>">
                        <input type="hidden" name="action" value="demo_purchase">
                        <button type="submit" class="btn btn-accent btn-lg btn-block">
                            💳 Pay Now (₹<?php echo number_format((float)$book['price'], 2); ?>) &mdash; Purchase
                        </button>
                    </form>
                <?php else: ?>
                    <!-- Purchase Options for Guest User -->
                    <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem; text-align: center;">
                        <p style="font-weight: 600; color: var(--dark); margin-bottom: 0.5rem;">
                            Ready to read?
                        </p>
                        <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 1rem;">
                            Please sign in or create an account to purchase this novel.
                        </p>
                        <a href="login.php?redirect=<?php echo urlencode('book-details.php?isbn=' . $book['ISBN']); ?>" class="btn btn-primary">
                            Sign In to Purchase
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
