<?php
/**
 * db.php - Database Configuration & Helper Functions
 * Connects to the existing 'bookstore' MySQL database without altering tables or schema.
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "bookstore";

// Establish MySQL connection using mysqli
$conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("<div style='font-family:sans-serif;padding:20px;background:#fee2e2;color:#991b1b;border-radius:8px;margin:20px auto;max-width:600px;'>" .
        "<h3 style='margin:0 0 10px;'>Database Connection Error</h3>" .
        "<p>Could not connect to the MySQL database <strong>'bookstore'</strong>. Please ensure Apache and MySQL are running in XAMPP.</p>" .
        "<p><small>Details: " . htmlspecialchars($conn->connect_error) . "</small></p>" .
        "</div>");
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

/**
 * Fetch all books, optional limit and category filter
 */
function get_all_books($conn, $limit = null, $category = null) {
    $sql = "SELECT * FROM `book`";
    $params = [];
    $types = "";

    if ($category && $category !== 'All') {
        $sql .= " WHERE `category` = ?";
        $params[] = $category;
        $types .= "s";
    }

    $sql .= " ORDER BY `ISBN` ASC";

    if ($limit !== null && (int)$limit > 0) {
        $sql .= " LIMIT " . (int)$limit;
    }

    if (!empty($params)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($sql);
    }

    $books = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }
    return $books;
}

/**
 * Fetch a single book by ISBN
 */
function get_book_by_isbn($conn, $isbn) {
    $stmt = $conn->prepare("SELECT * FROM `book` WHERE `ISBN` = ? LIMIT 1");
    $isbnInt = (int)$isbn;
    $stmt->bind_param("i", $isbnInt);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        return $row;
    }
    return null;
}

/**
 * Search books by title or author
 */
function search_books($conn, $query) {
    $query = trim($query);
    if ($query === "") {
        return get_all_books($conn);
    }

    $searchTerm = "%" . $query . "%";
    $stmt = $conn->prepare("SELECT * FROM `book` WHERE `title` LIKE ? OR `author` LIKE ? OR `category` LIKE ? ORDER BY `title` ASC");
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $books = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }
    return $books;
}

/**
 * Get distinct categories from book table
 */
function get_book_categories($conn) {
    $categories = [];
    $result = $conn->query("SELECT DISTINCT `category` FROM `book` ORDER BY `category` ASC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['category'];
        }
    }
    return $categories;
}

/**
 * Get the next auto-calculated user_id (respects non-AUTO_INCREMENT schema)
 */
function get_next_user_id($conn) {
    $res = $conn->query("SELECT COALESCE(MAX(`user_id`), 0) + 1 AS next_id FROM `user`");
    $row = $res->fetch_assoc();
    return (int)$row['next_id'];
}

/**
 * Get the next auto-calculated order_id (respects non-AUTO_INCREMENT schema)
 */
function get_next_order_id($conn) {
    $res = $conn->query("SELECT COALESCE(MAX(`order_id`), 0) + 1 AS next_id FROM `orders`");
    $row = $res->fetch_assoc();
    return (int)$row['next_id'];
}

/**
 * Create a dummy/demo purchase order in the existing 'orders' table
 */
function record_order($conn, $userId, $isbn, $totalPrice, $status = "Completed") {
    $orderId = get_next_order_id($conn);
    $orderDate = date('Y-m-d H:i:s');
    $userIdInt = (int)$userId;
    $isbnInt = (int)$isbn;
    $totalPriceDec = (float)$totalPrice;

    $stmt = $conn->prepare("INSERT INTO `orders` (`order_id`, `user_id`, `ISBN`, `order_date`, `total_price`, `status`) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisds", $orderId, $userIdInt, $isbnInt, $orderDate, $totalPriceDec, $status);
    
    if ($stmt->execute()) {
        return [
            'success' => true,
            'order_id' => $orderId,
            'order_date' => $orderDate,
            'total_price' => $totalPriceDec,
            'status' => $status
        ];
    } else {
        return [
            'success' => false,
            'error' => $conn->error
        ];
    }
}

/**
 * Fetch orders for a user joined with book details
 */
function get_user_orders($conn, $userId) {
    $userIdInt = (int)$userId;
    $sql = "SELECT o.`order_id`, o.`order_date`, o.`total_price`, o.`status`, 
                   b.`ISBN`, b.`title`, b.`author`, b.`category`, b.`image`
            FROM `orders` o
            JOIN `book` b ON o.`ISBN` = b.`ISBN`
            WHERE o.`user_id` = ?
            ORDER BY o.`order_date` DESC, o.`order_id` DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userIdInt);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }
    return $orders;
}

/**
 * Auth Helpers
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function current_user() {
    if (is_logged_in()) {
        return [
            'user_id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'] ?? 'User',
            'email' => $_SESSION['user_email'] ?? ''
        ];
    }
    return null;
}
?>
