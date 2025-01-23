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

// Fetch key metrics
$total_users = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$total_posts = $conn->query("SELECT COUNT(*) AS count FROM questions")->fetch_assoc()['count'];
$total_comments = $conn->query("SELECT COUNT(*) AS count FROM comments")->fetch_assoc()['count'];
$total_likes = $conn->query("SELECT COUNT(*) AS count FROM likes")->fetch_assoc()['count'];

// Fetch recent user activity
$recent_users = $conn->query("SELECT COUNT(*) AS count FROM users WHERE DATE(created_at) > DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="d-flex">
        <?php include('sidebar.php'); ?>
        <div class="content p-4 w-100">
            <h1>Reports</h1>
<!-- Analytics Cards -->
<div class="row">
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-header text-center">
                        <i class="card-icon fas fa-users"></i>
                        <h4>Total Users</h4>
                    </div>
                    <div class="card-body text-center">
                        <h2><?php echo $total_users; ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-header text-center">
                        <i class="card-icon fas fa-comments"></i>
                        <h4>Total Posts</h4>
                    </div>
                    <div class="card-body text-center">
                        <h2><?php echo $total_posts; ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-header text-center">
                        <i class="card-icon fas fa-reply-all"></i>
                        <h4>Total Comments</h4>
                    </div>
                    <div class="card-body text-center">
                        <h2><?php echo $total_comments; ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-header text-center">
                        <i class="card-icon fas fa-thumbs-up"></i>
                        <h4>Total Likes</h4>
                    </div>
                    <div class="card-body text-center">
                        <h2><?php echo $total_likes; ?></h2>
                    </div>
                </div>
            </div>
        </div>

            <!-- Recent Activity -->
            <div class="mb-4">
                <h3>New Users in the Last 30 Days: <?php echo $recent_users; ?></h3>
            </div>

            <!-- Charts Section -->
            <div class="row">
                <div class="col-md-6">
                    <canvas id="pieChart"></canvas>
                </div>
                <div class="col-md-6">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Pie Chart: Distribution of Users, Posts, and Comments
        const ctx1 = document.getElementById('pieChart').getContext('2d');
        new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: ['Users', 'Posts', 'Comments', 'Likes'],
                datasets: [{
                    data: [<?php echo $total_users; ?>, <?php echo $total_posts; ?>, <?php echo $total_comments; ?>, <?php echo $total_likes; ?>],
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545']
                }]
            }
        });

        // Line Chart: Example Data for New Users Over 6 Months
        const ctx2 = document.getElementById('lineChart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['June', 'July', 'August', 'September', 'October', 'November'],
                datasets: [{
                    label: 'New Users',
                    data: [10, 20, 15, 25, 30, 40], // Replace with dynamic data
                    borderColor: '#007bff',
                    fill: false
                }]
            }
        });
    </script>
</body>
</html>
