<?php
// order-success.php - Dedicated Webpage for Order Confirmation & Payment Success
require_once __DIR__ . '/includes/db.php';

$orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

// Fetch order details from database
$stmt = $conn->prepare("
    SELECT o.`order_id`, o.`order_date`, o.`total_price`, o.`status`,
           b.`ISBN`, b.`title`, b.`author`, b.`category`, b.`image`,
           u.`name` AS `buyer_name`, u.`email` AS `buyer_email`
    FROM `orders` o
    JOIN `book` b ON o.`ISBN` = b.`ISBN`
    JOIN `user` u ON o.`user_id` = u.`user_id`
    WHERE o.`order_id` = ?
    LIMIT 1
");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$res = $stmt->get_result();
$order = ($res && $res->num_rows > 0) ? $res->fetch_assoc() : null;

$pageTitle = "Payment Successful - Order #" . $orderId;
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <a href="index.php">Home</a>
        <span class="breadcrumb-sep">/</span>
        <a href="browse.php">Browse Novels</a>
        <span class="breadcrumb-sep">/</span>
        <span>Order Confirmation</span>
    </nav>

    <div class="success-page-card">
        <div class="success-icon-badge">&#10003;</div>
        
        <!-- Exact required confirmation text -->
        <h1 class="success-title">Payment Successful!</h1>
        <p class="success-message">Payment Successful! Thank you for your purchase.</p>
        
        <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 2rem;">
            Your order has been placed successfully and will be processed shortly. We've sent a confirmation email to your registered address.
        </p>

        <?php if ($order): ?>
            <table class="order-summary-table">
                <tr>
                    <td>Order ID:</td>
                    <td><strong>#<?php echo htmlspecialchars($order['order_id']); ?></strong></td>
                </tr>
                <tr>
                    <td>Novel Title:</td>
                    <td><?php echo htmlspecialchars($order['title']); ?></td>
                </tr>
                <tr>
                    <td>Author:</td>
                    <td><?php echo htmlspecialchars($order['author']); ?></td>
                </tr>
                <tr>
                    <td>Customer:</td>
                    <td><?php echo htmlspecialchars($order['buyer_name']); ?> (<?php echo htmlspecialchars($order['buyer_email']); ?>)</td>
                </tr>
                <tr>
                    <td>Date &amp; Time:</td>
                    <td><?php echo date('M d, Y', strtotime($order['order_date'])) . ' &bull; ' . date('h:i A', strtotime($order['order_date'])); ?></td>
                </tr>
                <tr>
                    <td>Order Status:</td>
                    <td><span class="badge-completed">&#10003; <?php echo htmlspecialchars($order['status']); ?></span></td>
                </tr>
                <tr>
                    <td>Total Amount:</td>
                    <td>₹<?php echo number_format((float)$order['total_price'], 2); ?> (Paid)</td>
                </tr>
            </table>
        <?php else: ?>
            <div class="alert alert-info">
                Order confirmed! Your transaction was recorded successfully.
            </div>
        <?php endif; ?>

        <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
            <a href="orders.php" class="btn btn-primary">
                📦 View in My Orders
            </a>
            <a href="browse.php" class="btn btn-outline">
                📚 Continue Browsing Novels
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
