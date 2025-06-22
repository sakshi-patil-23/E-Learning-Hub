<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connect to MySQL
$conn = new mysqli("localhost", "root", "", "elearning");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dept = $_POST['department'];
    $year = $_POST['year'];
    $subject = $_POST['subject'];
    $semester = $_POST['semester'];
    $unit = $_POST['unit'];
    $target_dir = "uploads/";  // Folder to store uploaded files
    $youtube_links = $_POST['youtube_links'] ?? [];
    $youtube_descs = $_POST['youtube_descriptions'] ?? [];

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
    }

    $success = 0;

    // Handle Notes upload
    if (!empty($_FILES["notes_files"]["name"][0])) {
        foreach ($_FILES["notes_files"]["tmp_name"] as $i => $tmpName) {
            if ($_FILES["notes_files"]["error"][$i] === 0) {
                $file_path = $target_dir . basename($_FILES["notes_files"]["name"][$i]);
                if (move_uploaded_file($tmpName, $file_path)) {
                    $stmt = $conn->prepare("INSERT INTO study_materials (department, year, semester, subject, unit, type, link_or_file) VALUES (?, ?, ?, ?, ?, 'notes', ?)");
                    $stmt->bind_param("ssssss", $dept, $year, $semester, $subject, $unit, $file_path);
                    if ($stmt->execute()) {
                        $success++;
                    } else {
                        echo "<script>alert('❌ Notes Insert Error: " . addslashes($stmt->error) . "');</script>";
                    }
                } else {
                    echo "<script>alert('❌ Failed to move uploaded notes file: " . $_FILES["notes_files"]["name"][$i] . "');</script>";
                }
            } else {
                echo "<script>alert('❌ Upload error for note file: " . $_FILES["notes_files"]["name"][$i] . " | Error Code: " . $_FILES["notes_files"]["error"][$i] . "');</script>";
            }
        }
    }

    // Handle Books upload
    if (!empty($_FILES["book_files"]["name"][0])) {
        foreach ($_FILES["book_files"]["tmp_name"] as $i => $tmpName) {
            if ($_FILES["book_files"]["error"][$i] === 0) {
                $file_path = $target_dir . basename($_FILES["book_files"]["name"][$i]);
                if (move_uploaded_file($tmpName, $file_path)) {
                    $stmt = $conn->prepare("INSERT INTO study_materials (department, year, semester, subject, unit, type, link_or_file) VALUES (?, ?, ?, ?, ?, 'books', ?)");
                    $stmt->bind_param("ssssss", $dept, $year, $semester, $subject, $unit, $file_path);
                    if ($stmt->execute()) {
                        $success++;
                    } else {
                        echo "<script>alert('❌ Book Insert Error: " . addslashes($stmt->error) . "');</script>";
                    }
                } else {
                    echo "<script>alert('❌ Failed to move uploaded book file: " . $_FILES["book_files"]["name"][$i] . "');</script>";
                }
            } else {
                echo "<script>alert('❌ Upload error for book file: " . $_FILES["book_files"]["name"][$i] . " | Error Code: " . $_FILES["book_files"]["error"][$i] . "');</script>";
            }
        }
    }

    // Handle YouTube Links
    for ($i = 0; $i < count($youtube_links); $i++) {
        $link = $conn->real_escape_string($youtube_links[$i]);
        $desc = $conn->real_escape_string($youtube_descs[$i]);

        if (!empty($link)) {
            $stmt = $conn->prepare("INSERT INTO study_materials 
                (department, year, semester, subject, unit, type, link_or_file, description) 
                VALUES (?, ?, ?, ?, ?, 'video', ?, ?)");
            $stmt->bind_param("sssssss", $dept, $year, $semester, $subject, $unit, $link, $desc);
            if ($stmt->execute()) {
                $success++;
            } else {
                echo "<script>alert('❌ YouTube Link Insert Error: " . addslashes($stmt->error) . "');</script>";
            }
            $stmt->close();
        }
    }

    // Final status message using JavaScript popup
    if ($success > 0) {
        // Redirect to upload.html page after successful upload
        echo "<script>
            alert('✅ $success item(s) uploaded successfully.');
            window.location.href = 'upload.html';  // Redirect to the upload page
        </script>";
    } else {
        echo "<script>alert('❌ No files or links were uploaded. Please check for errors.');</script>";
    }
}
?>
