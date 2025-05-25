	<?php
	// 数据库连接
	$conn = new mysqli("localhost", "root", "", "cinema-userr");

	$film_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	$sql = "SELECT * FROM allfilm WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $film_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$film = $result->fetch_assoc();

	// 查询评论
	$comment_sql = "SELECT c.comment_text, c.segment, u.username FROM comments c 
					JOIN user u ON c.user_id = u.id 
					WHERE c.film_id = ? ORDER BY c.created_at DESC";
	$comment_stmt = $conn->prepare($comment_sql);
	$comment_stmt->bind_param("i", $film_id);
	$comment_stmt->execute();
	$comments = $comment_stmt->get_result();
	
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<title><?php echo htmlspecialchars($film['filmname']); ?></title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>

		<div class="header">
			<div class="left-header">
				<a href="profilepage.php">
					<img src="https://pic.52112.com/180704/EPS-180704_297/MApbjeCZ6z_small.jpg" alt="Logo" class="logo">
				</a>
				<p>eiganights</p>
			</div>
				<form method="GET" action="/eiganights/searchpage.php">
					<div class="search-container">
						<input type="text" id="search-input" name="query" placeholder="Search" class="search-input" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
						<button class="button" type="submit">🔍</button>
						<button type="button" class="button" onclick="clearSearch()">x</button>
					</div>
				</form>
			<div>
				<button>Sign in</button>
				<button>Register</button>
			</div>
		</div> 
		
		<div style="padding:10px 5vw">
			<h1><?php echo htmlspecialchars($film['filmname']); ?></h1>
			<img src="<?php echo htmlspecialchars($film['poster']); ?>" width="200">
			<p class="movie-interface font">⭐：<?php echo htmlspecialchars($film['rank']); ?></p>
			<h3 class="movie-interface font">Introduction：<?php echo htmlspecialchars($film['introduction']); ?></p>
			
			<div class="left-header">
				<form method="POST" action="/eiganights/add_to_watchlist.php">	
					<input type="hidden" name="film_id" value="<?php echo $film_id; ?>">
					<input type="hidden" name="user_id" value="1"> 
					<button type="submit">add to watchlist</button>
				</form>
				<form method="POST" action="/eiganights/delete_watchlist.php">
					<input type="hidden" name="film_id" value="<?php echo $film_id; ?>">
					<input type="hidden" name="user_id" value="1">
					<button type="submit">remove from watchlist</button>
				</form>
			</div>
				
			<h2>comments</h2>
			<?php while($row = $comments->fetch_assoc()): ?>
				<div>
					<strong><?php echo htmlspecialchars($row['username']); ?></strong> in [<?php echo htmlspecialchars($row['segment']); ?>] wrote：<br>
					<p><?php echo htmlspecialchars($row['comment_text']); ?></p>
				</div>
			<?php endwhile; ?>

			<div>
				<h3>Post a comment</h3>
				<form method="POST" action="/eiganights/add_comment.php">
					<input type="hidden" name="film_id" value="<?php echo $film_id; ?>">
					<input type="hidden" name="user_id" value="1"> 
					Segement：<input type="text" name="segment" required><br><br>
					Comment：<textarea name="comment_text" required></textarea><br>
					<button type="submit">Submit</button>
				</form>	
			</div>
		</div>
		
		<footer class="page-footer">
			<p>Follow us on</p>
			<a href="https://www.instagram.com/你的账号" target="_blank">
			<img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png" alt="Instagram Logo" class="footer-logo">
			</a>
			<a href="https://www.youtube.com/channel/你的频道ID" target="_blank">
			<img src="https://upload.wikimedia.org/wikipedia/commons/0/09/YouTube_full-color_icon_%282017%29.svg" alt="Youtube Logo" class="footer-logo">
			</a>
			<a href="https://www.linkedin.com/in/你的LinkedIn用户名" target="_blank">
				<img src="https://upload.wikimedia.org/wikipedia/commons/0/01/LinkedIn_Logo.svg" alt="LinkedIn Logo" class="footer-logo">
			</a>
		</footer>

	</body>
	</html>
