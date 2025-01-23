<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "forum";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";
$success = "";

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if username or email already exists
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username or email already taken.";
        } else {
            // Insert new user into database
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = "Registration successful! You can now <a href='login.php'>log in</a>.";
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayub's Forum - Register</title>
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
        <h2>Create an Account</h2>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" name="register">Register</button>
        </form>
        
        <p>Already have an account? <a href="login.php">Login here</a></p>

        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
