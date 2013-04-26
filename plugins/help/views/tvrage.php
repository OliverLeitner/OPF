<?php
/*
*  the plugin development documentation...
*/
//loading our plugins templates...
$templates = $dynamic->loadTemplates($plugin_name["help"],$data_con);

//content filling...
$output = array();
$output["content"] = '
<h3>TVRage Parser Plugin: A sample &amp; a Tool</h3>
<p>
	The TVRage plugin is the reason why i originally started this project.
	I wanted to interface with tvrage, grab their content into a database and
	locally serve it to a community that i am part of.
</p>
<p>
	The TVRage plugin enables you to do just that, the idea is: fetching the
	Data from tvrage.org into a Database of choice, then parse the interresting
	parts into an rss feed, that can then be read from a web app or from within a
	gadget.
</p>
<h3>The base requirements:</h3>
<ol>
<li>
	<p>The current version comes with a bash script to fetch the data from tvrage.org
	so you might want to have a linux system or cygwin or maybe a bash to windows 
	port installed on a windows server to run that. some good links on that:<br />
	<br />
	<a href="http://win-bash.sourceforge.net/">http://win-bash.sourceforge.net/</a><br />
	<a href="http://www.cygwin.com/install.html">http://www.cygwin.com/install.html</a><br /><br />
	If you dont wanna run the script by hand every day, there are schedulers available
	for windows (besides the built in one):<br /><br />
	<a href="http://cronforwindows.com/">http://cronforwindows.com/</a><br />
	<a href="http://cronw.sourceforge.net/install.html">http://cronw.sourceforge.net/install.html</a></p><br /><br />
</li>
<li>
	<p>
		A MySQL/Percona/MariaDB Database, on linux, you either already have it installed, or grab
		the packaged version from your maintainer repositories. (apt-get, yum...)
	</p>
	<p>
		Windows users may find a version for their systems here:<br /><br />
		<a href="http://www.mysql.com/downloads/mysql/">http://www.mysql.com/downloads/mysql/</a><br />
		<a href="https://downloads.mariadb.org/mariadb/">https://downloads.mariadb.org/mariadb/</a><br /><br />
	</p>
</li>
<li>
	<p>
		A Webserver, any Webserver that supports PHP will do.
	</p>
</li>
</ol>
<h3>How to install the Plugin and enable the Tool</h3>
<p>
	Installing and Enabling the plugin is as simple as following these three steps:
</p>
<ol>
	<li>
		<strong>Create the database and/or insert the needed tables into it</strong><br ><br />
		<p>Open your hosters phpmyadmin or use your favorite mysql client, and copy &amp; paste
		the following code into an existing database:</p>
		<pre>
CREATE TABLE IF NOT EXISTS channels (
  chanid int(11) NOT NULL AUTO_INCREMENT,
  channame varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (chanid)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS channels_mm_episodes (
  chanid bigint(20) NOT NULL,
  episodeid bigint(20) NOT NULL,
  KEY chanid (chanid,episodeid)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS classes (
  classid bigint(20) NOT NULL AUTO_INCREMENT,
  classname varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (classid)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS classes_mm_series (
  classid bigint(20) NOT NULL,
  seriesid bigint(20) NOT NULL,
  KEY classid (classid,seriesid)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS country (
  countryid bigint(20) NOT NULL AUTO_INCREMENT,
  countryname varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (countryid)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS country_mm_series (
  countryid bigint(20) NOT NULL,
  seriesid bigint(20) NOT NULL,
  KEY countryid (countryid,seriesid)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS episodes (
  episodeid bigint(20) NOT NULL AUTO_INCREMENT,
  seriesid int(11) NOT NULL,
  title varchar(255) CHARACTER SET utf8 NOT NULL,
  episodenum varchar(255) CHARACTER SET utf8 NOT NULL,
  link varchar(255) CHARACTER SET utf8 NOT NULL,
  episodelength int(11) NOT NULL,
  episodesid bigint(20) NOT NULL,
  PRIMARY KEY (episodeid)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS genres (
  genreid int(11) NOT NULL AUTO_INCREMENT,
  genrename varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (genreid)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS genres_mm_series (
  genreid bigint(20) NOT NULL,
  seriesid bigint(20) NOT NULL,
  KEY genreid (genreid),
  KEY seriesid (seriesid)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS schedules (
  scheduleid bigint(20) NOT NULL AUTO_INCREMENT,
  combined bigint(100) NOT NULL,
  PRIMARY KEY (scheduleid)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS schedules_mm_episodes (
  scheduleid bigint(20) NOT NULL,
  episodeid bigint(20) NOT NULL,
  KEY scheduleid (scheduleid,episodeid)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS series (
  seriesid bigint(20) NOT NULL AUTO_INCREMENT,
  seriesname varchar(255) CHARACTER SET utf8 NOT NULL,
  serieslink varchar(255) CHARACTER SET utf8 NOT NULL,
  startdate varchar(255) CHARACTER SET utf8 NOT NULL,
  enddate varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (seriesid)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `status` (
  statusid bigint(20) NOT NULL AUTO_INCREMENT,
  statusname varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (statusid)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS status_mm_series (
  statusid bigint(20) NOT NULL,
  seriesid bigint(20) NOT NULL,
  KEY statusid (statusid,seriesid)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		</pre>
	</li>
	<li>
		<strong>Change 3 lines in your conf/config.php</strong><br /><br />
		<p>
			Open the file conf/config.php in your favorite text editor
			and change the following 3-5 lines to point to your database host
			your database and have your user credentials: 
		</p>
		<pre>
//database configuration values
define(&quot;DB_NAME&quot;, &quot;database_name&quot;);
define(&quot;DB_USER&quot;, &quot;database_username&quot;);
define(&quot;DB_PASSWORD&quot;, &quot;database_password&quot;);
define(&quot;DB_HOST&quot, &quot;localhost&quot;);
define(&quot;DB_PORT&quot;, 3306);
		</pre>
	</li>
	<li>
		<strong>Add jobs/runme.sh to your crontab</strong><br /><br />
		<p>
			This is probably the hardest part to do, open your scheduled
			jobs on the system, and tell your system to run the script
			"jobs/runme.sh" maybe once a day at a time of your choice.
		</p>
		<p>
			"runme.sh" starts the php scripts which are in the same
			directory with it one at a time, and these scripts access
			tvrage.org and fetch the data you want from it.
		</p>
		<p>
			<strong>Optional:</strong> There is some variables inside
			"runme.sh" that you can influence its behaviour with,
			just open it in a text editor and have a look.
		</p>
	</li>
</ol>
<h3>Accessing and using it</h3>
<p>
	The main feed can be accessed via <a href="?page=feed" target="_blank">index.php?page=feed</a>, a list of possible
	other parameters that are built in:
</p>
<table>
	<tr><th>Parameter</th><th>Meaning</th></tr>
	<tr><td>?page=feed</td><td>Accessing the main Feed</td></tr>
	<tr><td>?page=feed&limit=#&offset=#</td><td>Pagination option where limit is num of shows per page and offset is page num.</td></tr>
	<tr><td>?page=feed&genre=Genre</td><td>Show every upcoming of a certain genre</td></tr>
	<tr><td>?page=feed&channel=Channel</td><td>Show every upcoming at a certain channel</td></tr>
	<tr><td>?page=feed&country=Country</td><td>Show everything upcoming produced in a certain country</td></tr>
	<tr><td>?page=feed&series=Series</td><td>Search for airtimes of your favorite series</td></tr>
	<tr><td>?page=feed&class=Classification</td><td>Show every upcoming show of a certain type</td></tr>
	<tr><td>?page=feed&status=Showstatus</td><td>Find upcoming shows that follow a certain status</td></tr>
	<tr><td>?q=something</td><td>Search the Database for a specific keyword/phrase</td></tr>
	<tr><td>?page=channels</td><td>List all possible ways to categorize the the feed except genres</td></tr>
</table>
';

//and parse the output to the template
$output = $parser->fillMainTemplate($output,NULL,$templates["body"]);

//and we put the output out...
$page = array();
$page["tvrage"] = $parser->cleanup($output);
?>