<?php
session_start();
include("php/db.php");


if (isset($_SESSION["user"])) {
   header("Location: index.php");
}

?>
<!doctype html>
<html lang="en">
<?php include("php/head.php") ?>

<head>

    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include('php/nav.php')?>
    <div class="container">
        <?php
        if (isset($_POST["login"])) {
    
           $email = $_POST["email"];
           $password = $_POST["password"];
            $sql = "SELECT * FROM user WHERE email = '$email'";
            $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($user) {
                if (password_verify($password, $user["password"])) {
                    session_start();
                    $_SESSION["user"] = "yes";
                    $_SESSION['user_id'] = $user["id"];
                    header("Location: index.php");
                    
                }else{
                    echo "<div class='alert alert-danger'>Password does not match</div>";
                }
            }else{
                echo "<div class='alert alert-danger'>Email does not match</div>";
            }
        }
        ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" placeholder="Enter Email:" name="email" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter Password:" name="password" class="form-control">
            </div>
            <div class="form-btn">
                <input type="submit" value="Login" name="login" class="btn btn-primary">
            </div>
        </form>

    </div>
    <?php include("php/scripts.php")?>
</body>

</html>