<?php
// orders.php - Dedicated Webpage for User Order History (No JavaScript)
$pageTitle = "My Orders";
require_once __DIR__ . '/includes/db.php';

if (!is_logged_in()) {
    header("Location: login.php?redirect=orders.php");
    exit;
}

$currentUser = current_user();
$orders = get_user_orders($conn, $currentUser['user_id']);

require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <a href="index.php">Home</a>
        <span class="breadcrumb-sep">/</span>
        <span>My Orders</span>
    </nav>

    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <span class="section-tag">Account Dashboard</span>
        <h1 class="section-title">My Orders</h1>
        <p class="section-subtitle">
            Hello, <strong><?php echo htmlspecialchars($currentUser['name']); ?></strong>! Here is the list of novels you have purchased.
        </p>
    </div>

    <?php if (!empty($orders)): ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Book Cover</th>
                        <th>Title &amp; Author</th>
                        <th>Date Ordered</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><strong>#<?php echo htmlspecialchars($order['order_id']); ?></strong></td>
                            <td style="width: 60px;">
                                <img src="<?php echo htmlspecialchars($order['image']); ?>" alt="Cover" class="order-thumb">
                            </td>
                            <td>
                                <strong style="color: var(--dark); font-size: 0.95rem;">
                                    <?php echo htmlspecialchars($order['title']); ?>
                                </strong>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">
                                    by <?php echo htmlspecialchars($order['author']); ?> &bull; <?php echo htmlspecialchars($order['category']); ?>
                                </div>
                            </td>
                            <td>
                                <?php echo date('M d, Y', strtotime($order['order_date'])) . ' &bull; ' . date('h:i A', strtotime($order['order_date'])); ?>
                            </td>
                            <td>
                                <strong style="color: var(--dark);">₹<?php echo number_format((float)$order['total_price'], 2); ?></strong>
                            </td>
                            <td>
                                <span class="badge-completed">&#10003; <?php echo htmlspecialchars($order['status']); ?></span>
                            </td>
                            <td>
                                <a href="book-details.php?isbn=<?php echo urlencode($order['ISBN']); ?>" class="btn btn-outline btn-sm">
                                    View Novel
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <p style="font-size: 0.85rem; color: var(--text-muted);">
                Total orders: <strong><?php echo count($orders); ?></strong>
            </p>
            <a href="browse.php" class="btn btn-primary btn-sm">
                Browse More Novels &rarr;
            </a>
        </div>
    <?php else: ?>
        <div class="alert alert-info" style="text-align: center; padding: 3rem 1.5rem;">
            <span style="font-size: 2rem; display: block; margin-bottom: 0.5rem;">📚</span>
            <p style="font-weight: 600; font-size: 1.1rem; margin-bottom: 0.5rem;">You haven't ordered any novels yet.</p>
            <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Explore our curated shelf and make your first purchase!</p>
            <a href="browse.php" class="btn btn-primary">Browse Novels Now &rarr;</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
