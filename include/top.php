<?
	if ($_SERVER['SERVER_PORT']!='443') header('Location: https://govisit.it/adm.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sr-yu" lang="sr-yu" dir="ltr" > 
	<head> 
		<meta http-equiv="content-type" content="text/html; charset=cp1250" /> 
		<title>GoVisit.it :: Conversion manager</title>
		<link rel="stylesheet" type="text/css" href="css/style.css?type<? echo mktime(); ?>">
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery.metadata.js"></script>
		<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
		<script type="text/javascript" src="js/govisit.js?type<? echo mktime(); ?>"></script>
	</head>
	<body>
		<ul>
			<li><a href="user_list.php">Users</a>
				<ul>
					<li><a href="user_add.php">Add user</a></li>
					<li><a href="user_import.php">Import users</a></li>
				</ul>
			</li>
			<li><a href="network_list.php">Networks</a>
				<ul>
					<li><a href="network_add.php">Add network</a></li>
				</ul>
			</li>
			<li>
				<a href="product_list.php">Products</a>
				<ul>
					<li><a href="product_add.php">Add product</a></li>
				</ul>
			</li>
			<li>
				<a href="sources_list.php">Sources</a>
				<ul>
					<li><a href="sources_add.php">Add source</a></li>
					<li><a href="badsources_list.php">Bad sources</a></li>
				</ul>
			</li>
			<li><a href="sessions_list.php">Sessions</a>
				<ul>
					<li><a href="generate_link.php">Generate tracking link</a></li>
					<li><a href="sessions_import.php">Import converted sessions</a></li>
				</ul>
			</li>
			<li><a href="settings_list.php">Settings</a>
				<ul>
					<li><a href="settings_add.php">Add new option</a></li>
				</ul>
			</li>
			<li><a href="mailing_list.php">Mailing lists</a>
				<ul>
					<li><a href="mailing_add.php">Add list</a></li>
				</ul>
			</li>
			<li><a href="report.php">Report</a></li>
			<li><a href="http://logout:logout@govisit.it" onClick="return confirm('Logout?')">Logout</a></li>
		</ul>
		<div class="content">