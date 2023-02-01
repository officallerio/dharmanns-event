<?php

include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

session_start();

error_reporting(0);

if (isset($_SESSION["user_id"])) {
  header("Location: welcome.php");
}

$total_users = 50;
$result = mysqli_query($conn, "SELECT COUNT(*) as id FROM users WHERE user_type='user'");
$data = mysqli_fetch_assoc($result);
$current_count = $data['id'];
$key = substr(bin2hex(random_bytes(10)), 0, 15);

if (isset($_POST["signup"])) {

  $full_name = mysqli_real_escape_string($conn, $_POST["signup_full_name"]);
  $email = mysqli_real_escape_string($conn, $_POST["signup_email"]);
  $password = mysqli_real_escape_string($conn, md5($_POST["signup_password"]));
  $cpassword = mysqli_real_escape_string($conn, md5($_POST["signup_cpassword"]));
  $birthday = mysqli_real_escape_string($conn, $_POST["signup_birthday"]);
  $token = md5(rand());

  $check_email = mysqli_num_rows(mysqli_query($conn, "SELECT email FROM users WHERE email='$email'"));

  if ($password !== $cpassword) {
    echo "<script>alert('Password did not match.');</script>";
  } elseif ($check_email > 0) {
    echo "<script>alert('Email already exists.');</script>";
  } else {
    $age = (date("Y") - date("Y", strtotime($birthday)));
    if ($age < 18) {
      echo "<script>alert('You are not eligible to register, As you are below 18');</script>";
    } elseif ($age > 30) {
      echo "<script>alert('You are not eligible to register, As you are above 30');</script>";
    } else {
      if ($current_count >= $total_users) {
        echo "<script>alert('Sorry, the maximum number of registered users has been reached.');</script>";
      } else {
        $sql = "INSERT INTO users (full_name, email, password, token, birthday, user_type, status, event_key) VALUES ('$full_name', '$email', '$password', '$token', '$birthday', 'user', 'not attended', '$key')";
        if (mysqli_query($conn, $sql)) {
          echo "<script>alert('Register Successfully.');</script>";
          header("refresh: 0.1");
        } else {
          echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
      }
    }
  }
}

if (isset($_POST["signin"])) {
  $email = mysqli_real_escape_string($conn, $_POST["email"]);
  $password = mysqli_real_escape_string($conn, md5($_POST["password"]));

  $check_user = mysqli_query($conn, "SELECT id, user_type, full_name FROM users WHERE email='$email' AND password='$password'");
  if (mysqli_num_rows($check_user) > 0) {
    $row = mysqli_fetch_assoc($check_user);
    if ($row["user_type"] == "admin") {
      $_SESSION["user_id"] = $row['id'];
      $_SESSION["user_type"] = $row['user_type'];
      $_SESSION["full_name"] = $row['full_name'];
      header("Location: welcome.php");
    } else {
      $_SESSION["user_id"] = $row['id'];
      $_SESSION["user_type"] = $row['user_type'];
      $_SESSION["full_name"] = $row['full_name'];
      header("Location: welcome.php");
    }
  } else {
    echo "<script>alert('Login details is incorrect. Please try again.');</script>";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/style.css" />
  <title>Dhar Mann's Event</title>
</head>

<body>
  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">
        <form action="" method="post" class="sign-in-form">
          <h2 class="title">Login</h2>
          <div class="input-field">
            <i class="fas fa-users"></i>
            <input type="text" placeholder="Email Address" name="email" value="<?php echo $_POST['email']; ?>" required
              autocomplete="email" />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Password" name="password" value="<?php echo $_POST['password']; ?>"
              required autocomplete="current-password" />
          </div>
          <input type="submit" value="Login" name="signin" class="btn solid" />
        </form>
        <form action="" class="sign-up-form" method="post">
          <h2 class="title">Register</h2>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Full Name" name="signup_full_name"
              value="<?php echo $_POST["signup_full_name"]; ?>" required />
          </div>
          <div class="input-field">
            <i class="fas fa-envelope"></i>
            <input type="email" placeholder="Email Address" name="signup_email"
              value="<?php echo $_POST["signup_email"]; ?>" required />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Password" name="signup_password"
              value="<?php echo $_POST["signup_password"]; ?>" required />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Confirm Password" name="signup_cpassword"
              value="<?php echo $_POST["signup_cpassword"]; ?>" required />
          </div>
          <div class="input-field">
            <i class="fas fa-birthday-cake"></i>
            <input type="date" placeholder="Birthday (MM/DD/YY)" name="signup_birthday"
              value="<?php echo $_POST["signup_birthday"]; ?>" required />
          </div>
          <input type="submit" class="btn" name="signup" value="Sign up" />
        </form>
      </div>
    </div>

    <div class="panels-container">
      <div class="panel left-panel">
        <div class="content">
          <h3>New here ?</h3>
          <p>
            Register now to get a chance to see Dhar Mann's Event !
            Join Now !
          </p>
          <p>
            Number of Participants:
            <?php echo $current_count; ?> /
            <?php echo $total_users; ?>
          </p>
          <button class="btn transparent" id="sign-up-btn">
            Register
          </button>
        </div>
        <img src="img/log.svg" class="image" alt="" />
      </div>
      <div class="panel right-panel">
        <div class="content">
          <h3>Already Registered ?</h3>
          <p>
            Login now to get a chance to see Dhar Mann's Event !

          </p>
          <button class="btn transparent" id="sign-in-btn">
            Login
          </button>
        </div>
        <img src="img/register.svg" class="image" alt="" />
      </div>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
  <script src="app.js"></script>
</body>

</html>