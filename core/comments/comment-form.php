<?php

if (isset($_SESSION['user_id'])) {

    echo '
<form action="../comments/make-comment.php" method="post">
    <input type="hidden" name="user_id" value="' . htmlspecialchars($_SESSION['user_id']) . '">
    <input type="hidden" name="post_id" value="' . htmlspecialchars($post['id']) . '">
    <label for="comment">Comment:</label>
    <input type="text" id="comment" name="comment" value="">
    <br>
    <button type="submit">Post</button>
</form>
';

} 
?>

    
