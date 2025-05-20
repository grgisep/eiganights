<?php
function searchFilms($query) {
    global $conn;
    $query = $conn->real_escape_string($query);
    $sql_search = "SELECT id, filmname, rank, poster FROM allfilm WHERE filmname LIKE '%$query%'";
    $result_search = $conn->query($sql_search);
    $searchResults = [];

    if ($result_search && $result_search->num_rows > 0) {
        while ($row = $result_search->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }
    return $searchResults;
}
?>