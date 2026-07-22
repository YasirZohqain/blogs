<?php
// Database connection ki file ko is page par shamil (include) kiya hai taaki hum queries chala sakein
include 'connection/config.php';

// Check kar rahe hain ki kya URL ya Form ke zariye 'ID' bheji gayi hai (Request aayi hai ya nahi)
if (isset($_POST['ID'])) {
    
    // Bheji gayi ID ko pakad kar ek variable ($ID) mein save kiya
    $ID = $_POST['ID'];

    // SQL ki DELETE query tayar ki, jahan '?' ek safe placeholder hai taaki koi hacker database tabah na kar sake
    $deleteQuery = "DELETE FROM `story` WHERE `ID` = ?";
    
    // mysqli_prepare se query ko database mein pehle se bheja taaki database iska structure samajh le
    $stmt = mysqli_prepare($conn, $deleteQuery);
    
    // '?' ki jagah asli $ID ko fit kiya ('i' ka matlab hai ki ID sirf ek Integer/Number ho sakti hai)
    mysqli_stmt_bind_param($stmt, 'i', $ID);

    // Is query ko database ke andar final run (execute) kiya takay data delete ho jaye
    if (mysqli_stmt_execute($stmt)) {
        
        // Agar data kamyabi se delete ho gaya, toh user ko wapas 'index.php' (Main Page) par bhej do
        header('Location: index.php');
        
        // Code ki execution ko yahin rok diya taaki aage ka koi fuzool code run na ho
        exit;
    } else {
        // Agar database mein delete karte waqt koi galti ya error aaya, toh yeh message dikhao
        echo 'Delete failed.';
    }
} else {
    // Agar kisi ne bina ID bheje is page ko kholne ki koshish ki, toh yeh error dikhao
    echo 'No ID found in request.';
}
?>
