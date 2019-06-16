<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title><?php echo ucfirst($this->router->fetch_class()); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?php /*<link href="<?php echo base_url('assets/css/bootstrap.min.css');?>" rel="stylesheet"> */?>
    <?php


        if(date('G')>6 && date('G')<20){
            $link='https://bootswatch.com/4/flatly/bootstrap.min.css';
        }else{
            $link='https://bootswatch.com/4/darkly/bootstrap.min.css';
        }

    ?>
    <link href="<?php echo $link; ?>" rel="stylesheet">

	<link href="<?php echo base_url('assets/css/adds/sticky-footer.css');?>" rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>