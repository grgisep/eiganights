<?php

		$user_id = 1;
		$sql = "SELECT username, joined, avatar FROM user WHERE id = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("i", $user_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$user= $result->fetch_assoc();

?>
