<?php


session_start();

include 'config.php';

$sql = "SELECT * FROM users WHERE id='{$_SESSION["user_id"]}'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['user_id'];
        $full_name = $row['full_name'];
        $email = $row['email'];
        $birthday = $row['birthday'];
    }
}

$check_user = mysqli_query($conn, "SELECT id, full_name  FROM users_donation WHERE full_name='$full_name'");
if (mysqli_num_rows($check_user) > 0) {
    if (isset($_POST["donation_input"])) {
        $amount = mysqli_real_escape_string($conn, $_POST['donation_input']);
        $sql = "UPDATE users_donation SET donations=donations+'$amount' WHERE full_name='$full_name'";
        if (mysqli_query($conn, $sql)) {
            header("Location: index.php");
        } else {
            header("Location: index.php");
        }
    }
} else {
    if (isset($_POST["donation_input"])) {
        $amount = mysqli_real_escape_string($conn, $_POST['donation_input']);
        $sql = "INSERT INTO users_donation (full_name, donations, email, birthday) VALUES ('$full_name', '$amount', '$email', '$birthday')";
        if (mysqli_query($conn, $sql)) {
            header("Location: index.php");
        } else {
            header("Location: index.php");
        }
    }
}
?>