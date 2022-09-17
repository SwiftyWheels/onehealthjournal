<?php
session_start();

// Call this only if the server was sent a post request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($dbConn)) {
        $dbConn = require_once("../db/connect.php");
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
if (isset($_SESSION["loggedIn"])) {
    header("Location: ../index.php", true, 302);
}