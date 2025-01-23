<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "forum";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$question_id = $_POST['question_id'];
$comment_text = $_POST['comment_text'];
$author = "User"; // Use the logged-in user's name or guest

// Insert the comment into the database
$query = "INSERT INTO comments (question_id, comment_text, author) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iss", $question_id, $comment_text, $author);
$stmt->execute();

echo json_encode(['status' => 'success']);
?>
