
<h1>PreK Books installer for Windows</h1>
<p>This script will do a complete install of a web server, database, and all the files for the Preschool Book card catalog.  
<h2>It will</h2>
<ul><li>Download the XAMPP package, if needed, and verify the file matches
<li>Test that it can create the installation directory.
<li>Install the XAMPP-Lite package.  This provides the Maria database, Apache web server, and Php web page environment.
<li>Create the database instance for the book catalog
<li>Create database users to access the book catalog
<li>Start up the database and web server
</ul>
<h2>Prompts during installation</h2>
While installing, you will be asked to answer a few questions. Yes or no questions will provide a default response. Simply pressing Enter will take the default.
<br>Otherwise, enter y, yes, Yes, YES, n, no, No, NO  to answer. 
<ul><li>Confirm using the default installation location
<li>Alternatively, use the application data location of your account.
<li>Lastly, prompt for full path of location you would like to install.
<li>It will prompt for a few passwords
<ul><li>Application user for connecting to the database from inside the webpages
<li>The master account of the database. The XAMPP application comes with that user having no password. Which is, of course, bad
<li>Optionally, if you want another user in the database, it can create that for you as well. 
</ul>
</ul>
So keep those passwords in a safe place (a password manager is best). It is unlikely you will need to use any of them for day to day. But, if it crashes, recovering the data would be more difficult without them. 
<h2>So do all those passwords makes this secure?</h2>
<p>Short answer is no. 
<h3>The long boring answer</h3>
<ul><li>XAMPP is meant for development purposes not production. But this database and web page does not have sensitive information, so it's not a huge issue
<li>The database is not encrypted, so it is possible to look in the database files, and see the data. That's on the list to work on. 
<li>The web server is using http not https. Again, xampp is meant for development. 
</ul>
<h3>I really want it secure</h2>
<br>Ok, well, then XAMPP-Lite shouldn't be used, it is really designed for development use.
<ul><li>Probably use SQL Express instead. That can be used as well by the prek-books database.
<li>Use a different webserver, like Microsoft's for instance.  I'm not really a Windows guy, so I don't have first hand knowledge
<li>Create a self signed certificate so you can use https. It's not too difficult but is still not trusted.  
<li>Get a signed certificate instead of self signed. That is obviously an expense.
<li>Host this on a server instead of a PC. 
<ul><li>This should be able to run on anything that can run MySql (Maria db) and a Web server that supports PHP.
</ul>
<li>Could be ported easily to another database. All database calls are contained in 1 php file.
</ul>
<h2>So it has installed - What do I do</h2>
Bring up a broweser on the machine, and enter the URL  <i>http://localhost</i>
<br>Or from another machine, go to the IP address or full machine name where this is installed.
<h2>Disclaimer</h2>
I wrote the database and the webpages. I can help with those issues. 
<br>I have no association with the development of XAMPP. I just found it on the internet, and it suits this purpose. But I haven't spent much time learning its internals.
<br>phpMyAdmin looks pretty cool, giving you a graphical interface similar to SQL Servers SSMS. If you want to do your own SQL commands in the database.
<ul><li>http://localhost/phpMyAdmin
<li>Remember, to backup your database regularly. phpMyAdmin will do that with the export tab.
</ul>
If you want to look at more XAMPP information
<ul><li>http://localhost/dashboard</ul>
<p>
<i>Paul Root</li>
<i>Copyright 
