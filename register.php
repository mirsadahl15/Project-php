<?php  
require_once dirname(__FILE__) . '/php/db.php';
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}

?>
<!doctype html>
<html lang="en">
<?php include("php/head.php") ?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

</head>

<body><?php include('php/nav.php')?>


    <div class="container">

        <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO user (name, surname, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $surname, $email, $password);

    if ($stmt->execute()) {
        header("Location: login.php");  // Redirect to login after successful registration
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
    </div>


    <div class="container">

        <form action="/projects-platform/register.php" method="post">
            <div class="form-group"><input type="text" class="form-control" name="name" placeholder="Name:">
            </div>
            <div class="form-group"><input type="text" class="form-control" name="surname" placeholder="Surname:">
            </div>
            <div class="form-group"><input type="email" class="form-control" name="email" placeholder="Email:">
            </div>
            <div class="form-group"><input type="password" class="form-control" name="password" placeholder="Password:">
            </div>
            <div class="form-btn"><input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>

    </div><?php include("php/scripts.php")?>
</body>

</html>