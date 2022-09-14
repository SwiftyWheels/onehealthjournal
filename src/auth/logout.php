<?php
session_start();

// Easiest way to clear session data
$_SESSION = array();

header("Location: ../index.php", true, 302);
