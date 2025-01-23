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

// Query to get the questions
$query = "SELECT * FROM questions ORDER BY created_at DESC LIMIT 10";
$result = $conn->query($query);

// Get user details if logged in
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayub's Forum - Home</title>
    <link rel="stylesheet" href="/css/style.css">

    <style>
        /* Full-page hero section */
        body, html {
            background-color: #ADD8E6;
            height: 100%;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            overflow-x: visible; /* Prevent horizontal scrolling */
        }

        /* Navbar Styling */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px; /* Increased padding for better spacing */
            color: white;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 10;
            flex-wrap: wrap;
            box-sizing: border-box; /* Prevent overflow */
        }

        .navbar .logo {
            font-size: 28px;
            font-weight: bold;
            color: white;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 30px;
            margin-left: auto;
        }

        .nav-links li {
            position: relative;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .nav-links a:hover {
            background-color: #4bc6ff; /* Darker Blue */
            border-radius: 5px;
        }

        .nav-links .dropdown {
            position: relative;
        }

        .nav-links .dropdown .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #0033aa;
            list-style: none;
            padding: 0;
            margin: 0;
            width: 200px;
            border-radius: 5px;
        }

        .nav-links .dropdown .dropdown-menu li {
            margin: 0;
        }

        .nav-links .dropdown .dropdown-menu a {
            background-color: #4bc6ff;
            display: block;
            padding: 10px;
            color: white;
        }

        .nav-links .dropdown:hover .dropdown-menu {
            display: block;
        }

        /* Hero Image */
        .hero-image {
            background-image: linear-gradient(to right, #cae9f5, #6fdce8); /* Cool gradient from light blue to dark blue */
            height: 100vh; /* Full viewport height */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
        }

        .hero-text {
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
        }

        .hero-text h1 {
            font-size: 3rem;
        }

        .hero-text p {
            font-size: 1.5rem;
        }

        .hero-text button {
            border: none;
            outline: 0;
            display: inline-block;
            padding: 10px 25px;
            color: black;
            background-color: #ddd;
            text-align: center;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 20px;
        }

        .hero-text button:hover {
            background-color: #555;
            color: white;
        }

        main {
            padding: 20px;
            margin-top: 100px; /* Offset the content below the fixed navbar */
        }

        .recent-questions {
            margin-top: 20px;
        }

        .question-list {
            list-style-type: none;
            padding: 0;
        }

        .question-item {
            margin-bottom: 20px;
        }

        .question-item h3 {
            margin: 0;
            font-size: 1.5rem;
        }

        .question-item p {
            color: #555;
        }

        .question-item small {
            color: #888;
        }

        /* Footer Styling */
        .footer {
            background-color: #86C5D8;
            color: white;
            padding: 20px 0;
            text-align: center;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
        }
    </style>
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

    <!-- Hero Section -->
    <div class="hero-image">
        <div class="hero-text">
            <h1>Welcome to Student Forums</h1>
            <p>Ask questions, get answers, and connect with the community!</p>
        </div>
    </div>
</header>

<main>
    <div class="recent-questions">
        <h2>Recent Questions</h2>
        <?php if ($result->num_rows > 0): ?>
            <ul class="question-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="question-item">
                        <h3><a href="/php/view_question.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h3>
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

<script>
// JavaScript to reveal the navbar after scrolling
window.addEventListener('scroll', function() {
    const heroSection = document.querySelector('.hero-image');
    const navbar = document.querySelector('.navbar');
    const scrollPosition = window.scrollY;

    if (scrollPosition > 200) {
        navbar.style.backgroundColor = '#86C5D8';
    } else {
        navbar.style.backgroundColor = 'transparent'; // Reset navbar background color
    }
});
</script>

<script src="/scripts/modal.js"></script>
</body>
</html>
