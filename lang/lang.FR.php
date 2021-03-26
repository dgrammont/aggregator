<?php

$lang = array();

/* DataTables */
$lang['dataTables'] = "lang/dataTable.French.json";
$lang['any_entrie'] = "Vous n'avez sélectionné aucune entrée !";
$lang['several_entries'] = "Vous avez sélectionné plusieurs entrées !";
$lang['alert'] = "Alerte !";

/* Buttons */
$lang['add'] = "Ajouter";
$lang['edit_settings'] = "Modifier";
$lang['delete'] = "Supprimer";
$lang['Cancel'] = "Annuler";
$lang['Apply'] = "Appliquer";
$lang['Validate'] = "Valider";
$lang['close'] = "Fermer";
$lang['display'] = "Afficher";
$lang['refuse'] = "Refuser";

/* index & Menu*/
$lang['Sign_in'] = "Se connecter";
$lang['User login'] = "Identifiant";
$lang['Password'] = "Mot de Passe";
$lang['Users'] = "Utilisateurs";
$lang['Browse_Sites'] = "Parcourir";
$lang['Things'] = "Objets";
$lang['Channels'] = "Canaux";
$lang['ThingHTTPs'] = "Actions HTTP";
$lang['Reacts'] = "Déclencheurs";
$lang['Sign_Out'] = "Se déconnecter";
$lang['My_Account'] = "Mon compte";
$lang['docs_page'] = "Documentation Aggregator pour cette page";
$lang['Data_visualisation'] = "Visualisation des données";
$lang['Data_Analysis'] = "Analyse des données";
$lang['Sounds'] = "Enregistrements sonors";
$lang['Logbook'] = "Journal de bord";

/* thingView */
$lang['hightchart'] = '{
    lang: {
        months: ["Janvier ", "Février ", "Mars ", "Avril ", "Mai ", "Juin ", "Juillet ", "Août ", "Septembre ", "Octobre ", "Novembre ", "Décembre "],
        weekdays: ["Dimanche ", "Lundi ", "Mardi ", "Mercredi ", "Jeudi ", "Vendredi ", "Samedi "],
        shortMonths: ["Jan", "Fév", "Mar", "Avr", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"],
        decimalPoint: ",",
        resetZoom: "Reset zoom",
        resetZoomTitle: "Reset zoom à  1:1",
        downloadPNG: "Télécharger au format PNG image",
        downloadJPEG: "Télécharger au format JPEG image",
        downloadPDF: "Télécharger au format PDF document",
        downloadSVG: "Télécharger au format SVG image vectorielle",
        printChart: "Imprimer le graphique",
        noData: "Aucune donnée à  afficher",
        loading: "Chargement...",
		viewFullscreen : "Afficher en plein écran"
    }
}';

$lang['graphic'] = "Graphique";
$lang['hide_all'] = "Cacher tout";
$lang['more_historical_data'] = "Plus de données historiques";
$lang['setting filter'] = "Réglage du filtre";
$lang['simple_moving_average'] = "Moyenne Mobile Simple";
$lang['exponential_moving_average'] = "Moyenne Mobile Exponentielle";
$lang['period'] = "Periode";
$lang['smoothed'] = "lissé(e)";
$lang['xDateFormat'] = "%A %e %B à  %Hh%M";

/* Users */
$lang['Change_Password'] = "Mot de passe";
$lang['Change_Time_Zone'] = "Changer fuseau horaire";
$lang['New_API_Key'] = "Nouvelle clé API";
$lang['Suspending'] = "Suspendre";
$lang['Failed_login'] = "Échec de la connexion";
$lang['Failed_logins'] = "Échecs de la connexion";

$lang['user'] = "Utilisateur";
$lang['Users_suspending'] = "Utilisateurs suspendus";
$lang['login'] = "Identifiant";
$lang['API_Key'] = "Clé API";
$lang['last_sign_in'] = "Dernière connexion";
$lang['count'] = "Total";
$lang['language'] = "Langue";
$lang['rights'] = "Droits";
$lang['tel_number'] = "Téléphone";
$lang['delay_SMS'] = "Limite d'intervalle d'envoi SMS";
$lang['confirm_password'] = "Confirmez le mot de passe";
$lang['confirm_password_not_match'] = "Le mot de passe et la confirmation du mot de passe ne correspondent pas";

/* Time Zone */
$lang['Time_Zone'] = "Fuseau horaire";

/* thing */
$lang['thing'] = "Objet";
$lang['elevation'] = "Altitude";
$lang['class'] = "Catégorie";
$lang['classes'] = array('ruche' => "Ruche", 'objet' => 'Objet', 'weather' => 'Station météo');
$lang['sel_status'] = array('private' => "Privé", 'public' =>"Public" );

/* User formulaire */
$lang['sel_language'] = array('FR' => "Français", 'EN' => "Anglais" );
$lang['sel_rights'] = array(1 => "Utilisateur", 2 =>"Administrateur" );

/* things */
$lang['things'] = "Objets";
$lang['access'] = "Accès";
$lang['tag'] = "Etiquette";
$lang['author'] = "Créateur";
$lang['Ip_address'] = "Adresse IP";

/* channels */
$lang['channel'] = "Canal";
$lang['channels'] = "Canaux";
$lang['write_API_Key'] = "Clé API";
$lang['last_write_entry'] = "Date dernière entrée";
$lang['last_entry_id'] = "Nb Valeurs";
$lang['generate_New_API_Key'] = "Générer une nouvelle clé API";
$lang['view_last_values'] = "Afficher les dernières valeurs";
$lang['download_CSV'] = "Télécharger CSV";
$lang['clear_all_feed'] = "Effacer tout le flux";

/* Channel */
$lang['field'] = "Champ";
$lang['status'] = "Statut";
$lang['last_write_at'] = "Dernière écriture à"; 

/* thingHTTPs */
$lang['created'] = "Créé"; 
$lang['method']  = "Méthode";
$lang['send']    = "Envoyer";
$lang['thingHTTP'] = "Objet HTTP";

/* SMS */
$lang['read'] = "Lire";
$lang['write'] = "Ecrire";
$lang['date_of_issue'] = "Date d'émission";
$lang['date_of_receipt'] = "Date de reception";
$lang['to'] = "à";
$lang['from'] = "de";
$lang['sent'] = "Envoyés";
$lang['received'] = "Reçus";
$lang['clear'] = "Effacer tout";

/* Reacts */
$lang['user'] = "Utilisateur";
$lang['name'] = "Nom";
$lang['channel_to_check'] = "Canal à Vérifier";
$lang['Choose_your_channel'] = "Choisir votre canal";
$lang['field_check'] = "Champs à Vérifier";
$lang['Choose_your_field'] = "Choisir votre champ";
$lang['condition'] = "Condition";
$lang['action'] = "Action à Effectuer";
$lang['Has_not_been_updated_for'] = "N'a pas été mis à jour depuis";

/* TimeControl */
$lang['timeControl'] = "Tâche planifiée";
$lang['timeControls'] = "Tâches planifiées";
$lang['month'] = "mois";
$lang['dayWeek'] = "jour semaine";
$lang['dayMonth'] = "jour mois";
$lang['hour'] = "heure";
$lang['actionable_type'] = "type d'action";
$lang['sel_actionable_type'] = array('' => 'Choisissez une action', 
                                     'thinghttps' => "ThingHTTP",
									 'scripts' => "Script"
									  );

/* webcam */
$lang['download_picture'] = "Télécharger l'image";

/* react formulaire */
$lang['select_react_type'] = array('0' => "Uniquement la première fois que la condition est remplie",
                                   '1' => "Chaque fois que la condition est remplie");

$lang['select_channel_id'] = "Sélectionner un Canal";

$lang['select_actionable_type'] = array('' => 'Choisissez une action', 
                                        'thingHTTP' => "Requête HTTP",
										'email' => "Envoyez un Email" );

$lang['select_interval'] = array('on_insertion' => "Lors de l'insertion de Valeur",
                         '10' => "Toutes les 10 minutes",
                         '30' => "Toutes les 30 minutes",
                         '60' => "Toutes les 60 minutes" );

$lang['select_condition'] = array(	'gt' => "est supérieur à",
                                    'gte' => "est supérieur ou égal à",
                                    'lt' => "est inférieur à",
                                    'lte' => "est inférieur ou égal à",
                                    'eq' =>  "est égal à",
                                    'neq' => "est différent de" );

$lang['select_react_type'] = array ('0' => "Exécuter l'action uniquement la première fois que la condition est remplie",
                                    '1' => "Exécuter une action chaque fois que la condition est remplie");

//------------Aide pour les formulaires ---------------//
$lang['react_aide'] = "<h3>Options du déclencheur</h3>
<ul>
	<li>Nom du déclencheur : Saisissez un nom unique pour votre déclencheur.</li>
	<li>Fréquence de test : Choisissez de tester votre condition à chaque fois que des données entrent dans le canal ou périodiquement.</li>
	<li>Condition : Sélectionnez un canal, un champ et la condition de votre déclencheur.</li>
	<li>Action : Sélectionnez Requête HTTP, Envoyer un Email, à exécuter lorsque la condition est remplie.</li>
	<li>Option : Sélectionnez le moment où le déclencheur s'exécute.</li>
</ul>";

$lang['user_aide'] = "<h3>Options pour l'utilisateur</h3>
<ul>
	<li><b>Identifiant</b>: Saisissez un identifiant unique pour l'utilisateur.</li>
	<li><b>Clé API</b>: Clé API auto générée pour l'utilisateur.</li>
	<li><b>Quota</b>: Entrez le quota d'envoi de SMS quotidiens </li>			
	<li><b>Limite d'intervalle d'envoi SMS</b>: Entrez la limite d'intervalle de temps entre deux émissions consécutives de SMS </li>					
</ul>";

$lang['time_zone_aide'] = "<h3>Paramètres de fuseau horaire</h3>
<ul>
	<li>Le fuseau horaire est utilisé lors de l'affichage des données dans vos graphiques et lors de la planification de vos applications d'agrégateur.</li>
</ul>";

$lang['channel_aide'] = "<h3>Paramètres du canal</h3>
<ul>
	<li>Nom du canal: entrez un nom unique pour le canal.</li>
	<li>Description: entrez une description du canal.</li>
	<li>Canal #: entrez un nom de champ. Chaque canal peut avoir jusqu'à 8 champs.</li>
</ul>";
$lang['thingHTTP_aide'] = "<h3>Paramètres de la requète HTTP</h3>
<ul>
	<li><b>Nom</b>: entrez un nom unique pour votre requète HTTP.</li>
	<li><b>API Key</b>: clé API générée automatiquement pour la requète HTTP.</li>
	<li><b>URL</b>: saisissez l'adresse du site Web à partir duquel vous demandez ou écrivez des données en commençant par http: // ou https: //.</li>
	<li><b>Auth Username</b>: si votre URL nécessite une authentification, entrez le nom d'utilisateur pour accéder aux canaux ou sites Web privés.</li>
	<li><b>Auth Password</b>: si votre URL nécessite une authentification, entrez le mot de passe pour l'authentification pour accéder aux canaux ou sites Web privés.</li>
	<li><b>Method</b>: sélectionnez la méthode HTTP requise pour accéder à l'URL.</li>
	<li><b>Content Type</b>: entrez le MIME ou le type du formulaire pour le contenu de la demande. Par exemple, application/x-www-form-urlencoded.</li>
	<li><b>HTTP Version</b>: sélectionnez la version du protocol HTTP sur votre serveur.</li>
	<li><b>Host</b>: si votre demande ThingHTTP nécessite une adresse d'hôte, entrez le nom de domaine ici. Par exemple, api.aggregate.com.</li>
	<li><b>Headers</b>: si votre demande ThingHTTP nécessite des en-têtes personnalisés, entrez les informations ici. Vous devez spécifier le nom de l'en-tête et une valeur.</li>
	<li><b>Body</b>: saisissez le message que vous souhaitez inclure dans votre requète.</li>
	<li><b>Parse</b>: si vous souhaitez analyser la réponse, entrez la chaîne exacte à rechercher dans les données de la réponse.</li>
</ul>
";

$lang['cookieConsent'] = "Nous aimerions utiliser des cookies pour mieux comprendre votre utilisation de ce site Web. Vous trouverez plus d'informations à ce sujet et sur vos droits en tant qu'utilisateur dans notre"; 
$lang['privacy_policy'] = "politique de confidentialité";		


$lang['script_aide'] = "<h3>Script pour Analyse</h3>
<p>Explorer les données collectées dans un canal ou extraites d'un site Web</p>
<ul>
	<li>Trouvez et supprimez les mauvaises données</li>
	<li>Convertir les données en différentes unités</li>
	<li>Calculer de nouvelles données</li>
	<li>Construire des modèles de données</li>
</ul>
<p>Après analyse, vous pouvez écrire les données sur un canal ou les publier pour partager vos résultats.</p>";	

$lang['time_control_aide']="<h3>Options des tâches planifiées</h3>
                        <ul>
                            <li>Nom de la tâche planifiée : Saisissez un nom unique pour votre tâche planifiée.</li>
                            <li>Minute : de 00 à 59 ou * pour toutes les minutes </li>
                            <li>Heure : de 0 à 23 ou * pour toutes les heures </li>
                            <li>Jour du mois : 1 à 31 ou * pour tous les jours du mois </li>
                            <li>Mois : de 1 à 12 ou * pour tous les mois </li>
                            <li>Jour de la semaine : de 0 à 6 (Dimanche = 0),ou * pour tous les jours </li>
                            <li>Type d'action : Sélectionnez Requête HTTP ou script à exécuter.</li>
                            <li>Option : Sélectionnez le script à effectuer.</li>
                        </ul>";













 ?>