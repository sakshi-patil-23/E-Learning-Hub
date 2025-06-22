

<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}

$email = $_SESSION['email'];

$conn = new mysqli("localhost", "root", "", "elearning");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM register WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!-- HTML STARTS -->
<!DOCTYPE html>
<html lang="en">
<head>
       <!-- Head content here -->
         <meta charset="UTF-8">
    <title>Update Profile - E-Learning Hub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Same CSS as your original, untouched for styling */

     body {
            background: #f5f7fa;
            margin: 0;
            font-family: sans-serif;
            background: url('https://img.freepik.com/premium-photo/3d-computer-books-about-learning_387680-513.jpg?w=996') no-repeat center/cover fixed;
        }
        
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #333;
            padding: 15px 30px;
            color: #fff;
            margin-bottom: 20px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        
        .nav-links {
            list-style: none;
            display: flex;
            gap: 25px;
            padding: 0;
            margin: 0;
        }
        
        .nav-links li {
            position: relative;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            padding: 8px;
            display: block;
        }

        a:hover {
            background: orange;
            border-radius: 4px;
        }

        h2 {
            text-align: center;
            color: #333;
        }
 .feedback-container {
        background-color: rgba(255, 255, 255, 0.9);
        max-width: 600px;
        margin: 30px auto;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
    }

    label {
        font-weight: 600;
        display: block;
        margin-top: 15px;
    }

       input,
    select {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    button {
        margin-top: 20px;
        padding: 12px;
        width: 100%;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #45a049;
    }

    .profile-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 15px;
        display: block;
        border: 3px solid #ddd;
    }

    #upload-label {
        margin-top: 10px;
        display: block;
        text-align: center;
        color: #555;
    }

    #photo {
        display: block;
        margin: 0 auto;
    }

    .success-msg {
        text-align: center;
        color: green;
        margin-bottom: 10px;
    }

        button:hover {
            background-color: #45a049;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 15px;
            display: block;
            border: 3px solid #ddd;
        }

        #upload-label {
            margin-top: 10px;
            display: block;
            text-align: center;
            color: #555;
        }

        #photo {
            display: block;
            margin: 0 auto;
        }

        .success-msg {
            text-align: center;
            color: green;
            margin-bottom: 10px;
        }

    </style>

</head>
<body>
    
<nav class="navbar">
    <div class="logo">E-Learning Hub 📚</div>
    <ul class="nav-links">
        <li><a href="firstpg.php">Study Material</a></li>
        <li><a href="scholarships.html">Scholarships</a></li>
        <li><a href="feedback.html">Feedback</a></li>
        <li><a href="update_profile.php">Profile</a></li>
        <li><a href="index.html">Logout</a></li>
    </ul>
</nav>

<div class="feedback-container">
    <h2>Update Profile</h2>

    <!-- ✅ Display success message -->
<?php
if (isset($_GET['status']) && $_GET['status'] === 'success') {
    echo '<script>
        alert("Profile updated successfully!");
        window.history.replaceState({}, document.title, window.location.pathname);
    </script>';
}
?>



    <form method="POST" action="update-profile-handler.php" enctype="multipart/form-data">
        <img id="previewImg" src="<?= isset($user['profile_pic']) && !empty($user['profile_pic']) ? htmlspecialchars($user['profile_pic']) : 'https://via.placeholder.com/120' ?>" alt="Profile Picture" class="profile-img">
        <input type="file" id="photo" name="photo" accept="image/*">
        <input type="hidden" name="existing_photo" value="<?= htmlspecialchars($user['profile_pic'] ?? '') ?>">

        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" required>

        <label for="email">Email ID</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" readonly>

        <label for="dept">Department</label>
        <select id="dept" name="dept" required>
            <?php
           $departments = [
    'CSE' => 'Computer Science and Engineering',
    'DS' => 'Data Science Engineering',
    'AIML' => 'Artificial Intelligence and ML',
    'ENTC' => 'Electronics and Telecommunication',
    'MECH' => 'Mechanical',
    'CIVIL' => 'Civil',
    'CHEM' => 'Chemical'
];

            foreach ($departments as $code => $name) {
                $selected = ($user['department'] === $code) ? 'selected' : '';
                echo "<option value=\"$code\" $selected>$name</option>";
            }
            ?>
        </select>

        <label for="year">Year</label>
        <select id="year" name="year" required>
            <?php
            $years = ['1' => 'First Year', '2' => 'Second Year', '3' => 'Third Year', '4' => 'Fourth Year'];
            foreach ($years as $code => $label) {
                $selected = ($user['year'] == $code) ? 'selected' : '';
                echo "<option value=\"$code\" $selected>$label</option>";
            }
            ?>
        </select>

        <button type="submit">Update Profile</button>
    </form>
</div>

</body>
</html>
