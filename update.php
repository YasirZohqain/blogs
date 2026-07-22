<?php
// Database connection ki file ko is page par include (shamil) kiya hai taaki SQL queries chalayi ja sakein
include 'connection/config.php';

// Check kar rahe hain ki kya user ne edit form ko mukammal karke "submit_update" (Update) button dabaya hai
if (isset($_POST['submit_update'])) {
    
    // Form se aane wali makhsoos ID ko pakad kar variable mein rakha taaki pata chale kis record ko badalna hai
    $ID = $_POST['ID'];
    
    // User ne jo naya Title input kiya hai, use form se nikal kar $TITLE variable mein save kiya
    $TITLE = $_POST['title'];
    
    // User ne jo naya Content input kiya hai, use form se nikal kar $CONTENT variable mein save kiya
    $CONTENT = $_POST['content'];
    
    // Shuru mein naye image ke naam ko khali (null) rakha hai, jab tak yeh confirm na ho jaye ki user nayi image upload kar raha hai
    $image_final = null;

    // Check kar rahe hain ki kya user ne sach mein koi nayi image select ki hai (agar temporary location khali nahi hai)
    if (!empty($_FILES['image']['tmp_name'])) {
        
        // Nayi image jahan temporary save hui hai, us temporary path/location ko variable mein save kiya
        $img_location = $_FILES['image']['tmp_name'];
        
        // Nayi image ka jo asli original naam hai, use $_FILES array se nikal kar save kiya
        $img_original_name = $_FILES['image']['name'];
        
        // Image ke naam se uski extension (jaise PNG, JPG) alag ki aur strtolower se use chote lafzon (lowercase) mein badla
        $ext = strtolower(pathinfo($img_original_name, PATHINFO_EXTENSION));

        // Check kiya ja raha hai ki extension kya hai, taaki us hisab se PHP memory mein photo ko read karne ka source banaya jaye
        if ($ext == 'jpg' || $ext == 'jpeg') {
            // Agar file JPG/JPEG hai, toh use compress karne ke liye imagecreatefromjpeg ka object/resource banaya
            $source_img = imagecreatefromjpeg($img_location);
        } elseif ($ext == 'png') {
            // Agar file PNG hai, toh use compress karne ke liye imagecreatefrompng ka object/resource banaya
            $source_img = imagecreatefrompng($img_location);
        } elseif ($ext == 'webp') {
            // Agar file pehle se hi WEBP hai, toh use read karne ke liye imagecreatefromwebp ka object/resource banaya
            $source_img = imagecreatefromwebp($img_location);
        } else {
            // Agar file in charo formats ke alawa koi gair-bhauni ya khatarnak file (jaise .php script) hai, toh script ko yahin rok do
            die('Error: Only JPG, JPEG, PNG, and WEBP images are allowed.');
        }

        // Nayi image ke liye ek brand-new unique naam paida kiya aur aakhri mein extension '.webp' fix kar di
        $image_final = uniqid('story_', true) . '.webp';
        
        // Assets folder ke andar upload folder ka pura rasta naye unique naam ke sath jor kar tayar kiya
        $target_path = 'assets/upload/' . $image_final;

        // Imagewebp function ke zariye image ko 65% quality par compress kiya aur permanent folder ($target_path) mein save kar diya
        if (!imagewebp($source_img, $target_path, 65)) {
            // Agar kisi wajah se image compression fail ho jati hai, toh error message dikhao aur code ko yahin rok do
            die('Error: Failed to compress and upload image file.');
        }

        // Image kamyabi se save hone ke baad, server ki RAM memory ko saaf karne ke liye temporary image object ko mita (delete) diya
        imagedestroy($source_img);
    }

    // Agar $image_final variable ab null nahi rha, iska matlab user ne sach mein nayi image upload ki hai
    if ($image_final !== null) {
        
        // Title, Content aur Image Teeno ko update karne ki SQL query tayar ki
        $query = "UPDATE `story` SET `title` = ?, `content` = ?, `image` = ? WHERE `ID` = ?";
        
        // Prepared statement ka use karte hue query ko database connection ($conn) ke sath prepare kiya
        $stmt = mysqli_prepare($conn, $query);
        
        // Charo parameters ko sequence ke sath placeholders (?) ki jagah bind kiya ('sssi' ka matlab 3 Strings aur 1 Integer ID)
        mysqli_stmt_bind_param($stmt, 'sssi', $TITLE, $CONTENT, $image_final, $ID);
    } else {
        // Agar $image_final abhi bhi null hai, iska matlab user ne image nahi badli, isliye sirf Title aur Content badalny ki query bani
        $query = "UPDATE `story` SET `title` = ?, `content` = ? WHERE `ID` = ?";
        
        // Query ko database connection ke sath secure tarike se prepare kiya
        $stmt = mysqli_prepare($conn, $query);
        
        // Teeno parameters ko order ke mutabiq bind kiya ('ssi' ka matlab 2 Strings aur 1 Integer ID)
        mysqli_stmt_bind_param($stmt, 'ssi', $TITLE, $CONTENT, $ID);
    }

    // Upar tayar ki gayi final update query ko database ke andar execute (run) kiya
    if (mysqli_stmt_execute($stmt)) {
        
        // Agar database mein data successfully badal gaya, toh user ko automatic main page (index.php) par bhej (redirect) do
        header('Location: index.php');
        
        // Code ki execution ko yahin mukammal khatam kar diya taaki aage ka koi fuzool code backend par run na ho
        exit;
    } else {
        // Agar database mein data update karte waqt koi galti ya syntax error aaya, toh yeh error print karo
        echo 'Update failed.';
    }
}

// Check kar rahe hain ki kya user ne pehle wale page (main page) par kisi post ke "Edit" button par click kiya hai
if (isset($_POST['edit'])) {
    
    // Jis makhsoos post ko edit karna hai, uski ID ko $_POST ke zariye pakad kar variable mein rakha
    $ID = $_POST['ID'];
    
    // Database se us makhsoos ID ka poora purana data nikalne ke liye SELECT query tayar ki
    $dataFetch = "SELECT * FROM `story` WHERE `ID` = ?";
    
    // SQL Injection se bachne ke liye query ko secure tarike se prepare kiya
    $stmt = mysqli_prepare($conn, $dataFetch);
    
    // Placeholder '?' ki jagah user se aane wali integer ID ko safely bind kiya
    mysqli_stmt_bind_param($stmt, 'i', $ID);
    
    // Query ko database par final execute (run) kiya taaki data fetch ho sake
    mysqli_stmt_execute($stmt);
    
    // Database se aane wale pure data ka result set (object) nikal kar $result mein save kiya
    $result = mysqli_stmt_get_result($stmt);
    
    // Us data ko ek aasan associative array ($row) mein convert kiya taaki hum ise HTML form ke andar print kar sakein
    $row = mysqli_fetch_assoc($result);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Blog Post</title>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const imageInput = document.getElementById('image');
            const preview = document.getElementById('previewImage');
            const oldImageSrc = preview.src;

            if (imageInput && preview) {
                imageInput.addEventListener('change', function () {
                    const file = this.files && this.files[0];

                    if (!file) {
                        preview.src = oldImageSrc;
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>
</head>
<body>
    <?php if (isset($row)) { ?>
        <form action="update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="ID" value="<?php echo $row['ID']; ?>">

            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($row['title']); ?>"><br><br>

            <label for="content">Content:</label>
            <textarea id="content" name="content"><?php echo htmlspecialchars($row['content']); ?></textarea><br><br>

            <label>Current Image:</label><br>
            <img id="previewImage" src="assets/upload/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" style="max-width: 250px; margin-bottom: 10px;"><br>

            <label for="image">Choose New Image:</label>
            <input type="file" id="image" name="image" accept="image/*"><br><br>

            <button type="submit" name="submit_update">Save Changes</button>
        </form>
    <?php } else { ?>
        <p>No post selected for update.</p>
    <?php } ?>
</body>
</html>
