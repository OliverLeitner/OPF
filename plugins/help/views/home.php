<?php
/* our startpage */
//and the output part.
//call is ?page=$arraykey (in our case ?page=home)

//loading our plugins templates...
$templates = $dynamic->loadTemplates($plugin_name["help"]);

//this is the homepage of our project, some simple
//description with a knowledgebase in the background.
$output = array();
$output["content"] = '
			<h3>So, what is this?</h3>
			<p>
				This is a fully featured and enhanceable framework.
				It will keep your coding simple, you dont need to reinvent the
				wheel, and it comes with base functionality that is enough for 99%
				of your cases.
			</p>
			<p>
				In its current configuration it parses database information
				into an RSS Feed, in my case that information is tvrage.org data.
			</p>
			<h3>Features:</h3>
			<p>
				The functionality is easy to enhance by a very simple yet
				powerful system called "plugins" for feature enhancements
				or "sources" for adding support for different database types
				you are not bound to the current config and may create your own views
				with your own templates and your own databases/code/hooks.
			</p>
			<h3>The Basic requirements are as follows:</h3>
			<ul>
				<li>PHP Version 5.3 and upward</li>
				<li>A Webserver capable of handling your requests</li>
			</ul>
			<h3>If you want to use the included tvrage.org feeding functionality:</h3>
			<ul>
				<li>A linux machine</li>
				<li>A Database (currently only mysql source is coded, can easily be enhanced by you too, find instructions below)</li>
			</ul>
			<h3>Find what you need right within the project root:</h3>
			<ul>
			<li>Some basic administrational scripts go under "<strong>admin</strong>"</li>
			<li>In "<strong>jobs/tvrage/</strong>" you find the scripts that form the fetcher for the tvrage.org data</li>
			easily starteable via the bash script "<strong>runme.sh</strong>" in the same directory.</li>	
			</ul>
			<p>
				<strong>Further information and documentation can be found on the left side and the bottom of this page.</strong>
			</p>
			<p>Enjoy!;-)<br />
			Oliver</p>
			';


//and parse the output to the template
$output = $parser->fillMainTemplate($output,$templates["body"]);

//and we put the output out...
$page = array();
$page["home"] = $parser->cleanup($output);
?>
