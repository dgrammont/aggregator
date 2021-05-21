<?php

    use Aggregator\Support\Str;
	
	$racine = './';
	$repertoire = array('administration' , 'support' , 'api', 'video' );  // les répertoires de premier niveau du site
	if  (Str::contains($_SERVER['PHP_SELF'], $repertoire)){
		$racine = '../';
	}
	$repertoire = array('administration/support',  'support/fr', 'support/en' );      // les répertoires de second niveau du site
    if  (Str::contains($_SERVER['PHP_SELF'], $repertoire)){
		$racine = '../../';
	}
	$repertoire = array('administration/support/fr', 'administration/support/en');      // les répertoires de troisième niveau du site
    if  (Str::contains($_SERVER['PHP_SELF'], $repertoire)){
		$racine = '../../../';
	}
	
    // Sélecteur de langue
	
	if(isset($_SESSION["language"]) && $_SESSION["language"] !== ''){
		$langue = $_SESSION["language"];
	}else{
		if( isset($_COOKIE['lang'])){
			$_SESSION['language'] = $_COOKIE['lang'];
			$langue = $_COOKIE['lang'];
		}
		else{
			$langue = Str::getLanguage($_SERVER['HTTP_ACCEPT_LANGUAGE']);
		}	
	}
		
	$lang_file = 'lang.' . strtoupper($langue) . '.php';
 	include_once "{$racine}lang/{$lang_file}";
	
?>