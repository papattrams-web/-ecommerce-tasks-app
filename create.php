<?php
require "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $status = $_POST["status"] ?? "pending";
    $allowed_statuses = ["pending", "in_progress", "done"];

    if ($title === "") {
        $error = "Please enter a task title.";
    } elseif (!in_array($status, $allowed_statuses, true)) {
        $error = "Please choose a valid status.";
    } else {
        $stmt = $conn->prepare("INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $description, $status);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="container">
    <h1>Add Task</h1>

    <?php if ($error !== ""): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="create.php">
        <label for="title">Title</label>
        <input id="title" type="text" name="title" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="5"></textarea>

        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="done">Done</option>
        </select>

        <button type="submit">Save Task</button>
    </form>

    <a href="index.php">Back to Tasks</a>
</main>
</body>
</html>
