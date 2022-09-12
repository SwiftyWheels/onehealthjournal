<?php
session_start();

if (isset($_SESSION["errorMessages"])) {
    unset($_SESSION["errorMessages"]);
}

// Call this only if the server was sent a post request
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($dbConn)) {
        $dbConn = require_once("connect.php");
    }

    $email = htmlspecialchars($_POST["email"]);
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);
    // Encrypt the password
    $encryptedPass = password_hash($password, PASSWORD_DEFAULT);

    // Create the SQL statement
    $sql = "INSERT INTO users (email, username, password)
            VALUES (:email, :username, :password);";

    // Set the parameters for the SQL statement
    $params = [
        ':email' => $email,
        ':username' => $username,
        ':password' => $encryptedPass,
    ];

    // Prepare the SQL statement
    $statement = $dbConn->prepare($sql);
    // Execute the statement and store the result
    try {
        $result = $statement->execute($params);
    } catch (PDOException $exception) {
        $errorCode = $exception->errorInfo[1];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 minimum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Patrick Hogg">
    <meta name="description"
          content="describe what is the purpose of the current page
                 and how it fits into the project (website or
                 web app. Be generous with your description.">
    <title>Register Account</title>
    <link rel="stylesheet" href="css/components/register/register-styles.css">
</head>
<body>
<main>
    <div class="form form-register">
        <?php
        if (isset($errorCode) && $errorCode === 1062) {
            echo "<p class='error'>Username or Email already exists!</p>";
        }
        if (isset($result)) {
            echo "<p class='success'>Successfully added account! </p>";
        }
        ?>
        <form action="" method="post">
            <label>Email: <input type="email" name="email" required></label>
            <label>Username: <input type="text" name="username" required></label>
            <label>Password: <input type="password" name="password" required></label>
            <input type="submit" value="Register">
        </form>
        <div>
            <a href="login.php">Login</a>
        </div>
    </div>
</main>
</body>
</html>

