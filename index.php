<?php
session_start();

if (isset($_SESSION["errorMessages"])) {
    unset($_SESSION["errorMessages"]);
}

// If the user isn't logged in, send them to the login page
if (!isset($_SESSION["loggedIn"])) {
    header("Location: login.php", true, 302);
} else {
    $username = $_SESSION["username"];
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
    <title>One Health Journal</title>
</head>
<body>
<header>
    <?php
    if (isset($username)) {
        echo "<h1>Welcome, $username!</h1>";
        $displayPic = glob("images/users/" . $username . "/" . $username . "*");
        if (file_exists($displayPic[0])) {
            echo "<img src='$displayPic[0]' alt='display picture' style='width: 50px;height: 50px'>";

        }
    }
    ?>
    <a href="logout.php">Logout</a>
    <a href="upload.php">Upload File</a>
</header>
<main>
</main>

</body>
</html>