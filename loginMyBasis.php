<?php 
$response = array('status' => 'NOK');
if(isset($_POST['username'])){
	$user = $_POST['username'];
}
else{
	$response = array_merge($response, array('error' => 'user not set'));
}
if(isset($_POST['password'])){
	$password = $_POST['password'];
}
else{
	$response = array_merge($response, array('error' => 'pass not set'));
}

if(isset($_POST['start_date'])){
	$start_date = $_POST['start_date'];
}
else{
	$response = array_merge($response, array('error' => 'start_date missing'));
}
if(isset($_POST['end_date'])){
	$end_date = $_POST['end_date'];
}
else{
	$response = array_merge($response, array('error' => 'end_date missing'));
}
$interval = "60";
if(isset($_POST['interval'])){
	$interval = $_POST['interval'];
}

$heartrate = "false";
if(isset($_POST['heartrate'])){
	$heartrate = $_POST['heartrate'];
}


$steps = "false";
if(isset($_POST['steps'])){
	$steps = $_POST['steps'];
}

$calories = "false";
if(isset($_POST['calories'])){
	$calories = $_POST['calories'];
}

$gsr = "false";
if(isset($_POST['gsr'])){
	$gsr = $_POST['gsr'];
}

$skin_temp = "false";
if(isset($_POST['skin_temp'])){
	$skin_temp = $_POST['skin_temp'];
}

$bodystates = "false";
if(isset($_POST['bodystates'])){
	$bodystates = $_POST['bodystates'];
}


$cookie_jar = tempnam('/tmp', 'cookie');


$ch = curl_init();
$url = "https://app.mybasis.com/login"; 

//curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_HEADER, 0); 
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0"); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$post_data = array( 
	"next" => "https://app.mybasis.com", 
	"username" => $user, 
	"password" => $password, 
	"submit" => "Login",
);
	
$post_data = (is_array($post_data)) ? http_build_query($post_data) : $post_data; 


curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($post_data))); 
curl_setopt($ch, CURLOPT_POST, true); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); 

//session_write_close();
$output = curl_exec($ch); 
$info = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
curl_close($ch); 

$logged = false;
if ($output === false || $info != 200) { 
	$response = array_merge($response, array('error' => 'not logged'));
}

// quando il login avviene con successo il codice $info Ë 302
// perchË a login effettuato cerca di fare un redirect che noi in cURL non gestiamo
// quindi se $output NON Ë vuoto vuol dire che la login non Ë andata a buon fine
if($output == "" && $info == 302) {
	$logged = true;
}

if(!$logged) {
	$response = array_merge($response, array('error' => 'not logged'));
}
else {

	$ch = curl_init();
	//$url = "https://app.mybasis.com/api/v1/chart/me?breaks=4&interval=3600&units=ms&start_date=2014-01-04&end_date=2014-01-17&calories=true";
	$url = "https://app.mybasis.com/api/v1/chart/me?summary=true&interval=" . $interval 
	. "&units=ms&start_date=" . $start_date 
	. "&end_date=" . $end_date 
	. "&heartrate=" . $heartrate 
	. "&steps=" . $steps 
	. "&calories=" . $calories 
	. "&gsr=" . $gsr 
	. "&skin_temp=" . $skin_temp 
	. "&bodystates=" . $bodystates ;
	//curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_HEADER, 0); 
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0"); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

	//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	//session_write_close();
	$output = curl_exec($ch); 
	$info = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
	curl_close($ch); 

	// qui $info deve essere 200 cosÏ Ë sicuro che l'output Ë corretto
	if ($output === false || $info != 200) { 
		$response = array_merge($response, array('error' => 'API not work'));
	}
	else {
		$response = array('status' => 'OK', 'url' => $url);
		$response = array_merge($response, json_decode($output, true));
	}

}
echo json_encode($response);	
// remove the cookie jar
unlink($cookie_jar) or die("Can't unlink $cookie_jar");

?>





