<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload News - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('https://img.freepik.com/premium-photo/3d-computer-books-about-learning_387680-513.jpg?w=996') no-repeat center center fixed;
            background-size: cover;
            overflow-x: hidden;
        }

        .navbar {
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(0, 51, 102, 0.9);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            color: #fff;
            font-size: 1.8rem;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 25px;
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

        .container {
            max-width: 600px;
            margin: 80px auto;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #003366;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            color: #003366;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }

        button {
            background-color: #003366;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #002244;
        }

        footer {
            background-color: rgba(0, 51, 102, 0.95);
            color: white;
            text-align: center;
            padding: 15px;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: 60px;
        }
    </style>
</head>

<body>

    <!-- Navigation Bar -->
    <header>
        <nav class="navbar">
            <div class="logo">E-Learning Hub 📚</div>
            <ul class="nav-links">
                <li><a href="upload.html"><i class="fas fa-upload"></i> Upload Study Material</a></li>
                <li><a href="upload_news.php"><i class="fas fa-newspaper"></i> Upload News</a></li>
                <li><a href="viewfeedback.php"><i class="fas fa-comments"></i> See Feedback</a></li>
                <li><a href="index.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- News Upload Form -->
    <div class="container">
        <h2>Upload News</h2>
        <form action="upload_news.php" method="POST">
            <label for="title">News Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="content">Content:</label>
            <textarea name="content" id="content" rows="6" required></textarea>

            <button type="submit">Submit News</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2025 E-Learning Hub. All rights reserved.</p>
    </footer>

    <!-- PHP Logic -->
    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $conn = new mysqli("localhost", "root", "", "elearning");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $title = $conn->real_escape_string($_POST['title']);
        $content = $conn->real_escape_string($_POST['content']);

        $sql = "INSERT INTO news (title, content) VALUES ('$title', '$content')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('News uploaded successfully!');</script>";
        } else {
            echo "<script>alert('Error uploading news: " . $conn->error . "');</script>";
        }

        $conn->close();
    }
    ?>
</body>

</html>
