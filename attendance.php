<?php

session_start();

include 'config.php';



$inputKey = $_POST["inputKey"];

$sql = "SELECT * FROM users WHERE id='{$_SESSION["user_id"]}'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $full_name = $row['full_name'];
    }
}

// Connect to the database and get the stored key
$result = mysqli_query($conn, "SELECT status FROM users WHERE event_key = '$inputKey' AND full_name = '$full_name'");

if (mysqli_num_rows($result) > 0) {
    // Input key matches the key in the database for the current user
    $row = mysqli_fetch_assoc($result);
    if ($row["status"] === "Attended") {
        // The user is already set to Attended
        echo json_encode(["done" => true]);
    } else {
        // Update the user's status to Attended
        $sql = "UPDATE users SET status = 'Attended' WHERE event_key = '$inputKey' AND full_name = '$full_name'";
        $result = mysqli_query($conn, $sql);
        echo json_encode(["success" => true]);
    }
} else {
    // Input key does not match the key in the database for the current user
    echo json_encode(["failed" => false]);
}
?>