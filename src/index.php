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
    add category to workout table,
    add 30 day challenge,
    add weight goal
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
    <link rel="stylesheet" href="../resources/css/components/table/table-styles.css">
    <link rel="stylesheet" href="../resources/css/components/form/form-styles.css">
    <link rel="stylesheet" href="../resources/css/components/index/index-styles.css">
    <script defer type="module" src="../resources/js/scripts.js"></script>
    <script defer type="module" src="../resources/js/nav-scripts.js"></script>
    <script defer src="../resources/js/utilities/progress-bars.js"></script>
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
                    <a href="account/account.php">My Account</a>
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
        <div class="nav-dropdown-container">
            <div class="nav-dropdown-bar"></div>
            <div class="nav-dropdown-bar"></div>
            <div class="nav-dropdown-bar"></div>
            <div class="nav-dropdown-links hidden">
                <ul>
                    <li>
                        <a href="account/account.php">My Account</a>
                    </li>
                    <li>
                        <a href="account/upload.php">Upload File</a>
                    </li>
                    <li>
                        <a href="auth/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<main>
    <div class="container-nav">
        <div class="nav-dropdown-container">
            <div class="nav-dropdown-bar"></div>
            <div class="nav-dropdown-bar"></div>
            <div class="nav-dropdown-bar"></div>
            <div class="nav-dropdown-links hidden">
                <ul>
                    <li>
                        <button class="button" id="workoutButtonNav">Workouts</button>
                    </li>
                    <li>
                        <button class="button" id="caloriesButtonNav">Calories</button>
                    </li>
                    <li>
                        <button class="button" id="weightButtonNav">Weigh-Ins</button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="nav-buttons">
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
    </div>
    <div class="container-components">
        <div class="">
            <div class="container-workout hidden" id="containerWorkout">
                <p>Workout Container</p>
            </div>
            <div class="container-calories hidden" id="containerCalories">
                <p>Calories Container</p>
            </div>
            <div class="container-weight" id="containerWeight">
                <div class="container-content">
                    <div class="container-weight-header">
                        <?php
                        if (isset($statement)) {
                            $rowCount = $statement->rowCount();
                            $totalWeighIns = array_values($statement->fetchAll());
                            $firstWeight = $totalWeighIns[$rowCount - 1]["weight_amount"];
                            $currentWeight = $totalWeighIns[0]['weight_amount'];
                            $weightLost = (floatval($firstWeight) - floatval($currentWeight));

                            if (isset($_SESSION["goalWeight"])) {
                                $goalWeight = $_SESSION["goalWeight"];
                                $weightTillGoal = (floatval($currentWeight) - floatval($goalWeight));
                                $weightDifference = floatval($firstWeight) - floatval($goalWeight);
                                $percentOfGoal = ($weightLost / $weightDifference) * 100;
                                $percentOfGoal = number_format($percentOfGoal, 2);
                                $percentOfGoal = floatval($percentOfGoal);
                            }
                        }
                        ?>
                        <div class="container-weight-header-items">
                            <div class="container-weight-header-item">
                                <div class="container-weight-header-item-header">
                                    <p>Starting Weight</p>
                                </div>
                                <div class="container-weight-header-item-body">
                                    <?php
                                    if (isset($firstWeight)) {
                                        echo "<p>" . $firstWeight . " lbs</p>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="container-weight-header-item">
                                <div class="container-weight-header-item-header">
                                    <p>Weight Goal</p>
                                </div>
                                <div class="container-weight-header-item-body">
                                    <?php
                                    if (isset($goalWeight)) {
                                        echo "<p>" . $goalWeight . " lbs</p>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="container-weight-header-item">
                                <div class="container-weight-header-item-header">
                                    <p>Current Weight</p>
                                </div>
                                <div class="container-weight-header-item-body">
                                    <?php
                                    if (isset($currentWeight)) {
                                        echo "<p>" . $currentWeight . " lbs</p>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="container-weight-header-item">
                                <div class="container-weight-header-item-header">
                                    <p>Weight Lost</p>
                                </div>
                                <div class="container-weight-header-item-body">
                                    <?php
                                    if (isset($weightLost)) {
                                        echo "<p>" . $weightLost . " lbs</p>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="container-weight-header-item progress-bar">
                                <div class="container-weight-header-item-header">
                                    <p>Percent of Goal</p>
                                </div>
                                <div class="container-weight-header-item-body bar-container">
                                    <div class="bar">
                                    </div>
                                    <?php
                                    if (isset($percentOfGoal)) {
                                        echo "<span>" . $percentOfGoal . "%</span>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-weight-items">
                        <?php
                        if (isset($result) && isset($statement) && isset($rowCount) && isset($totalWeighIns)) {
                            $fetchAmount = min(7, $rowCount);
                            for ($i = 0; $i < $fetchAmount; $i++) {
                                $weightDate = $totalWeighIns[$i]['weight_date'];
                                $weightAmount = $totalWeighIns[$i]['weight_amount'];
                                $weightLost = (floatval($firstWeight) - floatval($weightAmount));

                                $dayName = date('D', strtotime($weightDate));
                                $dayNum = date("d", strtotime($weightDate));
                                $month = date('M', strtotime($weightDate));
                                $year = date('Y', strtotime($weightDate));

                                if (isset($goalWeight)) {
                                    $weightTillGoal = (floatval($weightAmount) - floatval($goalWeight));
                                }


                                echo "<div class='weight-item'>";
                                echo "<div class='weight-item-date'>";
                                echo "<p>" . $dayName . " - " . $month . " " . $dayNum . " - " .
                                    $year . " </p>";
                                echo "</div>";
                                echo "<p class='weight-amount'>Weighed: " . $weightAmount . " lbs</p>";
                                echo "<p class='weight-lost'>Weight Lost: " . $weightLost . " lbs</p>";
                                if (isset($weightTillGoal)) {
                                    echo "<p class='weight-goal'>Weight till goal: " .
                                        $weightTillGoal . " lbs</p>";
                                }
                                echo "</div>";
                            }
                        }
                        if (isset($fetchAmount) && isset($rowCount)) {
                            echo "<div>";
                            echo "<p>Showing the last 7 weigh-ins</p>";
                            echo "<p class='mute-text'>Total weigh-ins: " . $rowCount . "</p>";
                            echo "</div>";
                        }
                        ?>

                    </div>
                    <div class="form">
                        <h3>Log Weight</h3>
                        <form action="health/weight.php" method="post">
                            <div class="group">
                                <input
                                        type="number" class="input" id="weight" name="weight"
                                        min="0" step="0.01" required>
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
                </div>
            </div>
        </div>
    </div>
</main>
<footer>
    <p>Copyright&COPY; Patrick Hogg 2022</p>
</footer>
</body>
</html>
