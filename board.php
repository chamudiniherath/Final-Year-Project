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
?>






<style>

body, html {
    height: 100%;
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(to right, #05193D 0%, #05193D 100%);
    color: #555;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.task-list-container {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.task-list h1, .task-list h2, .task-list h3 {
    color: #555;
}

.task-box {
    background: #000000;
    margin-bottom: 15px;
    padding: 15px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.task-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.delete-task-btn {
    padding: 10px 15px;
    background-color: #e66767;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.delete-task-btn:hover {
    background-color:black ;
}

/* Search Input Styling */
#searchTaskInput {
    padding: 10px;
    border: 2px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    width: calc(100% - 24px);
    margin-bottom: 20px;
}

/* Button Styling */
.btn {
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
    color: white;
    font-size: 16px;
    background-color: #0B234C;
    border: none;
    cursor: pointer;
    display: inline-block;
    margin-right: 10px;
    text-align: center;
}

.btn:hover {
    background-color: #0B234C;
}

.btn-board {
    background-color: #3498db;
}

.btn-board:hover {
    background-color: #2980b9;
}

.btn-update {
    background-color: #27ae60;
}

.btn-update:hover {
    background-color: #2ecc71;
}

/* Responsiveness */
@media (max-width: 768px) {
    .btn, #searchTaskInput, .task-box, .modal-content {
        width: 100%;
        box-sizing: border-box;
    }
}

    .delete-task-btn {
    padding: 8px 12px;
    background-color: #d9534f; 
    color: white;
    text-align: center;
    text-decoration: none;
    font-size: 14px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s;
}

.delete-task-btn:hover {
    background-color: #c9302c; /* Darken color on hover */
}

</style>
<?php include 'header.php';?>

<?php
    $sql = "SELECT * FROM `projects` WHERE `Short name` = '$shortName'";
     if($result = $connection->query($sql)){
        $rowsCount = $result->num_rows;
        if($rowsCount>0){
            $row = $result->fetch_assoc();
            $result->free_result();
        }
        else{
            echo '<span class="error-msg">sql error</span>';
        } 
    }
?>

<div class="container task-list-container">
<?php
    // Displaying the image at the top left corner
    echo '<img src="data:image/png;base64,';
    echo base64_encode(file_get_contents('C:\Users\chamu\Downloads\phpdesktop-chrome-57.0-rc-php-7.1.3\phpdesktop-chrome-57.0-rc-php-7.1.3\www\Image\Task1.png'));
    echo '" alt="Embedded Image" style="width: 100px; height: auto; position: absolute; top: 50; left: 0;">';
?>

    <h1>Task list</h1>
    <h2>Current project: <strong><?php echo $row['Full name']; ?></strong></h2>
    <div class="lg-6 whoami">
        <?php echo 'Logged in as <strong>' . $_SESSION['user'] . '</strong> <a href="logout.php">[logout]</a>'; ?>
    </div>
    <div class="lg-6 createBoard">
        <a href="newTask.php?sn=<?php echo $shortName ?>" class="btn">Create task</a>
    </div>
    <div class="lg-12">
        <a class="back" href="index.php">&lt;--- Back to projects</a>
    </div>

    <div style="text-align: center; margin-bottom: 20px;">
    <input type="text" id="searchTaskInput" onkeyup="searchTasks()" placeholder="Search for tasks..." style="padding: 10px; width: 20%; font-size: 16px; border: 2px solid #ddd; border-radius: 4px;">
</div>



<script> 
function searchTasks() {
    var input, filter, taskBoxes, i, taskBox, taskName;
    input = document.getElementById("searchTaskInput");
    filter = input.value.toUpperCase();
    taskBoxes = document.getElementsByClassName("task-box");

    for (i = 0; i < taskBoxes.length; i++) {
        taskBox = taskBoxes[i];
        taskName = taskBox.querySelector('.task h4');
        if (taskName) {
            if (taskName.innerHTML.toUpperCase().indexOf(filter) > -1) {
                taskBoxes[i].style.display = "";
            } else {
                taskBoxes[i].style.display = "none";
            }
        }       
    }
}
</script> 




    <div class="task-list">
        <div class="lg-3 backlog">
            <h3>Backlog</h3>
            <div>
                
                <?php
    
                    $sql1 = "SELECT * FROM tasks WHERE project_short_name = '$shortName' AND state = '1'";
                    $sql2 = "SELECT * FROM tasks WHERE project_short_name = '$shortName' AND state = '2'";
                    $sql3 = "SELECT * FROM tasks WHERE project_short_name = '$shortName' AND state = '3'";
                    $sql4 = "SELECT * FROM tasks WHERE project_short_name = '$shortName' AND state = '4'";
           
                    if($result = $connection->query($sql1)){
                        $projectsCount = $result->num_rows;
                        if($projectsCount>0){

                            while ($row = mysqli_fetch_array($result)) {
                                $tn = $row['project_task_num'];
                                echo "
<div class='task-box'><br>
<div class='task-box'>
    <!-- ... other task details ... -->
    <a href='javascript:void(0);' onclick='confirmDelete(\"".urlencode($shortName)."\", \"".urlencode($tn)."\")' class='delete-task-btn'>Delete</a>
</div><br>
    <a href='task.php?sn=".htmlspecialchars($shortName)."&tn=".htmlspecialchars($tn)."' class='task'>
        <h4>".htmlspecialchars($row['task_name'])."</h4>
        <div>
            <span class='task-priority'>Priority: ";
            switch(htmlspecialchars($row['priority'])) {
                case '1':
                    echo "High";
                    break;
                case '2':
                    echo "Medium";
                    break;
                case '3':
                    echo "Low";
                    break;
             
            }
            echo "</span>
            <span class='task-id'>".htmlspecialchars($row['project_short_name'])."-".htmlspecialchars($row['project_task_num'])."</span>
        </div>
    </a>
    <select class='changeStatus' onchange='location = this.value'>
       
        <option value='changeStatus.php?sn=".htmlspecialchars($shortName)."&tn=".htmlspecialchars($tn)."&status=1' selected='selected'>Backlog</option>
        <option value='changeStatus.php?sn=".htmlspecialchars($shortName)."&tn=".htmlspecialchars($tn)."&status=2'>In Progress</option>
        <option value='changeStatus.php?sn=".htmlspecialchars($shortName)."&tn=".htmlspecialchars($tn)."&status=3'>Test</option>
        <option value='changeStatus.php?sn=".htmlspecialchars($shortName)."&tn=".htmlspecialchars($tn)."&status=4'>Done</option>
    </select>
</div>
";

                            }
                            $result->free_result();
                        }
                    }
                
                ?>
            </div>
        </div>

        <script>
function confirmDelete(shortName, taskNum) {
    var confirmDelete = confirm('Do you want to delete this task?');
    if (confirmDelete) {
        // If the user confirmed, redirect to the delete script with the task details
        window.location.href = 'deleteTask.php?sn=' + shortName + '&tn=' + taskNum;
    }
}
</script>

        <div class="lg-3 inprogress">
            <h3>In progress</h3>
            <div>
                <?php
                    if($result = $connection->query($sql2)){
                        $projectsCount = $result->num_rows;
                        if($projectsCount>0){

                            while ($row = mysqli_fetch_array($result)) {
                                $tn = $row['project_task_num'];
                                echo "
                                <div class='task-box'>
                                <br>
<div class='task-box'>
    <!-- ... other task details ... -->
    <a href='javascript:void(0);' onclick='confirmDelete(\"".urlencode($shortName)."\", \"".urlencode($tn)."\")' class='delete-task-btn'>Delete</a>
</div><br>
                                <a href='task.php?sn=".htmlspecialchars($shortName)."&tn=".htmlspecialchars($tn)."' class='task'>
                                <h4>".htmlspecialchars($row['task_name'])."</h4>
                                <div>
                                    <span class='task-priority'>Priority: ";
                                    switch(htmlspecialchars($row['priority'])) {
                                        case '1':
                                            echo "High";
                                            break;
                                        case '2':
                                            echo "Medium";
                                            break;
                                        case '3':
                                            echo "Low";
                                            break;
                                     
                                    }
                                    echo "</span>
                                    <span class='task-id'>".htmlspecialchars($row['project_short_name'])."-".htmlspecialchars($row['project_task_num'])."</span>
                                </div>
                            </a>
                                    <select class='changeStatus' onchange='location = this.value'>
                                        
                                        <option value='changeStatus.php?sn=$shortName&tn=$tn&status=1'>Backlog</option>
                                        <option value='changeStatus.php?sn=$shortName&tn=$tn&status=2'selected='selected'>In progress</option>
                                        <option value='changeStatus.php?sn=$shortName&tn=$tn&status=3'>Test</option>
                                        <option value='changeStatus.php?sn=$shortName&tn=$tn&status=4'>Done</option>
                                    </select>
                                </div>
                                ";

                         

                                
                            }
                            $result->free_result();
                        }
                    }
                ?>
            </div>
        </div>
        <div class="lg-3 test">
            <h3>Test</h3>
            <div>
                <?php
                    if($result = $connection->query($sql3)){
                        $projectsCount = $result->num_rows;
                        if($projectsCount>0){

                            while ($row = mysqli_fetch_array($result)) {
                                $tn = $row['project_task_num'];
                                echo "
                                <div class='task-box'>
                                <br>
<div class='task-box'>
    <!-- ... other task details ... -->
    <a href='javascript:void(0);' onclick='confirmDelete(\"".urlencode($shortName)."\", \"".urlencode($tn)."\")' class='delete-task-btn'>Delete</a>
</div><br>
                                <a href='task.php?sn=".htmlspecialchars($shortName)."&tn=".htmlspecialchars($tn)."' class='task'>
                                <h4>".htmlspecialchars($row['task_name'])."</h4>
                                <div>
                                    <span class='task-priority'>Priority: ";
                                    switch(htmlspecialchars($row['priority'])) {
                                        case '1':
                                            echo "High";
                                            break;
                                        case '2':
                                            echo "Medium";
                                            break;
                                        case '3':
                                            echo "Low";
                                            break;
                                     
                                    }
                                    echo "</span>
                                    <span class='task-id'>".htmlspecialchars($row['project_short_name'])."-".htmlspecialchars($row['project_task_num'])."</span>
                                </div>
                            </a>
                                    <select class='changeStatus' onchange='location = this.value'>
                        
                                        <option value='changeStatus.php?sn=$shortName&tn=$tn&status=1'>Backlog</option>
                                        <option value='changeStatus.php?sn=$shortName&tn=$tn&status=2'>In progress</option>
                                        <option value='changeStatus.php?sn=$shortName&tn=$tn&status=3'selected='selected'>Test</option>
                                        <option value='changeStatus.php?sn=$shortName&tn=$tn&status=4'>Done</option>
                                    </select>
                                </div>
                                ";


                            }
                            $result->free_result();
                        }
                    }
                ?>
            </div>
        </div>
        <div class="lg-3 done">
            <h3>Done</h3>
            <div>
                <?php
                    if($result = $connection->query($sql4)){
                        $projectsCount = $result->num_rows;
                        if($projectsCount>0){

                            while ($row = mysqli_fetch_array($result)) {
                                $tn = $row['project_task_num'];
                                echo "
                                <div class='task-box'>
                                <br>
<div class='task-box'>
    <!-- ... other task details ... -->
    <a href='javascript:void(0);' onclick='confirmDelete(\"".urlencode($shortName)."\", \"".urlencode($tn)."\")' class='delete-task-btn'>Delete</a>
</div><br>
                                <a href='task.php?sn=".htmlspecialchars($shortName)."&tn=".htmlspecialchars($tn)."' class='task'>
                                <h4>".htmlspecialchars($row['task_name'])."</h4>
                                <div>
                                    <span class='task-priority'>Priority: ";
                                    switch(htmlspecialchars($row['priority'])) {
                                        case '1':
                                            echo "High";
                                            break;
                                        case '2':
                                            echo "Medium";
                                            break;
                                        case '3':
                                            echo "Low";
                                            break;
                                     
                                    }
                                    echo "</span>
                                    <span class='task-id'>".htmlspecialchars($row['project_short_name'])."-".htmlspecialchars($row['project_task_num'])."</span>
                                </div>
                            </a>
                                    <select class='changeStatus' onchange='location = this.value'>
                                        <option class='no-display' selected='selected'>Status</option>
                                        <option value='changeStatus.php?sn=$shortName&tn=$tn&status=1'>Backlog</option>
                                        <option value='changeStatus.php?sn=$shortName&tn=$tn&status=2'>In progress</option>
                                        <option value='changeStatus.php?sn=$shortName&tn=$tn&status=3'>Test</option>
                                        <option value='changeStatus.php?sn=$shortName&tn=$tn&status=4' selected='selected'>Done</option>
                                    </select>
                                </div>
                                ";

                            
                            }
                            $result->free_result();
                        }
                    }
                ?>
            </div>
        </div>
    </div>
        
</div>

<?php $connection->close(); ?>
<?php include 'footer.php';?>
