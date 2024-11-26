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
    executeQuery($deleteAddressQuery);

    // Delete from userinfo table
    $deleteUserInfoQuery = "
        DELETE userinfo 
        FROM userinfo
        INNER JOIN Posts ON userinfo.userID = Posts.userID
        WHERE Posts.postID = '$postID'";
    executeQuery($deleteUserInfoQuery);

    // Delete from users table
    $deleteUsersQuery = "
        DELETE users 
        FROM users
        INNER JOIN Posts ON users.userID = Posts.userID
        WHERE Posts.postID = '$postID'";
    executeQuery($deleteUsersQuery);

    // Delete from Posts table
    $deletePostQuery = "DELETE FROM Posts WHERE postID = '$postID'";
    executeQuery($deletePostQuery);

    // Delete unused cities
    $deleteCityQuery = "
        DELETE FROM cities 
        WHERE cityID NOT IN (SELECT DISTINCT cityID FROM addresses)";
    executeQuery($deleteCityQuery);

    // Delete unused provinces
    $deleteProvinceQuery = "
        DELETE FROM provinces 
        WHERE provinceID NOT IN (SELECT DISTINCT provinceID FROM addresses)";
    executeQuery($deleteProvinceQuery);
}


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
    <title>InstaStatus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/icon2.png">

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

        .cardTitle {
            margin-top: 20px;
        }

        .postContent {
            flex-grow: 1;
            margin-right: 20px;
        }

        .button {
            display: flex;
            flex-direction: column;
            align-items: start;
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
                                    <?php echo $post['firstname'] . ' ' . $post['lastname']; ?>
                                    <span class="dateTime"> · <?php echo $post['dateTime']; ?></span>
                                    <span class="privacy">
                                        · <?php echo $post['privacy']; ?>
                                        <?php if ($post['isEdited']) {
                                            echo " · Edited";
                                        } ?>
                                    </span>
                                </h5>
                                <p class="address">
                                    <?php echo $post['cityName'] . ', ' . $post['provinceName']; ?>
                                </p>
                                <p class="content"><?php echo $post['content']; ?></p>
                            </div>
                            <form method="post" class="button">
                                <input type="hidden" value="<?php echo $post['postID']; ?>" name="id">
                                <a href="edit.php?postID=<?php echo $post['postID']; ?>"
                                    class="btn btn-primary mb-2 w-100">Edit</a>
                                <button class="btn btn-danger w-100" name="btnDelete">Delete</button>
                            </form>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>