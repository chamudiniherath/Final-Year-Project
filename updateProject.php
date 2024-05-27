<?php
session_start();
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

$shortName = $_GET['sn'];

$sql = "SELECT * FROM `projects` WHERE `Short name` = '$shortName'";
if ($result = $connection->query($sql)) {
    if ($result->num_rows > 0) {
        $project = $result->fetch_assoc();
        $result->free_result();
    } else {
        echo '<span class="error-msg">Project not found.</span>';
        exit();
    }
}
?>


<?php include 'header.php'; ?>

<style>
    body,
    html {
        height: 100%;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(120deg, #071E47 0%, #092048 100%);
        color: #333;
    }

    .container.project-view {
        width: 100%;
        max-width: 600px;
        background-color: rgba(255, 255, 255, 0.95);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        margin: 40px auto;
        position: relative;
        z-index: 2;
    }

    .single-project {
        background: #ffffff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-size: 16px;
        color: #333;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 14px;
    }

    .btn-update {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-update:hover {
        background-color: #45a049;
    }

    .back {
        display: inline-block;
        margin-bottom: 20px;
        color: #007bff;
        text-decoration: none;
        font-size: 16px;
    }

    .back:hover {
        text-decoration: underline;
    }

    .popup {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #4CAF50;
        color: white;
        padding: 12px 20px;
        border-radius: 5px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
        opacity: 1;
        transition: opacity 0.5s ease;
    }


    /* Container and Form Styling */
    .container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .single-project {
        background: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-size: 16px;
        color: #333;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 14px;
    }

    .btn-update {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .btn-update:hover {
        background-color: #45a049;
    }

    /* Back link styling */
    .back {
        display: inline-block;
        margin-bottom: 20px;
        color: #007bff;
        text-decoration: none;
        font-size: 16px;
    }

    .back:hover {
        text-decoration: underline;
    }

    /* Popup Message Styling */
    .popup {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #4CAF50;
        color: white;
        padding: 12px 20px;
        border-radius: 5px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
        opacity: 1;
        transition: opacity 0.5s ease;
    }
</style>
<?php
// Displaying the image at the top left corner
echo '<img src="data:image/png;base64,';
echo base64_encode(file_get_contents('C:\Users\chamu\Downloads\phpdesktop-chrome-57.0-rc-php-7.1.3\phpdesktop-chrome-57.0-rc-php-7.1.3\www\Image\Task1.png'));
echo '" alt="Embedded Image" style="width: 100px; height: auto; position: absolute; top: 50; left: 30;">';
?>

<div class="container project-view">
    <h1>Edit Project: <?php echo htmlspecialchars($project['Full name']); ?></h1>
    <div class="lg-12">
        <a class="back" href="index.php">&lt;-- Back to project list</a>
    </div>

    <div class="lg-12 single-project">
        <form action="updateProjectDetails.php" method="post">
            <input type="hidden" name="originalShortName" value="<?php echo htmlspecialchars($shortName); ?>">

            <label for="full_name">Full Project Name:</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($project['Full name']); ?>" required>

            <label for="short_name">Short Project Name:</label>
            <input type="text" id="short_name" name="short_name" value="<?php echo htmlspecialchars($project['Short name']); ?>" required>

            <button type="submit" class="btn-update">Update Project</button>
        </form>
    </div>
</div>

<?php if (isset($_SESSION['update_success'])) : ?>
    <div id="success-popup" class="popup">
        <?php
        echo $_SESSION['update_success'];
        unset($_SESSION['update_success']);
        ?>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            setTimeout(function() {
                var successPopup = document.getElementById('success-popup');
                successPopup.style.opacity = '0';
               
                
                setTimeout(function() {
                    successPopup.remove();
                },500);
                
           },3000 );// Display the popup for 3 seconds before fading out
        });

    </script> 
    


<?php endif; ?>

<?php $connection->close(); ?>
<?php include 'footer.php'; ?>