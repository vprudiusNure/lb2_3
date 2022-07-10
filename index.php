<?php
	require_once 'db.php';

	if ($_SERVER['REQUEST_URI'] == '/')
		$page = 'home';
	else {
		$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$url_parts = explode('/', trim($url_path, ' /'));
		$page = array_shift($url_parts);
		$module = array_shift($url_parts);
		unset($url_path);
	}

	$dataOut = null;

	header("HTTP/1.1 200 OK");

	switch ($page) {
		case 'home':
			include_once 'home.phtml';
			break;
		case 'handler':
			switch ($module) {
				case 'cpu':
					require_once 'handlers/cpu.php';
					break;
				case 'software':
					require_once 'handlers/software.php';
					break;
				case 'guarantee':
					require_once 'handlers/guarantee.php';
					break;
			}
			break;
		default:
			header("HTTP/1.1 404 Page Not Found");
			break;
	}

	$dbh = null;

	print $dataOut;
