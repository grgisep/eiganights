<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "cinema-userr";

$conn = new mysqli($servername, $username, $password, $dbname);

$user_id = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST['username'];
    $new_avatar = $_POST['avatar'];

    $sql = "UPDATE user SET username = ?, avatar = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $new_username, $new_avatar, $user_id);

    if ($stmt->execute()) {
        header("Location: qq.php");
        exit;
    } else {
        echo "Update failed:" . $conn->error;
    }
}
?>
