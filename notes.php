<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$db = 'elearning';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$dept = $_GET['dept'] ?? '';
$year = $_GET['year'] ?? '';
$sem = $_GET['sem'] ?? '';

$semesters = ['Sem 1', 'Sem 2'];
$materials = [];

// Handle semester-based filtering
if ($dept && $year && $sem) {
    $sql = "SELECT subject, unit, type, link_or_file, description 
            FROM study_materials 
            WHERE department = ? AND year = ? AND semester = ? 
            ORDER BY subject, unit, type";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $dept, $year, $sem);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $materials[$row['subject']][$row['unit']][$row['type']][] = $row['link_or_file'];
    }

    $stmt->close();
}

// Handle search
$search_result = null;
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $stmt = $conn->prepare("SELECT * FROM study_materials WHERE link_or_file LIKE ? OR description LIKE ?");
    $search_term = "%" . $search . "%";
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $search_result = $stmt->get_result();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>E-Learning Hub - Study Materials</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      background: url('https://img.freepik.com/premium-photo/3d-computer-books-about-learning_387680-513.jpg?w=996') no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #333;
      padding: 15px 30px;
      color: white;
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 25px;
    }

    .nav-links li a {
      color: white;
      text-decoration: none;
      font-weight: bold;
    }

    .content {
      padding: 40px;
      background: rgba(255, 255, 255, 0.95);
      margin: 30px auto;
      max-width: 1000px;
      border-radius: 16px;
      box-shadow: 0 5px 15px rgba(18, 14, 14, 0.2);
    }

    .material-box {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 20px;
      box-shadow: 0 0 10px #ccc;
    }

    h2, h3, h4 {
      color: #333;
    }

    h3 { margin-top: 10px; color: darkorange; }
    ul { padding-left: 20px; }

    form {
      margin-bottom: 30px;
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      align-items: center;
    }

    input, select, button {
      padding: 8px 12px;
      font-size: 14px;
    }

    button {
      background: darkorange;
      color: white;
      border: none;
      cursor: pointer;
    }

    button:hover {
      background: orangered;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table, th, td {
      border: 1px solid #ddd;
    }

    th, td {
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f4f4f4;
    }

    .link {
      color: #2e6db6;
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
      <li><a href="update-profile.html">Profile</a></li>
      <li><a href="index.html">Logout</a></li>
    </ul>
  </nav>

  <div class="content">
    <!-- Filter Form -->
    <form method="GET" action="">
      <label>Department:
        <input type="text" name="dept" value="<?= htmlspecialchars($dept) ?>" readonly>
      </label>
      <label>Year:
        <input type="text" name="year" value="<?= htmlspecialchars($year) ?>" readonly>
      </label>
      <label>Semester:
        <select name="sem" required>
          <option value="">--Select Semester--</option>
          <?php foreach ($semesters as $option): ?>
            <option value="<?= $option ?>" <?= ($sem == $option) ? 'selected' : '' ?>><?= $option ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <button type="submit">Filter</button>
    </form>

  
    <!-- Filtered Materials Display -->
    <?php if ($dept && $year && $sem): ?>
      <h2>Study Material for <?= htmlspecialchars(strtoupper($dept)) ?> - Year <?= htmlspecialchars($year) ?> - <?= htmlspecialchars($sem) ?></h2>
      <?php if (empty($materials)): ?>
        <p>No materials found for the selected filters.</p>
      <?php else: ?>
        <?php foreach ($materials as $subject => $units): ?>
          <div class="material-box">
            <h3><?= htmlspecialchars($subject) ?></h3>
            <?php foreach ($units as $unit => $types): ?>
              <h4>Unit <?= htmlspecialchars($unit) ?></h4>
              <?php foreach ($types as $type => $items): ?>
                <strong><?= ucfirst($type) ?>:</strong>
                <ul>
                  <?php foreach ($items as $item): ?>
                    <?php
// Find corresponding description
$desc_sql = "SELECT description FROM study_materials 
             WHERE department = ? AND year = ? AND semester = ? 
             AND subject = ? AND unit = ? AND type = ? AND link_or_file = ? LIMIT 1";
$desc_stmt = $conn = new mysqli($host, $user, $pass, $db); // Reopen connection since closed earlier
$desc_stmt = $conn->prepare($desc_sql);
$desc_stmt->bind_param("sssssss", $dept, $year, $sem, $subject, $unit, $type, $item);
$desc_stmt->execute();
$desc_result = $desc_stmt->get_result();
$desc_row = $desc_result->fetch_assoc();
$description = $desc_row['description'] ?? '';
$desc_stmt->close();
$conn->close();
?>

<li>
  <?php if ($type === 'video'): ?>
    <a href="<?= htmlspecialchars($item) ?>" target="_blank">Watch Video</a>
  <?php else: ?>
    <a href="<?= htmlspecialchars($item) ?>" target="_blank"><?= htmlspecialchars(basename($item)) ?></a>
  <?php endif; ?>
  <br><small><?= htmlspecialchars($description) ?></small>
</li>

                  <?php endforeach; ?>
                </ul>
              <?php endforeach; ?>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    <?php endif; ?>

  

</body>
</html>
