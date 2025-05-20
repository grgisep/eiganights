<?php
session_start(); // 加这一行

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cinema-userr";

$conn = new mysqli($servername, $username, $password, $dbname);

// ✔️ 使用登录用户的 ID（确保登录系统设置了 $_SESSION['user_id']）
$user_id = $_SESSION['user_id'] ?? 1; // 测试阶段可以默认是 1

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST['username'];
    $new_avatar = $_POST['avatar'];

    $sql = "UPDATE user SET username = ?, avatar = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $new_username, $new_avatar, $user_id);

    if ($stmt->execute()) {
        // ✔️ 可选：把新数据保存到 session 中
        $_SESSION['username'] = $new_username;
        $_SESSION['avatar'] = $new_avatar;

        header("Location: profile.php"); // 改成你想返回的页面
        exit;
    } else {
        echo "Update failed: " . $conn->error;
    }
}
?>
