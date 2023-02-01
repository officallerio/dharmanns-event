<?php

session_start();

include 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/main/dashboard.css">
    <link rel="stylesheet" href="css/main/dwithf.css">
    <title>Dhar Mann's Event - Home</title>
</head>

<body>
    <header>
        <div class="header-container">
            <div class="header-wrapper">
                <h1>Dhar Mann's Event</h1>
                <button id='logout-btn' name='logout-btn'>Logout</button>
            </div>
        </div>
        </div>
    </header>
    <?php
    $sql = "SELECT * FROM users WHERE id='{$_SESSION["user_id"]}'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="home">
                <div class="container">
                    <div class="home-weapper">
                        <div class="home-left">

                            <div class="profile">
                                <img src="img/user.png" alt="user">
                                <h3>
                                    <?php echo $row['full_name']; ?>
                                </h3>
                            </div>
                            <div class="pages">
                                <?php
                                $sql = "SELECT * FROM users WHERE id='{$_SESSION["user_id"]}'";
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    if ($row["user_type"] == "admin") {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<button id='admin-view-btn' name='admin-view'>View Users</button>";
                                        }
                                    } else {
                                        echo "<button id='user-view-btn' name='admin-view'>View My Info</button>";
                                        echo "<button id='attend-btn' name='attend-btn'>Attend</button>";
                                        echo "<input type='text' placeholder='Enter Key' id='event_key_input' value=''/>";
                                        echo "<style>
                                                .home-left {
                                                    height: 320px;
                                                }
                                                .explore>div {
                                                    bottom: 510px;
                                                }
                                                .
                                            </style>";
                                    }
                                }
                                ?>
                            </div>
                            <div class="explore">
                                <div><label class="darkTheme"> <span></span></label> Apply Dark Theme</div>
                            </div>
                        </div>
                        <div class="home-center">
                            <div class="home-center-wrapper">
                                <div class="createPost">
                                    <h3 class="mini-headign">Create Post</h3>
                                    <form action="post.php" method="post" enctype="multipart/form-data">
                                        <div class="post-text">
                                            <input type="text" placeholder="" name="content_post" id="content_post" value=""
                                                oninput="updateCounter()" />
                                            <p id="counter">0/500</p>

                                        </div>
                                        <div class="post-icon">
                                            <form action="" method="post" enctype="multipart/form-data">
                                                <a style="background: #00203FFF;">
                                                    <label for="file-input" style="cursor: pointer; background: #00203FFF;">
                                                        <i style="background: #00203FFF;" class="fa-solid fa-file"></i>
                                                        <input type="file" id="file-input" name="file" style="display:none;">
                                                        Select a File
                                                    </label>
                                                    <label style="cursor: pointer; background: #00203FFF;">
                                                        <i style="background: #00203FFF;" class="fa fa-upload"></i>
                                                        <input type="submit" name="submit" value="Upload" style="display:none;">
                                                        Post
                                                    </label>
                                                </a>
                                            </form>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php
                            $counter = 1;
                            $sql = "SELECT * FROM tblposts";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $content = $row['content'];
                                    $user_id = $row['user_id'];
                                    $full_name = $row['full_name'];
                                    echo "<div class='fb-post" . $counter . "'>
                                             <div class='fb-post-container'>
                                                 <div class='fb-p-main'>
                                                 <div class='post-title'>
                                                 <ul>
                                                     <li>
                                                         <h3>$full_name</h3>
                                                     </li>
                                                 </ul>
                                                 <p>$content
                                             </p>
                                             </div>
                                                 </div>
                                             </div>
                                         </div>";
                                    $counter++;
                                }
                            }
                            $counter = 1;
                            $sql = "SELECT * FROM tblphotos";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $file_url = $row['file_path'];
                                    $file_name = $row['file_name'];
                                    $file_type = $row['file_type'];
                                    $full_name = $row['full_name'];
                                    if ($file_type == 'jpg' || $file_type == 'jpeg' || $file_type == 'png' || $file_type == 'gif') {
                                        echo "<div class='fb-post" . $counter . "'>
                                            <div class='fb-post-container'>
                                                <div class='fb-p-main'>
                                                <div class='post-title'>
                                                <ul>
                                                    <li>
                                                        <h3>$full_name</h3>
                                                    </li>
                                                </ul>
                                                <p>
                                            </p>
                                            </div>
                                                <img src='$file_url'>
                                                </div>
                                            </div>
                                        </div>";
                                        $counter++;
                                    }
                                }
                            }
                            $sql = "SELECT * FROM tblvideos";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $file_url = $row['file_path'];
                                    $file_name = $row['file_name'];
                                    $file_type = $row['file_type'];
                                    $fullname = $row['full_name'];
                                    if ($file_type == 'mp4' || $file_type == 'mkv') {
                                        echo "<div class='fb-post" . $counter . "'>
                                            <div class='fb-post-container'>
                                                <div class='fb-p-main'>
                                                <div class='post-title'>
                                                <ul>
                                                    <li>
                                                        <h3>$fullname</h3>
                                                    </li>
                                                </ul>
                                                <p>
                                            </p>
                                            </div>
                                                <video controls>
                                                <source src='$file_url' type='video/mp4'>
                                                </video>
                                                </div>
                                            </div>
                                        </div>";
                                        $counter++;
                                    }
                                }
                            }
                            $sql = "SELECT * FROM tblboth";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $file_url = $row['file_path'];
                                    $file_name = $row['file_name'];
                                    $file_type = $row['file_type'];
                                    $fullname = $row['full_name'];
                                    $content = $row['content'];
                                    if ($file_type == 'mp4' || $file_type == 'mkv') {
                                        echo "<div class='fb-post" . $counter . "'>
                                            <div class='fb-post-container'>
                                                <div class='fb-p-main'>
                                                <div class='post-title'>
                                                <ul>
                                                    <li>
                                                        <h3>$fullname</h3>
                                                    </li>
                                                </ul>
                                                <p>$content
                                            </p>
                                            </div>
                                                <video width='852' height='320' controls>
                                                <source src='$file_url' type='video/mp4'>
                                                </video>
                                                </div>
                                            </div>
                                        </div>";
                                        $counter++;
                                    }
                                }
                            }
                            $sql = "SELECT * FROM tblboth";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $file_url = $row['file_path'];
                                    $file_name = $row['file_name'];
                                    $file_type = $row['file_type'];
                                    $fullname = $row['full_name'];
                                    $content = $row['content'];
                                    if ($file_type == 'jpg' || $file_type == 'jpeg' || $file_type == 'png' || $file_type == 'gif') {
                                        echo "<div class='fb-post" . $counter . "'>
                                            <div class='fb-post-container'>
                                                <div class='fb-p-main'>
                                                <div class='post-title'>
                                                <ul>
                                                    <li>
                                                        <h3>$fullname</h3>
                                                    </li>
                                                </ul>
                                                <p>$content
                                            </p>
                                            </div>
                                                <img src='$file_url' alt='Uploaded Image' style='width: 100%;'>
                                                </div>
                                            </div>
                                        </div>";
                                        $counter++;
                                    }
                                }
                            }
                            ?>
                        </div>
                        <div class="home-right">
                            <div class="home-right-wrapper">
                                <div class="event-friend">
                                    <div class="event">
                                        <h3 class="heading">Donations</h3>
                                        <img src="img/gcash.svg" alt="event-img">
                                        <div class="event-date">
                                            <h4>GCASH <span>E-Wallet</span></h4>
                                        </div>
                                        <div class="overlay-admin">
                                            <div class="body-admin">
                                                <div class="admin-center">
                                                    <div class="admin-title">
                                                        <label>ADMIN DASHBOARD</label>
                                                    </div>
                                                    <div class="admin-description">
                                                        <table id="admin-panel">
                                                            <tr>
                                                                <th>Full Name</th>
                                                                <th>Email</th>
                                                                <th>Birthday</th>
                                                                <th>Donation</th>
                                                                <th>Event Key</th>
                                                                <th>Status</th>
                                                            </tr>
                                                            <?php
                                                            $sql = "SELECT u.*, ud.donations FROM users u LEFT JOIN users_donation ud ON u.full_name = ud.full_name WHERE u.user_type = 'user'";
                                                            $result = mysqli_query($conn, $sql);
                                                            if (mysqli_num_rows($result) > 0) {
                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    $full_name = $row['full_name'];
                                                                    $email = $row['email'];
                                                                    $birthday = $row['birthday'];
                                                                    $donations = $row['donations'];
                                                                    $event_key = $row['event_key'];
                                                                    $status = $row['status'];
                                                                    echo "
                                                                    <tr>
                                                                    <td>$full_name</td>
                                                                    <td>$email</td>
                                                                    <td>$birthday</td>
                                                                    <td>$donations</td>
                                                                    <td>$event_key</td>
                                                                    <td>$status</td>
                                                                    <td></td>
                                                                    </tr>";
                                                                }
                                                            }
                                                            ?>
                                                        </table>
                                                    </div>
                                                    <div class="admin-close-btn">
                                                        <button id="close-admin-btn">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="overlay-user-info">
                                            <div class="body-user-info">
                                                <div class="user-info-center">
                                                    <div class="user-info-title">
                                                        <label>USER DASHBOARD</label>
                                                    </div>
                                                    <div class="user-info-description">
                                                        <table id="user-info-panel">
                                                            <tr>
                                                                <th>Full Name</th>
                                                                <th>Email</th>
                                                                <th>Birthday</th>
                                                                <th>Donation</th>
                                                                <th>Event Key</th>
                                                                <th>Status</th>
                                                            </tr>
                                                            <?php
                                                            $sql = "SELECT u.*, ud.donations FROM users u LEFT JOIN users_donation ud ON u.full_name = ud.full_name WHERE u.id ='{$_SESSION["user_id"]}'";
                                                            $result = mysqli_query($conn, $sql);
                                                            if (mysqli_num_rows($result) > 0) {
                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    $full_name = $row['full_name'];
                                                                    $email = $row['email'];
                                                                    $birthday = $row['birthday'];
                                                                    $donations = $row['donations'];
                                                                    $event_key = $row['event_key'];
                                                                    $status = $row['status'];
                                                                    echo "
                                                                    <tr>
                                                                    <td>$full_name</td>
                                                                    <td>$email</td>
                                                                    <td>$birthday</td>
                                                                    <td>$donations</td>
                                                                    <td>$event_key</td>
                                                                    <td>$status</td>
                                                                    <td></td>
                                                                    </tr>";
                                                                }
                                                            }
                                                            ?>
                                                        </table>
                                                    </div>
                                                    <div class="user-info-close-btn">
                                                        <button id="close-user-btn">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="overlay-donation-list">
                                            <div class="body-donation-list">
                                                <div class="donation-list-center">
                                                    <div class="donation-list-title">
                                                        <label>LIST OF DONATORS</label>
                                                    </div>
                                                    <div class="donation-list-description">
                                                        <table id="donation-list-panel">
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Amount</th>
                                                            </tr>
                                                            <?php
                                                            $sql = "SELECT * FROM users_donation";
                                                            $result = mysqli_query($conn, $sql);
                                                            if (mysqli_num_rows($result) > 0) {
                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    $fullname = $row['full_name'];
                                                                    $donations = $row['donations'];

                                                                    echo "
                                                                            <tr>
                                                                            <td>$fullname</td>
                                                                            <td>$donations</td>
                                                                            </tr>";
                                                                }
                                                            }
                                                            ?>
                                                        </table>
                                                    </div>
                                                    <div class="donation-list-close-btn">
                                                        <button id="donation-list-close-btn">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="overlay-event-key-sucess">
                                            <div class="body-event-key-sucess">
                                                <div class="event-key-sucess-center">
                                                    <div class="event-key-sucess-icon">
                                                        <i class="fa fa-check"></i>
                                                    </div>
                                                    <div class="event-key-sucess-title">
                                                        <label>Event Key Accepted</label>
                                                    </div>
                                                    <div class="event-key-sucess-description">
                                                        <p>See you soon !</p>
                                                    </div>
                                                    <div class="event-key-sucess-close-btn">
                                                        <button id="close-event-key-sucess-btn">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="overlay-event-key-again">
                                            <div class="body-event-key-again">
                                                <div class="event-key-again-center">
                                                    <div class="event-key-again-icon">
                                                        <i class="fa fa-question-circle"></i>
                                                    </div>
                                                    <div class="event-key-again-title">
                                                        <label>You Already Attended This Event</label>
                                                    </div>
                                                    <div class="event-key-again-description">
                                                        <p>Bungawon !</p>
                                                    </div>
                                                    <div class="event-key-again-close-btn">
                                                        <button id="close-event-key-again-btn">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="overlay-event-key-failed">
                                            <div class="body-event-key-failed">
                                                <div class="event-key-failed-center">
                                                    <div class="event-key-failed-icon">
                                                        <i class="fa fa-times"></i>
                                                    </div>
                                                    <div class="event-key-failed-title">
                                                        <label>Event Key Not Accepted</label>
                                                    </div>
                                                    <div class="event-key-failed-description">
                                                        <p>Invalid Event Key</p>
                                                    </div>
                                                    <div class="event-key-failed-close-btn">
                                                        <button id="close-event-key-failed-btn">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="overlay-input-failed">
                                            <div class="body-input-failed">
                                                <div class="input-failed-center">
                                                    <div class="input-failed-icon">
                                                        <i class="fa fa-times"></i>
                                                    </div>
                                                    <div class="input-failed-title">
                                                        <label>Please Enter A Event Key</label>
                                                    </div>
                                                    <div class="input-failed-description">

                                                    </div>
                                                    <div class="input-failed-close-btn">
                                                        <button id="close-input-failed-close-btn">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="overlay-1">
                                            <div class="body-donation">
                                                <div class="donation-center">
                                                    <div class="donation-icon">
                                                        <i class="fa fa-check"></i>
                                                    </div>
                                                    <div class="donation-title">
                                                        <label>Donation Succesfully Sent</label>
                                                    </div>
                                                    <div class="donation-description">
                                                        <p>Thank you !</p>
                                                    </div>
                                                    <div class="donation-close-btn">
                                                        <button id="close-donation-btn">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="overlay-failed">
                                            <div class="body-failed">
                                                <div class="donation-center-failed">
                                                    <div class="donation-icon-failed">
                                                        <i class="fa fa-times"></i>
                                                    </div>
                                                    <div class="donation-title-failed">
                                                        <label>Donation Failed</label>
                                                    </div>
                                                    <div class="donation-description-failed">
                                                        <p>Please enter a valid amount.</p>
                                                    </div>
                                                    <div class="donation-close-btn-failed">
                                                        <button id="close-donation-btn-failed">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="overlay-failed2">
                                            <div class="body-failed2">
                                                <div class="donation-center-failed2">
                                                    <div class="donation-icon-failed2">
                                                        <i class="fa fa-times"></i>
                                                    </div>
                                                    <div class="donation-title-failed2">
                                                        <label>No One Donated Yet</label>
                                                    </div>
                                                    <div class="donation-description-failed2">

                                                    </div>
                                                    <div class="donation-close-btn-failed2">
                                                        <button id="close-donation-btn-failed2">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="number" placeholder="      Enter Amount" name="donation_input" value="" />
                                        <button id="open-donation-btn" name="donate-btn"><i
                                                class="fas fa-donate"></i>Donate</button>
                                    </div>
                                </div>
                                <div class="event-friend">
                                    <div class="event">
                                        <h3 class="heading">List of Donators</h3>
                                        <button id="donation-list-btn" name="donation-list-btn"><i
                                                class="fas fa-donate"></i>View List of Donators</button>
                                    </div>
                                </div>
                            </div>
                        <?php
        }
    }
    ?>
                </div>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>

                    document.getElementById("logout-btn").addEventListener("click", function () {
                        window.location.replace("logout.php");
                    })

                    function updateCounter() {
                        let input = document.getElementById("content_post");
                        let counter = document.getElementById("counter");
                        counter.innerHTML = input.value.length + "/" + input.maxLength;
                    }

                    document.getElementById("content_post").maxLength = 500;

                    document.getElementById("open-donation-btn").addEventListener("click", function (event) {
                        event.preventDefault();
                        const donationInput = document.getElementsByName("donation_input")[0].value;
                        if (!donationInput || donationInput == 0) {
                            document.getElementsByClassName("donation-center-failed")[0].classList.add("active");
                            document.getElementsByClassName("overlay-failed")[0].classList.add("active");
                        } else {
                            document.getElementsByClassName("donation-center")[0].classList.add("active");
                            document.getElementsByClassName("overlay-1")[0].classList.add("active");
                            fetch('donation.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: `donation_input=${donationInput}`
                            })
                                .then(response => response.text())
                                .then(data => {
                                })
                                .catch(error => {
                                });
                        }
                    });

                    var attendance = document.getElementById("attend-btn");
                    if (attendance) {
                        document.getElementById("attend-btn").addEventListener("click", function (event) {
                            event.preventDefault();

                            const inputKey = document.getElementById("event_key_input").value;
                            if (!inputKey) {
                                document.getElementsByClassName("input-failed-center")[0].classList.add("active");
                                document.getElementsByClassName("overlay-input-failed")[0].classList.add("active");
                                return;
                            }

                            const xhr = new XMLHttpRequest();
                            xhr.open("POST", "attendance.php", true);
                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            xhr.onreadystatechange = function () {
                                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                                    console.log(this.responseText);
                                    const response = JSON.parse(this.responseText);
                                    if (response.success) {
                                        // Input key matches the key in the database
                                        document.getElementsByClassName("event-key-sucess-center")[0].classList.add("active");
                                        document.getElementsByClassName("overlay-event-key-sucess")[0].classList.add("active");
                                    } else if (response.done) {
                                        document.getElementsByClassName("event-key-again-center")[0].classList.add("active");
                                        document.getElementsByClassName("overlay-event-key-again")[0].classList.add("active");
                                    } else {
                                        // Input key does not match the key in the database
                                        document.getElementsByClassName("event-key-failed-center")[0].classList.add("active");
                                        document.getElementsByClassName("overlay-event-key-failed")[0].classList.add("active");
                                    }
                                }
                            };
                            xhr.send(`inputKey=${inputKey}`);
                        });
                    }

                    document.getElementById("close-event-key-sucess-btn").addEventListener("click", function () {
                        document.getElementsByClassName("event-key-sucess-center")[0].classList.remove("active");
                        document.getElementsByClassName("overlay-event-key-sucess")[0].classList.remove("active");
                        window.location.replace("index.php");
                    });

                    document.getElementById("close-event-key-failed-btn").addEventListener("click", function () {
                        document.getElementsByClassName("event-key-failed-center")[0].classList.remove("active");
                        document.getElementsByClassName("overlay-event-key-failed")[0].classList.remove("active");
                    });

                    document.getElementById("close-event-key-again-btn").addEventListener("click", function () {
                        document.getElementsByClassName("event-key-again-center")[0].classList.remove("active");
                        document.getElementsByClassName("overlay-event-key-again")[0].classList.remove("active");
                    });

                    document.getElementById("close-input-failed-close-btn").addEventListener("click", function () {
                        document.getElementsByClassName("input-failed-center")[0].classList.remove("active");
                        document.getElementsByClassName("overlay-input-failed")[0].classList.remove("active");
                    });

                    document.getElementById("close-donation-btn-failed").addEventListener("click", function () {
                        document.getElementsByClassName("donation-center-failed")[0].classList.remove("active");
                        document.getElementsByClassName("overlay-failed")[0].classList.remove("active");
                    });

                    document.getElementById("close-donation-btn").addEventListener("click", function () {
                        document.getElementsByClassName("donation-center")[0].classList.remove("active");
                        document.getElementsByClassName("overlay-1")[0].classList.remove("active");
                        document.getElementsByName("donation_input")[0].value = '';
                        window.location.replace("index.php");
                    });

                    var btn = document.getElementById("admin-view-btn");
                    if (btn) {
                        btn.addEventListener("click", function (event) {
                            event.preventDefault();
                            document.getElementsByClassName("admin-center")[0].classList.add("active");
                            document.getElementsByClassName("overlay-admin")[0].classList.add("active");
                            document.body.classList.add("overflow-hidden");
                        });
                    }

                    var btn1 = document.getElementById("user-view-btn");
                    if (btn1) {
                        btn1.addEventListener("click", function (event) {
                            event.preventDefault();
                            document.getElementsByClassName("user-info-center")[0].classList.add("active");
                            document.getElementsByClassName("overlay-user-info")[0].classList.add("active");
                            document.body.classList.add("overflow-hidden");
                        });
                    }

                    document.getElementById("close-user-btn").addEventListener("click", function (event) {
                        event.preventDefault();
                        document.getElementsByClassName("user-info-center")[0].classList.remove("active");
                        document.getElementsByClassName("overlay-user-info")[0].classList.remove("active");
                        document.body.classList.remove("overflow-hidden");
                    });

                    document.getElementById("close-admin-btn").addEventListener("click", function (event) {
                        event.preventDefault();
                        document.getElementsByClassName("admin-center")[0].classList.remove("active");
                        document.getElementsByClassName("overlay-admin")[0].classList.remove("active");
                        document.body.classList.remove("overflow-hidden");
                    });

                    document.getElementById("donation-list-btn").addEventListener("click", function (event) {
                        event.preventDefault();

                        var rowCount = document.getElementById("donation-list-panel").rows.length;
                        if (rowCount <= 1) {
                            document.getElementsByClassName("donation-center-failed2")[0].classList.add("active");
                            document.getElementsByClassName("overlay-failed2")[0].classList.add("active");
                        } else {
                            document.getElementsByClassName("donation-list-center")[0].classList.add("active");
                            document.getElementsByClassName("overlay-donation-list")[0].classList.add("active");
                            document.body.classList.remove("overflow-hidden");
                        }
                    });

                    document.getElementById("close-donation-btn-failed2").addEventListener("click", function () {
                        document.getElementsByClassName("donation-center-failed2")[0].classList.remove("active");
                        document.getElementsByClassName("overlay-failed2")[0].classList.remove("active");
                    });

                    document.getElementById("donation-list-close-btn").addEventListener("click", function (event) {
                        event.preventDefault();
                        document.getElementsByClassName("donation-list-center")[0].classList.remove("active");
                        document.getElementsByClassName("overlay-donation-list")[0].classList.remove("active");
                        document.body.classList.remove("overflow-hidden");
                    });

                    var darkButton = document.querySelector(".darkTheme");

                    var isDarkMode = localStorage.getItem("isDarkMode") === "true";

                    if (isDarkMode) {
                        document.body.classList.add("dark-color");
                        darkButton.classList.add("button-Active");
                    }

                    darkButton.onclick = function () {
                        darkButton.classList.toggle("button-Active");
                        document.body.classList.toggle("dark-color");
                        localStorage.setItem("isDarkMode", document.body.classList.contains("dark-color"));
                    };

                </script>
</body>

</html>