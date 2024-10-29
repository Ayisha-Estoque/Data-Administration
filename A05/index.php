<?php
include('connect.php');

// CHECK IF THE BUTTON WAS CLICKED TO CREATE A POST
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

    // Insert user info into userinfo table
    $userinfoQuery = "INSERT INTO userinfo (userInfoID, userID, addressID, firstname, lastname, birthday) 
                    VALUES ('$userInfoID', '$userID', '$addressID','$firstname', '$lastname', '$birthday')";
    executeQuery($userinfoQuery);
    $userInfoID = mysqli_insert_id($conn);

    // Insert city
    $citiesQuery = "INSERT INTO cities (cityID, cityName) 
                    VALUES ('$cityID', '$cityName') ON DUPLICATE KEY UPDATE cityID=LAST_INSERT_ID(cityID)";
    executeQuery($citiesQuery);
    $cityID = mysqli_insert_id($conn);

    // Insert province
    $provincesQuery = "INSERT INTO provinces (provinceID, provinceName) 
                        VALUES ('$provinceID', '$provinceName') ON DUPLICATE KEY UPDATE provinceID=LAST_INSERT_ID(provinceID)";
    executeQuery($provincesQuery);
    $provinceID = mysqli_insert_id($conn);
    
    // Insert address to link user to city and province
    $addressQuery = "INSERT INTO addresses (userInfoID, cityID, provinceID) 
                        VALUES ('$userInfoID', '$cityID', '$provinceID')";
    executeQuery($addressQuery);
    
    // Insert post into Posts table
    $postsQuery = "INSERT INTO Posts (postID, userID, content, dateTime, privacy, isDeleted, attachment) 
                   VALUES ('$postID', '$userID', '$content', '$dateTime', '$privacy', '$isDeleted', '$attachment')";
    executeQuery($postsQuery);
}


// Fetch posts with all necessary information
$query = "SELECT Posts.*, userinfo.firstname, userinfo.lastname, 
                 cities.cityName, provinces.provinceName 
          FROM Posts 
          LEFT JOIN userinfo ON Posts.userID = userinfo.userID 
          LEFT JOIN addresses ON userinfo.userInfoID = addresses.userInfoID 
          LEFT JOIN cities ON addresses.cityID = cities.cityID 
          LEFT JOIN provinces ON addresses.provinceID = provinces.provinceID";

$result = executeQuery($query);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Post Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            background-color: #F0EAD6;
        }
        .navbar{
            background-color: #4B4B4D;
            width: 100%;
            top: 0;
        }
        .navbarHeader{
            color: white;
            font-size: 24px;
            text-decoration: none;
        }
        .container{
            font-family: 'Roboto';
        }
        .small-date {
            font-size: 0.8rem;
            font-weight: 300;
        }
        .privacy{
            font-size: 0.8rem;
            font-weight: 300; 
        }
        .address{
            font-size: 13px;
            font-weight: 400; 
        }
        .content{
            text-align: center;
            font-size: 17px;
            font-family: 'Poppins';
        }
        .card {
            border-radius: 10px;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            max-width: 1200px;
            width: 100%;
            background-color: #F9E6D7;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); 
        }
        .footer {
            text-align: center;
            width: 100%;
            font-size: 14px;
            color: #1A1A2E;
            margin-top: 20px;
        }
        
    </style>
</head>

<body>
<nav class="navbar">
  <div class="container-fluid">
    <span class="navbarHeader">InstaStatus</span>
  </div>
</nav>

<div class="container">
    <h1 class="header mt-5">Feed</h1>
    <div class="row">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($post = mysqli_fetch_assoc($result)) {
                ?>
                <div class="col-12">
                    <div class="card my-3">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo htmlspecialchars($post['firstname'] . ' ' . $post['lastname']); ?>
                                <span class="small-date"> · <?php echo htmlspecialchars($post['dateTime']); ?></span>
                                <span class="privacy"> · <?php echo htmlspecialchars($post['privacy']); ?></span>
                            </h5>
                            <p class="address"><?php echo htmlspecialchars($post['cityName'] . ', ' . $post['provinceName']); ?></p>
                            <p class="content"><?php echo htmlspecialchars($post['content']); ?></p>
                            <form method="post">
                                <input type="hidden" value="<?php echo $post['postID']; ?>" name="id">
                            </form>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No posts found.</p>";
        }
        ?>
    </div>
</div>

<div class="footer">
        &copy; 2024 Ayisha | All Rights Reserved
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>

