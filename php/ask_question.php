<?php
session_start(); // Start the session to access logged-in user's details

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "forum";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $tags = $_POST['tags'];

    // Use session data for author or default to 'Guest'
    $author = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

    // Insert the question into the database
    $query = "INSERT INTO questions (title, description, tags, author) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $title, $description, $tags, $author);

    // Check if the query executed successfully
    if ($stmt->execute()) {
        echo "Question submitted successfully.";
        header("Location: /php/index.php"); // Redirect to homepage
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Check if the user is logged in to set the author dynamically
$author = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayub's Forum - Ask a Question</title>
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
        <div class="ask-question-container">
            <h2>Ask a Question</h2>
            <form action="ask_question.php" method="POST">
                <div class="form-group">
                    <label for="title">Question Title</label>
                    <input type="text" id="title" name="title" placeholder="Enter the title of your question" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Describe your question..." rows="10" required></textarea>
                </div>
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <input type="text" id="tags" name="tags" placeholder="Add relevant tags (e.g. html, css)" required>
                </div>
                <div class="form-group">
                    <label for="author">Author</label>
                    <input type="text" id="author" name="author" value="<?php echo $author; ?>" readonly>
                </div>
                <div class="form-actions">
                    <button type="submit" name="submit">Submit Question</button>
                </div>
            </form>            
        </div>
    </main>

    <!-- Footer -->
<footer class="footer">
    <p>&copy; 2024 ForumName. All Rights Reserved.</p>
</footer>

</body>
</html>
