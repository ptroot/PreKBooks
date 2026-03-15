<h1>Preschool Teachers boxes of books</h1>
<p>
Teachers have a lot of books, and they can be used for many different moments through out the school year. 
<br>This database and web page can be used to help quickly find a book, or search for a book for a specific weekly theme. 
<p>Currently, it is using a maria database (mysql), but the web page has been written to separate the connection to the database into just
one library file. I plan to port it next to SQL express. Also, will figure out having a web server on Windows.
<p>Start by setting up a maria server and add a user capable of creating a database.
<br>I made it in a lxc container, to keep it separate from the main network. 
<ul><li>Next create the prekbooks database with the <i>mariadb/create-prek.sql</i> script.
<li>Encrypt the password for the user using the <i>encrypt.php</i> script and enter that into <i>vars.php</i>, and update the encryption key in <i>book_lib.php</i>.
<li>You can use <i>add-data.sql</i> has a model for mass adding books.

Paul Root - March 2026

