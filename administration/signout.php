<?php
	require_once('../definition.inc.php');
	session_start();

	
	// destruction complète de la session, efface également le cookie de session.
	// Note : cela détruira la session et pas seulement les données de session !
	
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000 , $params["path"], $params["domain"], $params["secure"], $params["httponly"] );
	$retour = setcookie("auth" , '', time() - 42000 , PATH , $_SERVER["HTTP_HOST"] , false, true); 
    

	// On supprime les variables de session
	session_unset();
	// Finalement, on détruit la session.
	session_destroy();
	header("Location: ../index.php");
?>