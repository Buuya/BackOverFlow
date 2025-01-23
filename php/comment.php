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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_id = intval($_POST['question_id']);
    $comment_text = htmlspecialchars($_POST['comment_text']);
    $user_id = $_SESSION['author'] ?? null; // Null for anonymous users

    $stmt = $conn->prepare("INSERT INTO comments (question_id, user_id, comment_text) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $question_id, $user_id, $comment_text);
    if ($stmt->execute()) {
        echo "Comment added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
