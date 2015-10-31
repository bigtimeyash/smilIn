<?php
	include("db.php");
	

	mysql_query("INSERT INTO `smilin`.`pics` (`pathName`) VALUES ('".$_POST['hiddenPicPath']."')");
	mysql_query("INSERT INTO new_table (userString, pin, picId) VALUES ('".$_POST['username']."','".$_POST['pin']."',LAST_INSERT_ID())");

	header("location:index.php?error=created");
	
?>