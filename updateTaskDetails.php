<?php
session_start();
if(!isset($_SESSION['logged-in'])){
    header('Location: login.php');
    exit();
}

require_once "connect.php";

$connection = new mysqli($host, $db_user, $db_password, $db_name);

if($connection->connect_errno){
    echo "Error: ".$connection->connect_errno;
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Get POST data
    $task_id = $_POST['task_id'];
    $task_name = $_POST['task_name'];
    $task_desc = $_POST['task_desc'];
    $new_priority = $_POST['new_priority'];

    // Prepare and bind
    $stmt = $connection->prepare("UPDATE tasks SET task_name=?, task_desc=?, priority=? WHERE id=?");
    $stmt->bind_param("ssii", $task_name, $task_desc, $new_priority, $task_id);

    // Execute the statement
if($stmt->execute()){
    // Set a success message in the session
    $_SESSION['update_success'] = "Task updated successfully.";

    // Redirect to task.php with the short name and task number
    header('Location: task.php?sn=' . urlencode($_POST['shortName']) . '&tn=' . urlencode($_POST['taskNum']));
    exit(); // Make sure to call exit after headers to ensure the script stops executing
} else {
    // Error
    echo "Error updating record: " . $stmt->error;
}

    $stmt->close();
}

$connection->close();
?>
