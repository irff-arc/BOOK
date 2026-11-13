<?php
// browse.php - Dedicated Webpage for Browsing Novels
$pageTitle = "Browse Novels";
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

$selectedCategory = isset($_GET['category']) ? trim($_GET['category']) : 'All';
$categories = get_book_categories($conn);
$novels = get_all_books($conn, null, $selectedCategory);
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <a href="index.php">Home</a>
        <span class="breadcrumb-sep">/</span>
        <span>Browse Novels</span>
    </nav>

    <!-- Page Header -->
    <div class="section-header" style="margin-bottom: 2rem;">
        <span class="section-tag">Novel Catalog</span>
        <h1 class="section-title">Browse Our Novels</h1>
        <p class="section-subtitle">
            Explore our curated novels. Choose a genre below or click any title for full details and purchase options.
        </p>


    </div>

    <!-- Novels Grid -->
    <?php if (!empty($novels)): ?>
        <div class="books-grid">
            <?php foreach ($novels as $novel): ?>
                <article class="book-card">
                    <a href="book-details.php?isbn=<?php echo urlencode($novel['ISBN']); ?>" class="book-cover-link">
                        <img src="<?php echo htmlspecialchars($novel['image']); ?>" alt="<?php echo htmlspecialchars($novel['title']); ?> Cover" class="book-cover-img" loading="lazy">
                    </a>
                    <div class="book-card-body">
                        <span class="book-card-cat"><?php echo htmlspecialchars($novel['category']); ?></span>
                        <h2 class="book-card-title">
                            <a href="book-details.php?isbn=<?php echo urlencode($novel['ISBN']); ?>">
                                <?php echo htmlspecialchars($novel['title']); ?>
                            </a>
                        </h2>
                        <p class="book-card-author">by <?php echo htmlspecialchars($novel['author']); ?></p>
                        <p class="book-card-desc"><?php echo htmlspecialchars($novel['description']); ?></p>
                        
                        <div class="book-card-footer">
                            <span class="book-card-price">₹<?php echo number_format((float)$novel['price'], 2); ?></span>
                            <a href="book-details.php?isbn=<?php echo urlencode($novel['ISBN']); ?>" class="btn btn-primary btn-sm">
                                View Details &rarr;
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info" style="text-align: center;">
            No novels found in the "<?php echo htmlspecialchars($selectedCategory); ?>" category. <a href="browse.php">View all novels</a>.
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
