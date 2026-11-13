<?php
// register.php - Dedicated Webpage for User Registration (No JavaScript)
$pageTitle = "Create an Account";
require_once __DIR__ . '/includes/db.php';

if (is_logged_in()) {
    header("Location: index.php");
    exit;
}

$error = "";
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';

if (strpos($redirect, 'http://') === 0 || strpos($redirect, 'https://') === 0) {
    $redirect = 'index.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Please fill in all the required fields.";
    } elseif (strlen($name) > 50) {
        $error = "Name cannot be longer than 50 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($email) > 50) {
        $error = "Email address cannot exceed 50 characters.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match. Please re-enter carefully.";
    } else {
        // Check for existing email in `user` table
        $checkStmt = $conn->prepare("SELECT `user_id` FROM `user` WHERE `email` = ? LIMIT 1");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkRes = $checkStmt->get_result();

        if ($checkRes && $checkRes->num_rows > 0) {
            $error = "An account with this email already exists. Please log in.";
        } else {
            // Get next user_id safely (respects non-AUTO_INCREMENT schema)
            $nextUserId = get_next_user_id($conn);
            $hashedPassword = sha1($password);

            $insertStmt = $conn->prepare("INSERT INTO `user` (`user_id`, `name`, `email`, `password`) VALUES (?, ?, ?, ?)");
            $insertStmt->bind_param("isss", $nextUserId, $name, $email, $hashedPassword);

            if ($insertStmt->execute()) {
                $_SESSION['user_id'] = $nextUserId;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;

                header("Location: " . $redirect);
                exit;
            } else {
                $error = "Could not register account: " . htmlspecialchars($conn->error);
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <div class="auth-container">
        <div class="auth-header">
            <span style="font-size: 2.2rem; display: block; margin-bottom: 0.5rem;">✨</span>
            <h1>Create Account</h1>
            <p>Join our online bookstore to order novels and track purchases.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php?redirect=<?php echo urlencode($redirect); ?>">
            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control" 
                    placeholder="e.g. Jane Doe" 
                    maxlength="50"
                    value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="regEmail" class="form-label">Email Address</label>
                <input 
                    type="email" 
                    id="regEmail" 
                    name="email" 
                    class="form-control" 
                    placeholder="e.g. jane@example.com" 
                    maxlength="50"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="regPass" class="form-label">Password</label>
                <input 
                    type="password" 
                    id="regPass" 
                    name="password" 
                    class="form-control" 
                    placeholder="Minimum 6 characters" 
                    minlength="6"
                    required
                >
            </div>

            <div class="form-group">
                <label for="confirmPass" class="form-label">Confirm Password</label>
                <input 
                    type="password" 
                    id="confirmPass" 
                    name="confirm_password" 
                    class="form-control" 
                    placeholder="Re-enter password" 
                    minlength="6"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 1rem;">
                Register Account &rarr;
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
            Already have an account? <a href="login.php?redirect=<?php echo urlencode($redirect); ?>" style="font-weight: 600;">Sign in here</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
