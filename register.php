<?php
session_start();

if(isset($_SESSION['logged-in'])){
    header('Location: index.php');
    exit();
}

require_once "connect.php"; // database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['login'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['passwordConfirm'];

    if ($password !== $passwordConfirm) {
        $_SESSION['registration_error'] = "Passwords do not match.";
    } else {
        $hashedPassword = $password;
        $connection = new mysqli($host, $db_user, $db_password, $db_name);

        if ($connection->connect_errno != 0) {
            echo "Error: " . $connection->connect_errno;
        } else {
            $stmt = $connection->prepare("SELECT id FROM users WHERE login = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            
            if($stmt->num_rows == 0) {
                $stmt->close();
                $stmt = $connection->prepare("INSERT INTO users (login, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $username, $hashedPassword);
                
                if ($stmt->execute()) {
                    $_SESSION['registered'] = true;
                    $_SESSION['registration_success'] = "Registration successful!";
                    header('Location: register.php');
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $_SESSION['registration_error'] = "Username already exists.";
            }
            $connection->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>


body {
    font-family: Arial, sans-serif;
    background: linear-gradient(to right, #74ebd5, #9face6); /* Updated background gradient */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.register-box {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
}

h1 {
    text-align: center;
    color: #333;
}

.input-box {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #666;
}

input[type="text"], input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #5cb85c; /* Bootstrap 'success' green */
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #4cae4c; /* Darker green */
}

.error-message, .success-message {
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 4px;
    text-align: center;
}

.error-message {
    color: #D8000C;
    background-color: #FFD2D2;
}

.success-message {
    color: #4F8A10;
    background-color: #DFF2BF;
}

p {
    text-align: center;
}

a {
    color: #0056b3;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-box {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .input-box {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #0056b3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #004494;
        }
        .error-message {
            color: #D8000C;
            background-color: #FFD2D2;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        .success-message {
            color: #4F8A10;
            background-color: #DFF2BF;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        p {
            text-align: center;
        }
        a {
            color: #0056b3;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h1>Register</h1>
    <?php if(isset($_SESSION['registration_success'])): ?>
        <div class="success-message"><?php echo $_SESSION['registration_success']; unset($_SESSION['registration_success']); ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="input-box">
            <label for="login">Username:</label>
            <input type="text" name="login" required>
        </div>
        <div class="input-box">
            <label for="password">Password:</label>
            <input type="password" name="password" required>
        </div>
        <div class="input-box">
            <label for="passwordConfirm">Confirm Password:</label>
            <input type="password" name="passwordConfirm" required>
        </div>
        <button type="submit">Register</button>
    </form>
    <?php
        if(isset($_SESSION['registration_error'])){
            echo '<div class="error-message">' . $_SESSION['registration_error'] . '</div>';
            unset($_SESSION['registration_error']);
        }
    ?>
    <p>Already have an account? <a href="login.php">Log in here</a>.</p>
</div>

</body>
</html>
