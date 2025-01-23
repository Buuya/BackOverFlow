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

// Query to get the most popular hashtags
$tags_query = "
    SELECT tags, usage_count 
    FROM tags 
    ORDER BY usage_count DESC
    LIMIT 20"; // Limit to top 20 hashtags
$tags_result = $conn->query($tags_query);

// Handle search functionality
$search_query = "";
if (isset($_POST['search'])) {
    $search_term = $conn->real_escape_string($_POST['search_term']);
    $search_query = "tags LIKE '%$search_term%' OR author LIKE '%$search_term%'";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayub's Forum - Hashtags</title>
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

    <main class="container">
        <h2>Popular Hashtags</h2>
        <p>Explore the most popular topics on our forum:</p>

        <div class="search-bar">
        <form method="POST" action="">
            <input type="text" name="search_term" placeholder="Search for questions..." value="<?php echo isset($_POST['search_term']) ? htmlspecialchars($_POST['search_term']) : ''; ?>">
            <button type="submit" name="search">Search</button>
        </form>
    </div>

        <div class="tags-grid">
            <?php
            if ($tags_result->num_rows > 0) {
                while ($tag = $tags_result->fetch_assoc()) {
                    echo '<div class="tag-card">';
                    echo '<h3><a href="search.php?tag=' . urlencode($tag['tags']) . '">#' . htmlspecialchars($tag['tags']) . '</a></h3>';
                    echo '<p>Used ' . htmlspecialchars($tag['usage_count']) . ' times</p>';
                    echo '</div>';
                }
            } else {
                echo "<p>No hashtags found.</p>";
            }
            ?>
        </div>
    </main>

</body>
</html>
