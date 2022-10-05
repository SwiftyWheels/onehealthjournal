<?php
session_start();

// If the user isn't logged in, send them to the login page
if (!isset($_SESSION["loggedIn"])) {
    header("Location: ../auth/login.php", true, 302);
} else {
    $username = $_SESSION["username"];
    $glob = glob("../../resources/images/users/" . $username . "/" . $username . "*");
    if ($glob && file_exists($glob[0])) {
        $displayPic = $glob[0];
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($dbConn)) {
        $dbConn = require_once("../db/connect.php");
    }

    $weight = filter_var($_POST["weight"], FILTER_VALIDATE_FLOAT);
    $weight = number_format($weight, 2);
    $date = $_POST["date"];

    $sql = "INSERT INTO weight (weight_amount, weight_date)
            VALUES (:weight_amount, :weight_date);";

    $params = [
        ':weight_amount' => $weight,
        ':weight_date' => $date,
    ];

    $statement = $dbConn->prepare($sql);

    try {
        $result = $statement->execute($params);
        $lastID = $dbConn->lastInsertID();
    } catch (PDOException $exception) {
        echo $exception;
    }

    if (isset($result) && isset($lastID)) {
        $sql = "INSERT INTO users_weight (users_id, weight_id)
            VALUES (:users_id, :weight_id);";

        $params = [
            ':users_id' => $_SESSION["id"],
            ':weight_id' => $lastID,
        ];

        $statement = $dbConn->prepare($sql);

        try {
            $result = $statement->execute($params);
            header("Location: ../index.php", true, 302);
        } catch (PDOException $exception) {
            echo $exception;
        }
    }
}