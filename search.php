<?php
// search.php - Dedicated Webpage for Searching Novels
$pageTitle = "Search Novels";
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$hasSearched = isset($_GET['q']);

if ($hasSearched && $query !== '') {
    $searchResults = search_books($conn, $query);
} else {
    // Show nothing if no query provided
    $searchResults = [];
}
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <a href="index.php">Home</a>
        <span class="breadcrumb-sep">/</span>
        <span>Search</span>
    </nav>

    <!-- Search Box Card -->
    <div class="search-box-card">
        <div class="section-header" style="margin-bottom: 1.5rem;">
            <span class="section-tag">Search Collection</span>
            <h1 class="section-title">Search for a Novel</h1>
            <p class="section-subtitle">
                Find books by novel title (e.g. The Floating World, Fairy Tale) or author name (e.g. Axie Oh, Stephen King).
            </p>
        </div>

        <!-- Standard HTML Form (Pure GET - No JS) -->
        <form method="GET" action="search.php">
            <div class="search-form-row">
                <input 
                    type="text" 
                    name="q" 
                    class="search-input-field" 
                    placeholder="Enter book title or author..." 
                    value="<?php echo htmlspecialchars($query); ?>"
                    required
                >
                <button type="submit" class="btn btn-primary">
                    🔍 Search
                </button>
                <?php if ($query !== ''): ?>
                    <a href="search.php" class="btn btn-outline">Clear</a>
                <?php endif; ?>
            </div>
        </form>


    </div>

    <?php if ($query !== ''): ?>
        <!-- Results Status -->
        <div style="margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem; color: var(--dark); font-weight: 700;">
                Search results for &ldquo;<?php echo htmlspecialchars($query); ?>&rdquo;
            </h2>
            <p style="font-size: 0.88rem; color: var(--text-muted);">
                Found <?php echo count($searchResults); ?> <?php echo (count($searchResults) === 1) ? 'novel' : 'novels'; ?>
            </p>
        </div>

        <!-- Books Grid -->
        <?php if (!empty($searchResults)): ?>
            <div class="books-grid">
                <?php foreach ($searchResults as $novel): ?>
                    <article class="book-card">
                        <a href="book-details.php?isbn=<?php echo urlencode($novel['ISBN']); ?>" class="book-cover-link">
                            <img src="<?php echo htmlspecialchars($novel['image']); ?>" alt="<?php echo htmlspecialchars($novel['title']); ?> Cover" class="book-cover-img" loading="lazy">
                        </a>
                        <div class="book-card-body">
                            <span class="book-card-cat"><?php echo htmlspecialchars($novel['category']); ?></span>
                            <h3 class="book-card-title">
                                <a href="book-details.php?isbn=<?php echo urlencode($novel['ISBN']); ?>">
                                    <?php echo htmlspecialchars($novel['title']); ?>
                                </a>
                            </h3>
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
            <div class="alert alert-info" style="text-align: center; padding: 2.5rem 1.5rem;">
                <p style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem;">
                    No novels found matching &ldquo;<?php echo htmlspecialchars($query); ?>&rdquo;
                </p>
                <p style="color: var(--text-muted); margin-bottom: 1.25rem;">
                    Check the spelling or try searching by author surname.
                </p>
                <a href="browse.php" class="btn btn-primary btn-sm">Browse All Novels &rarr;</a>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
