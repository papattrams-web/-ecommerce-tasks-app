<?php
require "db.php";

$id = intval($_GET["id"] ?? $_POST["id"] ?? 0);
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
        $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $description, $status, $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        header("Location: index.php");
        exit;
    }
}

$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$task = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$task) {
    die("Task not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="container">
    <h1>Edit Task</h1>

    <?php if ($error !== ""): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="edit.php">
        <input type="hidden" name="id" value="<?php echo $task["id"]; ?>">

        <label for="title">Title</label>
        <input id="title" type="text" name="title" value="<?php echo htmlspecialchars($task["title"]); ?>" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="5"><?php echo htmlspecialchars($task["description"]); ?></textarea>

        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="pending" <?php echo $task["status"] === "pending" ? "selected" : ""; ?>>Pending</option>
            <option value="in_progress" <?php echo $task["status"] === "in_progress" ? "selected" : ""; ?>>In Progress</option>
            <option value="done" <?php echo $task["status"] === "done" ? "selected" : ""; ?>>Done</option>
        </select>

        <button type="submit">Update Task</button>
    </form>

    <a href="index.php">Back to Tasks</a>
</main>
</body>
</html>
