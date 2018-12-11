<?php
$referer = $_SERVER['HTTP_REFERER'];
Header ("Location: http://css-validator.kldp.org/validator?uri=$referer");
?>
