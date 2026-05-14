<?php
session_start();
$host = "localhost";
$username = "root"; // your DB username
$password = "";     // your DB password
$dbname = "skill_tracking_system"; // your DB name

$conn = new mysqli($host, $username, $password, $dbname);

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize inputs
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$userType = $_POST['userType'];

if ($userType == 'employee') {
    $query = "SELECT * FROM employees WHERE email='$email' AND password='$password'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'employee';
        header("Location: ex dhashboard.html");
        exit();
    } else {
        echo "<script>alert('Invalid Employee credentials'); window.history.back();</script>";
    }
} else {
    $query = "SELECT * FROM hr WHERE email='$email' AND password='$password'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'hr';
        header("Location: ex hrdhashboard.html");
        exit();
    } else {
        echo "<script>alert('Invalid HR credentials'); window.history.back();</script>";
    }
}

$conn->close();
?>
