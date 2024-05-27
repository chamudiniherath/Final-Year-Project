<?php
    session_start();
    if(!(isset($_SESSION['logged-in']))){
        header('Location: login.php');
        exit();
    }
    $shortName = $_GET['sn'];
?>
<?php include 'header.php';?>





<style>


body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #051B40 0%,#051B40 100%);
}

.container.task-view {
    max-width: 600px;
    margin: 40px auto;
    padding: 20px;
    background-color: rgba(255, 255, 255, 0.95);
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

h1, label {
    color:#BECCE4;
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

button[type="submit"] {
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
    background-color: #45a049;
}

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
        color: #fff;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: #051B40 no-repeat right center / 20px;
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



<?php
    // Displaying the image at the top left corner
    echo '<img src="data:image/png;base64,';
    echo base64_encode(file_get_contents('C:\Users\chamu\Downloads\phpdesktop-chrome-57.0-rc-php-7.1.3\phpdesktop-chrome-57.0-rc-php-7.1.3\www\Image\Task1.png'));
    echo '" alt="Embedded Image" style="width: 100px; height: auto; position: absolute; top: 50; left: 40;">';
?>


<div class="container loginContainer">
    
    <br style="clear:both;"/>
    <br /><br /><br /><br /><br /><br /><br />
    <h1>New Task <span>(<?php echo $_GET['sn']; ?>)</span></h1>
    <div class="login-box newTaskBox">
        <form method="post" action="newTaskValidation.php?sn=<?php echo $shortName; ?>">
            <div class="input-box">
                <label for="taskTitle">Title:</label>
                <textarea name="taskTitle" class="taskTitle"></textarea>
            </div>
            <div class="input-box">
                <label for="taskDescription">Description:</label>
                <textarea name="taskDescription" class="taskDesc"></textarea>
            </div>
            <div class="input-box">
                <label for="taskPriority">Priority:</label>
                <select name="taskPriority" class="taskPriority">
                    <option value="1">High</option>
                    <option value="2">Medium</option>
                    <option value="3">Low</option>
                </select>
            </div>
            <button type="submit">Add</button>
        </form>
        <?php
            if(isset($_SESSION['addProjectError'])){
                echo $_SESSION['addProjectError'];
                unset($_SESSION['addProjectError']);
            }
        ?>
    </div>
</div>

<?php include 'footer.php';?>
