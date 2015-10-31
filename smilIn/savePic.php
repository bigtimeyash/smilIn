
<?php
	// requires php5
	define('UPLOAD_DIR', 'pics/uploads/');
	$img = $_POST['imgBase64'];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$tmpfile = $_POST['username']."_".uniqid().'.png';
	$file = UPLOAD_DIR . $tmpfile;
	$success = file_put_contents($file, $data);
	//return json_encode($img);
	echo $tmpfile;
?>