<?php
session_start();
require_once "connect.php";

$connection = new mysqli($host, $db_user, $db_password, $db_name);
$shortName = $_GET['sn'];
if ($connection->connect_errno != 0) {
    echo "Error: " . $connection->connect_errno . "<br>";
    echo "Description: " . $connection->connect_error;
    exit();
} else {
    // Assume task priority is sent via POST method
    $taskTitle = $connection->real_escape_string($_POST['taskTitle']);
    $taskDescription = $connection->real_escape_string($_POST['taskDescription']);
    $taskPriority = isset($_POST['taskPriority']) ? intval($_POST['taskPriority']) : 0;  

    // Validate priority
    if ($taskPriority < 1 || $taskPriority > 3) { // assuming 1 is highest priority and 3 is lowest
        echo "Invalid task priority.";
        exit();
    }

    // Get the current count of tasks for the project
    $sqlCount = "SELECT * FROM `tasks` WHERE `project_short_name` = '$shortName'";
    if ($result = $connection->query($sqlCount)) {
        $taskCount = $result->num_rows + 1;
        
        // Now include the priority in the SQL insert statement
        $sql = "INSERT INTO `tasks` (`id`, `project_short_name`, `project_task_num`, `task_name`, `task_desc`, `state`, `priority`) VALUES (NULL, ?, ?, ?, ?, 1, ?)";
        
        $stmt = $connection->prepare($sql);
        if ($stmt) {
            // Bind parameters to prevent SQL injection
            $stmt->bind_param("sissi", $shortName, $taskCount, $taskTitle, $taskDescription, $taskPriority);
            if ($stmt->execute()) {
                header('Location: board.php?sn=' . $shortName);
            } else {
                echo '2nd sql error';
            }
            $stmt->close();
        } else {
            echo 'Prepare statement error';
        }
    } else {
        echo '1st sql error';
    }
    $connection->close();
}
?>
