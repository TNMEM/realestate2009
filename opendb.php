<?php

// @mysql_connect is to supress error message

//$con = @mysql_connect("louisking.db.4228949.hostedresource.com","louisking","Teraaii0");
//$con = @mysql_connect("localhost:3306","root","Teraaii0");
$con = @mysql_connect("awsmysql.cuuagsmfyvhg.us-east-1.rds.amazonaws.com:3306","LOUTEST","LOUTEST");
if ($con)
{
    mysql_select_db("LOUTEST", $con);
}
else
{
    die('Could not connect:<br /><br />' . mysql_error());
}

?>
