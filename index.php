<?php
session_start();

// Redirect the user to the login page if not logged in
if (!isset($_SESSION['logged-in'])) {
    header('Location: login.php');
    exit();
}


require_once "connect.php";
$connection = new mysqli($host, $db_user, $db_password, $db_name);

if ($connection->connect_errno != 0) {
    echo "Error: " . $connection->connect_errno . "<br>";
    echo "Description: " . $connection->connect_error;
    exit();
}

// Flag to determine if a redirect is needed after processing
$redirectNeeded = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['newPassword']) && isset($_POST['confirmPassword'])) {
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($newPassword === $confirmPassword) {
            $username = $_SESSION['user'];

            $hashedPassword = $newPassword;

            $updateStmt = $connection->prepare("UPDATE users SET password = ? WHERE login = ?");
            $updateStmt->bind_param("ss", $hashedPassword, $username);
            if ($updateStmt->execute()) {
                $_SESSION['password_changed'] = "Password changed successfully!";
            } else {
                $_SESSION['password_error'] = "Failed to change password.";
            }
            $redirectNeeded = true;
        } else {
            $_SESSION['password_error'] = "Passwords do not match.";
            $redirectNeeded = true;
        }
    }
}

if ($redirectNeeded) {
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<?php include 'header.php'; ?>




<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
    .btn {
        padding: 5px 10px;
        border-radius: 4px;
        text-decoration: none;
        color: white;
        font-size: 14px;
        margin-right: 5px;
        display: inline-block;
        text-align: center;
    }

    .btn-update {
        background-color: #28a745;
    }

    .btn-delete {
        background-color: #dc3545;
    }

    .btn-update:hover,
    .btn-delete:hover {
        opacity: 0.8;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #ccc;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #dc3545;
        width: 80%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: red;
        text-decoration: none;
        cursor: pointer;
    }

    .success-message,
    .error-message {
        color: #28a745;
    }

    .error-message {
        color: #dc3545;
    }

    .search-container {
        margin-bottom: 20px;
        text-align: center;
    }

    #searchInput {
        padding: 10px;
        width: 50%;
        font-size: 16px;
        border: 2px solid #ddd;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        #searchInput {
            width: 90%;
            font-size: 14px;
        }

        .btn,
        .modal-content,
        .project-list {
            width: 100%;
        }

        .modal-content {
            margin: 10% auto;
        }

        .project-list th,
        .project-list td {
            padding: 8px;
        }

        .lg-12 {
            overflow-x: auto;
        }
    }

    @media (max-width: 480px) {
        .btn {
            margin-bottom: 5px;
            padding: 5px;
            font-size: 12px;
        }

        .whoami,
        .createBoard {
            text-align: center;
            margin-bottom: 10px;
        }
    }



    body,
    html {
        height: 100%;
        margin: 0;
        font-family: Arial, sans-serif;
        background: linear-gradient(to right, #081F45, #081F45);
        color: #EFE2E2;
    }

    .container.projectListContainer {

        background: rgb(11, 41, 93);
       
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }



    .project-list {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
    }

    .project-list th,
    .project-list td {
        text-align: left;
        padding: 12px;
    }

    .project-list tr:nth-child(even) {
        background-color: #0F3371;
    }

    .btn {
        padding: 5px 10px;
        border-radius: 4px;
        text-decoration: none;
        color: white;
        font-size: 14px;
        margin-right: 5px;
        display: inline-block;
        text-align: center;
    }

    .btn-update {
        background-color: #fefefe;
    }

    .btn-delete {
        background-color: #dc3545;
    }

    .btn-update:hover,
    .btn-delete:hover {
        opacity: 0.8;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .success-message,
    .error-message {
        color: #28a745;
    }

    .error-message {
        color: #dc3545;
    }

    .search-container {
        margin-bottom: 20px;
        text-align: center;
    }

    #searchInput {
        padding: 10px;
        width: 50%;
        font-size: 16px;
        border: 2px solid #ddd;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        #searchInput {
            width: 90%;
            font-size: 14px;
        }

        .btn,
        .modal-content,
        .project-list {
            width: 100%;
        }

        .modal-content {
            margin: 10% auto;
        }

        .project-list th,
        .project-list td {
            padding: 8px;
        }

        .lg-12 {
            overflow-x: auto;
        }
    }

    @media (max-width: 480px) {
        .btn {
            margin-bottom: 5px;
            padding: 5px;
            font-size: 12px;
        }

        .whoami,
        .createBoard {
            text-align: center;
            margin-bottom: 10px;
        }
    }




    .btn {
        padding: 5px 10px;
        border-radius: 4px;
        text-decoration: none;
        color: white;
        font-size: 14px;
        margin-right: 5px;
        display: inline-block;
        /* Added for better button-like appearance */
        text-align: center;
    }

    .btn-update {
        background-color: #28a745;
        /* Green */
    }

    .btn-delete {
        background-color: #dc3545;
        /* Red */
    }

    .btn-update:hover,
    .btn-delete:hover {
        opacity: 0.8;
        
    }


    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #AAB6CB;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* Add style for success and error messages */
    .success-message {
        color: #28a745;
       
    }

    .error-message {
        color: #dc3545;
       
    }
</style>

<div class="container projectListContainer">
    <?php
    // Displaying the image to the left side at the top
    echo '<img src="data:image/png;base64,';
    echo base64_encode(file_get_contents('C:\Users\chamu\Downloads\phpdesktop-chrome-57.0-rc-php-7.1.3\phpdesktop-chrome-57.0-rc-php-7.1.3\www\Image\Task1.png'));
    echo '" alt="Embedded Image" style="width: 100px; height: auto; float: left; margin-right: 20px;">';
    ?>
    <h1>Projects list</h1>

    <?php
    if (isset($_SESSION['password_changed'])) {
        echo '<div class="success-message">' . $_SESSION['password_changed'] . '</div>';
        unset($_SESSION['password_changed']);
        // Add JavaScript code to remove the success message after 2 seconds
        echo '<script>setTimeout(function() { document.querySelector(".success-message").remove(); }, 2000);</script>';
    }
    if (isset($_SESSION['password_error'])) {
        echo '<div class="error-message">' . $_SESSION['password_error'] . '</div>';
        unset($_SESSION['password_error']);
     echo '<script>setTimeout(function() { document.querySelector(".error-message").remove(); }, 2000);</script>';
    }
    ?>
    <div class="lg-6 whoami">
        <?php echo 'Logged in as <strong>' . $_SESSION['user'] . '</strong> <a href="logout.php">[logout]</a>'; ?>
    </div>
    <div class="lg-6 createBoard">
        <a href="newProject.php" class="btn">Create board</a>
        <button class="btn btn-update" onclick="openModal()">Change Password</button>
    </div>


    <style>
        .search-container {
            margin-bottom: 20px;
            text-align: center;
        }

        #searchInput {
            padding: 10px;
            width: 50%;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .table-row-display {
            display: none;
        }

        .table-row-display td {
            display: table-cell;
        }
    </style>

    <script>
        function searchProjects() {
            var input, filter, table, tr, td, i;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.querySelector(".project-list");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td");
                if (td.length > 1) { // Ensures that this does not apply to the header row
                    var fullName = td[0].textContent || td[0].innerText;
                    var shortName = td[1].textContent || td[1].innerText;
                    if (fullName.toUpperCase().indexOf(filter) > -1 || shortName.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>









    <div class="lg-12">
        <br>
        <div class="search-container">
            <input type="text" id="searchInput" onkeyup="searchProjects()" placeholder="Search for projects...">
        </div>
        <table class="project-list">
            <thead>
                <tr>
                    <th>Full name</th>
                    <th>Short name</th>
                    <th>Tasks left</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $currentUser = $connection->real_escape_string($_SESSION['user']);
                $sql = "SELECT * FROM projects WHERE `user` = '$currentUser'";

                if ($result = $connection->query($sql)) {
                    $projectsCount = $result->num_rows;
                    if ($projectsCount > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            $sn = $row['Short name'];
                            $sumSQL = "SELECT count(*) as tasksLeft FROM `tasks` WHERE project_short_name = '$sn' AND state != 4";
                            $sumResult = $connection->query($sumSQL);
                            $row2 = $sumResult->fetch_assoc();

                            echo "
                                <tr>
                                    <td>" . htmlspecialchars($row['Full name']) . "</td>
                                    <td>" . htmlspecialchars($row['Short name']) . "</td>
                                    <td>" . htmlspecialchars($row2['tasksLeft']) . "</td>
                                    <td>
                                        <a href='board.php?sn=" . urlencode($row['Short name']) . "' class='btn btn-board'>Board</a>
                                        <a href='updateProject.php?sn=" . urlencode($row['Short name']) . "' class='btn btn-update'>Update</a>
                                        <a href='javascript:void(0);' onclick='confirmDelete(\"" . urlencode($row['Short name']) . "\")' class='btn btn-delete'>Delete</a>
                                    </td>
                                </tr>
                            ";
                        }
                        $result->free_result();
                    } else {
                        echo "<tr><td colspan='4'>No projects found for the current user.</td></tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Change Password</h2>
        <form id="changePasswordForm" method="post" action="">
            <label for="newPassword">New Password:</label>
            <input type="password" id="newPassword" name="newPassword" required><br><br>
            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required><br><br>
            <input type="submit" value="Change Password">
        </form>
    </div>
</div>

<script>
    function openModal() {
        var modal = document.getElementById("changePasswordModal");
        modal.style.display = "block";
    }

    function closeModal() {
        var modal = document.getElementById("changePasswordModal");
        modal.style.display = "none";
    }

    function confirmDelete(shortName) {
        var confirmAction = confirm("Are you sure you want to delete this project? This action cannot be undone.");
        if (confirmAction) {
            window.location.href = "deleteProject.php?sn=" + shortName;
        } else {
          
        }
    }
</script>

<?php include 'footer.php'; ?>