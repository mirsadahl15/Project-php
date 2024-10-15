<?php
session_start();

include("php/db.php");

if (!isset($_SESSION["user"])) {
header("Location: login.php");
}
?>
<?php include("php/head.php") ?>
<?php include('php/nav.php')?>
<div class="container text-center mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="display-5 mb-5">Welcome!</h1>
                    <a href="logout.php" class="btn btn-warning">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("php/scripts.php")?>
<?php include("php/footer.php") ?>