<?php
/*
*  the plugin development documentation...
*/
//loading our plugins templates...
$templates = $dynamic->loadTemplates($plugin_name["help"],$data_con);

//content filling...
$output = array();
$output["content"] = '
<h3>Sources Dev Howto: Developing alternate Sources</h3>

<p><strong>What is a Source?</strong></p>

<p>
	A source is anything you want to grab data from.
	might it be an API Server,an RSS Feed, some Weathercam Data, Wikipedia Data, Amazon Services,
	EPG Data, Racing Results, Latest Forum Data, Documents in the cloud
	or on your local server...
	you name it.
</p>

<p>
	A good example for a source is the tvrage.org XML Interface,
	You can look on the Sample tvrage parser under:<br /><br />
	
	<strong>libs/sources/class.tvrage.php</strong>
</p>
<p>
	To add your own Source, copy that file and rename it. change
	all info you want to change within it,Then add it to the "<strong>conf/config.php</strong>", you
	may also overwrite the values of that global configuration file
	locally, as shown in the head of "<strong>class.tvrage.php</strong>".
</p>
<p>
	OPF offers already built in functionality to access remote and local
	sources securely and efficiently, you can find those in the file 
	"<strong>libs/class.parser.php</strong>". if you want to add your own
	functionality or extend existing functionality, you can do that i.e. via
	the so called "<strong>Hooks</strong>" functionality as explained right<br /><br />
</p>
<h3><a href="?page=plugin_dev">here</a></h3>
';

//and parse the output to the template
$output = $parser->fillMainTemplate($output,NULL,$templates["body"]);

//and we put the output out...
$page = array();
$page["source_dev"] = $parser->cleanup($output);
?>