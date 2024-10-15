<?php 
session_start();
include '../php/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error_message = ''; // Initialize an empty error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $abstract = isset($_POST['abstract']) ? $_POST['abstract'] : '';
    $visible = isset($_POST['visible']) ? 1 : 0;
    $allow_comments = isset($_POST['allow_comments']) ? 1 : 0;
    $owner_id = $_SESSION['user_id'];

    // Validate the abstract length
    if (strlen($abstract) < 50) {
        $error_message = 'The abstract must be at least 50 characters long.';
    } else {
        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO project (subject, abstract, owner_id, visible, allow_comments) VALUES (?, ?, ?, ?, ?)");
        // Bind parameters
        $stmt->bind_param("ssiii", $subject, $abstract, $owner_id, $visible, $allow_comments);
        
        try {
            if ($stmt->execute()) {
                header("Location: index.php");
                exit(); // Make sure to exit after redirect
            } else {
                $error_message = "Error: " . $stmt->error;
            }
        } catch (\Exception $ex) {
            $error_message = "Error: " . $ex->getMessage();
        }
    }
}
?>

<?php include("../php/head.php") ?>
<?php include('../php/nav.php') ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Create New Project</h1>
    <form method="POST">
        <div class="form-group">
            <label for="subject">Project Subject</label>
            <input type="text" name="subject" id="subject" class="form-control" placeholder="Project Subject" required>
        </div>
        <div class="form-group">
            <label for="abstract">Project Abstract (50-800 words)</label>
            <textarea name="abstract" id="abstract" class="form-control" placeholder="Project Abstract (50-800 words)"
                rows="5" required></textarea>
            <?php if ($error_message): ?>
            <small class="text-danger"><?= htmlspecialchars($error_message) ?></small>
            <?php endif; ?>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" name="visible" id="visible" class="form-check-input" checked>
            <label class="form-check-label" for="visible">Make project visible</label>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" name="allow_comments" id="allow_comments" class="form-check-input" checked>
            <label class="form-check-label" for="allow_comments">Allow comments</label>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Create Project</button>
    </form>
</div>

<?php include("../php/scripts.php") ?>
<?php include("../php/footer.php") ?>