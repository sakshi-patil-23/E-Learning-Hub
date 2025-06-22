<?php
// Database credentials
$host = "localhost";
$user = "root";
$password = "";
$db = "elearning";

// Create connection
$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form values safely
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$year = $_POST['year'] ?? '';
$department = $_POST['department'] ?? '';
$rating = $_POST['rating'] ?? 0;
$comments = $_POST['comments'] ?? '';


// ✅ List of allowed email domains
$allowed_domains = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'rediffmail.com'];

// ✅ Extract the domain from the email
$email_domain = strtolower(substr(strrchr($email, "@"), 1));

// ✅ Validate email domain
if (!in_array($email_domain, $allowed_domains)) {
    echo "<h3 style='color:red;'>❌ Invalid email domain. Only Gmail, Yahoo, Outlook, Hotmail, or Rediffmail are allowed.</h3>";
    echo "<a href='feedback.html'>Go Back to Feedback Form</a>";
    exit();
}

// Prepare and execute SQL query
$sql = "INSERT INTO feedback (name, email, year, department, rating, comments)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssis", $name, $email, $year, $department, $rating, $comments);

if ($stmt->execute()) {
    echo "<h2>Feedback Submitted Successfully!</h2>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
