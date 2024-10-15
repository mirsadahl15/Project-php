<?php   
session_start();

include("../php/db.php");

if (!isset($_SESSION["user"])) {
    header("Location: /projects-platform/login.php");
}

// Fetch all visible projects
$projects = $conn->query("SELECT project.*, user.name, user.surname FROM project JOIN user ON project.owner_id = user.id WHERE project.visible = 1");

// Insert comment handling
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_comment') {
    $project_id = $_POST['project_id'];
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO project_comments (project_id, user_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $project_id, $user_id, $comment);
    $stmt->execute();
}

// Update comment handling
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'edit_comment') {
    $comment_id = $_POST['comment_id'];
    $updated_comment = $_POST['updated_comment'];

    $stmt = $conn->prepare("UPDATE project_comments SET comment = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $updated_comment, $comment_id, $_SESSION['user_id']);
    $stmt->execute();
}

// Fetch comments for each project
$comment_stmt = $conn->prepare("SELECT pc.*, u.name, u.surname FROM project_comments pc JOIN user u ON pc.user_id = u.id WHERE pc.project_id = ? ORDER BY pc.created_at DESC");
?>

<?php include("../php/head.php") ?>
<?php include("../php/nav.php") ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Projects
        <a href="new.php" class="btn btn-success float-right">Add New Project</a>
    </h1>

    <?php while ($project = $projects->fetch_assoc()): ?>
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title"><?= htmlspecialchars($project['subject']) ?> by
                <?= htmlspecialchars($project['name'] . ' ' . $project['surname']) ?></h2>
            <p class="card-text"><?= htmlspecialchars($project['abstract']) ?></p>

            <div class="mt-3">
                <?php 
                // Check if the current user is the owner of the project
                if ($_SESSION['user_id'] == $project['owner_id']): ?>
                <a href="edit.php?id=<?= $project['id'] ?>" class="btn btn-warning">Edit Project</a>
                <a href="delete.php?id=<?= $project['id'] ?>" class="btn btn-danger">Delete Project</a>
                <?php endif; ?>
            </div>

            <!-- Comment Form -->
            <h3 class="mt-4">Comments</h3>
            <form method="POST" class="mb-3">
                <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                <input type="hidden" name="action" value="add_comment">
                <div class="form-group">
                    <textarea name="comment" class="form-control" placeholder="Add a comment..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Comment</button>
            </form>

            <!-- Fetch and display comments for this project -->
            <?php 
            $comment_stmt->bind_param("i", $project['id']);
            $comment_stmt->execute();
            $result_comments = $comment_stmt->get_result();

            while ($comment = $result_comments->fetch_assoc()): ?>
            <div class="alert alert-light d-flex justify-content-between align-items-center mb-2"
                id="comment-<?= $comment['id'] ?>">
                <div>
                    <strong><?= htmlspecialchars($comment['name'] . ' ' . $comment['surname']) ?>:</strong>
                    <span class="comment-text"><?= htmlspecialchars($comment['comment']) ?></span>
                    <br>
                    <small class="text-muted"><?= htmlspecialchars($comment['created_at']) ?></small>
                </div>

                <?php if ($_SESSION['user_id'] == $comment['user_id']): ?>
                <div>
                    <button class="btn btn-warning btn-sm edit-comment" data-id="<?= $comment['id'] ?>"
                        data-text="<?= htmlspecialchars($comment['comment']) ?>">Edit</button>
                    <a href="delete_comment.php?id=<?= $comment['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                </div>
                <?php endif; ?>
            </div>


            <div class="edit-form" id="edit-form-<?= $comment['id'] ?>" style="display:none;">
                <form method="POST">
                    <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                    <input type="hidden" name="action" value="edit_comment">
                    <div class="form-group">
                        <textarea name="updated_comment" class="form-control"
                            required><?= htmlspecialchars($comment['comment']) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Comment</button>
                    <button type="button" class="btn btn-secondary cancel-edit">Cancel</button>
                </form>
            </div>

            <?php endwhile; ?>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php include("../php/footer.php") ?>

<script>
document.querySelectorAll('.edit-comment').forEach(button => {
    button.addEventListener('click', function() {
        const commentId = this.getAttribute('data-id');
        const commentText = this.getAttribute('data-text');

        document.getElementById('comment-' + commentId).style.display =
            'none'; // Hide the current comment
        document.getElementById('edit-form-' + commentId).style.display = 'block'; // Show the edit form
    });
});

document.querySelectorAll('.cancel-edit').forEach(button => {
    button.addEventListener('click', function() {
        const commentId = button.parentElement.parentElement.id.split('-')[2]
        document.getElementById('comment-' + commentId).style.display = 'block'; // Show the comment
        document.getElementById('edit-form-' + commentId).style.display = 'none'; // Hide the edit form
    });
});
</script>