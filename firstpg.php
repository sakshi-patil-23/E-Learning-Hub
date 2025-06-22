<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>E-Learning Hub</title>
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            background: url('https://img.freepik.com/premium-photo/3d-computer-books-about-learning_387680-513.jpg?w=996') no-repeat center/cover fixed;
            display: flex;
        }

        /* SIDEBAR */
        .sidebar {
            width: 220px;
            background-color: #222;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: -220px;
            transition: left 0.3s ease-in-out;
            padding-top: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
            z-index: 2;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar h3 {
            margin: 0 0 20px;
            text-align: center;
            color: orange;
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 12px 20px;
            border-bottom: 1px solid #444;
        }

        .sidebar a:hover {
            background-color: #444;
            color: orange;
        }

        /* MAIN PAGE CONTENT */
        .main-content {
            margin-left: 0;
            width: 100%;
            transition: margin-left 0.3s ease-in-out;
        }

        .navbar {
    display: flex;
    justify-content: flex-start; /* Align items to the left */
    align-items: center;
    background: #333;
    padding: 15px 30px;
    color: #fff;
}

.logo {
    font-size: 24px;
    font-weight: bold;
    margin-right: auto; /* Push other elements to the right */
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

        .dropdown,
        .sub-dropdown,
        .year-dropdown {
            display: none;
            position: absolute;
            background: #444;
            top: 100%;
            left: 0;
            z-index: 1;
            border-radius: 5px;
            padding: 12px 12px 10px 20px;
            min-width: 200px;
        }

        li:hover>.dropdown,
        .dropdown:hover,
        .dropdown li:hover>.sub-dropdown,
        .sub-dropdown:hover,
        .sub-dropdown li:hover>.year-dropdown,
        .year-dropdown:hover {
            display: block;
        }

        a:hover {
            background: orange;
            border-radius: 4px;
        }

        .content {
            padding: 40px;
        }

        .syllabus {
            display: none;
            margin-top: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-left: 4px solid orange;
        }

        .news-section {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-height: 500px;
            overflow: hidden;
            position: relative;
        }

        .news-section h2 {
            margin-bottom: 15px;
            color: #003366;
        }

        .news-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            animation: scrollNews 20s linear infinite;
        }

        .news-card {
            padding: 15px;
            border-radius: 10px;
            background-color: #f2f2f2;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, background 0.3s ease;
        }

        .news-card:hover {
            transform: scale(1.02);
            background: #fff7e6;
        }

        .news-card h3 {
            margin: 0 0 5px;
            font-size: 18px;
            color: #333;
        }

        .news-card .date {
            font-size: 13px;
            color: gray;
            margin-bottom: 10px;
        }

        .news-card .content {
            margin: 0;
            font-size: 15px;
            color: #444;
        }

        @keyframes scrollNews {
            0% {
                transform: translateY(0%);
            }

            100% {
                transform: translateY(-100%);
            }
        }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <h3>📌 Menu</h3>
        <a href="syllabus.html">📘 Syllabus</a>
        <a href="qp.html">🧪 Previous Years Question Papers</a>
        <a href="pg1.html">ℹ️College Info</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <nav class="navbar">
            <button onclick="toggleMenu()">☰</button>
            <div class="logo">E-Learning Hub 📚</div>
            <ul class="nav-links">
                <li>
                    <a>Study Material</a>
                    <ul class="dropdown">
                        <li><a href="gate.html" onclick="showSyllabus('gate')">GATE</a></li>
                        <li>
                            <a>Department</a>
                            <ul class="sub-dropdown" id="deptDropdown"></ul>
                        </li>
                    </ul>
                </li>
                <li><a href="scholarships.html">Scholarships</a></li>
                <li><a href="feedback.html">Feedback</a></li>
                <li><a href="update_profile.php">Profile</a></li>
                <li><a href="index.html">Logout</a></li>
            </ul>
        </nav>

        <div class="content">
            <div id="syllabusDisplay" class="syllabus"></div>

            <!-- News Section -->
            <div class="news-section">
                <h2>📰 Latest News</h2>
                <div class="news-container" id="newsContainer">
                    <?php
                    $conn = new mysqli("localhost", "root", "", "elearning");
                    if ($conn->connect_error) {
                        echo "<div class='news-card error'>Error loading news</div>";
                    } else {
                        $sql = "SELECT title, content, posted_on FROM news ORDER BY posted_on DESC LIMIT 5";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='news-card'>
                                        <h3>" . htmlspecialchars($row["title"]) . "</h3>
                                        <p class='date'>" . date("d M Y", strtotime($row["posted_on"])) . "</p>
                                        <p class='content'>" . nl2br(htmlspecialchars($row["content"])) . "</p>
                                      </div>";
                            }
                        } else {
                            echo "<div class='news-card'>No news available</div>";
                        }
                        $conn->close();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleMenu() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("active");
            const mainContent = document.querySelector(".main-content");
            mainContent.style.marginLeft = sidebar.classList.contains("active") ? "220px" : "0";
        }

        const departments = {
            'cse': 'Computer Science and Engineering',
            'ds': 'Data Science Engineering',
            'ai': 'Artificial Intelligence and ML',
            'entc': 'Electronics and Telecommunication',
            'mech': 'Mechanical',
            'civil': 'Civil',
            'chem': 'Chemical'
        };
        const years = ['First', 'Second', 'Third', 'Final'];

        function showSyllabus(key) {
            const display = document.getElementById("syllabusDisplay");
            display.innerHTML = syllabusData[key] || "<p>Syllabus not available.</p>";
            display.style.display = "block";
        }

        const deptDropdown = document.getElementById('deptDropdown');
        Object.entries(departments).forEach(([key, name]) => {
            const deptItem = document.createElement('li');
            deptItem.innerHTML = `<a>${name}</a>`;
            const yearList = document.createElement('ul');
            yearList.className = 'year-dropdown';
            years.forEach((yr, idx) => {
                const li = document.createElement('li');
                const y = idx + 1;
                li.innerHTML = `<a href="notes.php?dept=${key}&year=${y}">${yr} Year</a>`;
                yearList.appendChild(li);
            });
            deptItem.appendChild(yearList);
            deptDropdown.appendChild(deptItem);
        });
    </script>
</body>

</html>
