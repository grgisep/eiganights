<?php
// 数据库连接
$conn = new mysqli("localhost", "root", "", "cinema-userr");

// 获取表单传来的 user_id 和 film_id
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
$film_id = isset($_POST['film_id']) ? intval($_POST['film_id']) : 0;

// 检查数据有效性
if ($user_id > 0 && $film_id > 0) {
    // 执行删除操作
    $delete_sql = "DELETE FROM watchlist WHERE user_id = ? AND film_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $user_id, $film_id);
    
    if ($delete_stmt->execute()) {
        if ($delete_stmt->affected_rows > 0) {
            echo "<script>alert('Successfully remove the film from the Watchlist'); window.history.back();</script>";
        } else {
            echo "<script>alert('the film is not in Watchlist'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('deleted failed：" . $delete_stmt->error . "'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid parameter'); window.history.back();</script>";
}

// 关闭连接
$conn->close();
?>
