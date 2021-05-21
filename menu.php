<?php 
    /*
	@fichier  menu.php							    		
    @auteur   (Touchard Washington le Mans)
    @date     16 Avril 2021
    @version  v1.5 - First release						
    @details  menu pour toutes les pages du site Aggregator
	*/
	
   
    require_once('api/Str.php');
    require_once('lang/lang.conf.php');
	
    use Aggregator\Support\Str;


    $racine = './';
	$nb = substr_count($_SERVER["REQUEST_URI"], '/');	
	if($nb > 2){
		$racine = '../';
		for($i = $nb ; $i > 3 ; $i--){
			$racine .= '../';
		}		
	}
	

	
	// Menu à droite
	function navbar_right(){
		
		global $lang;
		global $racine;
		
	    if (!isset($_SESSION['login']))
			echo "<a class='nav-link' href='{$racine}administration/' id='nav-sign-in'>{$lang['Sign_in']}</a>\n";
		else{
			echo "<li class='nav-item dropdown'>\n";
					
			echo "<a class='nav-link dropdown-toggle' href='#' id='navbardrop' data-toggle='dropdown'>\n";
			echo "<img alt='Avatar' height='30' id='nav-avatar-logo' src='{$racine}images/icon-avatar.svg' style='padding: 0 10px; '>\n";
			echo $_SESSION['login']; 
			echo "</a>\n";
			echo "<div class='dropdown-menu'>\n";
			if ($_SESSION['droits'] > 1){
				echo "<a class='dropdown-item' href='{$racine}administration/users'>{$lang['Users']}</a>\n";			
			}	
			else{
				echo "<a class='dropdown-item' href='{$racine}administration/users'>{$lang['My_Account']}</a>\n";						
			}	
			echo "<a class='dropdown-item' href='{$racine}administration/things'>{$lang['Things']}</a>\n";
			echo "<a class='dropdown-item' href='{$racine}administration/channels'>{$lang['Channels']}</a>\n";
			echo "<a class='dropdown-item' href='{$racine}administration/thingHTTPs'>{$lang['ThingHTTPs']}</a>\n";
			echo "<a class='dropdown-item' href='{$racine}administration/reacts'>{$lang['Reacts']}</a>\n";
			echo "<a class='dropdown-item' href='{$racine}administration/timeControls'>{$lang['timeControls']}</a>\n";
			echo "<a class='dropdown-item' href='{$racine}administration/scripts'>Scripts</a>\n";
			echo "<a class='dropdown-item' href='{$racine}administration/sms'>SMS</a>\n";
			echo "<a class='dropdown-item' href='{$racine}administration/signout' id='nav-sign-in'>{$lang['Sign_Out']}</a>\n";
			echo "</div>\n";
			echo "</li>\n";
		}
	}	

?>

	<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
		<a class="navbar-brand" href="<?= $racine ?>">
			<img alt="Beehive logo" height="30" id="nav-Beehive-logo" src="<?=  $racine ?>images/beehive_logo.png" style="padding: 0 8px; ">
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		
		
		
		<div class="collapse navbar-collapse" id="navbarsExampleDefault">
        
		<ul class="navbar-nav mr-auto">
			
			<li class="nav-item">
				<a class="nav-link" href="<?= $racine ?>accueil" id="nav-sign-in"><?= $lang['Browse_Sites'] ?></a>
			</li>					
			<li class="nav-item">
				<a class="nav-link" href="<?= $racine ?>webcam" id="nav-sign-in">Webcam</a>
			</li>	
        </ul>
		
		<!-- Menu à droite -->
		<ul class="navbar-nav navbar-right" style="margin-right: 78px;">
			<li class="nav-item">			
				<?php 	navbar_right()		?>
			</li>
		</ul>
        
		</div>
    </nav>
	
	
		
		