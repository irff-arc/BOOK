<?php
// seed.php - Seeds sample books if table is empty
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "bookstore";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// 1. Check if books exist
$checkBooks = $conn->query("SELECT COUNT(*) AS total FROM `book`");
$bookCount = ($checkBooks) ? (int)$checkBooks->fetch_assoc()['total'] : 0;

if ($bookCount === 0) {
    $books = [
        [
            'ISBN' => 100001,
            'title' => 'The Silent Patient',
            'author' => 'Alex Michaelides',
            'description' => 'A shocking psychological thriller of a woman\'s act of violence against her husband and of the therapist obsessed with uncovering her motive.',
            'price' => 1299.00,
            'category' => 'Thriller',
            'image' => 'assets/images/silent-patient.jpg'
        ],
        [
            'ISBN' => 100002,
            'title' => 'The Floating World',
            'author' => 'Axie Oh',
            'description' => 'A Korean-inspired romantic fantasy following an amnesiac ex-soldier and a spirited acrobat whose destinies intertwine in a world of silver light and shadowy demons.',
            'price' => 1399.00,
            'category' => 'Fantasy',
            'image' => 'assets/images/the-floating-world-v3.jpg'
        ],
        [
            'ISBN' => 100003,
            'title' => 'The Demon and the Light',
            'author' => 'Axie Oh',
            'description' => 'The thrilling conclusion to the Floating World duology, where Ren must navigate political pressure to save Sunho from the monstrous power consuming him.',
            'price' => 1499.00,
            'category' => 'Fantasy',
            'image' => 'assets/images/the-demon-and-the-light.jpg'
        ],
        [
            'ISBN' => 100004,
            'title' => 'White Nights',
            'author' => 'Fyodor Dostoevsky',
            'description' => 'A poignant short story exploring themes of loneliness, unrequited love, and the fleeting nature of happiness in the ethereal twilight of St. Petersburg.',
            'price' => 999.00,
            'category' => 'Classic Fiction',
            'image' => 'assets/images/white-nights-v3.jpg'
        ],
        [
            'ISBN' => 100005,
            'title' => 'Incidents Around the House',
            'author' => 'Josh Malerman',
            'description' => 'A terrifying new horror novel told through the eyes of an eight-year-old girl whose family is haunted by an insidious entity called Other Mommy.',
            'price' => 1399.00,
            'category' => 'Horror',
            'image' => 'assets/images/incidents-around-the-house.jpg'
        ],
        [
            'ISBN' => 100006,
            'title' => 'When the Wolf Comes Home',
            'author' => 'Nat Cassidy',
            'description' => 'A thrilling horror novel about Jess Bailey, a struggling actress who discovers a runaway boy capable of turning fears into reality, leading them to flee from his monstrous father.',
            'price' => 1499.00,
            'category' => 'Horror',
            'image' => 'assets/images/when-the-wolf-comes-home.jpg'
        ],
        [
            'ISBN' => 100007,
            'title' => 'Fairy Tale',
            'author' => 'Stephen King',
            'description' => 'A dark fantasy adventure about a teenage boy who inherits the keys to a parallel world where good and evil are at war.',
            'price' => 1499.00,
            'category' => 'Fantasy',
            'image' => 'assets/images/fairy-tale.jpg'
        ],
        [
            'ISBN' => 100008,
            'title' => 'The Maidens',
            'author' => 'Alex Michaelides',
            'description' => 'A psychological suspense novel about a brilliant but troubled therapist obsessed with a secret society and a series of murders at Cambridge University.',
            'price' => 1299.00,
            'category' => 'Thriller',
            'image' => 'assets/images/the-maidens.jpg'
        ]
    ];

    $stmt = $conn->prepare("INSERT INTO `book` (`ISBN`, `title`, `author`, `description`, `price`, `category`, `image`) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($books as $b) {
        $stmt->bind_param("isssdss", $b['ISBN'], $b['title'], $b['author'], $b['description'], $b['price'], $b['category'], $b['image']);
        $stmt->execute();
    }
    $stmt->close();
    echo "Successfully seeded " . count($books) . " sample novels into 'book' table.\n";
} else {
    echo "Book table already has $bookCount records. Skipping book seeding.\n";
}

// 2. Check if demo user exists
$checkUsers = $conn->query("SELECT COUNT(*) AS total FROM `user`");
$userCount = ($checkUsers) ? (int)$checkUsers->fetch_assoc()['total'] : 0;

if ($userCount === 0) {
    // Insert demo user with SHA1 hash of 'password123'
    $demoPass = sha1('password123');
    $conn->query("INSERT INTO `user` (`user_id`, `name`, `email`, `password`) VALUES (1, 'Alex Morgan', 'demo@bookstore.com', '$demoPass')");
    echo "Successfully created sample demo user: demo@bookstore.com / password123\n";
} else {
    echo "User table already has $userCount records. Skipping user seeding.\n";
}

$conn->close();
