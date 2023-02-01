<?php

session_start();

include 'config.php';

if (isset($_POST["submit"])) {
    if (empty($_POST["content_post"]) && empty($_FILES["file"]["name"])) {
        echo "<script>alert('Please enter a sentence or upload a file.');</script>";
        header("refresh: 0.1");
    } elseif (empty($_POST["content_post"]) && !empty($_FILES["file"]["name"])) {
        $allowed_file_types = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt', 'mp4', 'mkv', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'zip', 'rar');
        $file_name = mysqli_real_escape_string($conn, $_FILES["file"]["name"]);
        $file_tmp_name = $_FILES["file"]["tmp_name"];
        $file_size = $_FILES["file"]["size"];
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Check if the user is an admin
        if ($_SESSION['user_type'] != 'admin') {
            echo "<script>alert('You do not have permission to submit a file.');</script>";
            header("Location: welcome.php");
        } else if ($file_size > 25000000) {
            echo "<script>alert('File is too big. Maximum file size is 25MB.');</script>";
            header("Location: welcome.php");
        } else if (!in_array($file_type, $allowed_file_types)) {
            echo "<script>alert('Invalid file type. Please upload a valid file type.');</script>";
            header("Location: welcome.php");
        } else {
            // check if file type is jpg, jpeg, png, gif
            if (in_array($file_type, array('jpg', 'jpeg', 'png', 'gif'))) {
                $table_name = "tblphotos";
            } else if (in_array($file_type, array('mp4', 'mkv'))) {
                $table_name = "tblvideos";
            } else {
                $table_name = "tblothers";
            }

            $sql = "SELECT * FROM users WHERE id='{$_SESSION["user_id"]}'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $fullname = $row['full_name'];
                }
            }

            // Generate new file name
            $file_new_name = rand() . $file_name;

            // Prepare query for inserting into the database
            $sql = "INSERT INTO $table_name (file_name, file_type, file_path, user_id, full_name) VALUES ('$file_new_name', '$file_type', 'attachments/$file_new_name', '{$_SESSION["user_id"]}', '$fullname')";

            // Execute query and move the uploaded file
            if (mysqli_query($conn, $sql) && move_uploaded_file($file_tmp_name, "attachments/" . $file_new_name)) {
                header("Location: welcome.php");
            } else {
                echo "<script>alert('File Cannot Be Uploaded.');</script>";
                header("Location: welcome.php");
            }
        }
    } elseif (empty($_FILES["file"]["name"]) && !empty($_POST["content_post"])) {
        if ($_SESSION['user_type'] != 'admin') {
            echo "<script>alert('You do not have permission to submit a file.');</script>";
            header("Location: welcome.php");
        } else {

            $sql = "SELECT * FROM users WHERE id='{$_SESSION["user_id"]}'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $fullname = $row['full_name'];
                }
            }

            $sentence = mysqli_real_escape_string($conn, $_POST['content_post']);
            // Insert sentence into tblposts table
            $sql = "INSERT INTO tblposts (content, user_id, full_name) VALUES ('$sentence', '{$_SESSION["user_id"]}', '$fullname')";
            if (mysqli_query($conn, $sql)) {
                header("Location: welcome.php");
            } else {
                header("Location: welcome.php");
            }
        }
    } else {
        $allowed_file_types = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt', 'mp4', 'mkv', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'zip', 'rar');
        $file_name = mysqli_real_escape_string($conn, $_FILES["file"]["name"]);
        $file_tmp_name = $_FILES["file"]["tmp_name"];
        $file_size = $_FILES["file"]["size"];
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if ($_SESSION['user_type'] != 'admin') {
            echo "<script>alert('You do not have permission to submit a file.');</script>";
            header("Location: welcome.php");
        } else if ($file_size > 25000000) {
            echo "<script>alert('File is too big. Maximum file size is 25MB.');</script>";
            header("Location: welcome.php");
        } else if (!in_array($file_type, $allowed_file_types)) {
            echo "<script>alert('Invalid file type. Please upload a valid file type.');</script>";
            header("Location: welcome.php");
        } else {

            $sql = "SELECT * FROM users WHERE id='{$_SESSION["user_id"]}'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $fullname = $row['full_name'];
                }
            }

            $file_new_name = rand() . $file_name;
            $sentence = mysqli_real_escape_string($conn, $_POST['content_post']);
            $sql = "INSERT INTO tblboth (file_name, file_type, file_path, content, user_id, full_name) VALUES ('$file_new_name', '$file_type', 'attachments/$file_new_name', '$sentence', '{$_SESSION["user_id"]}', '$fullname')";
            if (mysqli_query($conn, $sql) && move_uploaded_file($file_tmp_name, "attachments/" . $file_new_name)) {
                header("Location: welcome.php");
            } else {
                echo "<script>alert('File Cannot Be Uploaded.');</script>";
                header("Location: welcome.php");
            }
        }
    }
}

?>