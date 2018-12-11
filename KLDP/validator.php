<?php
require_once 'KSC5601.php';

function printe ($err) {
	echo "<script type=\"text/javascript\">\n" .
		"  alert('$err')\n" .
		"  history.back();\n" .
		"</script>\n";

	exit;
}

$ksc = new KSC5601;

function iconv_callback ($m) {
	$c = iconv ('utf8', 'iso-8859-1//TRANSLIT', $m[3]);
	if ( ! $GLOBALS['ksc']->is_utf8 ($c) )
		$c = iconv ('uhc', 'utf-8', $c);

	return $m[1] . $c;
}

#___phpinfo();
#exit;

if ( ! extension_loaded ('curl') )
	printe ("Not loaded Curl Extension");

$uri = $_SERVER['QUERY_STRING'];
$uri = $uri ? "?${uri}" : '';
$method = $_SERVER['REQUEST_METHOD'];

#$_server = "http://css-validator.kldp.org:8080/css-validator/validator{$uri}";
$_server = "http://css-validator.kldp.org:8080/css-validator/validator{$uri}";

$_lang = ( ! $_GET['_lang'] ) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : $_GET['_lang'];
#$_lang = 'ko';


$c = curl_init ();

curl_setopt ($c, CURLOPT_URL, $_server);
curl_setopt ($c, CURLOPT_TIMEOUT, 60);
curl_setopt ($c, CURLOPT_NOPROGRESS, 1);
curl_setopt ($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($c, CURLOPT_USERAGENT, "CSS-Validator KLDP Wrapper 1.0");

$header[] = "Host: css-validator.kldp.org";
if ( $_lang )
	$header[] = "Accept-Language: {$_lang}";
$header[] = 'Expect:';

curl_setopt ($c, CURLOPT_HEADER, 0); 
curl_setopt ($c, CURLOPT_NOBODY, 0);
curl_setopt ($c, CURLOPT_HTTPHEADER, $header);
curl_setopt ($c, CURLOPT_FAILONERROR, 1);

if ( $method == 'POST' ) {
	$_post = array ();

	if ( is_array ($_FILES['file']) )
		$_post['file'] = "@{$_FILES['file']['tmp_name']}";

	$_post = array_merge ($_post, $_POST);

	curl_setopt ($c, CURLOPT_POST, 1);
	curl_setopt ($c, CURLOPT_POSTFIELDS, $_post);
}

$data = curl_exec($c);

if ( empty ($data) ) {
	$err = trim (curl_error ($c));
	if ( ! $err )
		$err = 'Application server error!';
	printe ($err);
}

curl_close ($c);

// 20121129 버전 부터는 할 필요 없다.
#$data = preg_replace ('/(&(quot|#39));/', '\\1_semi_mark_', $data);
#$data = preg_replace_callback ("/((font|font-family)[^:]*:[ \n\t]*)([^;\n]+;)/m", iconv_callback, $data);
#$data = preg_replace ('/_semi_mark_/', ';', $data);
$data = preg_replace ("!(</html>).*!s", '\\1', $data);
echo trim ($data) ."\n";

#
# Local variables:
# tab-width: 4
# c-basic-offset: 4
# End:
# vim: set filetype=php noet sw=4 ts=4 fdm=marker:
# vim<600: noet sw=4 ts=4:
#
?>
