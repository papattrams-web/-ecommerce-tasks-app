<?php
require "db.php";

$sql = "CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('pending', 'in_progress', 'done') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    $message = "Setup complete!";
} else {
    $message = "Error creating table: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="container">
    <h1>Tasks App Setup</h1>
    <p><?php echo htmlspecialchars($message); ?></p>
    <a class="button" href="index.php">Go to Tasks App</a>
</main>
</body>
</html>
