
<?php
session_start();
$host = "localhost";
$db = "elearning";
$user = "root"; // Change if needed
$pass = "";     // Change if needed

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} // your DB connection script

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate against database
    $query = "SELECT * FROM register WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $_SESSION['email'] = $email;  // ✅ SESSION variable set here
        echo "success";
    } else {
        echo "error";
    }
$stmt->close();
$conn->close();
?>
