<?php
/**----------------------------------------------------------------------------------
    @fichier  cookieConsent.php							    		
    @auteur   Philippe SIMIER (Touchard Washington le Mans)
    @date     Avril 2020
    @version  v2.0 - Second release						
    @details  Consentement aux cookies pour le site web Aggregator 
-----------------------------------------------------------------------------------*/
   require_once('lang/lang.conf.php');
   
   if ( isset($_SESSION['cookieConsent']) && $_SESSION['cookieConsent'] === "0" ){
	// Affichage du formulaire d'acceptation des cookies
		$style = '';
	}else{
	    $style = "style = 'display: none;'";
	}
?>

<div id="cookieConsent" <?= $style ?>> 
 
	<?= $lang['cookieConsent'] ?><a href='support/<?= strtolower($_SESSION["language"]); ?>/privacy.php' target='_blank'> <?= $lang['privacy_policy'] ?>.</a><br/>
	<form class="form-horizontal" method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>" name="consent" >
		<button type="submit" class="btn btn-primary" value="Refuse" name="refuse" > <?= $lang['refuse'] ?></button>
		<button type="submit" class="btn btn-primary" value="OK" name="accept" > OK</button>
	</form>		
</div>




 