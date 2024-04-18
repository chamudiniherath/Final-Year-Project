<?php
    session_start(); //start the session

    if(!(isset($_SESSION['logged-in']))){ 
        header('Location: login.php'); 
        exit();
    }
    
    require_once "connect.php"; //database connection 

    $connection = new mysqli($host, $db_user, $db_password, $db_name); //create a new Mysqli databse connection

    if($connection->connect_errno!=0){ //check if there is a connection error
        echo "Error: ".$connection->connect_errno . "<br>"; //check if there is a databse connection error
        echo "Description: " . $connection->connect_error;
        exit();
    }

    //Flag to determine if a redirect is needed after procession
    $redirectNeeded = false;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['newPassword'])&& isset($_POST['confirmPassword'])){
            $newPassword = $_POST['newPassword'];
            $confirmPassword = $_POST['confirmPassword'];


            if($newPassword === $confirmPassword){
                $username = $_SESSION['user'];

                $hashedPassword = $newPassword;

                $updateStmt = $connection ->prepare("UPDATE users SET password = ? WHERE login =?");
                $updateStml ->bind_param("ss",$hashedPassword, $username );
                if($updateStmt-> execute()) {
                    $_SESSION['password_changed'] = "Password changed successfully!" ;
                } else {
                    $_SESSION['password_error'] = "Failed to change password" ;
                }
                $redirectNeeded = true;

            }else{
                $_SESSION ['password_error']= "Passwords do not much ";
                $redirectNeeded = true;
            }
        }
    }
    if($redirectNeeded) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
    ?>

    <?php include 'header.php '; ?>

    <meta  name="viewport" content="width=device-width, initial-scale=1">

    <style>
    .btn {
        padding: 5px 10px;
        border-radius: 4px;
        text-decoration: none;
        color:aliceblue;
        font-size: 14px;
        margin-right: 5px;
        display: inline-block;
        text-align: center;
    }

    .btn-update{
        background-color: #28a745;
    }

    .btn-delete{
        background-color: #dc3545;
    }

    .btn-update:hover, .btn-delete:hover{
        opacity: 0.8;
    }

    .modal {
        display : none;
        position: fixed;
        z-index: 1;
        left: 0;
        top : 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-position: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color : #fefefe;
        margin: 15% auto;
        padding : 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close{
        color : #aaa;
        float : right;
        font-size : 28px;
        font-weight: bold;
    }

    .close:hover, .close:focus{
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .success-message, .error-message{
        color: #dc3545;
    }

    .search-container{
        margin-bottom: 20px;
        text-align: center;
    }

    #searchInput{
        padding: 10px;
        width: 50%;
        font-size: 16px;
        border: 2px solid #ddd;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    @media (max-width :  768px){
        #searchInput {
            width: 90%;
            font-size: 14px;  
        }

        .btn, .modal-content, .project-list{
            width: 100%;
        }

    .model-content {
        margin: 10% auto;
    }

    .project-list th, .project-list td {
        padding: 8px;
    }

    .lg-12{
        overflow-x: auto;
    }

  @media (max-width: 480p) {
    .btn{
        margin-bottom: 5px;
        padding: 5px;
        font-size: 12px;
    }

    .whoami, .createBoard {
        text-align: center;
        margin-bottom: 10px;
    }

 }


 body, html{
    height: 100%;
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(to right, #6dd5ed, #2193b0 );
    color: #333;
 }

 .cnotainer.projectListContainer {
    background: rgba(255,255,255,0.9);
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    margin-top: 20px;
 }

 .project-list{
    border-collapse: collapse;
    width: 100%;
    margin-top: 20px;

 }

 .project-list th, .project-list td{
    text-align: left;
    padding:12px;

  }

  .project-list tr:nth-child(even){
    background-color: #f2f2f2;
  }

  .btn {
    padding: 5px 10px;
    border-radius: 4px;
    text-decoration: none;
    color: white;
    font-size:14px;
    margin-right: 5px;
    display: inline-block;
    text-align: center;
  }

  .btn-update{
    background-color: #28a745;
  }

  .btn-delete {
    background-color: #dc3545;
  }

  .btn-update:hover, .btn-delete:hover{
    opacity:0.8;
  }

  .modal{
    display: none;
    position:fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color:rgba(0,0,0,0.4)
  }

  .modal-content{
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;

  }

  .close{
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }

  .close:hover, .close:focus{
    color:black;
    text-decoration: none;
    cursor:pointer;

  }

  .success-message, .error-message{
    color:#28a745;
  }

  .error-message{
    color:#dc3545;
  }

  .search-container{
    margin-bottom: 20px;
    text-align:center;
  }

  #searchInput{
    padding: 10px;
    width: 50%;
    font-size: 16px;
    border: 2px solid #ddd;
    border-radius: 4px;
    margin-bottom: 20px;
  }

  @media (max-width: 768px){
    #searchInput{
        width: 90%;
        font-size: 14px;
    }

    .btn, .modal-content, .project-list{
        width: 100%;
    }

    .modal-content{
        margin: 10% auto;
    }

    .project-list th, .project-list td{
        padding: 8px;
    }

    .lg-12{
        overflow-x :auto;
    }
  }

  @media (max-width: 480px) {
    .btn{
        margin-bottom: 5px;
        padding: 5px;
        font-size: 12px;
    }

    .whoami, .createBoard{
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
    text-align: center;

}

.btn-update{
    background-color: #28a745;
}

.btn-delete{
   background-color: #dc3545;
}

.btn-update:hover, .btn-delete:hover{
    opacity:0.8;
}

.modal{
    display:none;
    position: fixed;
    z-index:1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0,0.4);
}

.modal-content{
    background-color:#fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

.close{
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus{
    color: black;
    text-decoration: none;
    cursor: pointer;
}

.sucess-message{
    color: #28a745;

}

.error-message{
    color: #dc3545;
}
    }

    </style>

    <div class="container projectListContainer">
        <h1>Projects List</h1>
        <?php
        if(isset($_SESSION['password_changed'])){
            echo'<div class="success-message">' .$_SESSION ['password_changed'] .'</div>';
            unset($_SESSION['password_changed']);

            echo'<script>setTimeout(function() { document.querySelector(".success-message").remove(); }, 2000); </script>';
        }
        if(isset($_SESSION['password_error'])){
            echo'<div class="error-message">' .$_SESSION['password_error'] .'</div>';
            unset($_SESSION['password_error']);
            echo '<script>setTimeout(function() {document.querySelection(".success-message").remove(); }, 2000);</script>';
        }
        
    </div>
  <style>

.search-container{
    margin-bottom: 20px;
    text-align : center;
}

#searchInput {
    padding: 10px;
    width: 50%;
    border: 2px solid #ddd;
    font-size: 16px;
    border-radius: 4px; 
    margin-bottom: 20px;
}

.table-row -display { display: none};
.table-row-display td{display: table-cell;}
</style>

<script>
    function searchProject (){
        var input, filter, table, tr, td,  i;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.querySelector(".project-list");
        tr = table.getEelementsByTagNmae("tr");

    for(i=0; i< tr.length; i++) {
        td= tr[i].getElementsByTagName("td");
        if(td.lenghth>1) {
            var fullName = td[0].textContent || td[0].innerText;
            var shortNmae = td[1].textContent || td[1].innerText;
            if(fullName.toUpperCase().indexOf(filetr)>-1 || shortName.toUpperCase().indexOf(filter)>-1){
                tr[i].style.display="";
            } else{
                tr[i].style.display="none";
            }
        }
    } 
    }
    </script>

    <div class="lg-12">
<br>
    <div class="search-container">
    <input type="text" id="searchInput" onkeyup="searchProjects()" placeholder="Serach for projects...">

</div>
    <table class="project-list">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Short Name</th>
                <th> Tasks left</th>
                <th>Action</th>
            </tr>
        </thead>
        <?php
        $currentUser =$connection->real_escape_string($_SESSION['user']);
        $sql ="SELECT * FROM projects WHERE 'user = '$currentUser'";

        if($result = $connection->query($sql)){
            $projectsCount = $result ->num_rows;
            if($projectsCount >0){
                while ($row = mysqli_fetch_array($result)){
                    $sn = $row['short name'];
                    $sumSQL  = "SELECT count (*) as tasksLeft FROM 'tasks' WHERE project_short_name = '$sn' AND state !=4";
                    $sumResult = $connection->query($sumSQL);
                    $row2 = $sumResult ->fetch_assoc();

                    echo " 
                        <tr> 
                        <td>".htmlspecialchars($row['Full name'])."</td>
                        <td>".htmlspecialchars($row['Short name'])."</td>
                        <td>".htmlspecialchars($row2['tasksLeft'])."</td>
                        <td>
                        <a href='board.php?sn=".urlencode($row['Short name'])."' class='btn btn-board'>Board</a>
                        <a href='updateProject.php?sn=".urlencode($row['Short name'])."' class='btn btn-update'>Update</a>
                        <a href='javascript:void(0);' onclick='confirmDelete(\"".urlencode($row['Short name'])."\")' class='btn btn-delete'>Delete</a>
                    </td>
                </tr> 
                    ";
                }
                $result ->free_result();
            }else{
                echo "tr><td colspan='4'> No projects for the current user. </td> </tr>";
            }
        }
        ?>
        </tbody>
    </table>
    </div>
    </div>

    <div id ="changePsswordModal" class="modal">
        <div class= "modal-content">
            <span class ="close" onclick="closeModal()">&times; </span>
            <h2>Change Password</h2>
            <form id= "changePasswordForm" method="post" action="">
                <lable for ="newPassword"> New Password </label>
                <input tupe = "password" id ="newPassword" name ="newPassword"required> <br> <br>
                <label for="confirmPassword"> Confirm Password </label>
                <input type = "password" id="confirmPassword" name = "confirmPassword" required ><br><br>
                <input type = "submit" value="Change Password">
            </form>
        
        </div>
    </div>

    <script>
    function openModal(){
        var modal = document .getElementById("changePasswordModal");
        modal.style.display ="block";
    }

    function closeModal (){
        var modal = document.getElementById ("changePasswordModal");
        modal.style.display ="none";
    }

    function confirmDelete(shortName ){
        var confrimAction = confirm("Are you sure you wnat to delete this project? This action cannot be undone");
        if(confirmAction){
            window.location.href = "delete[roject.php?sn =" + shortName;
        }else{

        }
    }
    </script>
    <?php include 'footer.php'; ?>



