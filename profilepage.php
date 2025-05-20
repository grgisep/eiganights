<?php
	require_once __DIR__ . '/db.php';
	require_once __DIR__ . '/get_user_info.php';
	require_once __DIR__ . '/tmdb_sync.php';

	$sql = "SELECT id, username, joined, avatar FROM user";
	$result = $conn->query($sql);

	$searchResults = [];

	// 获取最新评论作为“Trending”
	$trending_comment = null;
	$trend_sql = "
		SELECT af.filmname AS id, c.segment, c.comment_text, TIME(c.created_at) AS time 
		FROM comments c
		JOIN allfilm af ON c.film_id = af.id
		ORDER BY c.created_at DESC
		LIMIT 1
	";
	$res = $conn->query($trend_sql);
	if ($res && $res->num_rows > 0) {
		$trending_comment = $res->fetch_assoc();
	}

	if (isset($_GET['query']) && !empty($_GET['query'])) {
		$query = $conn->real_escape_string($_GET['query']);
    
		$sql_search = "SELECT id, filmname, rank ,poster FROM allfilm WHERE filmname LIKE '%$query%'";
		$result_search = $conn->query($sql_search);
    
    if ($result_search && $result_search->num_rows > 0) {
        while ($row = $result_search->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }
}

	$sql = "
		SELECT af.ID AS film_id, af.filmname, af.rank, af.poster
		FROM watchlist w
		JOIN allfilm af ON w.film_id = af.ID
		WHERE w.user_id = ?
	";

	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $user_id);
	$stmt->execute();
	$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $user['username']; ?> personal information </title>
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
	
	<script>
	    function clearSearch() {
    document.getElementById("search-input").value = "";
    document.getElementById("search-input").focus();
		
	</script>

	
	<div class="profile">
		<div class="left-profile">
			<img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="avatar">
			<div>
				<h2><?php echo htmlspecialchars($user['username']); ?></h2>
				<p> Joined <?php echo htmlspecialchars($user['joined']); ?></p>
				<button id="edit-button" class="edit-button" onclick="document.getElementById('editModal').style.display='block'">Edit profile</button>
			</div>
		</div>
	</div>
	
	<div id="editModal" style="display:none;" class="modal" action="/eiganights/update_profile.php" method="POST">
		<div class="modal-content">
			<span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
			<h2 style="color:black">Edit Profile</h2>
			<form id="profileForm" method="POST" action="/eiganights/update_profile.php">
				<label style="color:black">Username: 
					<input type="text" name="username" value="<?php echo $user['username']; ?>" required>
				</label><br><br>
				<label style="color:black">Avatar URL: 
					<input type="text" name="avatar" value="<?php echo $user['avatar']; ?>" required>
				</label><br><br>
				<button type="submit">Save</button>
			</form>
		</div>
	</div>
	
	<div class="watchlist">
			<?php
			echo "<h2>Watchlist</h2>";
			echo "<div class='movies'>";
			while ($row = $result->fetch_assoc()) {
				echo "<div class='movie-card'>";
				echo "<a href='movieinterface.php?id=" . $row['film_id'] . "'>";
				echo "<img src='" . htmlspecialchars($row['poster']) . "'>";
				echo "</a>";
				echo "<h3>" . htmlspecialchars($row['filmname']) . "</h3>";
				echo "<p>⭐" . htmlspecialchars($row['rank']) . "</p >";
				echo "</div>";
			}
			echo "</div>";
			?>
	</div>
	
	<div class="trending">
		<h3>Trending Comment</h3>
		<div class="comment-box">
			<?php if ($trending_comment): ?>
				<strong><?php echo htmlspecialchars($trending_comment['id']); ?></strong><br>
				🕒 <?php echo htmlspecialchars($trending_comment['segment']); ?><br>
				<p><?php echo htmlspecialchars($trending_comment['comment_text']); ?></p>
			<?php else: ?>
				<p>No comments yet</p>
			<?php endif; ?>
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
 