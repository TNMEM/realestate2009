<?php

// @mysql_connect is to supress error message
// first location server
$con = @mysql_connect("localhost","tnmemcom_tnmem","myhost");
if (!$con)
{
    // second location server
    $con = mysql_connect("localhost","root","teraaii");
}
else
{
    // first location database
    mysql_select_db("tnmemcom_LOUTEST", $con);
}

if (!$con)
{
    die('Could not connect:<br /><br />' . mysql_error());
}
else{
    // second location database
    mysql_select_db("LOUTEST", $con);
}

?>
