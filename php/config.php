
<?php 
 define('MYSQL_HOST', 'localhost');
 define('MYSQL_USER', 'root');
 define('MYSQL_PASSWORD', '');
 define('MYSQL_DATABASE', 'eeris');
 $con = mysqli_connect(
    MYSQL_HOST,
    MYSQL_USER,
    MYSQL_PASSWORD,
    MYSQL_DATABASE) 
    or die("Couldn't connect");
?>