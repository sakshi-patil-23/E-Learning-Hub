<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>View Feedback - E-Learning Hub</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- FontAwesome -->
  <style>
    body {
      background: url('https://img.freepik.com/premium-photo/3d-computer-books-about-learning_387680-513.jpg?w=996') no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .navbar {
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #333;
      color: white;
    }

    .logo {
      font-size: 1.8rem;
      font-weight: bold;
      display: flex;
      align-items: center;
    }

    .logo i {
      margin-right: 10px;
      font-size: 2rem;
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 25px;
      margin: 0;
      padding: 0;
    }

    .nav-links li {
      position: relative;
    }

    .nav-links li a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: color 0.3s ease;
    }

    .nav-links li a:hover {
      color: #ffcc00;
    }

    .nav-links li a i {
      font-size: 1.2rem;
    }

    .feedback-container {
      max-width: 1000px;
      margin: 30px auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      overflow-x: auto;
    }

    h2 {
      text-align: center;
      color: #333;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
      color: #333;
    }

    td {
      background-color: #fff;
    }

    tr:nth-child(even) td {
      background-color: #f9f9f9;
    }

    .no-data {
      text-align: center;
      padding: 20px;
      color: red;
    }
  </style>
</head>

<body>
  <!-- Admin Style Navigation Bar -->
  <nav class="navbar">
    <div class="logo"><i class="fas fa-graduation-cap"></i> E-Learning Hub 📚</div>
    <ul class="nav-links">
      <li><a href="upload.html"><i class="fas fa-upload"></i> Upload Study Material</a></li>
      <li><a href="upload_news.php"><i class="fas fa-newspaper"></i> Upload News</a></li>
      <li><a href="viewfeedback.php"><i class="fas fa-comments"></i> See Feedback</a></li>
      <li><a href="index.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </nav>

  <div class="feedback-container">
    <h2>All Student Feedback</h2>

    <?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "elearning";

    $conn = new mysqli($host, $user, $password, $db);

    if ($conn->connect_error) {
        die("<p class='no-data'>Connection failed: " . $conn->connect_error . "</p>");
    }

    $sql = "SELECT * FROM feedback ORDER BY id DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Year</th>
                    <th>Department</th>
                    <th>Rating</th>
                    <th>Comments</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . htmlspecialchars($row["name"]) . "</td>
                    <td>" . htmlspecialchars($row["email"]) . "</td>
                    <td>" . htmlspecialchars($row["year"]) . "</td>
                    <td>" . htmlspecialchars($row["department"]) . "</td>
                    <td>" . $row["rating"] . "</td>
                    <td>" . nl2br(htmlspecialchars($row["comments"])) . "</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p class='no-data'>No feedback available yet.</p>";
    }

    $conn->close();
    ?>
  </div>
</body>

</html>
