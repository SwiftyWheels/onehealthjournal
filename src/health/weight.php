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

    $weight = $_POST["weight"];
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
        } catch (PDOException $exception) {
            echo $exception;
        }
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
    <title>Weigh In</title>
    <link rel="stylesheet" href="../../resources/css/components/navigation/nav-styles.css">
    <link rel="stylesheet" href="../../resources/css/components/health/weight-styles.css">
</head>
<body>
<header>
    <nav>
        <div class="nav-logo">
            <ul>
                <li><a href="../index.php"><img
                                src="https://picsum.photos/30/30"
                                alt="logo"></a></li>
                <li>One Health Journal</li>
            </ul>
        </div>
        <div class="nav-links">
            <ul>
                <li>
                    <a href="../index.php">Home</a>
                </li>
                <li>
                    <a href="../account/upload.php">Upload File</a>
                </li>
                <li>
                    <a href="../auth/logout.php">Logout</a>
                </li>
                <?php
                if (isset($displayPic)) {
                    echo "<li>";
                    echo "<img src='$displayPic' alt='display picture'>";
                    echo "</li>";
                }
                ?>
            </ul>
        </div>
    </nav>
</header>
<main>
    <div class="form">
        <h3>Weigh-In</h3>
        <form action="" method="post">
            <div class="group">
                <input type="number" class="input" id="weight" name="weight" required>
                <label for="weight">Weight(lbs):</label>
            </div>
            <div class="group">
                <?php
                echo "<input type='date' class='input' name='date' value='";
                echo date("Y-m-d") . "' required>";
                echo "<label>Date:</label>";
                ?>
            </div>
            <input class='button' type="submit" value="Submit">
        </form>
    </div>
</main>
</body>
</html>