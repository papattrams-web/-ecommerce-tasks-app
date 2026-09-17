<?php
require "db.php";
$result = $conn->query("SELECT * FROM tasks ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="container">
    <h1>My Task List</h1>
    <a class="button" href="create.php">Add Task</a>

    <?php if ($result->num_rows === 0): ?>
        <p class="empty-message">No tasks have been added yet.</p>
    <?php endif; ?>

    <?php while ($task = $result->fetch_assoc()): ?>
        <section class="task">
            <h2><?php echo htmlspecialchars($task["title"]); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($task["description"])); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars(str_replace("_", " ", $task["status"])); ?></p>
            <a href="edit.php?id=<?php echo $task["id"]; ?>">Edit</a>
            <a href="delete.php?id=<?php echo $task["id"]; ?>" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
        </section>
    <?php endwhile; ?>
</main>
</body>
</html>

<?php $conn->close(); ?>
