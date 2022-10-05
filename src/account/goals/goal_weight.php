<?php
session_start();

if($_SERVER["REQUEST_METHOD"] === "POST"){
    if (isset($_POST["weightGoal"])) {
        $weightGoal = filter_var($_POST["weightGoal"], FILTER_VALIDATE_FLOAT);
        $weightGoal = number_format($weightGoal, 2);

        if (!isset($dbConn)) {
            $dbConn = require_once("../../db/connect.php");
        }

        $sql = "INSERT INTO goal_weight(goal_weight_amount)
                VALUES (:weight);";
        $params = [
            ':weight' => $weightGoal
        ];

        $statement = $dbConn->prepare($sql);

        try {
            $result = $statement->execute($params);
            $lastID = $dbConn->lastInsertID();
        } catch (PDOException $exception) {
            echo $exception;
        }

        if (isset($result) && isset($lastID)) {
            $userID = $_SESSION["id"];

            $sql = "INSERT INTO users_goal_weight(users_id, goal_weight_id)
                    VALUES (:userID, :goalWeightID);";

            $params = [
                ':userID' => $userID,
                ':goalWeightID' => $lastID
            ];

            $statement = $dbConn->prepare($sql);

            try {
                $result = $statement->execute($params);
                header("Location: ../account.php", true, 302);
            } catch (PDOException $exception) {
                echo $exception;
            }
        }
    }
}