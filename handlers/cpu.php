<?php
	require_once 'db.php';

	// список компьютеров с заданным типом центрального процессора
	if (isset($_GET['getBtn'])) {
		$cpuId = filter_input(INPUT_GET, 'cpuList', FILTER_VALIDATE_INT);

		$stmt = $dbh->prepare("SELECT `computer`.`netname`, `computer`.`motherboard`, `computer`.`ram_capacity`, `computer`.`hdd_capacity`, `computer`.`monitor`, `computer`.`vendor`, `computer`.`purchase`, `computer`.`guarantee`, `processor`.`name`, `processor`.`frequency` FROM `computer`, `processor` WHERE `processor`.`id` = `computer`.`processor_id` AND `computer`.`processor_id` = ?");
		$stmt->execute([$cpuId]);

		$data = array();

		foreach ($stmt as $row) {
			$purchase_date = strtotime($row['purchase']);
			$guarantee_date = strtotime($row['guarantee']);

			$data[] = array(
				"name" => $row['vendor'] . " " . $row['netname'],
				"processor" => $row['name'],
				"frequency" => $row['frequency'],
				"motherboard" => $row['motherboard'],
				"monitor" => $row['monitor'],
				"hdd_capacity" => $row['hdd_capacity'],
				"ram_capacity" => $row['ram_capacity'],
				"purchase" => date("d.m.Y", $purchase_date),
				"guarantee" => date("d.m.Y", $guarantee_date),
				"guaranteePeriod" => date("Y", $guarantee_date) - date("Y", $purchase_date)
			);
		}

		header('Content-type: application/json; charset=utf-8');
		$dataOut = json_encode($data);
	}
