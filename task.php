<?php
    session_start();
    if(!(isset($_SESSION['logged-in']))){
        header('Location: login.php');
        exit();
    }
    if(!(isset($_GET['sn']))){
        header('Location: index.php');
        exit();
    }

    require_once "connect.php";

    $connection = new mysqli($host, $db_user, $db_password, $db_name);

    if($connection->connect_errno!=0){
        echo "Error: ".$connection->connect_errno . "<br>";
        echo "Description: " . $connection->connect_error;
        exit();
    }
    $shortName = $_GET['sn'];
    $taskNum = $_GET['tn'];
?>

<?php include 'header.php';?>

<?php
    $sql = "SELECT * FROM `tasks` WHERE `project_short_name` = '$shortName' AND `project_task_num` = $taskNum";
     if($result = $connection->query($sql)){
        $rowsCount = $result->num_rows;
        if($rowsCount>0){
            $row = $result->fetch_assoc();
            $result->free_result();
        }
        else{
            echo '<span class="error-msg">sql error</span>';
            exit();
        } 
    }
?>
<style>


body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #071F48 0%, #071F48 100%);
}

.container.task-view {
    max-width: 600px;
    margin: 40px auto;
    padding: 20px;
    background-color: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

h1, label {
    color: #555;
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

input[type="text"], textarea, select {
    width: 100%;
    padding: 8px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

textarea {
    height: 150px;
    resize: vertical;
}

select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-position: right 10px center;
    background-repeat: no-repeat;
    padding-right: 30px;
}

/* button[type="submit"] {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 10px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

button[type="submit"]:hover {
    background-color: #4CAF50;
} */

.popup {
    background-color: #4CAF50;
    color: white;
    border-radius: 5px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.2);
    padding: 12px 20px;
    text-align: center;
}

@media (max-width: 768px) {
    .container.task-view {
        margin: 20px;
        padding: 15px;
    }
}

    /* Pop-up message styles */
    .popup {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    padding: 10px 20px;
    background-color: white;
    color: #4CAF50;
    border-radius: 5px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.2);
    opacity: 1;
    transition: opacity 1s ease;
}


    </style>
    <?php
    // Displaying the image at the top left corner
    echo '<img src="data:image/png;base64,';
    echo base64_encode(file_get_contents('C:\Users\chamu\Downloads\phpdesktop-chrome-57.0-rc-php-7.1.3\phpdesktop-chrome-57.0-rc-php-7.1.3\www\Image\Task1.png'));
    echo '" alt="Embedded Image" style="width: 100px; height: auto; position: absolute; top: 50; left: 30;">';
?>
<div class="container task-view">
    <h1><?php echo htmlspecialchars($shortName) . '-' . htmlspecialchars($taskNum) ?></h1>
    <div class="lg-12">
        <a class="back" href="board.php?sn=<?php echo htmlspecialchars($shortName) ?>">&lt;--- Back to board</a>
    </div>

    

    <style>
    .lg-12 {
        max-width: 600px;
        margin: 0 auto;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input[type="text"],
    textarea,
    select {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    select {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: url('https://cdn.iconscout.com/icon/free/png-256/arrow-drop-down-circle-1742877-1474726.png') no-repeat right center / 20px;
    }

    textarea {
        resize: vertical;
    }

    button[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button[type="submit"]:hover {
        background-color: #45a049;
    }
</style>

<div class="lg-12 single-task">
    <form action="updateTaskDetails.php" method="post">
        <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($row['id']); ?>">
        <input type="hidden" name="shortName" value="<?php echo htmlspecialchars($shortName); ?>">
        <input type="hidden" name="taskNum" value="<?php echo htmlspecialchars($taskNum); ?>">

        <label for="task_name">Task Name:</label>
        <input type="text" id="task_name" name="task_name" value="<?php echo htmlspecialchars($row['task_name']); ?>">

        <label for="task_desc">Description:</label>
        <textarea id="task_desc" name="task_desc"><?php echo htmlspecialchars($row['task_desc']); ?></textarea>

        <label for="new_priority">Priority:</label>
        <select id="new_priority" name="new_priority">
            <option value="1" <?php echo ($row['priority'] == '1' ? 'selected' : ''); ?>>High</option>
            <option value="2" <?php echo ($row['priority'] == '2' ? 'selected' : ''); ?>>Medium</option>
            <option value="3" <?php echo ($row['priority'] == '3' ? 'selected' : ''); ?>>Low</option>
        </select>

        <button type="submit">Update Task</button>
    </form>
</div>


</div>


        
</div>
<?php if(isset($_SESSION['update_success'])): ?>
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
                    }, 500); 
                }, 1000);
            });
        </script>
    <?php endif; ?>

<?php $connection->close(); ?>
<?php include 'footer.php';?>
