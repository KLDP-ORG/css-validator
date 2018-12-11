<?php
if ( ! extension_loaded ('curl') )
     echo "Not loaded Curl Extension\n";

$uri = $_SERVER['QUERY_STRING'];
$uri = $uri ? "?${uri}" : '';
$method = $_SERVER['REQUEST_METHOD'];

$_server = "http://domain.com/validator{$uri}";

$_lang = ( ! $_GET['_lang'] ) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : $_GET['_lang'];
#$_lang = 'ko';


$c = curl_init ();

curl_setopt ($c, CURLOPT_URL, $_server);
curl_setopt ($c, CURLOPT_TIMEOUT, 60);
curl_setopt ($c, CURLOPT_NOPROGRESS, 1);
curl_setopt ($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($c, CURLOPT_USERAGENT, "CSS-Validator KLDP Wrapper 1.0");

$header[] = "Host: domain.com";
if ( $_lang )
  $header[] = "Accept-Language: {$_lang}";
$header[] = 'Expect:';

curl_setopt ($c, CURLOPT_HEADER, 0); 
curl_setopt ($c, CURLOPT_NOBODY, 0);
curl_setopt ($c, CURLOPT_HTTPHEADER, $header);
curl_setopt ($c, CURL_FAILONERROR, 1);

if ( $method == 'POST' ) :
	$_post = array ();

	if ( is_array ($_FILES['file']) ) :
		$_post['file'] = "@{$_FILES['file']['tmp_name']}";
	endif;

	$_post = array_merge ($_post, $_POST);

	curl_setopt ($c, CURLOPT_POST, 1);
	curl_setopt ($c, CURLOPT_POSTFIELDS, $_post);
endif;

$data = curl_exec($c);

if ( empty ($data) ) :
  echo curl_error($c) . "\n";
endif;
curl_close ($c);

echo trim ($data) ."\n";
?>
