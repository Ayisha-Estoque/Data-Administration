<?php
include('connect.php');

if (isset($_GET['postID'])) {
    $postID = $_GET['postID'];
}

if (isset($_POST['btnEdit'])) {
    $content = $_POST['content'];

    $editQuery = "UPDATE Posts SET content = '$content', isEdited = TRUE WHERE postID = '$postID'";
    executeQuery($editQuery);

    header('Location: ./');
    exit();
}

$query = "SELECT * FROM Posts WHERE postID = '$postID'";
$result = executeQuery($query);

$post = mysqli_fetch_assoc($result);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InstaStatus | Edit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/icon2.png">
</head>

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

    .formLabel {
        margin-bottom: 10px;
        font-size: 20px;
        margin-top: 10px;
    }

    .footer {
        text-align: center;
        font-size: 14px;
        color: #1A1A2E;
        margin-top: 20px;
    }
</style>

<body>

    <nav class="navbar">
        <div class="container-fluid">
            <a href="index.php" class="navbarHeader">
                <img src="images/pic.png" alt="Icon" class="navbarIcon">
                InstaStatus
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="row mt-5">
            <div class="col">
                <div class="card shadow rounded-5 p-5">
                    <div class="h2 text-center">
                        Edit Post
                    </div>
                    <form method="post">
                        <label for="content" class="formLabel">Content:</label>
                        <textarea class="form-control" id="content" name="content" rows="4"
                            required><?php echo $post['content']; ?></textarea>
                        <label for="dateTime" class="formLabel">Date and Time:</label>
                        <input type="datetime-local" class="form-control" id="dateTime" name="dateTime" required>
                        <button class="mt-5 btn btn-primary" type="submit" name="btnEdit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        &copy; 2024 Ayisha | All Rights Reserved
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>