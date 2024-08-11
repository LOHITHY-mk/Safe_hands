<?php

include 'connect.php';

if (isset($_POST['SignUp'])) {
    $FirstName = $_POST['FirstName'];
    $LastName = $_POST['LastName'];
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];
    $Password = md5($Password); // Note: Consider using more secure hashing (e.g., password_hash())

    // Prepare and execute the query to check if the email already exists
    $checkEmailStmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmailStmt->bind_param("s", $Email);
    $checkEmailStmt->execute();
    $result = $checkEmailStmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "Email Address Already Exists!";
    } else {
        // Prepare and execute the query to insert the new user
        $insertQuery = $conn->prepare("INSERT INTO users (FirstName, LastName, Email, Password) VALUES (?, ?, ?, ?)");
        $insertQuery->bind_param("ssss", $FirstName, $LastName, $Email, $Password);
        
        if ($insertQuery->execute()) {
            header("Location: index.php");
            exit(); // Added exit() after header() to stop further script execution
        } else {
            echo "Error: " . $conn->error;
        }
    }
    
    $checkEmailStmt->close();
    $insertQuery->close();
}

if (isset($_POST['SignIn'])) {
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $password = md5($Password); // Note: Consider using more secure hashing (e.g., password_hash())

    // Prepare and execute the query to verify user credentials
    $sql = $conn->prepare("SELECT * FROM users WHERE email = ? AND Password = ?");
    $sql->bind_param("ss", $Email, $Password);
    $sql->execute();
    $result = $sql->get_result();
    
    if ($result->num_rows > 0) {
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['Email'] = $row['Email'];
        header("Location: homepage.php");
        exit(); // Added exit() after header() to stop further script execution
    } else {
        echo "Not Found, Incorrect Email or Password";
    }
    
    $sql->close();
}

$conn->close();

?>
