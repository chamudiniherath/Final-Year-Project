<?php
session_start();
if (!isset($_SESSION['logged-in'])) {
    header('Location: login.php');
    exit();
}

require_once "connect.php";
$connection = new mysqli($host, $db_user, $db_password, $db_name);

if ($connection->connect_errno != 0) {
    echo "Error: " . $connection->connect_errno;
} else {
    if(isset($_GET['sn'])){
        $shortName = $connection->real_escape_string($_GET['sn']);

        // Perform the deletion
        $stmt = $connection->prepare("DELETE FROM projects WHERE `Short name` = ?");
        $stmt->bind_param("s", $shortName);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Project deleted successfully.";
        } else {
            $_SESSION['message'] = "Error deleting project.";
        }
        $stmt->close();
    }
    $connection->close();
    header('Location: index.php'); // Redirect back to the projects list
    exit();
}
?>
