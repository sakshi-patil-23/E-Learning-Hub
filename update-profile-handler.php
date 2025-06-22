<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "elearning");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$name = $_POST['full_name'];
$dept = $_POST['dept'];
$year = $_POST['year'];
$existing_photo = $_POST['existing_photo'];
$photo_name = $existing_photo;

// Ensure 'profile' folder exists
if (!file_exists('profile')) {
    mkdir('profile', 0777, true);
}

// If a new file is uploaded
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $photo_name = "profile/" . basename($_FILES["photo"]["name"]);
    if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $photo_name)) {
        die("Error: Failed to upload image.");
    }
}

// Update DB
$stmt = $conn->prepare("UPDATE register SET full_name=?, department=?, year=?, profile_pic=? WHERE email=?");
$stmt->bind_param("sssss", $name, $dept, $year, $photo_name, $email);

if ($stmt->execute()) {
    header("Location: update_profile.php?status=success");
    exit();
} else {
    echo "Failed to update profile.";
}

$stmt->close();
$conn->close();
?>
