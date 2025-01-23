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

// Get the question ID from the URL (e.g., view_question.php?id=1)
$question_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch the question
$query = "SELECT * FROM questions WHERE id = $question_id";
$question_result = $conn->query($query);
$question = $question_result->fetch_assoc();

// Fetch the question with the tags
$tags = explode(',', $question['tags']);

// Fetch the comments for this question
$comments_query = "SELECT * FROM comments WHERE question_id = $question_id ORDER BY created_at DESC";
$comments_result = $conn->query($comments_query);

// Fetch the like count for this question
$likes_query = "SELECT COUNT(*) AS likes FROM likes WHERE question_id = $question_id";
$likes_result = $conn->query($likes_query);
$likes = $likes_result->fetch_assoc()['likes'];

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text'])) {
    $comment_text = $conn->real_escape_string($_POST['comment_text']);
    $author = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

    $insert_comment_query = "INSERT INTO comments (question_id, comment_text, author) VALUES ($question_id, '$comment_text', '$author')";
    if ($conn->query($insert_comment_query)) {
        header("Location: view_question.php?id=$question_id");  // Refresh page to show the new comment
    } else {
        echo "Error: " . $conn->error;
    }
}

// Handle like functionality
if (isset($_POST['like'])) {
    $user_id = 1;  // Replace with actual user ID (e.g., from session)
    $insert_like_query = "INSERT INTO likes (question_id, user_id) VALUES ($question_id, $user_id)";
    if ($conn->query($insert_like_query)) {
        header("Location: view_question.php?id=$question_id");  // Refresh page to show updated like count
    } else {
        echo "Error: " . $conn->error;
    }
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
    <title>View Question</title>
    <link rel="stylesheet" href="/css/style.css">

    <style>
        /* Centering the content below the navbar */
        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            margin-top: 20px;
        }

        .question-detail {
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .question-detail h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .question-detail p {
            font-size: 16px;
            color: #555;
        }

        .question-actions button {
            background-color: #86C5D8   ;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .question-actions button:hover {
            background-color: #4bc6ff;
        }

        .comments-section {
            max-width: 800px;
            width: 100%;
        }

        .comment-item {
            background-color: #f9f9f9;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .comment-author {
            font-weight: bold;
        }

        .comment-meta {
            font-size: 12px;
            color: #888;
        }

        .comment-form textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }

        .comment-form button {
            padding: 10px 20px;
            background-color: #86C5D8;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .comment-form button:hover {
            background-color: #4bc6ff;
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
            <li><a href="users.php">Users</a></li>
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
<div class="question-detail">
    <h1><?php echo htmlspecialchars($question['title']); ?></h1>
    <p><?php echo htmlspecialchars($question['description']); ?></p>
    <p><strong>Asked by:</strong> <?php echo htmlspecialchars($question['author']); ?> | <em><?php echo date("F j, Y, g:i a", strtotime($question['created_at'])); ?></em></p>
    
    <!-- Display Tags -->
    <p><strong>Tags:</strong>
        <?php if (!empty($tags)): ?>
            <?php foreach ($tags as $tag): ?>
                <span class="tag"><?php echo htmlspecialchars(trim($tag)); ?></span> <!-- Trim any extra spaces -->
            <?php endforeach; ?>
        <?php else: ?>
            <span>No tags available.</span>
        <?php endif; ?>
    </p>

    <div class="question-actions">
        <form method="POST" action="">
            <button type="submit" name="like">👍 Like (<?php echo $likes; ?>)</button>
        </form>
    </div>
</div>


    <div class="comments-section">
        <h2>Comments</h2>

        <?php if ($comments_result->num_rows > 0): ?>
            <?php while ($comment = $comments_result->fetch_assoc()): ?>
                <div class="comment-item">
                    <div class="comment-author">
                        <strong><?php echo htmlspecialchars($comment['author']); ?></strong>
                    </div>
                    <div class="comment-text">
                        <p><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                    </div>
                    <div class="comment-meta">
                        <small>Posted on <?php echo date("F j, Y, g:i a", strtotime($comment['created_at'])); ?></small>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php endif; ?>

        <div class="comment-form">
            <h3>Leave a Comment</h3>
            <form method="POST" action="">
                <textarea name="comment_text" rows="5" placeholder="Write your comment..." required></textarea>
                <button type="submit">Post Comment</button>
            </form>
        </div>
    </div>

</main>

<!-- Footer -->
<footer class="footer">
    <p>&copy; 2024 ForumName. All Rights Reserved.</p>
</footer>

<script src="/script/modal.js"></script>
<script src="/script/comments.js"></script>
<script src="/script/likes.js"></script>

</body>
</html>
