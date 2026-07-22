<?php

include 'connection/config.php';
$dataFetch = "SELECT * FROM `story`";
$result = mysqli_query($conn, $dataFetch);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
    <link rel="stylesheet" href="assets/style.css">

</head>
<body>


    <form action="insert.php" method="POST" class="form-wrap" enctype="multipart/form-data">
        <h1>Create Blog Post</h1>
        <div class="form-row">
            <div class="form-group half">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" name="content" class="form-control form-textarea"></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" class="form-control">
        </div>
        <div class="form-actions">
            <input type="submit" value="Submit" name="submit" class="btn btn-primary">
        </div>
    </form>



    <div class="blog-list">
        <h2>Blog Posts</h2>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="blog-post">
                <img src="assets/upload/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>" class="blog-image">
                <h3><?php echo $row['title']; ?></h3>
                <p><?php echo $row['content']; ?></p>
                <p><?php echo $row['ID']?></p>

                <form action="delete.php" method="POST" onsubmit="return confirm('Delete this post?');">
                    <input type="hidden" name="ID" value="<?php echo $row['ID']; ?>">
                    <button type="submit" name="delete">Delete</button>
                </form>

                <form action="update.php" method="POST">
                    <input type="hidden" name="ID" value="<?php echo $row['ID']; ?>">
                    <button type="submit" name="edit">Update</button>
                </form>
                    
            </div>
        <?php } ?>

    </div>


    
</body>
</html>