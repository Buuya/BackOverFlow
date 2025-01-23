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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post'])) {
    $post_id = $_POST['post_id'];
    $conn->query("DELETE FROM questions WHERE id=$post_id");
}

$posts = $conn->query("SELECT * FROM questions ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fa;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }
        .card-body {
            padding: 2rem;
        }
        .card-header {
            background: linear-gradient(90deg, #007bff, #00c6ff);
            color: white;
            padding: 1rem;
            border-radius: 12px 12px 0 0;
        }
        .card-icon {
            font-size: 3rem;
            color: white;
        }
        .content {
            margin-left: 250px; /* Adjust this if sidebar is wider */
        }
        .sidebar {
            background-color: #343a40;
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 50px;
        }
        .sidebar a {
            color: white;
            padding: 12px;
            text-decoration: none;
            display: block;
            font-size: 1.2rem;
            transition: background-color 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #007bff;
        }
        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
        }
        .charts-container {
            margin-top: 30px;
        }
        h1 {
            font-size: 36px;
            font-weight: 500;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include('sidebar.php'); ?>
        <div class="content p-4 w-100">
            <h1>Manage Posts</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($post = $posts->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $post['id']; ?></td>
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            <td><?php echo htmlspecialchars($post['author']); ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                    <button type="submit" name="delete_post" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
