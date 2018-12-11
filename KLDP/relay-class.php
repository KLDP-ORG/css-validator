<?php

Class HTTPRelay {
	const UAGENT = 'OOPS HTTP Relay Wrapper 1.0';
	static public $error = '';

	function __construct () {
		$this->error = &self::$error;
	}

	function get ($to, $tmout = 60, $httphost = '', $post = null) {
		if ( ! trim ($to) )
			return '';

		if ( ! is_resource ($c = curl_init ()) )
			return '';

		# header information
		self::set_header ($header, 'Host', self::http_host ($to, $httphost));
		self::set_header ($header, 'Except', '', true);
		self::client_ip_set ($header);

		curl_setopt ($c, CURLOPT_URL, $to);
		curl_setopt ($c, CURLOPT_TIMEOUT, $tmout);
		curl_setopt ($c, CURLOPT_NOPROGRESS, 1);
		curl_setopt ($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($c, CURLOPT_USERAGENT, self::UAGENT);
		curl_setopt ($c, CURLOPT_HEADER, 0);
		curl_setopt ($c, CURLOPT_NOBODY, 0);
		curl_setopt ($c, CURLOPT_HTTPHEADER, $header);
		curl_setopt ($c, CURLOPT_FAILONERROR, 1);

		if ( $post && is_array ($post) ) {
			curl_setopt ($c, CURLOPT_POST, 1);
			curl_setopt ($c, CURLOPT_POSTFIELDS, $post);
		}

		$data = curl_exec ($c);

		if ( empty ($data) )
			self::$error = curl_error ($c);

		curl_close ($c);

		return trim ($data);
	}

	function relay ($to, $tmout = 60, $httphost = '') {
		if ( ! trim ($to) )
			return '';

		if ( ! is_resource ($c = curl_init ()) )
			return '';

		# basic information
		$uri = trim ($_SERVER['QUERY_STRING']);
		$lang = $_GET['_lang'] ? $_GET['lang'] : $_SERVER['HTTP_ACCEPT_LANGUAGE'];

		if ( $uri )
			$uri = '?' . $uri;

		# header information
		self::set_header ($header, 'Host', self::http_host ($to, $httphost));
		self::set_header ($header, 'Except', '', true);
		self::set_header ($header, 'Accept-Language', $lang);
		self::client_ip_set ($header);

		curl_setopt ($c, CURLOPT_URL, $to . $uri);
		curl_setopt ($c, CURLOPT_TIMEOUT, $tmout);
		curl_setopt ($c, CURLOPT_NOPROGRESS, 1);
		curl_setopt ($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($c, CURLOPT_USERAGENT, self::UAGENT);
		curl_setopt ($c, CURLOPT_HEADER, 0);
		curl_setopt ($c, CURLOPT_NOBODY, 0);
		curl_setopt ($c, CURLOPT_HTTPHEADER, $header);
		curl_setopt ($c, CURL_FAILONERROR, 1);

		self::relay_post ($c);

		$data = curl_exec ($c);

		if ( empty ($data) )
			self::$error = curl_error ($c);

		curl_close ($c);

		return trim ($data);
	}

	function set_header (&$h, $d, $v, $noval = false) {
		if ( ! trim ($d) )
			return;

		if ( $noval == false && ! trim ($v) )
			return;

		$d = @trim ($d);
		$v = @trim ($v);

		$h[] = $d . ': ' . $v;
	}

	function http_host ($t, $h) {
		if ( ! $h) {
			$src = array ('!^http://!', '!/.*!');
			$dsc = array ('', '');
			$h = preg_replace ($src, $dsc, $t);
		}

		return $h;
	}

	function relay_post (&$c) {
		if ( $_SERVER['REQUEST_METHOD'] != 'POST' )
			return;

		$post = array ();

		if ( is_array ($_FILES['file']) )
			$post['file'] = "@{$_FILES['file']['tmp_name']}";
		$post = array_merge ($post, $_POST);

		curl_setopt ($c, CURLOPT_POST, 1);
		curl_setopt ($c, CURLOPT_POSTFIELDS, $post);
	}

	function client_ip_set (&$h, $ip = null) {
		if ( $ip == null )
			$v = $_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
		else {
			if ( $_SERVER['HTTP_X_FORWARDED_FOR'] )
				$v = $ip . ', ' . $_SERVER['HTTP_X_FORWARDED_FOR'];
			else
				$v = $ip . ', ' . $_SERVER['REMOTE_ADDR'];
		}

		self::set_header ($h, 'X-Forwarded-For', $v);
	}
}
