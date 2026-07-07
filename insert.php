<?php

include 'connection/config.php';

if (isset($_POST['submit'])) {

    //  Retrieve the title and content from the submitted form data using the $_POST superglobal
    $TITLE = $_POST['title'];
    $CONTENT = $_POST['content'];

   // print_r($_FILES['image']);

    // 1. Get the image file details from the $_FILES superglobal
    $img_location = $_FILES['image']['tmp_name'];
    $img_original_name = $_FILES['image']['name'];
    


    // 2. get the file extension of the uploaded image and convert it to lowercase
    $ext = strtolower(pathinfo($img_original_name, PATHINFO_EXTENSION));


    // 3. Define an array of allowed file extensions for image uploads
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];

    // 4. Check if the uploaded file's extension is in the allowed list
    if (!in_array($ext, $allowed_extensions)) {
        die("Error: Only JPG, JPEG, PNG, and WEBP images are allowed.");
    }

    // 5. Generate a unique filename for the uploaded image to avoid overwriting existing files
    $image_final = uniqid('', true) . '.' . $ext;


    // 6. Move the uploaded image from its temporary location to the desired directory on the server
     if (move_uploaded_file($img_location, 'assets/upload/'.$image_final)) {

        // 7. Prepare the SQL query to insert the story details into the database, including the title, content, and image filename
          $query = "INSERT INTO `story`(`title`, `content`, `image`) VALUES ('$TITLE','$CONTENT','$image_final')";

         // 8. Execute the SQL query using the mysqli_query function, passing the database connection and the query string
         mysqli_query($conn, $query);
         // 9. Check if the query execution was successful and provide feedback to the user
        echo "Story published successfully!";
    } else {
        // 10. If the image upload fails, display an error message to the user
        echo "Error: Failed to upload image file.";
    }
 

}





?>