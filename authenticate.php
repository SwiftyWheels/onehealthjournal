<?php
session_start();

// Call this only if the server was sent a post request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($dbConn)) {
        $dbConn = require_once("connect.php");
    }

    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);

    $sql = "SELECT * FROM users WHERE username = :username;";
    $statement = $dbConn->prepare($sql);

    $params = [':username' => $username];
    try {
        $result = $statement->execute($params);
        if ($result) {
            $user = $statement->fetch();

            if (password_verify($password, $user["password"])) {
                session_regenerate_id();
                $_SESSION["loggedIn"] = true;
                $_SESSION["username"] = $user["username"];
                $_SESSION["id"] = $user["id"];
            }else{
                $_SESSION["errorMessages"] = "Wrong username or password!";
                header("Location: login.php", true, 302);
            }
        }
    } catch (PDOException $exception) {
        $errorCode = $exception->errorInfo[1];
        echo $errorCode;
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
    <meta
            name="description"
            content="describe what is the purpose of the current page
                 and how it fits into the project (website or
                 web app. Be generous with your description.">
    <title>Authenticate</title>
</head>
<body>
<?php
if (isset($_SESSION["loggedIn"])) {
    echo "<p>Successfully logged in!</p>";
    echo "<p>Redirecting...</p>";
    header("refresh:3;url=index.php", true, 302);
}
?>
</body>
</html>
