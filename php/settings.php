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

// Fetch current site settings (replace with actual settings stored in DB or file)
$query = "SELECT * FROM site_settings WHERE id = 1";
$result = $conn->query($query);

// Check if settings exist and fetch
if ($result->num_rows > 0) {
    $current_settings = $result->fetch_assoc();
} else {
    // Set default settings if no settings found
    $current_settings = [
        'site_title' => 'Default Site Title',
        'site_description' => 'Default site description.',
        'admin_email' => 'admin@example.com',
        'enable_notifications' => 1,
        'enable_comment_notifications' => 1,
        'password_complexity' => 'medium'
    ];
}

// Handle form submission and save settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_title = $_POST['site_title'];
    $site_description = $_POST['site_description'];
    $admin_email = $_POST['admin_email'];
    
    // Update settings in the database
    $update_query = "UPDATE site_settings SET site_title='$site_title', site_description='$site_description', admin_email='$admin_email' WHERE id=1";
    if ($conn->query($update_query) === TRUE) {
        $message = "Settings updated successfully!";
    } else {
        $message = "Error updating settings: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
    <div class="d-flex">
        <?php include('sidebar.php'); ?>
        <div class="content p-4 w-100">
            <h1>Settings</h1>
            
            <?php if (isset($message)) { ?>
                <div class="alert alert-info"><?php echo $message; ?></div>
            <?php } ?>
            
            <!-- Site Settings Form -->
            <form method="POST" action="" class="mb-4">
                <h3>Site Information</h3>
                <div class="mb-3">
                    <label for="site_title" class="form-label">Site Title</label>
                    <input type="text" name="site_title" id="site_title" class="form-control" value="<?php echo htmlspecialchars($current_settings['site_title']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="site_description" class="form-label">Site Description</label>
                    <textarea name="site_description" id="site_description" class="form-control" rows="3" required><?php echo htmlspecialchars($current_settings['site_description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="admin_email" class="form-label">Admin Email</label>
                    <input type="email" name="admin_email" id="admin_email" class="form-control" value="<?php echo htmlspecialchars($current_settings['admin_email']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
            
            <!-- Notification Settings Form -->
            <h3>Notification Settings</h3>
            <form method="POST" action="" class="mb-4">
                <div class="mb-3">
                    <label class="form-check-label">Enable Email Notifications for New Posts</label>
                    <input type="checkbox" class="form-check-input" name="enable_notifications" id="enable_notifications" <?php echo $current_settings['enable_notifications'] ? 'checked' : ''; ?>>
                </div>
                <div class="mb-3">
                    <label class="form-check-label">Enable Email Notifications for New Comments</label>
                    <input type="checkbox" class="form-check-input" name="enable_comment_notifications" id="enable_comment_notifications" <?php echo $current_settings['enable_comment_notifications'] ? 'checked' : ''; ?>>
                </div>
                <button type="submit" class="btn btn-primary">Save Notification Settings</button>
            </form>

            <!-- Security Settings Form -->
            <h3>Security Settings</h3>
            <form method="POST" action="" class="mb-4">
                <div class="mb-3">
                    <label class="form-label">Password Complexity Requirements</label>
                    <select class="form-select" name="password_complexity" required>
                        <option value="low" <?php echo $current_settings['password_complexity'] === 'low' ? 'selected' : ''; ?>>Low</option>
                        <option value="medium" <?php echo $current_settings['password_complexity'] === 'medium' ? 'selected' : ''; ?>>Medium</option>
                        <option value="high" <?php echo $current_settings['password_complexity'] === 'high' ? 'selected' : ''; ?>>High</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save Security Settings</button>
            </form>
        </div>
    </div>
</body>
</html>
