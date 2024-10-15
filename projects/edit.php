<?php
session_start();
include("../php/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$project_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM project WHERE id = ? AND owner_id = ?");
$stmt->bind_param("ii", $project_id, $user_id);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();

// Error message initialization
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST['subject'];
    $abstract = $_POST['abstract'];
    $visible = isset($_POST['visible']) ? 1 : 0;
    $allow_comments = isset($_POST['allow_comments']) ? 1 : 0;

    // Validate the abstract length
    if (strlen($abstract) < 50) {
        $error_message = 'The abstract must be at least 50 characters long.';
    } else {
        $stmt = $conn->prepare("UPDATE project SET subject = ?, abstract = ?, visible = ?, allow_comments = ? WHERE id = ? AND owner_id = ?");
        $stmt->bind_param("ssiiii", $subject, $abstract, $visible, $allow_comments, $project_id, $user_id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit(); // Make sure to exit after redirect
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    }
}
?>

<?php include("../php/head.php") ?>
<?php include('../php/nav.php') ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Edit Project</h1>
    <?php if ($error_message): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label for="subject">Project Subject</label>
            <input type="text" name="subject" id="subject" class="form-control"
                value="<?= htmlspecialchars($project['subject']) ?>" required>
        </div>
        <div class="form-group">
            <label for="abstract">Project Abstract (50-800 words)</label>
            <textarea name="abstract" id="abstract" class="form-control" rows="5"
                required><?= htmlspecialchars($project['abstract']) ?></textarea>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" name="visible" id="visible" class="form-check-input"
                <?= $project['visible'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="visible">Make project visible</label>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" name="allow_comments" id="allow_comments" class="form-check-input"
                <?= $project['allow_comments'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="allow_comments">Allow comments</label>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Update Project</button>
    </form>
</div>

<?php include("../php/scripts.php") ?>
<?php include("../php/footer.php") ?>