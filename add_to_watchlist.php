<?php
// 数据库连接
$conn = new mysqli("localhost", "root", "root", "cinema-userr");

// 获取表单传来的 user_id 和 film_id
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
$film_id = isset($_POST['film_id']) ? intval($_POST['film_id']) : 0;

// 检查数据有效性
if ($user_id > 0 && $film_id > 0) {
    // 检查是否已存在这条记录（防止重复添加）
    $check_sql = "SELECT * FROM watchlist WHERE user_id = ? AND film_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $film_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        // 插入 watchlist 表
        $insert_sql = "INSERT INTO watchlist (user_id, film_id) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ii", $user_id, $film_id);
        if ($insert_stmt->execute()) {
            echo "<script>alert('Add in Watchlist successfully！'); window.history.back();</script>";
        } else {
            echo "<script>alert('add failed：" . $insert_stmt->error . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('The film is already in Watchlist'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid parameter'); window.history.back();</script>";
}

// 关闭连接
$conn->close();
?>
