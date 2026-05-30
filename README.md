<h1>Preschool Teachers boxes of books</h1>
<p>
Teachers have a lot of books, and they can be used for many different moments through out the school year. 
<br>This database and web page can be used to help quickly find a book, or search for a book for a specific weekly theme. 
<p>Currently, it is using a maria database (mysql) or SQL Express, but the web page has been written to separate the connection to the database into just
one library file. 
<br>Also, I have it working on Windows using XAMPP. See the windows folder readme. It gets a bit long winded.
<p>Start by setting up a maria server and add a user capable of creating a database.
<br>I made it in a lxc container, to keep it separate from the main network. 
<ul><li>Next create the prekbooks database with the <i>mariadb/create-prek.sql</i> script.
<li>Encrypt the password for the user using the <i>encrypt.php</i> script and enter that into <i>vars.php</i>, and update the encryption key in <i>book_lib.php</i>.
<li>You can use <i>add-data.sql</i> has a model for mass adding books.
</ul>
Future Stuff
<ul><li>Using XAMPP portable to run this on a thumb drive.
<li>Encrypting the database
<li>Do some more work on the password stuff in the webpages
<li>Running in LAMP (on a Chrome machine?)
<ul><li>The smart board of the Preschool I do stuff for, seems to be a Chrome machine.
<li>I'd probably have to get a Chromebook again.
<li>Postgresql, why not?
<li>Macintosh. I'd have to get one but maybe have an idea about that.
<li>Other ideas?
</ul>
Paul Root - May 2026

