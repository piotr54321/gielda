<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>Logowanie</title>
</head>
<body>

<div id="container">
	<h1>Test</h1>

	<div id="body">
		<p>Appropriate Cost Found: <?php echo $this->test_cost; ?></p>
		<p>Test: <?php echo $this->baza_model->hashgenerator("test"); ?></p>
	</div>
</div>

</body>
</html>