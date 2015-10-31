<?php
include("db.php");
$q = mysql_query("SELECT * FROM new_table, pics WHERE new_table.picId = pics.picId");

$photoStrings = '';
while($res = mysql_fetch_array($q))
{
	$photoStrings .= $res['pathName']; 
}


$q1 = mysql_query("SELECT * FROM new_table WHERE UCASE(userString) = UCASE('".$_POST['username']."') ");
echo "SELECT * FROM new_table WHERE UCASE(userString) = UCASE('".$_POST['username']."') ";
echo mysql_num_rows($q1);
echo mysql_error();
if(mysql_num_rows($q1) > 0 )
{

	header("location: success.php?fail=");
}
else
{
	header("location: failure.php");
}

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.kairos.com/recognize");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, "{
  \"image\": \"http://smilin.azurewebsites.net/pics/uploads/alec.jpeg\",
  \"gallery_name\": \"yashAlbum\"
}");

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "app_id: b2ec1c8a",
  "app_key: a582e0fbecc8923f0b008b72afdd0235"
));

$response = curl_exec($ch);
curl_close($ch);
echo $response." ";
var_dump($response);	
?>
