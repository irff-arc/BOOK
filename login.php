<?php
// login.php - Dedicated Webpage for User Login (No JavaScript)
$pageTitle = "Login";
require_once __DIR__ . '/includes/db.php';

// Redirect if already logged in
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
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = "Please enter both your email address and password.";
    } else {
        $stmt = $conn->prepare("SELECT `user_id`, `name`, `email`, `password` FROM `user` WHERE `email` = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $user = $result->fetch_assoc()) {
            $hashedAttempt = sha1($password);
            if ($user['password'] === $hashedAttempt || $user['password'] === $password) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];

                header("Location: " . $redirect);
                exit;
            } else {
                $error = "Incorrect password. Please try again.";
            }
        } else {
            $error = "No user account was found with that email.";
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <div class="auth-container">
        <div class="auth-header">
            <span style="font-size: 2.2rem; display: block; margin-bottom: 0.5rem;">📖</span>
            <h1>Sign In</h1>
            <p>Welcome back! Enter your login details below.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>



        <!-- Normal Login Form -->
        <form method="POST" action="login.php?redirect=<?php echo urlencode($redirect); ?>">
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control" 
                    placeholder="e.g. reader@example.com" 
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control" 
                    placeholder="Enter your password" 
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 1rem;">
                Log In &rarr;
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
            Don't have an account? <a href="register.php?redirect=<?php echo urlencode($redirect); ?>" style="font-weight: 600;">Create one here</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
