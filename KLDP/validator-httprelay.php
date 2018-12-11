<?php
require_once 'HTTPRelay.php';
require_once 'KSC5601.php';

set_error_handler('myException::myErrorHandler');

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

try {
	$http = new HTTPRelay;
	$http->posttype = 'form-data';
	$buf  = $http->relay (
		'http://css-validator.kldp.org:8080/css-validator/validator',
		10,
		'css-validator.kldp.org'
	);

	if ( $buf === false ) {
		throw new myException ($http->error, E_USER_ERROR);
	}

	$buf = preg_replace ("!(</html>).*!s", '\\1', $buf);
	echo trim ($buf) ."\n";
} catch ( myException $e ) {
	$buf = file_get_contents ('../common/error.tmeplate');

	$src = array ('/@ERROR_MSG@/', '/@ERROR_TRACE@/');
	$dst = array ($e->Message (), print_r ($e->TraceAsArray (), true));
	echo preg_replace ($src, $dst, $buf);
}

#
# Local variables:
# tab-width: 4
# c-basic-offset: 4
# End:
# vim: set filetype=php noet sw=4 ts=4 fdm=marker:
# vim<600: noet sw=4 ts=4:
#
?>
