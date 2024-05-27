<?php
session_start();
if (!isset($_SESSION['logged-in'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['sn']) || !isset($_GET['tn'])) {
    header('Location: index.php');
    exit();
}

require_once "connect.php";
$connection = new mysqli($host, $db_user, $db_password, $db_name);

if ($connection->connect_errno != 0) {
    echo "Error: ".$connection->connect_errno;
    exit();
}

$shortName = $connection->real_escape_string($_GET['sn']);
$taskNum = intval($_GET['tn']);

// Prepare the delete statement
$stmt = $connection->prepare("DELETE FROM tasks WHERE project_short_name = ? AND project_task_num = ?");
$stmt->bind_param("si", $shortName, $taskNum);

if ($stmt->execute()) {
    $_SESSION['task_deleted'] = "Task deleted successfully.";
} else {
    $_SESSION['task_deleted_error'] = "Error deleting task: " . $stmt->error;
}

$stmt->close();
$connection->close();

// Redirect back to the task list page
header('Location: board.php?sn='.urlencode($shortName));
exit();
?>
