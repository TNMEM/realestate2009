<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">    
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>The CA - Shelby, TN, Reappraisal - Select Property</title>
        
        <!-- ALWAYS 1 ... css -->
<?php
require "cssfiles.php"
?>

        <!-- ALWAYS 2 ... collect arguments and correct errors -->
<?php

require_once "opendb.php";

//$homeframe = "http://www.commercialappeal.com/news/local/reappraisal/";
$homeframe = "/index.php";

require_once "dieMsg.php";

if (($stnumber = $_POST['stnumber']) == '') {$stnumber = $_GET['stnumber'];}
if (($stname = $_POST['stname']) == '') {$stname = $_GET['stname'];}
if (($lname = $_POST['lname']) == '') {$lname = $_GET['lname'];}
if (($parcelid = $_POST['parcelid']) == '') {$parcelid = $_GET['parcelid'];}

if (trim($stnumber) . trim($stname) . trim($lname) . trim($parcelid) == '')
{
    dieMsg($homeframe, "Provide at Least One Property Identifier ...");
}

?>

        <!-- ALWAYS 3 ... js -->
<?php
require "jscripts.php";
?>

        <!-- ALWAYS 4 ... jQuery -->
        <script type="text/javascript">
            <!--
            jQuery(function() {

                // reset th
                jQuery('th').addClass('ui-widget-header property-compare');

                // start over
                jQuery('#home_button').click( function(){
                    parent.window.location = <?php echo "'" . $homeframe . "';\n"; ?>
                });

                // style the table
                jQuery('tr').mouseover(function() {
                    //jQuery(this).children('td').addClass("ui-state-highlight");
                    jQuery(this).addClass("ui-state-highlight");
                }).mouseout(function() {
                    //jQuery(this).children('td').removeClass("ui-state-highlight");
                    jQuery(this).removeClass("ui-state-highlight");
                });

            });
            -->
        </script>

    </head>
    <body class='ui-widget body-class'>

        <!-- insert no-javascript alert -->
        <noscript>
            <div id='no-javascript' class="ui-widget" style='display: inline;'>
                <br />
                <div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
                    <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
                    <strong>Alert:</strong> JavaScript is not enabled. Some features are disbled.</p>
                </div>
                <br />
            </div>
        </noscript>
        <!-- end no-javascript alert -->

<?php

$anOrder = " ORDER BY SCORE DESC, ADDRESS ASC";
if ((trim($stnumber) . trim($stname)) == "")
{
    $anOrder = " ORDER BY OWN1";
}
$qparcelid = "(1=1)";
if (trim($parcelid) != '') {$qparcelid = "(PARID = '" . trim($parcelid) . "')";}
/*
$qstnumber = "(1=1)";
if (trim($stnumber) != '') {$qstnumber = "(ADDRESS like '" . trim($stnumber) . "%')";}
$qstname = "(1=1)";
if (trim($stname) != '') {$qstname = "(ADDRESS like '%" . trim($stname) . "%')";}
*/
$qstnumber = "'" . trim($stnumber) . "'";
$qstname = "'" . trim($stname) . "'";
if ($qstnumber != "''" && $qstname != "''") {$qaddress = $qstnumber . '+' . $qstname;}
if ($qstnumber != "''" && $qstname == "''") {$qaddress = $qstnumber;}
if ($qstnumber == "''" && $qstname != "''") {$qaddress = $qstname;}
if ($qaddress != "") {$qaddress = "(MATCH (ADDRESS) AGAINST (\"" . trim($qaddress) . "\" IN BOOLEAN MODE))";} else {$qaddress = "(1=1)";}
$qlname = "(1=1)";
if (trim($lname) != '') {$qlname = "(OWN1 like '" . trim($lname) . "%')";}

$query = " SELECT *, " . $qaddress . " as SCORE FROM propview " .
"WHERE " . $qparcelid .
" AND " . $qaddress .
" AND " . $qlname .
$anOrder . " LIMIT 0, 100";
//print $query;
$result = mysql_query($query);
if (!$result)
{
    dieMsg($homeframe, "Error in Query: " . mysql_error() . " ...");
}

// start over
print "<div class='info-msg'><span id='home_button' style='cursor: pointer;'><img src='images/ca_icon_startover.gif' style='vertical-align: middle;'/> Start Over</span></div>\n";

print "<table class='ui-widget property-compare' width='100%'>\n";
print "<thead class='ui-widget-header property-compare'>\n";
print "<tr><th colspan='6'>Select Your Parcel Request</th></tr>\n";
print "<tr><th>Owner</th><th>Parcel</th><th>2009 Appraisal</th><th>Address</th><th>City</th><th>Zip</th></tr>\n";
print "</thead>\n<tbody class='ui-widget-content property-compare'>\n";

if (mysql_num_rows($result) == 0) {print "<tr><td>(No Parcels Found)</td></tr>\n";}

while($row = mysql_fetch_array($result))
{
    $ownstring = trim($row['OWN1']);
    if (trim($row['OWN2']) != '')
    {
        $ownstring = trim($row['OWN1']) . " " . trim($row['OWN2']);
    }
    print "<tr>\n";
    print "<td>" . str_replace('&', '&amp;', $ownstring) . "</td>" .
  "<td><a href='compareadds.php?parcelid=" . trim($row['PARID']) . "'>" . trim($row['PARID']) . "</a></td>" .
  "<td>" . "$" . number_format(trim($row['2009TOTAPR']), 2, ".", ",");
    if ($row['had2008sale'] == 1) {print " <img src='images/a-dollar.gif' width='7' title='2008 Sale' alt='2008 Sale'/>";}
    if ($row['had2008foreclose'] == 1) {print " <img src='images/a-number.gif' width='7' title='2008 Foreclosure' alt='2008 Foreclosure'/>";}
    print "</td>" .
  "<td>" . trim($row['ADDRESS']) . "</td>" .
  "<td>" . trim($row['CITY']) . "</td>" .
  "<td>" . trim($row['ZIP']) . "</td>";
    print "\n</tr>\n";
}
print "</tbody>\n</table>\n";

require_once "closedb.php";
require ("analyticsTracking.php");
?>
    </body>
</html>

