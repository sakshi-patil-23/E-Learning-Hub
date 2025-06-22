<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "elearning");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    // ✅ Fetch form values
    $full_name = trim($_POST['full_name']);
    $department = trim($_POST['department']);
    $year = trim($_POST['year']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // Consider using password_hash()

    // ✅ Check if the email already exists
    $sql_check = "SELECT * FROM register WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "<script>alert('Email is already registered.'); window.history.back();</script>";
        exit;
    }

    $stmt_check->close();

    // ✅ Insert into register table
    $sql1 = "INSERT INTO register (full_name, department, year, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("sssss", $full_name, $department, $year, $email, $password);
    
    if ($stmt1->execute()) {
        echo "<script>alert('Registration Successful!'); window.location.href = 'index.html';</script>";
    } else {
        echo "<script>alert('Error during registration: " . $stmt1->error . "'); window.history.back();</script>";
    }

    $stmt1->close();
}

$conn->close();
?>
