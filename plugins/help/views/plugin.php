<?php
/*
*  the plugin development documentation...
*/
//loading our plugins templates...
$templates = $dynamic->loadTemplates($plugin_name["help"],$data_con);

//content filling...
$output = array();
$output["content"] = '
<h3>Plugin Dev Howto: How to Develop a Plugin(website,tool...) for OPF</h3>
<p>
	developing plugins for OPF is a dead simple task, you just need to
	find a good name for your plugin, and then copy our sample plugin
	"help" and modify it to your hearts content.
</p>
<p>
	the typical pugin structure looks like this:
	
	<pre class="code">
	plugins\
		plugin_name\
			hooks\
				custom code, functions, structures, classes...
			images\
				your images of choice...
			templates\
				your template files...
			views\
				your webpage code...
			plugin.php
	</pre>
</p>
		<h3>Important files:</h3>
	<p>
		<strong>plugin.php:</strong><br />
		This file contains the base declaration that makes our plugin work, it has to exist, 
		and it has to contain a minimum of options, as shown below.
	
	<pre>
&lt;?php
/*
 central plugin loader
 this gets loaded before our
 plugin, here we can influence
 how our actual pages are named etc...
 */
//first we define our current name...
$plugin_name["your_plugin_name"] = "your_plugin_name";

//index output
$data = array(
	"webpage_name" => "plugin_name/views/webpage_file.php",
);

//add our stuff to output.
$plugin_out = array_merge($plugin_out,$data);
?&gt;
	</pre>
</p>
	<h3>IMPORTANT</h3>
	<p>DO NOT TRY TO RENAME ANYTHING ELSE THAN "your_plugin_name","webpage_name" and "webpage_file".<br />
	The variable names and the paths have to stay as they are. you can add as many webpages to
	your plugin as you want. to access your newly defined webpage, just call <strong>index.php?page=webpage_name</strong></p>
<p>
		<strong>templates/body.tmpl:</strong><br />
		This is the template that will be between your code and the user that views your
		website/tool/functionality. A minimum Template looks like the following, but you can
		add your own schema around the marker (thats the tag encapsulated within the 3 # symbols).
		
		<pre>
		###CONTENT###
		</pre>
		
		a somewhat more common definition of a body.tmpl file would be something like the following:
		
		<pre>
			&lt;html&gt;
				&lt;head&gt;
					&lt;title&gt;Title of your Website: Title of the webpage&lt;/title&gt;
				&lt;/head&gt;
				&lt;body&gt;
					###CONTENT###
				&lt;/body&gt;
			&lt;/html&gt;
		</pre>
</p>
<p>
	<strong>body.tmpl</strong> is only an example, basically every file you put into that directory becomes a template.
	if the file is named "<strong>sub_template.tmpl</strong>", "<strong>sub_template</strong>" becomes the templates name, 
	and you can access it via <strong>$templates["sub_template"]</strong> after youre done loading the templates (more on that below)
	Attention, this doesnt include executable code, for that you got the views directory;-).
</p>
<p>
	<strong>views/webpage_file.php</strong><br />
	This custom named file contains the actual program logic, a very simple example would look like this:
	
	<pre>
&lt;?php
/*
*  some comment describing this webpage...
*/
//loading our plugins templates...
$templates = $dynamic-&gt;loadTemplates($plugin_name[&quot;your_plugin_name&quot;],$data_con);

//you want some optional database custom handling?
//then uncomment the following two lines:
$out_includes = $dynamic-&gt;loadHooks(&quot;your_plugin_name&quot;);
foreach($out_includes AS $key =&gt; $value){ require_once($value); }

//filling our code or text into an array named like the content marker
$output = array();
$output[&quot;content&quot;] = &quot;hello world&quot;;

//and parse the output to the template
$output = $parser-&gt;fillMainTemplate($output,NULL,$templates[&quot;body&quot;]);

//and finally forward the output to the user...
$page = array();
$page[&quot;webpage_file&quot;] = $output;
?&gt;	
	</pre>
</p>
<h3>Optional Files:</h3>
<p>
	<strong>$dynamic-&gt;loadHooks($plugin_name[&quot;your_plugin_name&quot;]);</strong>
	actually does the trick and enables you to load additional code from your own external php files
	as defined in "hooks/" subdirectory, this helps alot, if youre dealing with reocurring code
	that is specific to your project. loading order is defined by name, so you can make it <strong>01_hook_db.php</strong>,
	<strong>02_hook_caching.php</strong>,... to have them ordered the way you want.
</p>
<h3>Further Infos</h3>
<p>
	As you might have guessed from the above examples, there is alot more to it than this short tutorial
	shows you. if you want to dig deeper into it, have a look at the included "<strong>default</strong>" plugin, this is
	a parser of tvrage.org (International Television Database) information, and it shows alot more of
	the potential of the OPF.<br /><br />
	<strong>You may also find further documentation on functions, the tvrage functionality and contact infos on
	the left side and in the footer of this help page.</strong>
</p>
<p>
	Enjoy!;-)
	Oliver
</p>
';

//and parse the output to the template
$output = $parser->fillMainTemplate($output,NULL,$templates["body"]);

//and we put the output out...
$page = array();
$page["plugin_dev"] = $parser->cleanup($output);
?>