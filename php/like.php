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
    $user_id = $_SESSION['user_id'] ?? null;

    $stmt = $conn->prepare("INSERT INTO likes (question_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $question_id, $user_id);
    if ($stmt->execute()) {
        echo "Like added.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
