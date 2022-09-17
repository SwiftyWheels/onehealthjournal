<?php
session_start();

if (isset($_SESSION["errorMessages"])) {
    unset($_SESSION["errorMessages"]);
}

// If the user isn't logged in, send them to the login page
if (!isset($_SESSION["loggedIn"])) {
    header("Location: auth/login.php", true, 302);
} else {
    $username = $_SESSION["username"];
    $glob = glob("../resources/images/users/" . $username . "/" . $username . "*");
    if ($glob && file_exists($glob[0])) {
        $displayPic = $glob[0];
    }
}

if (!isset($dbConn)) {
    $dbConn = require_once("db/connect.php");
}

if (isset($dbConn)) {
    $sql = "SELECT DISTINCT weight_amount, weight_date
            FROM weight
            LEFT JOIN users_weight uw
            ON weight.id = uw.weight_id
            WHERE users_id = :id
            ORDER BY weight_date DESC";

    $params = [
        ':id' => $_SESSION["id"],
    ];

    $statement = $dbConn->prepare($sql);

    try {
        $result = $statement->execute($params);
    } catch (PDOException $exception) {
        echo $exception;
    }
}
?>
<!--
TODO:
    Add one rep max,
    add weight to workout table,
    add category to workout table,
    add 30 day challenge
-->
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
    <title>One Health Journal</title>
    <link rel="stylesheet" href="../resources/css/components/navigation/nav-styles.css">
    <link rel="stylesheet" href="../resources/css/components/table-styles.css">
    <link rel="stylesheet" href="../resources/css/components/index/index-styles.css">
    <script src="../resources/js/scripts.js"></script>
</head>
<body>
<header>
    <nav>
        <div class="nav-logo">
            <ul>
                <li><a href="index.php"><img src="https://picsum.photos/30/30" alt="logo"></a></li>
                <li>One Health Journal</li>
            </ul>
        </div>
        <div class="nav-links">
            <ul>
                <li>
                    <a href="account/upload.php">Upload File</a>
                </li>
                <li>
                    <a href="auth/logout.php">Logout</a>
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
    <header>
        <div class="container-nav">
            <ul>
                <li>
                    <button class="button" id="workoutButton">Workouts</button>
                </li>
                <li>
                    <button class="button" id="caloriesButton">Calories</button>
                </li>
                <li>
                    <button class="button" id="weightButton">Weigh-Ins</button>
                </li>
            </ul>
        </div>
    </header>
    <div class="container-components">
        <div class="">
            <div class="container-workout hidden" id="containerWorkout">
                <p>Workout Container</p>
            </div>
            <div class="container-calories hidden" id="containerCalories">
                <p>Calories Container</p>
            </div>
            <div class="container-weight" id="containerWeight">
                <div class="form">
                    <h3>Log Weight</h3>
                    <form action="health/weight.php" method="post">
                        <div class="group">
                            <input type="number" class="input" id="weight" name="weight" required>
                            <label for="weight">Weight(lbs):</label>
                        </div>
                        <div class="group">
                            <?php
                            echo "<input type='date' class='input' name='date' required>";
                            echo "<label>Date:</label>";
                            ?>
                        </div>
                        <input class='button' type="submit" value="Submit">
                    </form>
                </div>
                <div class="table">
                    <h3>Weigh-Ins</h3>
                    <?php
                    echo "
                            <table>
                            <thead>
                                <tr>
                                    <th>Weight Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            ";
                    if (isset($result) && isset($statement)) {
                        while ($weight = $statement->fetch()) {
                            echo "<tr>";
                            echo "<td>$weight[weight_amount]</td>";
                            echo "<td>$weight[weight_date]</td>";
                            echo "<tr>";
                        }
                    }
                    echo "</table>";
                    ?>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>