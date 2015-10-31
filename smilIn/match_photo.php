<script language="php">
#ob_start();

$initialFileDir = 'pics/uploads/';
#$initialFileDir = '';
$testPhoto = $initialFileDir.'Alice1.jpg';
echo $testPhoto;
#echo "hello$testPhoto";
#passthru("/usr/bin/python2.7 cropPhotos.py {$testPhoto}");
#$realTestPhoto = exec("python cropPhotos.py {$testPhoto}");
#$realTestPhoto = ob_get_clean();
$photoBank = array($initialFileDir."Sanjay1.jpg", $initialFileDir."Sanjay2.jpg", $initialFileDir."Sanjay3.jpg", $initialFileDir."Jeffrey1.jpg", $initialFileDir."Jeffrey2.jpg", $initialFileDir."Jeffrey3.jpg", $initialFileDir."Yash1.jpg", $initialFileDir."Yash2.jpg", $initialFileDir."Yash3.jpg", $initialFileDir."Alice1.jpg", $initialFileDir."Alice2.jpg", $initialFileDir."Alice3.jpg", $initialFileDir."X1.jpg", $initialFileDir."X2.jpg", $initialFileDir."X3.jpg", $initialFileDir."Y1.jpg", $initialFileDir."Y2.jpg", $initialFileDir."Y3.jpg");
echo $photoBank[0];
$matchCounts = array();
$maxMatchCount = 0;
foreach ($photoBank as $trainPhoto)
{
	#$trainPhoto = 'Sanjay3_1.jpg';
	#$realTestPhoto = exec("python cropPhotos.py {$testPhoto}");
	$realTestPhoto = $testPhoto;
	#echo "New: ", $realTestPhoto, "<br>";
	$realTestPhoto .= ',';
	$realTestPhoto .= $trainPhoto;
	#echo $realTestPhoto, "<br>";

	#echo "/usr/bin/python2.7 photo_compare.py ".$realTestPhoto; 
	#passthru("/usr/bin/python2.7 photo_compare.py ".$realTestPhoto); 

	$output = exec("python photo_compare.py {$realTestPhoto}");
	$tokens = explode(" ", $output);
	#echo $tokens[1], "<br>";
	$matchCount = (integer)$tokens[1];
	#echo 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', "<br>";
	#echo $matchCount, "<br>";
	if ($matchCount > $maxMatchCount)
	{
		$maxMatchCount = $matchCount;
	}
	$matchCounts[] = $matchCount; 
	#echo $trainPhoto, "<br>", $output, "<br>";
	#var_dump($output);
}
#echo $maxMatchCount, "<br>";
$threshold =(integer)(0.05*((float)$maxMatchCount));
echo $threshold, "<br>";
for ($i = 0; $i < count($matchCounts); $i++)
{
	echo $matchCounts[$i], "<br>";
	if ($matchCounts[$i] > $threshold)
	{
		echo $photoBank[$i], "<br>";
	}
}

</script>
