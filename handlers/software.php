<?php
	require_once 'db.php';

	// список компьютеров с установленным ПО (название ПО выбирается из перечня)
	if (isset($_GET['getBtn'])) {
		$softwareId = filter_input(INPUT_GET, 'softwareList', FILTER_VALIDATE_INT);

		$stmt = $dbh->prepare("SELECT `computer`.`netname`, `computer`.`vendor`, `software`.`name` FROM `computer`, `computer_has_software`, `software` WHERE `computer`.`id` = `computer_has_software`.`computer_id` AND `computer_has_software`.`software_id` = `software`.`id` AND `software`.`id` = ?");
		$stmt->execute([$softwareId]);

		$dataOut = "";

		foreach ($stmt as $row) {
			$dataOut .= sprintf("<p><b>%s %s</b><br>%s: <i>Установлено</i></p>", $row['vendor'], $row['netname'], $row['name']);
		}

		header('Content-type: text/html; charset=utf-8');
	}
