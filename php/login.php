<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "forum";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to fetch user data
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);  // Bind both username and email
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();  // Fetch user data

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, create session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            // Check if the user is an admin
            if ($user['is_admin'] == 1) {
                $_SESSION['is_admin'] = true;  // Set a session for admin users
                header("Location: dashboard.php");  // Redirect to admin dashboard
            } else {
                header("Location: index.php");  // Redirect to home page for regular users
            }
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "No user found with that username/email.";
    }
}
?>

<!-- HTML for the login form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayub's Forum - Login</title>
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

    <div class="form-container">
        <h2>Login</h2>

        <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>

        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username or Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>

        <p>New here? <a href="register.php">Create an account</a></p>
    </div>
</body>
</html>
