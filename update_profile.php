<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cinema-userr";

$conn = new mysqli($servername, $username, $password, $dbname);

// 用户 ID（已登录）
$user_id = $_SESSION['user_id'] ?? 1;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_username = $_POST['username'];
    $avatar_path = null;

    // 处理头像上传
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); // 创建 uploads 目录
        }

        $tmp_name = $_FILES['avatar']['tmp_name'];
        $original_name = basename($_FILES['avatar']['name']);
        $extension = pathinfo($original_name, PATHINFO_EXTENSION);

        // 只允许图片格式
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array(strtolower($extension), $allowed_extensions)) {
            die("❌ 仅允许上传图片文件（jpg/png/gif/webp）");
        }

        // 新文件名
        $new_filename = 'avatar_user_' . $user_id . '.' . $extension;
        $save_path = $upload_dir . $new_filename;
        $avatar_path = '/eiganights/uploads/' . $new_filename;

        // 移动上传的文件
        if (!move_uploaded_file($tmp_name, $save_path)) {
            die("❌ 头像上传失败");
        }
    }

    // 更新数据库
    if ($avatar_path) {
        $sql = "UPDATE user SET username = ?, avatar = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $new_username, $avatar_path, $user_id);
    } else {
        $sql = "UPDATE user SET username = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_username, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['username'] = $new_username;
        if ($avatar_path) {
            $_SESSION['avatar'] = $avatar_path;
        }
        header("Location: profilepage.php");
        exit();
    } else {
        echo "❌ 更新失败：" . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
