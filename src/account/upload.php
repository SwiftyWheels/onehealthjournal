<?php
session_start();

// If the user isn't logged in, send them to the login page
if (!isset($_SESSION["loggedIn"])) {
    header("Location: login.php", true, 302);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    /*
     * Custom function to compress image size and
     * upload to the server using PHP
     */
    function compressImage($source, $destination, $quality)
    {
        // Get image info
        $imgInfo = getimagesize($source);
        $mime = $imgInfo['mime'];

        // Create a new image from file
        switch ($mime) {
            case 'image/png':
                $image = imagecreatefrompng($source);
                imagepng($image, $destination, ($quality / 10));
                break;
            case 'image/gif':
                $image = imagecreatefromgif($source);
                imagegif($image, $destination, $quality);
                break;
            default:
                $image = imagecreatefromjpeg($source);
                imagejpeg($image, $destination, $quality);
        }
        // Return compressed image
        return $destination;
    }

    $username = $_SESSION["username"];
    $target_dir = "../resources/images/users/" . $_SESSION["username"] . "/";

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $newFileName = $target_dir . $_SESSION["username"] . '.' . $imageFileType;
    $uploadOk = 1;

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        unlink($target_file);
    }

    $displayPic = glob("../resources/images/users/" . $username . "/" . $username . "*");

    if ($displayPic) {
        foreach ($displayPic as $pic) {
            if (file_exists($pic)) {
                unlink($pic);
            }
        }
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $compressedImage = compressImage($_FILES["fileToUpload"]["tmp_name"], $newFileName, 75);
        var_dump($compressedImage);
        echo "Compressed image!";
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $newFileName)) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
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
    <title>Upload</title>
    <link rel="stylesheet" href="../../resources/css/components/form/form-styles.css">
</head>
<body>
<div class="form">
    <form action="" method="post" enctype="multipart/form-data">
        Select image to upload: jpg, jpeg, png.
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form>
    <a href="../index.php">Home</a>
</div>
</body>
</html>

