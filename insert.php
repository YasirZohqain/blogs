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

    //echo "<b>Temporary Location:</b> " . $img_location . "<br>";

    // Check karein agar user ne image select hi nahi ki
    if (empty($img_location)) {
        die("Error: Please select an image first.");
    }
   

    // 2. get the file extension of the uploaded image and convert it to lowercase
    $ext = strtolower(pathinfo($img_original_name, PATHINFO_EXTENSION));


     // 3. Image ko check karne ke liye PHP ki memory mein source banaya
    // Yeh original image ka data read karega taaki hum use compress kar sakein
    if ($ext == 'jpg' || $ext == 'jpeg') {
        $source_img = imagecreatefromjpeg($img_location);
    } elseif ($ext == 'png') {
        $source_img = imagecreatefrompng($img_location);
    } elseif ($ext == 'webp') {
        $source_img = imagecreatefromwebp($img_location);
    } else {
        // Agar image format in teeno ke alawa kuch aur hai (ya virus hai), toh yahin block kar do
        die("Error: Only JPG, JPEG, PNG, and WEBP images are allowed.");
    }

    // 4. Naya bilkul unique naam banaya, aur extension lagayi '.webp' (Modern & lightweight format)
    $image_final = uniqid('story_', true) . '.webp';

     // Target folder ka rasta (path) tayar kiya
         $target_path = 'assets/upload/' . $image_final;


    // 5. COMPRESSION LAYER: Yeh image ka size chota karke folder mein save karega
    // Parameters: ($source_image, $target_path, $quality)
    // Quality ko 60-70 rakhna sabse best hota hai (Size 80% tak chota ho jata hai, quality wahi rehti hai)
    if (imagewebp($source_img, $target_path, 65)) {
        
        // Memory saaf karne ke liye temporary  object ko delete kiya (Server performance ke liye zaroori hai)
        imagedestroy($source_img);

        // 6. Database query tayar ki aur chalayi
        $query = "INSERT INTO `story`(`title`, `content`, `image`) VALUES ('$TITLE','$CONTENT','$image_final')";
        mysqli_query($conn, $query);
        
        echo "Story published and Image Compressed Successfully!";
        header('Location: index.php');
    } 
    else {
        echo "Error: Failed to compress and upload image file.";
    }
 

}





?>



