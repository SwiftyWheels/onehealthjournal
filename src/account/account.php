<?php
session_start();

if (!isset($_SESSION["loggedIn"])) {
    header("Location: auth/login.php", true, 302);
}

$username = $_SESSION["username"];
$glob = glob("../../resources/images/users/" . $_SESSION["username"] . "/" . $_SESSION["username"]
    . "*");
if ($glob && file_exists($glob[0])) {
    $displayPic = $glob[0];
}

if (!isset($dbConn)) {
    $dbConn = require_once("../db/connect.php");
}

if (isset($dbConn)) {
    $sql = "SELECT DISTINCT goal_weight_amount
            FROM goal_weight 
            LEFT JOIN users_goal_weight ugw 
            ON ugw.goal_weight_id = goal_weight.id
            WHERE ugw.users_id = :id 
            ORDER BY goal_weight_amount DESC;";

    $params = [
        ':id' => $_SESSION["id"],
    ];

    $statement = $dbConn->prepare($sql);

    try {
        $result = $statement->execute($params);
        $goalWeight = $statement->fetch()[0];
    } catch (PDOException $exception) {
        echo $exception;
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
            content="">
    <title>My Account</title>
    <link rel="stylesheet" href="../../resources/css/styles.css">
    <link rel="stylesheet" href="../../resources/css/components/navigation/nav-styles.css">
    <link rel="stylesheet" href="../../resources/css/components/form/form-styles.css">
    <link rel="stylesheet" href="../../resources/css/components/account/account-styles.css">
    <script type="module" src="../../resources/js/nav-scripts.js"></script>
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
                    <a href="../auth/logout.php">Logout</a>
                </li>
                <?php
                if (isset($displayPic)) {
                    echo "<li>";
                    echo "<img src='" . $displayPic . "' alt='display picture'>";
                    echo "</li>";
                }
                ?>
            </ul>
        </div>
        <div class="nav-dropdown-container">
            <div class="nav-dropdown-bar"></div>
            <div class="nav-dropdown-bar"></div>
            <div class="nav-dropdown-bar"></div>
            <div class="nav-dropdown-links hidden">
                <ul>
                    <li>
                        <a href="../index.php">Home</a>
                    </li>
                    <li>
                        <a href="../auth/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<div class="heading">
    <h1>Health Goals</h1>
</div>
<main>
    <div class="container-settings">
        <div class="form">
            <h2>Weight Goal</h2>
            <?php
            if (isset($goalWeight)) {
                $goal = $goalWeight ? $goalWeight : 0;
                $_SESSION["goalWeight"] = $goal;
                echo "<p>Current Weight Goal: " . $goal . " lbs</p>";
            }
            ?>
            <form action="goals/goal_weight.php" method="post">
                <div class="group">
                    <input
                            type="number" class="input" id="weightGoal" name="weightGoal"
                            min="0" required>
                    <label for="weightGoal">Weight(lbs):</label>
                </div>
                <input class='button' type="submit" value="Submit">
            </form>
        </div>
    </div>
</main>
</body>
</html>
