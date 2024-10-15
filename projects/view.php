<?php 
session_start();
include("../php/db.php");

// Check if the user is logged in
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
    // Add comment
    $comment_text = $_POST['comment'];

    // Validate comment input
    if (empty($comment_text)) {
        $error_message = 'Comment cannot be empty.';
    } else {
        // Insert comment into the database
        $stmt = $conn->prepare("INSERT INTO project_comments (project_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $project_id, $user_id, $comment_text);

        if ($stmt->execute()) {
            header("Location: view.php?id=" . $project_id); // Redirect to the same page to refresh comments
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    }
}

// Fetch comments for the project
$stmt = $conn->prepare("SELECT c.comment, c.created_at, u.name FROM project_comments c JOIN user u ON c.user_id = u.id WHERE c.project_id = ? ORDER BY c.created_at DESC");
$stmt->bind_param("i", $project_id);
$stmt->execute();
$comments = $stmt->get_result();
?>

<?php include("../php/head.php") ?>
<?php include('../php/nav.php') ?>

<div class="container mt-5">
    <h1 class="text-center mb-4"><?= htmlspecialchars($project['subject']) ?></h1>
    <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($project['abstract'])) ?></p>

    <h2 class="mt-4">Comments</h2>
    <?php if ($comments->num_rows > 0): ?>
    <ul class="list-group mb-4">
        <?php while ($comment = $comments->fetch_assoc()): ?>
        <li class="list-group-item">
            <strong><?= htmlspecialchars($comment['name']) ?>:</strong>
            <?= nl2br(htmlspecialchars($comment['comment'])) ?>
            <br>
            <small class="text-muted"><?= htmlspecialchars($comment['created_at']) ?></small>
        </li>
        <?php endwhile; ?>
    </ul>
    <?php else: ?>
    <p>No comments yet. Be the first to comment!</p>
    <?php endif; ?>

    <h3 class="mt-4">Add a Comment</h3>
    <?php if ($error_message): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label for="comment">Your Comment</label>
            <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Comment</button>
    </form>
</div>

<?php include("../php/scripts.php") ?>
<?php include("../php/footer.php") ?>