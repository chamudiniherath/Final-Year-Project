<?php
session_start();
require_once "connect.php";

// Make sure to sanitize inputs to prevent SQL Injection
$connection = new mysqli($host, $db_user, $db_password, $db_name);

if ($connection->connect_errno != 0) {
    echo "Error: " . $connection->connect_errno . "<br>";
    echo "Description: " . $connection->connect_error;
} else {
    // Assuming fullName and shortName are properly sanitized before insertion
    $fullName = $connection->real_escape_string($_POST['full']);
    $shortName = $connection->real_escape_string($_POST['short']);
    $user = $connection->real_escape_string($_SESSION['user']); // Retrieve the user from session

    // Modify the SQL to include the user column
    $sql = "INSERT INTO projects (`Full name`, `Short name`, `user`) VALUES ('$fullName', '$shortName', '$user')";

    if ($connection->query($sql)) {
        $_SESSION['newProjectSuccess'] = '<span class="success-msg">Project is added successfully.</span>';
        unset($_SESSION['addProjectError']);
        header('Location: index.php');
    } else {
        $_SESSION['addProjectError'] = '<span class="error-msg">Sorry! The project could not be added.</span>';
        // Redirecting back to the newProject page might be more appropriate
        header('Location: newProject.php');
    }
    $connection->close();
}
?>
