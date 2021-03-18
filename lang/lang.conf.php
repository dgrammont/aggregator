<?php

    use Aggregator\Support\Str;
	
	$racine = './';
	$repertoire = array('administration' , 'support' , 'api' );  // les répertoires de premier niveau du site
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

	if(isset($_SESSION["language"]) and $_SESSION["language"] !== ''){
		$langue = strtolower($_SESSION["language"]);
	}else{
		$langue = autoSelectLanguage(array('fr','en'), 'en');
	}
	
	$lang_file = 'lang.' . strtoupper($langue) . '.php';
 	include_once "{$racine}lang/{$lang_file}";

	
	
	/**
	 * Détection automatique de la langue du navigateur
	 *
	 * Les codes langues du tableau $aLanguages doivent obligatoirement être sur 2 caractères
	 *
	 * Utilisation : $langue = autoSelectLanguage(array('fr','en','es','it','de','cn'), 'en')
	 *
	 * @param array $aLanguages Tableau 1D des langues du site disponibles (ex: array('fr','en','es','it','de','cn')).
	 * @param string $sDefault Langue à choisir par défaut si aucune n'est trouvée
	 * @return string La langue du navigateur ou bien la langue par défaut
	 */
	function autoSelectLanguage($aLanguages, $sDefault = 'en') {
	    if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		    $aBrowserLanguages = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
		    foreach($aBrowserLanguages as $sBrowserLanguage) {
		        $sLang = strtolower(substr($sBrowserLanguage,0,2));
		        if(in_array($sLang, $aLanguages)) {
			        return $sLang;
		        }
		    }
	    }
	    return $sDefault;
	}

?>