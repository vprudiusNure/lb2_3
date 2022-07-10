<?php
	require_once 'db.php';

	function array2xml($data, &$xml) {
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				if (is_numeric($key)){
					$key = 'item-' . $key;
				}
				$subnode = $xml->addChild($key);
				array2xml($value, $subnode);
			}
			else {
				$xml->addChild($key, htmlspecialchars($value));
			}
		}
	}

	// список компьютеров с истекшим гарантийным сроком
	if (isset($_GET['getBtn'])) {
		$guaranteeDate = date('Y-m-d', strtotime(htmlspecialchars($_GET['guaranteeDate'])));

		$stmt = $dbh->prepare("SELECT `computer`.`netname`, `computer`.`motherboard`, `computer`.`ram_capacity`, `computer`.`hdd_capacity`, `computer`.`monitor`, `computer`.`vendor`, `computer`.`purchase`, `computer`.`guarantee`, `processor`.`name`, `processor`.`frequency` FROM `computer`, `processor` WHERE `processor`.`id` = `computer`.`processor_id` AND DATE(`computer`.`guarantee`) < DATE(?) ORDER BY `computer`.`guarantee` ASC");
		$stmt->execute([$guaranteeDate]);

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

		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><data/>');
		array2xml($data, $xml);

		header('Content-type: application/xml; charset=utf-8');

		$dataOut = $xml->asXML();
	}
