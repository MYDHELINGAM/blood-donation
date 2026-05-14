<?php
// Start a session if needed
session_start();

// Database connection
$servername = "localhost";
$username = "root";  // Use your DB username
$password = "";      // Use your DB password
$dbname = "employee_skills";  // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check DB connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Sanitize inputs
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$password = trim($_POST['password']);

// Check if user already exists
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  echo "<script>alert('User already exists. Please login.'); window.location.href='login.html';</script>";
} else {
  // Hash password before storing
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  $insert = "INSERT INTO users (email, password) VALUES (?, ?)";
  $stmt = $conn->prepare($insert);
  $stmt->bind_param("ss", $email, $hashedPassword);

  if ($stmt->execute()) {
    echo "<script>alert('Registration successful! Please login.'); window.location.href='login.html';</script>";
  } else {
    echo "Error: " . $stmt->error;
  }
}

$stmt->close();
$conn->close();
?>
