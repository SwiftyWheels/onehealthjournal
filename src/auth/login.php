<?php
session_start();

if (isset($_SESSION["loggedIn"])) {
    header("Location: ../index.php", true, 302);
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
    <title>Login</title>
    <link rel="stylesheet" href="../../resources/css/components/login/login-styles.css">
</head>
<body>
<main>
    <div class="form form-login">
        <form action="authenticate.php" method="post">
            <h3>Sign In</h3>
            <?php
            if (isset($_SESSION["errorMessages"])) {
                echo "<p class='error'>" . $_SESSION["errorMessages"] . "</p>";
            }
            ?>
            <div class="group">
                <input
                        type="text"
                        class="input"
                        id="username"
                        name="username"
                        required>
                <label for="username">Username</label>
            </div>
            <div class="group">
                <input
                        type="password"
                        class="input"
                        id="password"
                        name="password"
                        required>
                <label for="password">Password</label>
            </div>
            <input class="button" type="submit" value="Login">
        </form>
        <a class="" href="register.php">Register new account</a>
    </div>
</main>
</body>
</html>
