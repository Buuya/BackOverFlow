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

// Fetch analytics
$total_users = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$total_posts = $conn->query("SELECT COUNT(*) AS count FROM questions")->fetch_assoc()['count'];
$total_comments = $conn->query("SELECT COUNT(*) AS count FROM comments")->fetch_assoc()['count'];
$total_likes = $conn->query("SELECT COUNT(*) AS count FROM likes")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
        <?php include('sidebar.php'); ?>


    <div class="content p-4">
        <h1 class="text-center">Admin Dashboard</h1>

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

        <!-- Charts -->
        <div class="charts-container">
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

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        // Pie Chart
        const ctx1 = document.getElementById('pieChart').getContext('2d');
        new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: ['Users', 'Posts', 'Comments'],
                datasets: [{
                    data: [<?php echo $total_users; ?>, <?php echo $total_posts; ?>, <?php echo $total_comments; ?>],
                    backgroundColor: ['#007bff', '#28a745', '#ffc107']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // Line Chart
        const ctx2 = document.getElementById('lineChart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'New Users',
                    data: [5, 10, 20, 15, 25, 30],
                    borderColor: '#007bff',
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        },
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>
</body>
</html>
