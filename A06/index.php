<?php
include('connect.php');

if (isset($_POST['btnDelete'])) {
    $postID = $_POST['id'];

    // Delete from addresses table
    $deleteAddressQuery = "
        DELETE addresses 
        FROM addresses 
        INNER JOIN userinfo ON addresses.userInfoID = userinfo.userInfoID
        INNER JOIN Posts ON userinfo.userID = Posts.userID
        WHERE Posts.postID = '$postID'";
    mysqli_query($conn, $deleteAddressQuery);

    // Delete from userinfo table
    $deleteUserInfoQuery = "
        DELETE userinfo 
        FROM userinfo
        INNER JOIN Posts ON userinfo.userID = Posts.userID
        WHERE Posts.postID = '$postID'";
    mysqli_query($conn, $deleteUserInfoQuery);

    // Delete from users table
    $deleteUsersQuery = "
        DELETE users 
        FROM users
        INNER JOIN Posts ON users.userID = Posts.userID
        WHERE Posts.postID = '$postID'";
    mysqli_query($conn, $deleteUsersQuery);

    // Delete from Posts table
    $deletePostQuery = "DELETE FROM Posts WHERE postID = '$postID'";
    mysqli_query($conn, $deletePostQuery);

    // Delete unused cities
    $deleteCityQuery = "
        DELETE FROM cities 
        WHERE cityID NOT IN (SELECT DISTINCT cityID FROM addresses)";
    mysqli_query($conn, $deleteCityQuery);

    // Delete unused provinces
    $deleteProvinceQuery = "
        DELETE FROM provinces 
        WHERE provinceID NOT IN (SELECT DISTINCT provinceID FROM addresses)";
    mysqli_query($conn, $deleteProvinceQuery);

    echo "<script>alert('Post deleted successfully.');</script>";
}

// Fetch posts with all necessary information
$query = "SELECT Posts.*, userinfo.firstname, userinfo.lastname, 
                 cities.cityName, provinces.provinceName 
          FROM Posts 
          LEFT JOIN userinfo ON Posts.userID = userinfo.userID 
          LEFT JOIN addresses ON userinfo.userInfoID = addresses.userInfoID 
          LEFT JOIN cities ON addresses.cityID = cities.cityID 
          LEFT JOIN provinces ON addresses.provinceID = provinces.provinceID";
$result = mysqli_query($conn, $query);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Post Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
            display: flex;
            align-items: center;
        }

        .navbarIcon {
            width: 30px;
            height: 30px;
            margin-right: 8px;
        }

        .container {
            font-family: 'Roboto';
        }

        .dateTime {
            font-size: 0.8rem;
            font-weight: 300;
        }

        .privacy {
            font-size: 0.8rem;
            font-weight: 300;
        }

        .address {
            font-size: 13px;
            font-weight: 400;
        }

        .content {
            text-align: center;
            font-size: 17px;
            font-family: 'Poppins';
            margin-bottom: 20px;
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

        .cardBody {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cardTitle{
            margin-top: 20px;
        }

        .postContent {
            flex-grow: 1;
            margin-right: 20px;
        }

        .deleteButton {
            flex-shrink: 0;
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
            <span class="navbarHeader">
                <img src="images/pic.png" alt="Icon" class="navbarIcon">
                InstaStatus
            </span>
        </div>
    </nav>

    <div class="container">
        <h1 class="header mt-5">Feed</h1>
        <div class="row">
            <div class="d-flex justify-content-end mb-3">
                <a href="post.php" class="btn btn-primary" style="background-color: #FF6F61; border: none;">
                    + Create Post
                </a>
            </div>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($post = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="card my-3">
                        <div class="cardBody">
                            <div class="postContent">
                                <h5 class="cardTitle">
                                    <?php echo htmlspecialchars($post['firstname'] . ' ' . $post['lastname']); ?>
                                    <span class="dateTime"> · <?php echo htmlspecialchars($post['dateTime']); ?></span>
                                    <span class="privacy"> · <?php echo htmlspecialchars($post['privacy']); ?></span>
                                </h5>
                                <p class="address">
                                    <?php echo htmlspecialchars($post['cityName'] . ', ' . $post['provinceName']); ?>
                                </p>
                                <p class="content"><?php echo htmlspecialchars($post['content']); ?></p>
                            </div>
                            <div class="deleteButton">
                                <form method="post">
                                    <input type="hidden" value="<?php echo $post['postID']; ?>" name="id">
                                    <button class="btn btn-danger" name="btnDelete">Delete</button>
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