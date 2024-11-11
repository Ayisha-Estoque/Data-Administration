<?php
include('connect.php');

if (isset($_POST['btnSubmitPost'])) {
    // Fetch data from the form
    $content = $_POST['content'];
    $dateTime = $_POST['dateTime'];
    $privacy = $_POST['privacy'];
    $isDeleted = $_POST['isDeleted'];
    $firstname = $_POST['firstName'];
    $lastname = $_POST['lastName'];
    $cityName = $_POST['cityName'];
    $provinceName = $_POST['provinceName'];

    $provincesQuery = "INSERT INTO provinces (provinceName) VALUES ('$provinceName')";
    executeQuery($provincesQuery);
    $provinceID = mysqli_insert_id($conn);

    $citiesQuery = "INSERT INTO cities (cityName) VALUES ('$cityName')";
    executeQuery($citiesQuery);
    $cityID = mysqli_insert_id($conn);

    $userinfoQuery = "INSERT INTO userinfo (firstname, lastname, birthday) 
                      VALUES ('$firstname', '$lastname', '$birthday')";
    executeQuery($userinfoQuery);
    $userInfoID = mysqli_insert_id($conn);

    $addressQuery = "INSERT INTO addresses (userInfoID, cityID, provinceID) 
                     VALUES ('$userInfoID', '$cityID', '$provinceID')";
    executeQuery($addressQuery);
    $addressID = mysqli_insert_id($conn);

    $usersQuery = "INSERT INTO users (userInfoID) VALUES ('$userInfoID')";
    executeQuery($usersQuery);
    $userID = mysqli_insert_id($conn);

    // Insert into userinfo with the userID and addressID
    $updateUserInfoQuery = "UPDATE userinfo SET userID = '$userID', addressID = '$addressID' 
                            WHERE userInfoID = '$userInfoID'";
    executeQuery($updateUserInfoQuery);  // Update userinfo with the generated userID and addressID

    
    $postsQuery = "INSERT INTO Posts (userID, cityID, provinceID, content, dateTime, privacy, isDeleted, attachment) 
                   VALUES ('$userID', '$cityID', '$provinceID', '$content', '$dateTime', '$privacy', '$isDeleted', '$attachment')";

    if (executeQuery($postsQuery)) {
        header('Location: index.php');
        exit();
    }
}

// Query to fetch data for display
$query = "SELECT Posts.*, userinfo.firstname, userinfo.lastname, 
                 cities.cityName, provinces.provinceName 
          FROM Posts
          LEFT JOIN users ON Posts.userID = users.userID
          LEFT JOIN userinfo ON users.userInfoID = userinfo.userInfoID
          LEFT JOIN addresses ON userinfo.addressID = addresses.addressID
          LEFT JOIN cities ON addresses.cityID = cities.cityID 
          LEFT JOIN provinces ON addresses.provinceID = provinces.provinceID";

$result = executeQuery($query);
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images\icon2.png">
    <style>
        body {
            background-color: #F0EAD6;
        }
        .navbar {
            background-color: #4B4B4D;
            width: 100%;
            top: 0;
        }

        .navbarHeader {
            color: white;
            font-size: 24px;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .navbarIcon {
            width: 30px;
            height: 30px;
            margin-right: 8px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #F9E6D7;
            transition: transform 0.2s, box-shadow 0.2s;
            max-width: 800px;
            margin: 0 auto;
            margin-top: 30px;
        }
        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            margin-top: 30px;
            color: #1A1A2E;
        }
        .formLabel {
            color: #1A1A2E;
        }
        .btn-primary {
            background-color: #FF6F61;
            border: none;
        }
        .btn-primary:hover {
            background-color: #FF4F4F;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #1A1A2E;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<nav class="navbar">
  <div class="container-fluid">
    <a href="index.php" class="navbarHeader">
      <img src="images/pic.png" alt="Icon" class="navbarIcon">
      InstaStatus
    </a>
  </div>
</nav>

<div class="container mt-5">
    <h2>Create a New Post</h2>
    <div class="card p-4 shadow-sm">
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="firstName" class="formLabel">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter First Name" required>
            </div>
            <div class="mb-3">
                <label for="lastName" class="formLabel">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter Last Name" required>
            </div>
            <div class="mb-3">
                <label for="cityName" class="formLabel">City</label>
                <input type="text" class="form-control" id="cityName" name="cityName" placeholder="Enter City Name" required>
            </div>
            <div class="mb-3">
                <label for="provinceName" class="formLabel">Province</label>
                <input type="text" class="form-control" id="provinceName" name="provinceName" placeholder="Enter Province Name" required>
            </div>
            <div class="mb-3">
                <label for="content" class="formLabel">Content</label>
                <textarea class="form-control" id="content" name="content" rows="4" placeholder="Write your content here" required></textarea>
            </div>
            <div class="mb-3">
                <label for="dateTime" class="formLabel">Date and Time</label>
                <input type="datetime-local" class="form-control" id="dateTime" name="dateTime" required>
            </div>
            <div class="mb-3">
                <label for="privacy" class="formLabel">Privacy</label>
                <select class="form-select" id="privacy" name="privacy" required>
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="isDeleted" class="formLabel">Is Deleted</label>
                <select class="form-select" id="isDeleted" name="isDeleted" required>
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="attachment" class="formLabel">Attachment</label>
                <input type="file" class="form-control" id="attachment" name="attachment">
            </div>
            <button type="submit" class="btn btn-primary w-100" name="btnSubmitPost">Create Post</button>
        </form>
    </div>
</div>

<div class="footer">
    &copy; 2024 Ayisha | All Rights Reserved
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
