<!----------------------------------------------------------------------------------
    @fichier  administration/support/en/reacts.php							    		
    @auteur   Philippe SIMIER (Touchard Washington le Mans)
    @date     Avril 2020
    @version  v1.0 - First release						
    @details  support pour la page administration/reacts.php
------------------------------------------------------------------------------------>

<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Support - REACT</title>
    <!-- Bootstrap CSS version 4.1.1 -->
    <link rel="stylesheet" href="../../../css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="../../../scripts/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="../../../css/bootstrap.min.css" >
    <link rel="stylesheet" href="../../../css/ruche.css" />
</head>

<body>
	
	<?php require_once '../../../menu.php'; ?>
	
	<div class="container" >
		<div style="min-height : 500px">	
			<div class="row" style="background-color:white; padding-top: 65px; ">
				<div class="col-lg-12">
					<h4>Overview of reacts (react to events)</h4>
					<p>Respond when a channel's data meets certain conditions</p></br>
					<p>Reacts work with ThingHTTP applications, to perform actions when the data of a channel meets a certain condition.
                       You can configure a react to notify an event by SMS or Mail.
                       For example, when the weight of a hive suddenly drops, ask ThingHTTP to send an SMS containing a description of the event.</p></br>
					<h4>Read archived reacts</h4>
					<p>Choose Reacts in the user menu to display the table of archived reacts.</p>
					<p>For each of the reacts the following information is displayed. <ul>
					<li> The login of the owner user </li>
					<li> The name of the react </li>
					<li> The designation of the channel to be checked </li>
					<li> The logical condition for triggering the action. This condition is made up of three parts :
						<ul> 
						<li>A canal field </li>
						<li>A digital comparison operator</li>
						<li>A numerical value</li>
						</ul>
					<li> The designation of the thingHtttp application (this application triggers the service requested via an http request)
					</ul>
					
					<h4>Delete Reacts </h4>
					<p> You can delete one or more archived Reacts. Check the Reacts to delete then click on the Delete button. A confirmation window opens.
                          Validate the action</p></br>
					<h4>Edit a React </h4>
					<p>Check the react to modify then click on the setting button. A form opens.</p></br>
					
				</div>
			</div>
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
	
	
</body>	