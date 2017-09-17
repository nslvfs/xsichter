<?php
require_once( 'functions.php' );


if (isset($_GET['thandle'])) {
        if(validate_username($_GET['thandle'])){
                $tuser = $_GET['thandle'];
                $check = true;
        } else {
                $tuser = "";
                $check = false;
        }
} else {
        $tuser = "";
        $check = false;
}





if (isset($_GET['img'])) {
	$imgurl = htmlspecialchars($_GET['img'],ENT_QUOTES, 'UTF-8');
} else {
	$imgurl = "https://transmonster.wegbuxen.biz/twistlerpics/170820_Anne_Will/vlcsnap-2017-08-20-21h45m29s914.jpg";
}

$fchars = "()=\<>{}[]'\"";
for ($i = 0; $i < strlen($fchars); $i++){
    $imgurl = str_replace($fchars[$i],"",$imgurl);
}


if(!imgExists($imgurl))
{
	if (!file_exists($imgurl))
	{
		die('Keine gültige Quelle');
	}
}

$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG);
$detectedType = @exif_imagetype($imgurl);
$error = !in_array($detectedType, $allowedTypes);

if($error){
	die("ungültiger Dateityp");
}



if ( ! isset( $_GET['top_text'] ) && ! isset( $_GET['bottom_text'] ) ) {
?>
	<form>
	<p>Toptext:<br /><input name="top_text"/></p>
	<p>Bottomtext:<br /><input name="bottom_text" /></p>
	<p>Twitterhandle ohne @:<br /><input name="thandle" value="<?php echo $tuser; ?>" /></p>
	<p>URL:<br /><input name="img" value="<?php echo $imgurl; ?>">
	<p><input type="submit" /></p>
	<p>Twitterhandle funktioniert nur wenn Top- & Bottomtext leer bleiben</p>
	</form>
	<img src="<?php echo $imgurl; ?>" />
	</body>
</html>
<?php	die();
}

	// get form submission (or defaults)
	$top_text    = isset( $_GET['top_text'] )    ? $_GET['top_text'] : '';
	$bottom_text = isset( $_GET['bottom_text'] ) ? $_GET['bottom_text'] : '';
//	for ($i = 0; $i < strlen($fchars); $i++){
//		$bottom_text = str_replace($fchars[$i],"",$bottom_text);
//		$top_text =  str_replace($fchars[$i],"",$top_text);
//	}

	if((empty($top_text)) and (empty($bottom_text)))
	{
		if(!empty($tuser))
		{

			if(!twitterAccountExists($tuser)){
				die("unbekannter nutzername");
			}

			$maxtw = 100;

		        require_once 'codebird-php/src/codebird.php';
		        $CONSUMER_KEY = '';
		        $CONSUMER_SECRET = '';
		        $ACCESS_TOKEN = '';
		        $ACCESS_TOKEN_SECRET = '';
		        \Codebird\Codebird::setConsumerKey($CONSUMER_KEY, $CONSUMER_SECRET);
		        $cb = \Codebird\Codebird::getInstance();
		        $cb->setToken($ACCESS_TOKEN, $ACCESS_TOKEN_SECRET);
		        $cb->setConsumerKey($CONSUMER_KEY, $CONSUMER_SECRET);
		        $cb->setReturnFormat(CODEBIRD_RETURNFORMAT_ARRAY);
		        $reply = $cb->search_tweets("q=from:".$tuser."&result_type=recent&count=".$maxtw);

		        $realmax = count($reply["statuses"]);
		        $check = 0;

		        $i = mt_rand(0, $realmax - 1);
		        $top_text = $reply["statuses"][$i]["text"];

		        $i = mt_rand(0, $realmax - 1);
		        $bottom_text = $reply["statuses"][$i]["text"];
		        $top_text = str_replace("#","",$top_text);
		        $bottom_text = str_replace("#","",$bottom_text);
		} else {
			die("JUNGE 1 von 3 Feldern wirst du ja wohl ausfüllen können");
			}

	}


	$filename    = memegen_sanitize( $bottom_text ? $bottom_text : $top_text );

	// setup args for image
	$args = array(
		'top_text'    => $top_text,
		'bottom_text' => $bottom_text,
		'filename'    => $filename,
		'font'        => dirname(__FILE__) .'/impact.ttf',
		'memebase'    => $imgurl,
		'textsize'    => 40,
		'textfit'     => true,
		'padding'     => 10,
	);

	// create and output image
	memegen_build_image( $args );

function validate_username($username)
{
    return preg_match('/^[A-Za-z0-9_]{1,15}$/', $username);
}

function imgExists($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if(curl_exec($ch)!==FALSE)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function twitterAccountExists($username){
    $headers = get_headers("https://twitter.com/".$username);
    if(strpos($headers[0], '404') !== false ) {
        return false;
    } else {
        return true;
    }
}

?>
