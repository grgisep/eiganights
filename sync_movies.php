<?php
require_once __DIR__ . '/db.php'; // 数据库连接文件

$token = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5YzQ1ZTlmODU5NzM1MWFlMGU3MjY0OWE3MTU5ZDcxZSIsIm5iZiI6MTc0NzM4NzUwNC42NDIsInN1YiI6IjY4MjcwNDcwNDNjMTRlMjllODVhM2Y1ZSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.Rn-fd7RSQ3FySFAUGL8uX3BxoSXotEIOgXYSx41Uq7Y'; // 替换为你的 Bearer Token
$max_pages = 5; // 推荐先测试几页，最大可到 500

for ($page = 1; $page <= $max_pages; $page++) {
    $url = "https://api.themoviedb.org/3/movie/popular?language=en-US&page=$page";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "accept: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (!isset($data['results'])) {
        echo "Error on page $page<br>";
        continue;
    }

    foreach ($data['results'] as $movie) {
        $id = $movie['id'];
        $title = $movie['title'];
        $rank = $movie['vote_average'];
        $poster = 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'];
        $overview = $movie['overview'];

		$stmt = $conn->prepare("INSERT IGNORE INTO allfilm (id, filmname, rank, introduction, poster) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isdss", $id, $title, $rank, $overview, $poster);
        $stmt->execute();
    }

    echo "第 $page 页已同步完成<br>";
    sleep(1); // 防止 API 限速
}
?>
