<?php

session_start();

# DB configs and creds
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "facebook";

# Instantiate connection
$conn = new mysqli($servername, $username, $password, $dbname);

# Verify connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

# Get the input fields from the form
$email = $_POST['email'];
$password = $_POST['password'];

# Validate input fields
if (empty($email) || empty($password)) {
    die("Email and password are required.");
}


# Prepare the SQL query to check for the user
$sql = "SELECT id, email, password FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

# Check if the user exists and verify the password
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($password === $row['password']) {  # This is insecure, just for testing
        # Password is correct, set session variables
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['email'] = $row['email'];
        
        echo "<script>
            alert('Login successful. Welcome, " . $row['email'] . "!');
            window.location.href = '../index.html';
        </script>";

        # Redirect to the dashboard or another page once completed
        // header("Location: ../index.html");
        exit();
    } else {
        echo "Invalid password.";
    }
} else {
    echo "No user found with this email.";
}

# Close the statement and connection
$stmt->close();
$conn->close();


?>
