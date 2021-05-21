<?php session_start(); 

require_once('./definition.inc.php');
require_once('./api/Api.php');
require_once('./api/Str.php');
require_once('./lang/lang.conf.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;

// connexion à la base
if (isset($_SESSION['time_zone']))
	$bdd = Api::connexionBD(BASE, $_SESSION['time_zone']);
else
	$bdd = Api::connexionBD(BASE);

$thing_id  = Api::obtenir("id", FILTER_VALIDATE_INT);
$year      = Api::verifier("year", FILTER_VALIDATE_INT);


function ajouterArticle($thing_id){
		if(isset($_SESSION['login'])){
			echo "<div class=\"alert alert-secondary\" style= \" border-color: gray; border-style: dashed; border-width: medium; border-radius: 8px; margin-top: 10px;\">\n";
			echo "<p><a href=\"./administration/blog?thingId={$thing_id}\">Que voulez-vous dire aujourd'hui ?</a></p>\n";
			echo "</div>\n";
		}
}

function ajouterOutils($comment){
	if(isset($_SESSION['login'])){
		echo "<div class=\"outils\">";
		if ( $comment->status == "private"){
			echo "    <p><span id=\"{$comment->comment_id}\" class=\"update badge badge-warning \">{$comment->status}</span>";
		    echo "       <span id=\"{$comment->comment_id}\" class=\"delete badge  badge-danger\">X</span></p>\n";
		}
		else{
			echo "    <p><span id=\"{$comment->comment_id}\" class=\"update badge badge-success \">{$comment->status}</span>";
		    echo "       <span id=\"{$comment->comment_id}\" class=\"delete badge  badge-danger\">X</span></p>\n";
		}	
		echo "</div>";
	}
}

function afficherBlog($thing_id, $year){
	global $bdd;

	ajouterArticle($thing_id);
	try {
				
		$sql  = "SELECT * FROM `vue_blogs` WHERE `things_id` = {$thing_id} ";
		if(!isset($_SESSION['login'])) $sql .= " AND `status` = \"public\" ";
		if( $year != NULL)               $sql .= " AND YEAR( `visitDate` ) = {$year} ";
		$sql .= " ORDER BY `vue_blogs`.`visitDate` DESC";
		if( $year == NULL)   $sql .= " LIMIT 10";
		
		$stmt = $bdd->query($sql);

		while ($comment = $stmt->fetchObject()) {

			setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
			$date = strftime("%A %d %B %Y à %H:%M", strtotime( $comment->visitDate ));
			if ( !isset($_SESSION['time_zone'])) $date .= ' (UTC)';
			
			echo "<div class=\"blog-post popin\">\n";
			ajouterOutils($comment);
			echo "    <h2 class=\"blog-post-title\">{$comment->title}</h2>\n";
			echo "    <p class=\"blog-post-meta\">{$date} par {$comment->login} ";
			
			echo "    <p>{$comment->keyWord}</p><hr>\n";
			echo "    <p>{$comment->comment} </p>\n";
			echo "</div>\n";
		}
	} catch (\PDOException $ex) {
		echo($ex->getMessage());
	}
}

function afficherArchive($thing_id){
    global $bdd;
	
	$sql = "SELECT `name` FROM `things` WHERE `id` = {$thing_id}";
	$stmt = $bdd->query($sql);
	$thing = $stmt->fetchObject();
	
	echo "<div class=\"popin\" >\n";
	echo "    <h4>{$thing->name}</h4>\n";
	echo "    <ul class=\"file-tree file-list \">\n";
	try{
		$sql  = "SELECT count(*) as nb, YEAR(`visitDate`) as year FROM `vue_blogs` where `things_id` = {$thing_id} GROUP BY year order by year DESC";
		
		$stmt = $bdd->query($sql);
		
		while ($comment = $stmt->fetchObject())
		{
			echo "		<li  class='com'><a href=\"blogs?id={$thing_id}&year={$comment->year}\">{$comment->year}</a>\n";
			echo "      <span class=\"badge badge-pill badge-light\">{$comment->nb}</span>\n";
			echo "      </li>\n";		
		}
	}catch (\PDOException $ex) {
		echo($ex->getMessage());
	}
	echo '   </ul>';
	echo '</div>';		
}	
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Blogs - Aggregator</title>
        <!-- Bootstrap CSS version 4.1.1 -->
        <link rel="stylesheet" href="./css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/ruche.css" />
		<link rel="stylesheet" href="./css/jquery-confirm.min.css" />
		
        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="./scripts/popper.min.js"></script>
        <script src="./scripts/bootstrap.min.js"></script> 
		<script src="./scripts/jquery-confirm.min.js"></script>		
		
		
        <style>
			.outils {
				position:relative;
				float:right;
				cursor: pointer;
				font-size: 16px;
			}
			.update:before {
				content: "\f044";
				font-family: FontAwesome;
				text-decoration: inherit;
				color: #000;
				font-size: 20px;
				position: absolute;
				top: 5px;
				left: -25px;
			}
			
		</style>
        <script>
		   		
            $(document).ready(function () {
				
				let myOffset = new Date().getTimezoneOffset();
				console.log('myOffset ', myOffset);
				
				$('.update').on('click', function(e){
					console.log('clicked', this);
					let id = $(this).attr('id');
					let url = "administration/blog?id=" + id;
					console.log('url ', url);
					window.location.assign(url);
				}); 
				
				$('.delete').on('click', function(e){
					console.log('clicked', this);
					let id = $(this).attr('id');
					
					
					$.confirm({
						theme: 'bootstrap',
						title: 'Confirm!',
						content: 'Confirmez-vous la suppression du post ?',
						buttons: {
							confirm: {
								text: 'Confirmation', 
								btnClass: 'btn-blue', 
								action: function () {
								    console.log('suppression de id : ', id);
									
									$.post(
										'administration/deleteBlog.php', 
										{
											id : id 													
										},

										function(data){ 
											if(data == 'Success'){
												document.location.reload();
											}else{
												$.alert({
													theme: 'bootstrap',
													title: 'Alert!',
													content: "Oups on'a un problème !"
												});
											}	
										},

										'text' 
									);					
								}
							},
					 		cancel: {
								text: 'Annuler', // text for button
								action: function () {}
							}
						}
					});
					
				}); 
                
            });
        </script>

    </head>

    <body>

        <?php require_once 'menu.php'; ?>

        <div class="container" style="padding-top: 75px;" >
            <div style="min-height : 500px">
			    <div class="row">
					<div class="col-md-3 col-sm-12 col-xs-12">
						<?php afficherArchive($thing_id) ?>
					</div>
					<div class="col-md-9 col-sm-12 col-12">
						<?php afficherBlog($thing_id, $year) ?>
					</div>
				</div>
            </div>
            <?php require_once 'piedDePage.php'; ?>
        </div>
    </body>
</html>	
