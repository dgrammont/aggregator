<?php

$lang = array();

/* DataTables */
$lang['dataTables'] = "lang/dataTable.English.json";
$lang['any_entrie'] = "You haven't selected any entrie!";
$lang['several_entries'] = "You have selected several entries!";
$lang['alert'] = "Alert !";

/* Buttons */
$lang['add'] = "Add";
$lang['edit_settings'] = "Settings";
$lang['delete'] = "Delete";
$lang['Cancel'] = "Cancel";
$lang['Apply'] = "Apply";
$lang['Validate'] = "Valider";
$lang['close'] = "Close";
$lang['display'] = "Display";
$lang['refuse'] = "Refuse";

/* index & Menu */
$lang['Sign_in'] = "Sign in";
$lang['User login'] = "User login";
$lang['Password'] = "Password";
$lang['Users'] = "Users";
$lang['Browse_Sites'] = "Browse Sites";
$lang['Things'] = "Things";
$lang['Channels'] = "Channels";
$lang['ThingHTTPs'] = "ThingHTTPs";
$lang['Reacts'] = "Reacts";
$lang['Sign_Out'] = "Sign Out";
$lang['My_Account'] = "My Account";
$lang['docs_page'] = "Aggregator Docs for this page";
$lang['Data_visualisation'] = "Data visualisation";
$lang['Data_Analysis'] = "Data Analysis";

/* thingView */
$lang['hightchart'] = '{
    lang: {
        months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        weekdays: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        shortMonths: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        decimalPoint: ".",
        resetZoom: "Reset zoom",
        resetZoomTitle: "Reset zoom à  1:1",
        downloadPNG: "Download PNG image",
        downloadJPEG: "Download JPEG image",
        downloadPDF: "Download PDF document",
        downloadSVG: "Download SVG vector image",
        printChart: "Print chart",
        noData: "No data to display",
        loading: "Loading...",
	viewFullscreen : "View in full screen"
    }
}';

$lang['graphic'] = "Graphic";
$lang['hide_all'] = "Hide All";
$lang['more_historical_data'] = "More Historical Data";
$lang['setting filter'] = "Setting filter";
$lang['simple_moving_average'] = "Simple Moving Average";
$lang['exponential_moving_average'] = "Exponential Moving Average";
$lang['period'] = "Period";
$lang['smoothed'] = "smoothed";
$lang['xDateFormat'] = "%A, %b %e, %H:%M";

/* Users */
$lang['Change_Password'] = "Change Password";
$lang['Change_Time_Zone'] = "Change Time Zone";
$lang['New_API_Key'] = "New API Key";
$lang['Suspending'] = "Suspending";
$lang['Failed_login'] = "Failed login";
$lang['Failed_logins'] = "Failed Logins";

$lang['user'] = "User";
$lang['Users_suspending'] = "Users suspending";
$lang['API_Key'] = "API_Key";
$lang['login'] = "Login";
$lang['last_sign_in'] = "Last Sign In";
$lang['count'] = "Count";
$lang['language'] = "language";
$lang['rights'] = "Rights";
$lang['tel_number'] = "Tel Number";
$lang['delay_SMS'] = "SMS send interval limit";
$lang['confirm_password'] = "Confirm Password";
$lang['confirm_password_not_match'] = "Password and Confirm Password not match";

/* Time Zone */
$lang['Time_Zone'] = "Time Zone";

/* thing */
$lang['thing'] = "Thing";
$lang['elevation'] = "Elevation";
$lang['class'] = "Category";
$lang['classes'] = array('ruche' => "beehive", 'objet' => 'thing', 'weather' => 'weather station');
$lang['sel_status'] = array('private' => "private", 'public' => "public");

/* User formulaire */
$lang['sel_language'] = array('FR' => "French", 'EN' => "English");
$lang['sel_rights'] = array(1 => "User", 2 => "administrator");

/* things */
$lang['things'] = "Things";
$lang['access'] = "Access";
$lang['tag'] = "Tag";
$lang['author'] = "Author";
$lang['Ip_address'] = "IP Address";

/* channels */
$lang['channel'] = "Channel";
$lang['channels'] = "Channels";
$lang['write_API_Key'] = "Write API Key";
$lang['last_write_entry'] = "Date last entry";
$lang['last_entry_id'] = "Nb of values";
$lang['generate_New_API_Key'] = "Generate New API Key";
$lang['view_last_values'] = "View last values";
$lang['download_CSV'] = "Download CSV";
$lang['clear_all_feed'] = "Clear all feed";

/* Channel */
$lang['field'] = "Field";
$lang['status'] = "status";
$lang['last_write_at'] = "Last write at";

/* thingHTTPs */
$lang['created'] = "Created";
$lang['method'] = "Method";
$lang['send'] = "Send";
$lang['thingHTTP'] = "Thing HTTP";

/* SMS */
$lang['read'] = "Read";
$lang['write'] = "Write";
$lang['date_of_issue'] = "Date of issue";
$lang['date_of_receipt'] = "Date of receipt";
$lang['to'] = "To";
$lang['from'] = "From";
$lang['sent'] = "Sent";
$lang['received'] = "Received";
$lang['clear'] = "Clear all";

/* Reacts */
$lang['user'] = "User";
$lang['name'] = "Name";
$lang['channel_to_check'] = "Channel to check";
$lang['Choose_your_channel'] = "Choose your channel";
$lang['field_check'] = "Field to check";
$lang['Choose_your_field'] = "Choose your field";
$lang['condition'] = "Condition";
$lang['action'] = "Action perform";
$lang['Has_not_been_updated_for'] = "Has not been updated for";

/* TimeControl */
$lang['timeControl'] = "Time Control";
$lang['timeControls'] = "Time Controls";
$lang['month'] = "month";
$lang['dayWeek'] = "day Week";
$lang['dayMonth'] = "day month";
$lang['hour'] = "hour";
$lang['actionable_type'] = "actionable type";
$lang['sel_actionable_type'] = array('' => 'Choose your action',
    'thinghttps' => "ThingHTTP",
    'scripts' => "Script"
);


/* webcam */
$lang['download_picture'] = "Download picture";

/* react formulaire */
$lang['select_react_type'] = array('0' => 'Run action only the first time the condition is met',
    '1' => 'Run action each time condition is met');

$lang['select_channel_id'] = "Choose a Channel";

$lang['select_actionable_type'] = array('' => 'Choose your action',
    'thingHTTP' => "ThingHTTP",
    'email' => "Send a email");

$lang['select_interval'] = array('on_insertion' => "On data insertion",
    '10' => "Every 10 minutes",
    '30' => "Every 30 minutes",
    '60' => "Every 60 minutes");

$lang['select_condition'] = array('gt' => 'is greater than',
    'gte' => 'is greater or equal to',
    'lt' => 'is less than',
    'lte' => 'is less than or equal',
    'eq' => 'is equal to',
    'neq' => 'is not equal');

$lang['select_react_type'] = array('0' => 'Run action only the first time the condition is met',
    '1' => 'Run action each time condition is met');

//------------Aide pour les formulaires ---------------//
$lang['react_aide'] = "<h3>React Settings</h3>
<ul>
	<li>React Name: Enter a unique name for your React.</li>
	<li>Test Frequency: Choose whether to test your condition every time data enters the channel or on a periodic basis.</li>
	<li>Condition: Select a channel, a field and the condition for your React.</li>
	<li>Action: Select ThingHTTP, Send a SMS, Send a email to run when the condition is met.</li>
	<li>Options: Select when the React runs.</li>
</ul>";

$lang['user_aide'] = "<h3>User Settings</h3>
<ul>
	<li><b>Login</b>: Enter a unique login for your user.</li>
	<li><b>API Key</b>: Auto generated API key for the user.</li>
	<li><b>Quota</b>: Enter the quota of daily SMS</li>			
	<li><b>SMS Send Interval Limit</b>: Enter the interval limit in seconds between two SMS transmissions </li>					
</ul>";

$lang['time_zone_aide'] = "<h3>Time Zone Settings</h3>
<ul>
	<li>Time Zone is used when displaying data in your charts, and when scheduling your aggregator apps.</li>
</ul>";

$lang['channel_aide'] = "<h3>Channel Settings</h3>
<ul>
	<li>Channel Name: Enter a unique name for the channel.</li>
	<li>Description: Enter a description of the channel.</li>
	<li>Field#: enter a field name. Each  channel can have up to 8 fields.</li>	
</ul>";

$lang['thingHTTP_aide'] = "<h3>ThingHTTP Settings</h3>
<ul>
	<li><b>Name</b>: Enter a unique name for your ThingHTTP request.</li>
	<li><b>API Key</b>: Auto generated API key for the ThingHTTP request.</li>
	<li><b>URL</b>: Enter the address of the website you are requesting data from or writing data to starting with either http:// or https://.</li>
	<li><b>Auth Username</b>: If your URL requires authentication, enter the username for authentication to access private channels or websites.</li>
	<li><b>Auth Password</b>: If your URL requires authentication, enter the password for authentication to access private channels or websites.</li>
	<li><b>Method</b>: Select the HTTP method required to access the URL.</li>
	<li><b>Content Type</b>: Enter the MIME or form type of the request content. For example, application/x-www-form-urlencoded.</li>
	<li><b>HTTP Version</b>: Select the version of HTTP on your server.</li>
	<li><b>Host</b>: If your ThingHTTP request requires a host address, enter the domain name here. For example, api.aggregate.com.</li>
	<li><b>Headers</b>: If your ThingHTTP request requires custom headers, enter the information here. You must specify the name of the header and a value.</li>
	<li><b>Body</b>: Enter the message you want to include in your request.</li>
	<li><b>Parse</b>: If you want to parse the response, enter the exact string to look for in the response data.</li>
</ul>
";

$lang['cookieConsent'] = "We would like to use cookies to better understand your use of this website. More information about this and your rights as a user can be found in our";
$lang['privacy_policy'] = "privacy policy";

$lang['script_aide'] = "<h3>Script Analysis</h3>
<p>Explore data collected in a channel or scraped from a website</p>
<ul>
	<li>Find and remove bad data</li>
	<li>Convert data to different units</li>
	<li>Calculate new data</li>
	<li>Build data models</li>
</ul>
<p>After analysis, you can write data to the channel or publish it to share your results.</p>";

$lang['time_control_aide'] = "<h3>Options des tâches planifiées</h3>
                        <ul>
                            <li>Nom de la tâche planifiée : Saisissez un nom unique pour votre tâche planifiée.</li>
                            <li>Minute : de 00 à 59 ou * pour toutes les minutes </li>
                            <li>Heure : de 0 à 23 ou * pour toutes les heures </li>
                            <li>Jour du mois : 1 à 31 ou * pour tous les jours du mois </li>
                            <li>Mois : de 1 à 12 ou * pour tous les mois </li>
                            <li>Jour de la semaine : de 0 à 6 (Dimanche = 0), tous les jours de la semaine si *</li>
                            <li>Type d'action : Sélectionnez Requête HTTP ou script à exécuter </li>
                            <li>Option : Sélectionnez le script à effectuer.</li>
                        </ul>";
?>