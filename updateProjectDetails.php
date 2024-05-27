<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['logged-in'])) {
    header('Location: login.php');
    exit();
}

require_once "connect.php";
$connection = new mysqli($host, $db_user, $db_password, $db_name);

// Check connection
if ($connection->connect_errno != 0) {
    echo "Error: " . $connection->connect_errno . "<br>";
    echo "Description: " . $connection->connect_error;
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Extract and sanitize input
    $originalShortName = $connection->real_escape_string($_POST['originalShortName']);
    $fullName = $connection->real_escape_string($_POST['full_name']);
    $shortName = $connection->real_escape_string($_POST['short_name']);

    // Prepare SQL statement to prevent SQL injection
    $stmt = $connection->prepare("UPDATE `projects` SET `Full name` = ?, `Short name` = ? WHERE `Short name` = ?");
    $stmt->bind_param("sss", $fullName, $shortName, $originalShortName);

    // Execute the query and check for success
    if ($stmt->execute()) {
        $_SESSION['update_success'] = "Project updated successfully.";
    } else {
        $_SESSION['update_success'] = "Error updating project: " . $stmt->error;
    }

   
    $stmt->close();
    $connection->close();

    // Redirect back to the project list or edit page
    header('Location: index.php');
    exit();
}
?>
