<?php session_start(); 
	use Aggregator\Support\Api;
	
	// Si le formulaire cookieConsent est accepté 
	// On enregistre le consentement de l'utilisateur dans la base
	if( !empty($_POST['accept'])){
		
		require_once('definition.inc.php');
		require_once('api/Api.php');
		
		$bdd = Api::connexionBD(BASE);
		
		$sql = sprintf("UPDATE `data`.`users` SET `cookieConsent`= 1 WHERE `users`.`id` = %s;"
							  , $_SESSION['id']);

		$bdd->exec($sql);
		$_SESSION['cookieConsent'] = "1";
			
	}
	
	// Si le formulaire cookieConsent est refusé destruction de la session
	if( !empty($_POST['refuse'])){

		// efface également le cookie de session.
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"] );
		}
        unset($_SESSION['login']);
		header("Location: index.php");
		return;		
	}
?>