<?php
/*********************************************************************
**         		DATABASE CONFIGURATIONS - [CHANGE HERE]				**
*********************************************************************/


/* set here your database HOST. This is usually localhost or a host name provided by the hosting provider. */
$db_host = "YOUR-DB-HOST-HERE";

/* set here your database USER. This can be the default MySQL username root, a username provided by your hosting provider, or one that you created in setting up your database server .*/
$db_user = "YOUR-DB-USER-HERE";

/* set here your database PASSWORD. Using a password for the MySQL account is mandatory for site security. This is the same password used to access your database. This may be predefined by your hosting provider. */
$db_pass = "YOUR-DB-PASS-HERE";

/* set here your database NAME */
$db_name = "YOUR-DB-NAME-HERE";



/*************************************************************
**         		END OF DATABASE CONFIGURATIONS				**
**************************************************************/

$dblink=mysql_connect($db_host, $db_user, $db_pass) or die('Unable to establish a DB connection');

// UTF-8 mode
mysql_query("SET NAMES 'utf8'");

// Selects the database
mysql_select_db($db_name, $dblink);

?>
