<?php

Autoloader::add_core_namespace('Facebook');

require_once __DIR__ . '/Facebook/autoload.php';

Autoloader::add_classes(array(
	/**
	 * Facebook classes.
	 */


	'Facebook\\Facebook'									 => __DIR__.'/Facebook/Facebook.php',

));
