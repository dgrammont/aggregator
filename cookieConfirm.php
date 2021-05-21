<?php session_start(); 

    require_once('definition.inc.php');
	require_once('api/Api.php');
	require_once('api/Str.php');
    
	use Aggregator\Support\Str;
	use Aggregator\Support\Api;

		
	$bdd = Api::connexionBD(BASE);
	
	// si pas de session et pas de cookie lang on determine la langue de l'utilisteur à partir de HTTP_ACCEPT_LANGUAGE 
	if( !isset($_SESSION['language']) && !isset($_COOKIE['lang']) ){
		$lang = Str::getLanguage($_SERVER['HTTP_ACCEPT_LANGUAGE']); 
		$retour = setcookie("lang", $lang , time() + 3600 * 24 * 365 , PATH , $_SERVER["HTTP_HOST"] , false, true);
		$_SESSION['language'] = $lang;
    }
	
	// si un cookie lang est present et pas de session
	if( isset($_COOKIE['lang']) && !isset($_SESSION['language'])){
		$_SESSION['language'] = $_COOKIE['lang'];
	}
	
	// si un cookie auth est présent et aucun utilisateur connecté on ouvre une session
	if( isset($_COOKIE['auth']) && !isset($_SESSION['id']) ){
		$auth = explode('-', $_COOKIE['auth']);
		$sql = "SELECT * FROM `users` WHERE `id`={$auth[0]}";
		$stmt = $bdd->query($sql);
		$utilisateur =  $stmt->fetchObject();
		$key = sha1( $utilisateur->login . $utilisateur->User_API_Key . $_SERVER['REMOTE_ADDR']);
		if ($key === $auth[1]){
			// Le cookie est valide création de la session et prolongation du cookie
			$auth = $utilisateur->id . '-'. sha1( $utilisateur->login . $utilisateur->User_API_Key . $_SERVER['REMOTE_ADDR']);			
			$retour = setcookie("auth", $auth , time() + 3600 * 24 * NBDAY , PATH , $_SERVER["HTTP_HOST"] , false, true);
			
			$_SESSION['last_access']   = time();
			$_SESSION['ipaddr']		   = $_SERVER['REMOTE_ADDR'];
			$_SESSION['login'] 		   = $utilisateur->login;
			$_SESSION['id']			   = $utilisateur->id;
			$_SESSION['User_API_Key']  = $utilisateur->User_API_Key;
			$_SESSION['time_zone']     = $utilisateur->time_zone;
			$_SESSION['droits'] 	   = $utilisateur->droits;
			$_SESSION['language'] 	   = $utilisateur->language;
			$_SESSION['cookieConsent'] = $utilisateur->cookieConsent;						
		}
		else{
			// Le cookie est invalide on le détruit
			$retour = setcookie("auth" , '', time() - 42000 , PATH , $_SERVER["HTTP_HOST"] , false, true); 
		}			
	}
	
		
	
	// Si le formulaire cookieConsent est accepté 
	// On enregistre le consentement de l'utilisateur dans la base
	if( !empty($_POST['accept'])){
	
		$sql = sprintf("UPDATE `data`.`users` SET `cookieConsent`= 1 WHERE `users`.`id` = %s;"
							  , $_SESSION['id']);

		$bdd->exec($sql);
		$_SESSION['cookieConsent'] = "1";
	}
	
	// Si le formulaire cookieConsent est refusé destruction de la session et des cookies
	if( !empty($_POST['refuse'])){

		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000 , $params["path"], $params["domain"], $params["secure"], $params["httponly"] );
		setcookie("auth" , '', time() - 42000 , PATH , $_SERVER["HTTP_HOST"] , false, true); 
	
        unset($_SESSION['login']);
		header("Location: index.php");
		return;		
	}
	
	
?>