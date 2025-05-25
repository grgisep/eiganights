
		<?php
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "cinema-userr";

		// 创建连接
		$conn = new mysqli($servername, $username, $password, $dbname);

		$searchResults = [];
		$query = "";

		if (isset($_GET['query']) && !empty($_GET['query'])) {
			$query = $conn->real_escape_string($_GET['query']);
			$sql = "SELECT id, filmname, rank, poster FROM allfilm WHERE filmname LIKE '%$query%'";
			$result = $conn->query($sql);

			if ($result && $result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$searchResults[] = $row;
				}
			}
		}
		?>

		<!DOCTYPE html>
		<html>
		<head>
			<title>Search results <?php echo htmlspecialchars($query); ?></title>
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
			<h1 style="padding:40px 5vw 20px">Search results : <?php echo htmlspecialchars($query); ?></h1>

			<?php if (!empty($searchResults)): ?>
				<div class="movies">
				<?php foreach ($searchResults as $film): ?>
					<div class="movie-card">
						<a href="movieinterface.php?id=<?php echo urlencode($film['id']); ?>" target="_blank" >
							<img src="<?php echo htmlspecialchars($film['poster']); ?>" alt="Poster">
						</a>
						<div class="title"><?php echo htmlspecialchars($film['filmname']); ?></div>
						<div class="rating">⭐ <?php echo htmlspecialchars($film['rank']); ?></div>  
					</div>
				<?php endforeach; ?>
				</div>			
			<?php else: ?>
				<p style="padding:20px 5vw 20px">Don't find <?php echo htmlspecialchars($query); ?> </p>
			<?php endif; ?>

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

		</html>
