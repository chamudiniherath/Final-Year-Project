<?php

    session_start();

    session_unset();// unsetting all session variables

    header('Location: login.php'); // redirecting to the login.php page 

?>