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
    
</body>
</html>