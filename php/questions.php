<?php
session_start(); // Start session to track logged-in user

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "forum";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search functionality
$search_query = "";
if (isset($_POST['search'])) {
    $search_term = $conn->real_escape_string($_POST['search_term']);
    $search_query = "WHERE title LIKE '%$search_term%' OR description LIKE '%$search_term%' OR tags LIKE '%$search_term%' OR author LIKE '%$search_term%'";
}

$query = "SELECT * FROM questions $search_query ORDER BY created_at DESC";
$result = $conn->query($query);

// Get user details if logged in
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text'])) {
    $comment_text = $conn->real_escape_string($_POST['comment_text']);
    $author = $user_name; // Use session username or "Guest" if not logged in
    $question_id = $_POST['question_id'];

    $insert_comment_query = "INSERT INTO comments (question_id, comment_text, author) VALUES ($question_id, '$comment_text', '$author')";
    if ($conn->query($insert_comment_query)) {
        header("Location: view_question.php?id=$question_id"); // Refresh page to show the new comment
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayub's Forum - Questions</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<header>
<nav class="navbar">
        <div class="logo">Ayub's Forum</div>
        <input type="checkbox" id="menu-toggle">
        <label for="menu-toggle" class="menu-icon">&#9776;</label>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="questions.php">Questions</a></li>
            <li><a href="ask_question.php">Ask Question</a></li>
            <?php echo isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true ? '<li><a href="dashboard.php">Admin</a></li>' : ''; ?>
            <li><a href="tags.php">Hashtags</a></li>
            <li class="dropdown">
                <a href="#">Account</a>
                <ul class="dropdown-menu">
                    <?php if (isset($_SESSION['username'])) { ?>
                        <li><a href="logout.php">Logout</a></li>
                        <li><a href="#">Hello, <?php echo $_SESSION['username']; ?></a></li>
                    <?php } else { ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php } ?>
                </ul>
            </li>
        </ul>
    </nav>
</header>

<main>
    <div class="search-bar">
        <form method="POST" action="">
            <input type="text" name="search_term" placeholder="Search for questions..." value="<?php echo isset($_POST['search_term']) ? htmlspecialchars($_POST['search_term']) : ''; ?>">
            <button type="submit" name="search">Search</button>
        </form>
    </div>

    <div class="recent-questions">
        <h2>Recent Questions</h2>
        <?php if ($result->num_rows > 0): ?>
            <ul class="question-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="question-item">
                        <h3><a href="view_question.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <small>By: <?php echo htmlspecialchars($row['author']); ?> on <?php echo date("F j, Y, g:i a", strtotime($row['created_at'])); ?></small>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No questions found. Be the first to ask!</p>
        <?php endif; ?>
    </div>
</main>

<!-- Footer -->
<footer class="footer">
    <p>&copy; 2024 ForumName. All Rights Reserved.</p>
</footer>

<script src="/script/modal.js"></script>
<script src="/script/comments.js"></script>
<script src="/script/like.js"></script>
</body>
</html>
