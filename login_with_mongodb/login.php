<?php

session_start();

// Pull the MongoDB driver from vendor folder
require_once '../vendor/autoload.php';

try {
    // Connect to MongoDB Database
    $databaseConnection = new MongoDB\Client("mongodb+srv://praise:OOGJXQELmoGOkGFe@cluster0.a9prlnc.mongodb.net/");

    // Connecting to specific database in MongoDB
    $myDatabase = $databaseConnection->myDB;

    // Connecting to our MongoDB Collections
    $userCollection = $myDatabase->users;

    if(isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $data = array(
            "Email" => $email
        );

        // Fetch user from MongoDB Users Collection
        $fetch = $userCollection->findOne($data);

        if($fetch && password_verify($password, $fetch['Password'])) {
            // Create a session
            $_SESSION['email'] = $fetch['Email'];

            // Redirect to the profile page
            header('Location: ../profile.php');
            exit();
        } else {
            ?>
            <center><h4 style="color: red;">User Not Found</h4></center>
            <center><a href="../index.php">Try Again</a></center>
            <?php
        }
    }
} catch (Exception $e) {
    // Handle exceptions
    ?>
    <center><h4 style="color: red;">Error: <?= $e->getMessage() ?></h4></center>
    <center><a href="../index.php">Try Again</a></center>
    <?php
}
?>
