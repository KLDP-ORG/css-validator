<?php
$target = array (
	'css' => 'CSS',
	'css10' => 'CSS 1',
	'css20' => 'CSS 2',
	'css30' => 'CSS 3',
	'html20' => 'HTML 2.0',
	'html30' => 'HTML 3.0',
	'html32' => 'HTML 3.2',
	'html40' => 'HTML 4.0',
	'html401' => 'HTML 4.01',
	'xhtml10' => 'XHTML 1.0',
	'xhtml11' => 'XHTML 1.1',
	'xml10' => 'XML 1.0'
);

$color = array (
	'black' => '#000000',
	'blue' => '#6666FF',
	'gray' => '#898e79',
	'green' => '#009900',
	'orange' => '#ff6600',
	'red' => '#ff0000'
);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<title>http://validaotr.kldp.org/images/</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body {
	margin: 0 0 0 0;
	font-family: tahoma, sans-serif;
	background-color: #fff;
	color: #000;
}

td {
	font-size: 11px;
}

img {
	border: 0px none #000;
	vertical-align: middle;
}

h1 {
	font-family: tahoma, sans-serif;
	text-align: center;
}

#table {
	border: 0px dotted #000000;
	float: center;
}

.lists {
	font-family: tahoma, sans-serif;
	font-weight: bold;
	text-align: center;
	font-size: 11px;
}

.black {
	background-color: <?=$color['black']?>;
	color: #fff;
}
.blue {
	background-color: <?=$color['blue']?>;
	color: #fff;
}
.gray {
	background-color: <?=$color['gray']?>;
	color: #fff;
}
.green {
	background-color: <?=$color['green']?>;
	color: #fff;
}
.orange {
	background-color: <?=$color['orange']?>;
	color: #fff;
}
.red {
	background-color: <?=$color['red']?>;
	color: #fff;
}
</style>
</head>

<body>
<div id="title">
<h1>Various Color of HTML / CSS 80 * 15 Banners</h1>
</div>

<div id="table">
<table border="0" align="center">
<tr><td style="background-color: #fff; color: #000;">

<table border="0" cellspacing="2" cellpadding="3">
<tr class="lists">
<td>TARGET</td>
<td class="black">BLACK</td>
<td class="blue">BLUE</td>
<td class="gray">GRAY</td>
<td class="green">GREEN</td>
<td class="orange">ORANGE</td>
<td class="red">RED</td>
</tr>
<?php
foreach ( $target as $k => $v ) :
	echo "<tr>\n";
	echo "<td>$v</td>\n";
	foreach ( $color as $c => $vc ) :
		echo "<td style=\"vertical-align: middle;\"><img src=\"./$k-$c.png\" alt=\"$v\" width=\"80\" height=\"15\" /></td>\n";
	endforeach;
	echo "</tr>\n";
endforeach;
?>
</table>

</td></tr>
</table>
</div>
</body>
</html>
