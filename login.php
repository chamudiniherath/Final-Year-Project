<?php
session_start();
if (isset($_SESSION['logged-in'])) {
    header('Location: index.php');
    exit();
}
?>
<?php include 'header.php'; ?>
<style>
    body,
    html {
        height: 100%;
        margin: 0;
        font-family: Arial, sans-serif;
    }

    .container.loginContainer {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .login-box {
        background: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
    }

    body {
        background: linear-gradient(to right, #74ebd5, #acb6e5);
    }

    .input-box {
        margin-bottom: 10px;
    }

    .input-box input {
        width: calc(100% - 10px);
        padding: 5px;
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: #5cb85c;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #4cae4c;
    }

    .error-message {
        color: #d9534f;
        margin-bottom: 10px;
    }

    .register-link p {
        text-align: center;
    }
</style>

<div class="container loginContainer">

    <div class="login-box">
    <?php
        // Displaying the image to the left side at the top
        echo '<img src="data:image/png;base64,';
        echo base64_encode(file_get_contents('C:\Users\chamu\Downloads\phpdesktop-chrome-57.0-rc-php-7.1.3\phpdesktop-chrome-57.0-rc-php-7.1.3\www\Image\Task.png'));
        echo '" alt="Embedded Image" style="width: 100px; height: auto; float: left; margin-right: 20px;">';
        ?>
        <h1>Task Ease</h1>


        <form method="post" action="loginValidation.php">
            <div class="input-box">
                <label for="login">Login:</label>
                <input type="text" name="login">
            </div>
            <div class="input-box">
                <label for="password">Password:</label>
                <input type="password" name="password">
            </div>
            <button type="submit">Log in</button>
        </form>
        <?php
        if (isset($oginError_SESSION['l'])) {
            echo '<div class="error-message">' . $_SESSION['loginError'] . '</div>';
        }
        ?>
        <div class="register-link">
            <!--  new registration link/button -->
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>