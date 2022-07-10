<?php
	require_once 'db.php';

	$stmt = $dbh->prepare("SELECT `id`, `name` FROM `processor` ORDER BY `name` ASC");
	$stmt->execute();

	foreach ($stmt as $row) {
		printf("<option value=\"%d\">%s</option>", $row['id'], $row['name']);
	}
