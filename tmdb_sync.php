	<?php
	require_once __DIR__ . '/db.php';
	function fetchMovieFromTMDB($tmdb_id) {
		$api_key = '9c45e9f8597351ae0e72649a7159d71e';
		$url = "https://api.themoviedb.org/3/movie/{$tmdb_id}?api_key={$api_key}&language=zh-CN";

		$response = file_get_contents($url);
		if ($response === FALSE) {
			return null;
		}
		return json_decode($response, true);
	}

	// 示例：同步单个电影数据
	$tmdb_id = 550; // 你要同步的电影TMDB ID
	$movie_data = fetchMovieFromTMDB($tmdb_id);

	if ($movie_data) {
		// 把电影数据写入数据库，示例：
		$stmt = $conn->prepare("REPLACE INTO allfilm (tmdb_id, filmname, introduction, poster, `rank`) VALUES (?, ?, ?, ?, ?)");
		$filmname = $movie_data['title'];
		$introduction = $movie_data['overview'];
		$poster = "https://image.tmdb.org/t/p/w500" . $movie_data['poster_path'];
		$rank = $movie_data['vote_average'];
		$stmt->bind_param("isssd", $tmdb_id, $filmname, $introduction, $poster, $rank);
		$stmt->execute();
	} else {
		echo "同步失败，无法获取数据";
	}
	?>
