<?php
/*
*  the plugin development documentation...
*/
//loading our plugins templates...
$templates = $dynamic->loadTemplates($plugin_name["help"],$data_con);

//content filling...
$output = array();
$output["content"] = '
<h3>Database Drop-in Dev: Howto Enable *any* Database type</h3>
<p>
	OPF does support interaction with Databases, in the current
	version it comes with built in support for mysql(i) but it is
	easily expandeable to other databases.
</p>
<p>
	The idea behind it is, that you have a few common functions that you
	always use, when you are working with a database, those are SELECT, INSERT
	and UPDATE.<br /><br />
	Even if you work on multiple Tables, those functions dont ever seem to change.
</p>
<p>
	The sample mysql class can be found under "<strong>libs/db/class.mysqli.php</strong>",
	all existing database calls are done indirectly via "<strong>libs/db/class.db_core.php</strong>",
	furthermore, you can define which database type you want to use via the definition of 
	"<strong>define(&quot;DB_TYPE&quot;,&quot;name_of_your_type&quot;);</strong>" where name_of_your_type
	will be the middle name of the Drop-in Database class, i.e. in case of "<strong>class.mysqli.php</strong>"
	it is "mysqli". you may either define it globally via "<strong>conf/config.php</strong>" configuration file
	or within your own plugin.
</p>
<h3>Important:</h3>
<p>
	If you want to add your own Database support (postgresql, sqlite, oracle, mssql, handlersocket...),
	you will have to map your functions to the one deployed within "<strong>class.mysqli.php</strong>",
	the available functions at this point are the following:
</p>
<table>
	<tr><th>Function Name</th><th>Description</th></tr>
	<tr><td><strong>queryCountRows:</strong></td><td>Counts Rows within a Database Table</td></tr>
	<tr>
		<td class="sample">Example of Data Transmitted:</td>
		<td class="sample">
			<pre>
$db_con->queryCountRows("id","users");
			</pre>
		</td>
	</tr>
	<tr>
		<td><strong>querySingleRow:</strong></td><td>Grab the Data of a Single Table Row as unassociative Array</td>
	</tr>
	<tr>
		<td class="sample">Example of Data Transmitted:</td>
		<td class="sample">
			<pre>
$db_con->querySingleRow(
	"channels",	//FROM channels
	array("chanid,channame"),	//SELECT chanid,channame
	array("id" => " > 33"),		//WHERE id > 33
	array("channame" => "ABC"),	//AND channame LIKE ABC
	array("channame" => "NBC"), //OR channame LIKE NBC
);
			</pre>
		</td>
	</tr>
	<tr>
		<td><strong>queryFullData:</strong></td><td>Get an unassociative array of selected Data in a Table</td>
	</tr>
	<tr>
		<td class="sample">Example of Data Transmitted:</td>
		<td class="sample">
			<pre>
$db_con->queryFullData(
	"channels",	//FROM channels
	array("chanid,channame"),	//SELECT chanid,channame
	array("id" => " > 33"),		//WHERE id > 33
	array("channame" => "ABC"),	//AND channame LIKE ABC
	array("channame" => "NBC"), //OR channame LIKE NBC
	array("channame"),	//ORDER BY channame
	"ASC"	//ASC
);
			</pre>
		</td>
	</tr>
	<tr>
		<td><strong>queryMMData:</strong></td><td>Get an unassociative array of selected Data from multiple Tables</td>
	</tr>
	<tr>
		<td class="sample">Example of Data Transmitted:</td>
		<td class="sample">
			<pre>
$db_con->queryMMData(
	"channels",	//FROM episodes
	array("chanid,channame"),	//SELECT chanid,channame
	array(	//tables to join with the master table
	0 => array(
		"channels_mm_episodes" => "episodeid", //LEFT JOIN channels_mm_episodes ON
		"episodes" => "episodeid" //channels_mm_episodes.episodeid = episodeid
	) 
	),
	array("id" => " > 33"),		//WHERE id > 33
	array("channame" => "ABC"),	//AND channame LIKE ABC
	array("channame" => "NBC"), //OR channame LIKE NBC
	array("channame,episodename"),	//ORDER BY channame,episodename
	"ASC",	//ASC
	"episodename", //GROUP BY episodename
	array("limit" => 20,"offset" => 0)	//LIMIT 0,20
);
			</pre>
		</td>
	</tr>
	<tr>
		<td><strong>queryInsert:</strong></td><td>INSERT Data into a table</td>
	</tr>
	<tr>
		<td class="sample">Example of Data Transmitted:</td>
		<td class="sample">
			<pre>
$db_con->queryInsert(
	"channels",	//INTO channels
	array(	//(channame,changroup) VALUES(ABC,1)
		"channame" => "ABC",
		"changroup" => 1
	)
);
			</pre>
		</td>
	</tr>
	<tr>
		<td><strong>queryUpdate:</strong></td><td>UPDATE existing data in a table</td>
	</tr>
	<tr>
		<td class="sample">Example of Data Transmitted:</td>
		<td class="sample">
			<pre>
$db_con->queryUpdate(
	"channels",	//UPDATE channels
	array(	//SET channame = CBS, changroup = 1
		"channame" => "CBS",
		"changroup" => 1
	),
	array(	//WHERE chanid = 33
		"chanid" => 33,
	)
);
			</pre>
		</td>
	</tr>
</table>
<p>
	The Person that writes a plugin will never have to deal with your class directly, instead he
	calls the mapping functions accordingly from "<strong>class.db_core.php</strong>", a sample on how they will
	run a query:<br /><br />
	<pre>
$channels = $db_core->getQuery(
	"channels", //table
	NULL, //fields
	NULL, //keys
	NULL, //where
	NULL, //sqlands
	NULL, //sqlors
	array("values" => "channame"), //sortparms
		"ASC", //sortorder
	NULL, //group
	NULL, //limit
	$db_con, //dbcon
	"FULL" //functiontype
);
	</pre>
</p>
';

//and parse the output to the template
$output = $parser->fillMainTemplate($output,NULL,$templates["body"]);

//and we put the output out...
$page = array();
$page["database_dev"] = $parser->cleanup($output);
?>