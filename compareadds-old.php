<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">    
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>The CA - Shelby, TN, Reappraisal - Compare Parcels</title>

        <!-- ALWAYS 1 ... css -->
<?php
require "cssfiles.php";
?>
        <!-- ALWAYS 2 ... collect arguments and correct errors -->
<?php

require "opendb.php";

//$homeframe = "http://www.commercialappeal.com/news/local/reappraisal/";
$homeframe = "/index.php";

require "dieMsg.php";

$parcelid = $_GET['parcelid'];
if (trim($parcelid) == '')
{
    dieMsg($homeframe, "Could Not Locate Parcel ID ...");
}

$aPage = $_GET['aPage'];
if (trim($aPage) == '')
{
    $aPage = 1;
}

$onlysale = $_GET['onlysale'];
if (trim($onlysale) !='true')
{
    $onlysale = 'false';
}
?>

        <!-- ALWAYS 3 ... js -->
<?php
require "jscripts.php";
?>

        <!-- ALWAYS 4 ... jQuery -->
        <script type="text/javascript">
            <!--
            jQuery(function() { // begin the document ready function

                //jQuery('#themeswitcher').themeswitcher();

                getMap();

                // reset th
                jQuery('th').addClass('ui-widget-header property-compare');


                // print what i want to print via jquery.printarea.js
                jQuery('#print_button').click( function(){
                    jQuery("#print_area").printArea();
                });

                // start over
                jQuery('#home_button').click( function(){
                    parent.window.location = <?php echo "'" . $homeframe . "'"; ?>;
                });

                // checkbox GET
                var jsParcelid = <?php echo "'" . $parcelid . "';\n"; ?>
                var jsOnlysale = <?php echo  $onlysale . ";\n"; ?>
                // show and get state
                jQuery('#onlysale').parent().css({display: 'inline'});
                var chkstate = jQuery('#onlysale').attr('checked');
                // set according to $onlysale
                if (jsOnlysale != chkstate) {
                    jQuery('#onlysale').click();
                }
                // with actual click, do a different select
                jQuery('#onlysale').click( function(){
                    var chkstate = jQuery('#onlysale').attr('checked');
                    var aUrl = "compareadds.php?parcelid="+escape(jsParcelid)+"&aPage=1&onlysale="+chkstate;
                    window.location = aUrl;
                });

                // hoverintent plugin details
                var config = {
                    sensitivity: 3, // number = sensitivity threshold (must be 1 or higher)
                    interval: 200, // number = milliseconds for onMouseOver polling interval
                    over: tooltip_appear, // function = onMouseOver callback (REQUIRED)
                    timeout: 500, // number = milliseconds delay before onMouseOut
                    out: tooltip_disappear // function = onMouseOut callback (REQUIRED)
                };

                // tooltips use hoverintent
                jQuery('#sale1, #sale2a, #sale2b, #sale2c, #sale2d, #perm1, #perm2a, #perm2b, #perm2c, #perm2d').hoverIntent(config);

                function tooltip_appear(e) // e is the mouse variable ... e.pageX is position left
                {
                    anId = jQuery(this);
                    jQuery(anId).addClass('ui-state-hover');
                    jQuery('#tooltip_'+anId.attr('id')).animate({opacity: "show",
                        top: anId.position().top - jQuery('#tooltip_'+anId.attr('id')).height() - 15,
                        left: anId.position().left - jQuery('#tooltip_'+anId.attr('id')).width() / 2 + 5}, "normal", "linear");
                }

                function tooltip_disappear(e) // e is the mouse variable ... e.pageX is position left
                {
                    anId = jQuery(this);
                    jQuery(anId).removeClass('ui-state-hover');
                    jQuery('#tooltip_'+anId.attr('id')).animate({opacity: "hide", top: "0", left: "0"}, "normal", "linear");
                }


                // add hilight to table
                jQuery('tr').mouseover(function() {
                    //jQuery(this).children('td').addClass("ui-state-highlight");
                    jQuery(this).addClass("ui-state-highlight");
                }).mouseout(function() {
                    //jQuery(this).children('td').removeClass("ui-state-highlight");
                    jQuery(this).removeClass("ui-state-highlight");
                });

                //all hover and click logic for buttons (jquery_ui_buttons.html)
                // fg-button etc. are classes for selector, but don't have a stylesheet ...
                // ... but there could be styles for them
                jQuery(".fg-button:not(.ui-state-disabled)").hover( function(){
                    jQuery(this).addClass("ui-state-hover");
                }, function(){
                    jQuery(this).removeClass("ui-state-hover");
                }).mousedown( function(){
                    jQuery(this).parents('.fg-buttonset-single:first').find(".fg-button.ui-state-active").removeClass("ui-state-active");
                    if( jQuery(this).is('.ui-state-active.fg-button-toggleable, .fg-buttonset-multi .ui-state-active') ){
                        jQuery(this).removeClass("ui-state-active");
                    } else { jQuery(this).addClass("ui-state-active"); }
                }).mouseup( function(){
                    if(! jQuery(this).is('.fg-button-toggleable, .fg-buttonset-single .fg-button,  .fg-buttonset-multi .fg-button') ){
                        jQuery(this).removeClass("ui-state-active");
                    }
                });

            }); // end document ready jQuery function
            -->
        </script>

        <script type="text/javascript">
            <!--
            function openmapwin()
            {
                myRef = window.open('map.php','MAPWIN','left=20,top=20,width=520px,height=450px,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0');
                if(myRef)
                {
                    document.getElementById('popmsg01').style.display = 'none';
                    myRef.focus();
                }
                else
                {
                    document.getElementById('popmsg01').display.style.display = 'block';
                }
            }
            -->
        </script>

        <!-- MAP SCRIPT -->
        <!-- prevent the map's bird's eye popup message -->
        <style type="text/css">
            .header {visibility: hidden;}
            #MSVE_obliqueNotification {visibility: hidden;}
        </style>
        <script type="text/javascript" src="http://dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=6.2"></script>
        <script type="text/javascript">
            <!--

            var map = null;
            var layer = null;
            var pinArr = new Array();
            var pinCnt = 0;
            var locArr = new Array();
            var Mem = new VELatLong(35.152743356008,-90.05084643266);

            function getMap()
            {
                locArr[0] = new Array (document.getElementById("loctext01m").value, "images/R.gif");
                locArr[1] = new Array (document.getElementById("loctext02m").value, "images/1.gif");
                locArr[2] = new Array (document.getElementById("loctext03m").value, "images/2.gif");
                locArr[3] = new Array (document.getElementById("loctext04m").value, "images/3.gif");
                locArr[4] = new Array (document.getElementById("loctext05m").value, "images/4.gif");

                map = new VEMap('mymap');
                map.LoadMap(Mem, 10, '', false);
                layer = new VEShapeLayer();
                map.AddShapeLayer(layer);
                setPin(0);
            }

            function setPin(aLoc) {
                try {
                    map.Find(null, locArr[aLoc][0], null, null, null, null, null, null, null, null,
                    function(shapeLayer, findResults, places, moreResults, errorMsg) {
                        if (null != places) {
                            var pin = new VEShape(VEShapeType.Pushpin, places[0].LatLong);
                            pin.SetCustomIcon("<div style='float:left;filter:alpha(opacity=100.0);-moz-opacity:1.0;opacity:1.0;'><img src='"+locArr[aLoc][1]+"'></div>");
                            pin.SetTitle("Address:");
                            pin.SetDescription(locArr[aLoc][0]);
                            layer.AddShape(pin);
                            pinArr[pinCnt++] = places[0].LatLong;
                        }
                        if (aLoc++ < (locArr.length - 1)) {
                            setPin(aLoc);
                        }
                        else {
                            map.SetMapView(pinArr);
                            if (map.GetZoomLevel() > 17) {map.SetZoomLevel(17);}
                            layer.SetClusteringConfiguration(VEClusteringType.Grid);
                            document.getElementById('mymap').style.visibility = 'visible';
                        }
                    }

                );
                }
                catch (e) {
                    alert(e.message);
                }
            }

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

// NOTE: "WHERE (PARID like '%" . str_replace(' ', '%', trim($parcelid)) . "%')";

// get details of primary property
$query1 = "SELECT * FROM propview " .
"WHERE (PARID = '" . trim($parcelid) . "')";
//print $query1;
$result1 = mysql_query($query1);
if (!$result1)
{
    dieMsg($homeframe, "Error in Query1: " . mysql_error() . " ...");
}
$qrows1 = mysql_num_rows($result1);
$row1 = mysql_fetch_array($result1);


// select up to four matching properties
if ($qrows1 > 0)
{
    // ADDRESS street
    $qstreet = trim(preg_replace('#^\d+#', '', trim($row1['ADDRESS'])));
    $qaddress = "(STREET = '" . $qstreet . "')";
    // NBHD match
    $qnbhd = "(NBHD = '" . trim($row1['NBHD']) . "')";
    if (trim($row1['NBHD']) == '') {$qnbhd = "(NBHD = '99')";}
    // NGROUP match
    $qngroup = "(NGROUP = '" . trim($row1['NGROUP']) . "')";
    if (trim($row1['NGROUP']) == '') {$qngroup = "(NGROUP = '99')";}
    // CDU match
    $qcdu = "(CDUnum = " . trim($row1['CDUnum']) . ")";
    if ((trim($row1['CDUnum']) == '') || (is_null($row1['CDUnum'])))
    {
        $qcdu = "(True)";
    }
    // SFLA within 20%
    $qsfla1 = "(SFLA >= (" . trim($row1['SFLA']) . " * .8)" . ")";
    $qsfla2 = "(SFLA <= (" . trim($row1['SFLA']) . " * 1.2)" . ")";
    $msfla = trim($row1['SFLA']);
    if ((trim($row1['SFLA']) == '') || (is_null($row1['SFLA'])))
    {
        $qsfla1 = "(True)";
        $qsfla2 = "(True)";
        $msfla = 0;
    }
    // YRBLT within 10 years
    $qyrblt1 = "(YRBLT >= (" . trim($row1['YRBLT']) . " - 10))";
    $qyrblt2 = "(YRBLT <= (" . trim($row1['YRBLT']) . " + 10))";
    $myrblt = trim($row1['YRBLT']);
    if ((trim($row1['YRBLT']) == '') || (is_null($row1['YRBLT'])))
    {
        $qyrblt1 = "(True)";
        $qyrblt2 = "(True)";
        $myrblt = 0;
    }
    // query to get matching parcels
    $query2 = "SELECT *," .
    /*
    " CASE" .
   " WHEN (had2008sale = 1) THEN 1" .
  " ELSE 0" .
  " END as srt2008sale," .
  " CASE" .
  " WHEN " . $qnbhd . " THEN 1" .
  " ELSE 0" .
  " END as srtnbhd," .
  " CASE" .
  " WHEN " . $qngroup . " THEN 1" .
  " ELSE 0" .
  " END as srtngroup," .
  */
  " CASE" .
  " WHEN " . $qaddress . " THEN 1" .
  " ELSE 0" .
  " END as srtaddress" .
  " FROM propview" .
  " WHERE (" . $qnbhd . " OR " . $qngroup. ") AND " . (($onlysale == 'true') ? '(had2008sale = 1) AND ' : '') . "(PARID <> '" . $row1['PARID'] . "') AND " . $qcdu . "  AND " . $qsfla1 . "  AND " . $qsfla2 . " AND " . $qyrblt1 . "  AND " . $qyrblt2 .
  " ORDER BY srtaddress desc, ABS(SFLA - " . $msfla . "), ABS(YRBLT - " . $myrblt . ")" .
  " LIMIT " . (($aPage * 4) - 4) . ",4";
    //print $query2 . "<br />\n";
    $result2 = mysql_query($query2);
    if (!$result2)
    {
        dieMsg($homeframe, "Error in Query2: " . mysql_error() . " ...");
    }
    $qrows2 = mysql_num_rows($result2);
    //print $qrows2 . "<br />\n<br />\n";
    if ($qrows2 >= 1) {
        $row2a = mysql_fetch_array($result2);
    }
    if ($qrows2 >= 2) {
        $row2b = mysql_fetch_array($result2);
    }
    if ($qrows2 >= 3) {
        $row2c = mysql_fetch_array($result2);
    }
    if ($qrows2 >= 4) {
        $row2d = mysql_fetch_array($result2);
    }
}

// write the divs for the permits ... no need if PermitCount == 0 or there is not a $qrow2

function doPermitDiv($anID,$aRow)
{
    print "<div id='" . $anID . "' class='ui-widget tooltip' style='color: darkred;'>\n";
    print "<div class='tooltip-bg'><img src='images/hover.png' width='100%' height='100%' alt='' title=''/></div>\n";
    print "<center>Permits</center><br />\n";
    if (trim($aRow['PermitCount']) > 0)
    {
        $queryp = "SELECT * FROM (SELECT * FROM permview " .
    "WHERE (PARID = '" . trim($aRow['PARID']) . "') AND (PERMDT <> '') ORDER BY PERMDT desc LIMIT 6) as tbl ORDER BY tbl.PERMDT";
        //print $queryp;
        $resultp = mysql_query($queryp);
        if (!$resultp)
        {
            dieMsg($homeframe, "Error in Queryp: " . mysql_error() . " ...");
        }
        while($rowp = mysql_fetch_array($resultp))
        {
            print "$" . number_format(trim($rowp['AMOUNT']), 2, ".", ",") . "&nbsp;&nbsp;&nbsp;" . trim(date("m/d/Y", strtotime($rowp['PERMDT']))) . "<br />\n";
        }
    }
    print "<br /></div>\n";
}

doPermitDiv("tooltip_perm1", $row1);
doPermitDiv("tooltip_perm2a", $row2a);
doPermitDiv("tooltip_perm2b", $row2b);
doPermitDiv("tooltip_perm2c", $row2c);
doPermitDiv("tooltip_perm2d", $row2d);

// end of the divs for the permits

// write the divs for the sales ... no need if PermitCount == 0 or there is not a $qrow2

function doSalesDiv($anID, $aRow)
{
    print "<div id='" . $anID . "' class='ui-widget tooltip' style='color: darkgreen;'>\n";
    print "<div class='tooltip-bg'><img src='images/hover.png' width='100%' height='100%' alt='' title=''/></div>\n";
    print "<center>Recent Sales</center><br />\n";
    if (trim($aRow['SalesCount']) > 0)
    {
        $querys = "SELECT * FROM (SELECT * FROM salesview " .
    "WHERE (PARID = '" . trim($aRow['PARID']) . "') AND (SALEDT <> '') ORDER BY SALEDT desc LIMIT 6) as tbl ORDER BY tbl.SALEDT";
        //print $querys;
        $results = mysql_query($querys);
        if (!$results)
        {
            dieMsg($homeframe, "Error in Querys: " . mysql_error() . " ...");
        }
        while($rows = mysql_fetch_array($results))
        {
            if ($rows['INSTRTYP'] == 'TD') {echo "(Foreclosure)&nbsp;&nbsp;";}
            print "$" . number_format(trim($rows['PRICE']), 2, ".", ",") . "&nbsp;&nbsp;&nbsp;" . trim(date("m/d/Y", strtotime($rows['SALEDT']))) . "<br />\n";
        }
    }
    print "<br /></div>\n";
}

doSalesDiv("tooltip_sale1", $row1);
doSalesDiv("tooltip_sale2a", $row2a);
doSalesDiv("tooltip_sale2b", $row2b);
doSalesDiv("tooltip_sale2c", $row2c);
doSalesDiv("tooltip_sale2d", $row2d);

// end of the divs for the sales

// themeswitcher
print "<div id='themeswitcher'></div>";

// print or start over using theme icons via sprites
//print "<div class='ui-widget info-msg'><img id='print_button' alt='Print View' title='Print View' class='ui-icon ui-icon-print' src='images/1x1-trans.gif' style='display: inline; cursor: pointer;'/>Print&nbsp;&nbsp;&nbsp;<img id='home_button' alt='Start Over' title='Start Over' class='ui-icon ui-icon-home' src='images/1x1-trans.gif' style='display: inline; cursor: pointer;'/>Start Over</div>\n";
// print or start over using image icons
print "<div class='info-msg'><span id='home_button' style='cursor: pointer;'><img src='images/ca_icon_startover.gif' alt='Start Over' title='Start Over' style='vertical-align: middle;'/> Start Over</span>&nbsp;&nbsp;&nbsp;<span id='print_button' style='cursor: pointer;'><img src='images/ca_icon_print.gif' alt='Print' title='Print' style='vertical-align: middle;'/> Print</span></div>\n";
// begin the area to print
print "<div id='print_area'>\n";

print "<div id='mymap' style='visibility:visible;position:relative; width: 100%; height:200px; border: 1px solid;'></div>\n";

print "<table class='ui-widget property-compare' width='100%'>\n";
print "<thead class='ui-widget-header property-compare'>\n";
print "<tr><th colspan='2'>\n";
print "<form id='mapform' method='post' target='MAPWIN' action='map.php'>\n";
print "<input type='hidden' id='loctext01m' name='loctext01' value='" . trim($row1['ADDRESS']) . ", " . trim($row1['CITY']); if (trim($row1['CITY']) != ''){echo ', ';} print trim($row1['ZIP']) ."'/>\n";
if ($qrows2 >= 1) {print "<input type='hidden' id='loctext02m' name='loctext02' value='" . trim($row2a['ADDRESS']) . ", " . trim($row2a['CITY']); if (trim($row2a['CITY']) != ''){echo ', ';} print trim($row2a['ZIP']) ."'/>\n";}else{print "<input type='hidden' id='loctext02m' name='loctext02' value=''/>\n";}
if ($qrows2 >= 2) {print "<input type='hidden' id='loctext03m' name='loctext03' value='" . trim($row2b['ADDRESS']) . ", " . trim($row2b['CITY']); if (trim($row2b['CITY']) != ''){echo ', ';} print trim($row2b['ZIP']) ."'/>\n";}else{print "<input type='hidden' id='loctext03m' name='loctext03' value=''/>\n";}
if ($qrows2 >= 3) {print "<input type='hidden' id='loctext04m' name='loctext04' value='" . trim($row2c['ADDRESS']) . ", " . trim($row2c['CITY']); if (trim($row2c['CITY']) != ''){echo ', ';} print trim($row2c['ZIP']) ."'/>\n";}else{print "<input type='hidden' id='loctext04m' name='loctext04' value=''/>\n";}
if ($qrows2 >= 4) {print "<input type='hidden' id='loctext05m' name='loctext05' value='" . trim($row2d['ADDRESS']) . ", " . trim($row2d['CITY']); if (trim($row2d['CITY']) != ''){echo ', ';} print trim($row2d['ZIP']) ."'/>\n";}else{print "<input type='hidden' id='loctext05m' name='loctext05' value=''/>\n";}
//print "<input type='button' class='fg-button ui-state-default ui-priority-primary ui-corner-all' value='BIG MAP!' onclick='openmapwin();submit()'/>\n";
print "<input type='image' src='images/ca_button_bigmap.gif' onclick='openmapwin();submit()'/>\n";
print "<span id='popmsg01' style='color: darkred; display: none;'><br />(Enable pop ups)\n</span>";
print "</form>\n";
print "</th>\n";
print "<th colspan='4'>Compare Requested Parcel to These<br />\n";
print "<span style='display: none;'>(Prefer 2008 Sales: <input id='onlysale' type='checkbox'/>)</span></th></tr>\n";
print "<tr><th style='background: #fff'>\n";
if ($aPage <= 1) {$prevpage = 1;$prevvis='hidden';} else {$prevpage = $aPage - 1;$prevvis='visible';};
if ($aPage >= 10) {$nextpage = 10;$nextvis='hidden';} else {$nextpage = $aPage + 1;$nextvis='visible';};
print "<a style='visibility:" . $prevvis . ";' href='compareadds.php?parcelid=" . trim($row1['PARID']) . "&amp;aPage=" . $prevpage . "&amp;onlysale=" . $onlysale . "'><img style='vertical-align: middle; border:none;' src='images/ca_icon_previous.gif' width='20' title='Previous Parcel Matches' alt='Previous Parcel Matches'/></a>\n";
print "<span style='vertical-align: middle; color: black;'>[PG " . $aPage . "]</span>\n";
print "<a style='visibility:" . $nextvis . ";' href='compareadds.php?parcelid=" . trim($row1['PARID']) . "&amp;aPage=" . $nextpage . "&amp;onlysale=" . $onlysale . "'><img style='vertical-align: middle; border:none;' src='images/ca_icon_next.gif' width='20' title='Next Parcel Matches' alt='Next Parcel Matches'/></a>\n";
print "</th><th>Request</th><th>One</th><th>Two</th><th>Three</th><th>Four</th></tr>\n";
print "</thead>\n<tbody class='ui-widget-content property-compare'>\n";

$ownstring1 = trim($row1['OWN1']);
if (trim($row1['OWN2']) != '')
{
    $ownstring1 = trim($row1['OWN1']) . " " . trim($row1['OWN2']);
}

if ($qrows2 >=1)
{
    $ownstring2a = trim($row2a['OWN1']);
    if (trim($row1['OWN2']) != '')
    {
        $ownstring2a = trim($row2a['OWN1']) . " " . trim($row2a['OWN2']);
    }

}

if ($qrows2 >=2)
{
    $ownstring2b = trim($row2b['OWN1']);
    if (trim($row2b['OWN2']) != '')
    {
        $ownstring2b = trim($row2b['OWN1']) . " " . trim($row2b['OWN2']);
    }

}

if ($qrows2 >=3)
{
    $ownstring2c = trim($row2c['OWN1']);
    if (trim($row2c['OWN2']) != '')
    {
        $ownstring2c = trim($row2c['OWN1']) . " " . trim($row2c['OWN2']);
    }

}

if ($qrows2 >=4)
{
    $ownstring2d = trim($row2d['OWN1']);
    if (trim($row2d['OWN2']) != '')
    {
        $ownstring2d = trim($row2d['OWN1']) . " " . trim($row2d['OWN2']);
    }

}

print "<tr>\n";

print "<td>Parcel:</td>\n";
print "<td><a href='compareadds.php?parcelid=" . trim($row1['PARID']) . "'>" . trim($row1['PARID']) . "</a></td>\n";
if ($qrows2 >= 1) {print "<td><a href='compareadds.php?parcelid=" . trim($row2a['PARID']) . "'>" . trim($row2a['PARID']) . "</a></td>\n";}
if ($qrows2 >= 2) {print "<td><a href='compareadds.php?parcelid=" . trim($row2b['PARID']) . "'>" . trim($row2b['PARID']) . "</a></td>\n";}
if ($qrows2 >= 3) {print "<td><a href='compareadds.php?parcelid=" . trim($row2c['PARID']) . "'>" . trim($row2c['PARID']) . "</a></td>\n";}
if ($qrows2 >= 4) {print "<td><a href='compareadds.php?parcelid=" . trim($row2d['PARID']) . "'>" . trim($row2d['PARID']) . "</a></td>\n";}
print "</tr>\n<tr>\n";

print "<td>Address:</td>\n";
print "<td>";
print trim($row1['ADDRESS']) . "<br />\n" . trim($row1['CITY']); if (trim($row1['CITY']) != ''){echo ', ';} print trim($row1['ZIP']) . "<br />\n";
print  "</td>\n";
if ($qrows2 >= 1)
{
    print "<td>";
    print trim($row2a['ADDRESS']) . "<br />\n" . trim($row2a['CITY']); if (trim($row2a['CITY']) != ''){echo ', ';} print trim($row2a['ZIP']) . "<br />\n";
    print  "</td>\n";
}
if ($qrows2 >= 2)
{
    print "<td>";
    print trim($row2b['ADDRESS']) . "<br />\n" . trim($row2b['CITY']); if (trim($row2b['CITY']) != ''){echo ', ';} print trim($row2b['ZIP']) . "<br />\n";
    print  "</td>\n";
}
if ($qrows2 >= 3)
{
    print "<td>";
    print trim($row2c['ADDRESS']) . "<br />\n" . trim($row2c['CITY']); if (trim($row2c['CITY']) != ''){echo ', ';} print trim($row2c['ZIP']) . "<br />\n";
    print  "</td>\n";
}
if ($qrows2 >= 4)
{
    print "<td>";
    print trim($row2d['ADDRESS']) . "<br />\n" . trim($row2d['CITY']); if (trim($row2d['CITY']) != ''){echo ', ';} print trim($row2d['ZIP']) . "<br />\n";
    print  "</td>\n";
}
print "</tr>\n<tr>\n";

print "<td>Owner:</td>\n";
print "<td>" . str_replace('&', '&amp;', $ownstring1) . "</td>\n";
if ($qrows2 >= 1) {print "<td>" . str_replace('&', '&amp;', $ownstring2a) . "</td>\n";}
if ($qrows2 >= 2) {print "<td>" . str_replace('&', '&amp;', $ownstring2b) . "</td>\n";}
if ($qrows2 >= 3) {print "<td>" . str_replace('&', '&amp;', $ownstring2c) . "</td>\n";}
if ($qrows2 >= 4) {print "<td>" . str_replace('&', '&amp;', $ownstring2d) . "</td>\n";}
print "</tr>\n<tr>\n";

/*
print "<td>Match Quality:</td>\n";
print "<td><img src='images/check-green.jpg' width='16' title='Requested Parcel' alt='Requested Parcel'/></td>\n";
if ($qrows2 >= 1) {echo "<td>"; if($row2a['srtnbhd']==1){echo "<img src='images/check-green.jpg' width='16' title='Same Neighborhood' alt='Same Neighborhood'/>";} else if($row2a['srtngroup']==1){echo "<img src='images/check-yellow.jpg' width='16' title='Nearby Neighborhood' alt='Nearby Neighborhood'/>";} else{echo "<img src='images/circle-red-x.gif' width='16' title='Unmatched Neighborhood' alt='Unmatched Neighborhood'/>";}  echo "</td>\n";}
if ($qrows2 >= 2) {echo "<td>"; if($row2b['srtnbhd']==1){echo "<img src='images/check-green.jpg' width='16' title='Same Neighborhood' alt='Same Neighborhood'/>";} else if($row2b['srtngroup']==1){echo "<img src='images/check-yellow.jpg' width='16' title='Nearby Neighborhood' alt='Nearby Neighborhood'/>";} else{echo "<img src='images/circle-red-x.gif' width='16' title='Unmatched Neighborhood' alt='Unmatched Neighborhood'/>";}  echo "</td>\n";}
if ($qrows2 >= 3) {echo "<td>"; if($row2c['srtnbhd']==1){echo "<img src='images/check-green.jpg' width='16' title='Same Neighborhood' alt='Same Neighborhood'/>";} else if($row2c['srtngroup']==1){echo "<img src='images/check-yellow.jpg' width='16' title='Nearby Neighborhood' alt='Nearby Neighborhood'/>";} else{echo "<img src='images/circle-red-x.gif' width='16' title='Unmatched Neighborhood' alt='Unmatched Neighborhood'/>";}  echo "</td>\n";}
if ($qrows2 >= 4) {echo "<td>"; if($row2d['srtnbhd']==1){echo "<img src='images/check-green.jpg' width='16' title='Same Neighborhood' alt='Same Neighborhood'/>";} else if($row2d['srtngroup']==1){echo "<img src='images/check-yellow.jpg' width='16' title='Nearby Neighborhood' alt='Nearby Neighborhood'/>";} else{echo "<img src='images/circle-red-x.gif' width='16' title='Unmatched Neighborhood' alt='Unmatched Neighborhood'/>";}  echo "</td>\n";}
print "</tr>\n<tr>\n";
*/

print "<td>Sales:</td>\n";
print "<td id='sale1'><u>" . trim($row1['SalesCount']) . "</u>"; if ($row1['had2008sale'] == 1) {echo " <img src='images/a-dollar.gif' width='7' title='2008 Sale' alt='2008 Sale'/>";} if ($row1['had2008foreclose'] == 1) {echo " <img src='images/a-number.gif' width='7' title='2008 Foreclosure' alt='2008 Foreclosure'/>";} echo "</td>\n";
if ($qrows2 >= 1) {print "<td id='sale2a'><u>" . trim($row2a['SalesCount']) . "</u>"; if ($row2a['had2008sale'] == 1) {echo " <img src='images/a-dollar.gif' width='7' title='2008 Sale' alt='2008 Sale'/>";} if ($row2a['had2008foreclose'] == 1) {echo " <img src='images/a-number.gif' width='7' title='2008 Foreclosure' alt='2008 Foreclosure'/>";} echo "</td>\n";}
if ($qrows2 >= 2) {print "<td id='sale2b'><u>" . trim($row2b['SalesCount']) . "</u>"; if ($row2b['had2008sale'] == 1) {echo " <img src='images/a-dollar.gif' width='7' title='2008 Sale' alt='2008 Sale'/>";} if ($row2b['had2008foreclose'] == 1) {echo " <img src='images/a-number.gif' width='7' title='2008 Foreclosure' alt='2008 Foreclosure'/>";} echo "</td>\n";}
if ($qrows2 >= 3) {print "<td id='sale2c'><u>" . trim($row2c['SalesCount']) . "</u>"; if ($row2c['had2008sale'] == 1) {echo " <img src='images/a-dollar.gif' width='7' title='2008 Sale' alt='2008 Sale'/>";} if ($row2c['had2008foreclose'] == 1) {echo " <img src='images/a-number.gif' width='7' title='2008 Foreclosure' alt='2008 Foreclosure'/>";} echo "</td>\n";}
if ($qrows2 >= 4) {print "<td id='sale2d'><u>" . trim($row2d['SalesCount']) . "</u>"; if ($row2d['had2008sale'] == 1) {echo " <img src='images/a-dollar.gif' width='7' title='2008 Sale' alt='2008 Sale'/>";} if ($row2d['had2008foreclose'] == 1) {echo " <img src='images/a-number.gif' width='7' title='2008 Foreclosure' alt='2008 Foreclosure'/>";} echo "</td>\n";}
print "</tr>\n<tr>\n";

print "<td>Permits:</td>\n";
print "<td id='perm1'><u>" . trim($row1['PermitCount']) . "</u></td>\n";
if ($qrows2 >= 1) {print "<td id='perm2a'><u>" . trim($row2a['PermitCount']) . "</u></td>\n";}
if ($qrows2 >= 2) {print "<td id='perm2b'><u>" . trim($row2b['PermitCount']) . "</u></td>\n";}
if ($qrows2 >= 3) {print "<td id='perm2c'><u>" . trim($row2c['PermitCount']) . "</u></td>\n";}
if ($qrows2 >= 4) {print "<td id='perm2d'><u>" . trim($row2d['PermitCount']) . "</u></td>\n";}
print "</tr>\n<tr>\n";

print "<td>Acres:</td>\n";
print "<td>" . trim($row1['SumOfACRES']) . "</td>\n";
if ($qrows2 >= 1) {print "<td>" . trim($row2a['SumOfACRES']) . "</td>\n";}
if ($qrows2 >= 2) {print "<td>" . trim($row2b['SumOfACRES']) . "</td>\n";}
if ($qrows2 >= 3) {print "<td>" . trim($row2c['SumOfACRES']) . "</td>\n";}
if ($qrows2 >= 4) {print "<td>" . trim($row2d['SumOfACRES']) . "</td>\n";}
print "</tr>\n<tr>\n";

print "<td>Stories:</td>\n";
print "<td>" . trim($row1['STORIES']) . "</td>\n";
if ($qrows2 >= 1) {print "<td>" . trim($row2a['STORIES']) . "</td>\n";}
if ($qrows2 >= 2) {print "<td>" . trim($row2b['STORIES']) . "</td>\n";}
if ($qrows2 >= 3) {print "<td>" . trim($row2c['STORIES']) . "</td>\n";}
if ($qrows2 >= 4) {print "<td>" . trim($row2d['STORIES']) . "</td>\n";}
print "</tr>\n<tr>\n";

print "<td>Total Living Area:</td>\n";
print "<td>" . number_format(trim($row1['SFLA']), 0, ".", ",") . "</td>\n";
if ($qrows2 >= 1) {print "<td>" . number_format(trim($row2a['SFLA']), 0, ".", ",") . "</td>\n";}
if ($qrows2 >= 2) {print "<td>" . number_format(trim($row2b['SFLA']), 0, ".", ",") . "</td>\n";}
if ($qrows2 >= 3) {print "<td>" . number_format(trim($row2c['SFLA']), 0, ".", ",") . "</td>\n";}
if ($qrows2 >= 4) {print "<td>" . number_format(trim($row2d['SFLA']), 0, ".", ",") . "</td>\n";}
print "</tr>\n<tr>\n";

print "<td>Bedrooms:</td>\n";
print "<td>" . trim($row1['RMBED']) . "</td>\n";
if ($qrows2 >= 1) {print "<td>" . trim($row2a['RMBED']) . "</td>\n";}
if ($qrows2 >= 2) {print "<td>" . trim($row2b['RMBED']) . "</td>\n";}
if ($qrows2 >= 3) {print "<td>" . trim($row2c['RMBED']) . "</td>\n";}
if ($qrows2 >= 4) {print "<td>" . trim($row2d['RMBED']) . "</td>\n";}
print "</tr>\n<tr>\n";

print "<td>Full / Half Baths:</td>\n";
print "<td>" . trim($row1['FIXBATH']) . " / " . trim($row1['FIXHALF']) . "</td>\n";
if ($qrows2 >= 1) {print "<td>" . trim($row2a['FIXBATH']) . " / " . trim($row2a['FIXHALF']) . "</td>\n";}
if ($qrows2 >= 2) {print "<td>" . trim($row2b['FIXBATH']) . " / " . trim($row2b['FIXHALF']) . "</td>\n";}
if ($qrows2 >= 3) {print "<td>" . trim($row2c['FIXBATH']) . " / " . trim($row2c['FIXHALF']) . "</td>\n";}
if ($qrows2 >= 4) {print "<td>" . trim($row2d['FIXBATH']) . " / " . trim($row2d['FIXHALF']) . "</td>\n";}
print "</tr>\n<tr>\n";

print "<td>Exterior:</td>\n";
print "<td>" . trim($row1['EXTWALLdes']) . "</td>\n";
if ($qrows2 >= 1) {print "<td>" . trim($row2a['EXTWALLdes']) . "</td>\n";}
if ($qrows2 >= 2) {print "<td>" . trim($row2b['EXTWALLdes']) . "</td>\n";}
if ($qrows2 >= 3) {print "<td>" . trim($row2c['EXTWALLdes']) . "</td>\n";}
if ($qrows2 >= 4) {print "<td>" . trim($row2d['EXTWALLdes']) . "</td>\n";}
print "</tr>\n<tr>\n";

print "<td>Year Built:</td>\n";
print "<td>" . trim($row1['YRBLT']) . "</td>\n";
if ($qrows2 >= 1) {print "<td>" . trim($row2a['YRBLT']) . "</td>\n";}
if ($qrows2 >= 2) {print "<td>" . trim($row2b['YRBLT']) . "</td>\n";}
if ($qrows2 >= 3) {print "<td>" . trim($row2c['YRBLT']) . "</td>\n";}
if ($qrows2 >= 4) {print "<td>" . trim($row2d['YRBLT']) . "</td>\n";}
print "</tr>\n<tr>\n";

print "<td>2008 Total Appraisal:</td>\n";
print "<td>" . "$" . number_format(trim($row1['RTOTAPR']), 2, ".", ",") . "</td>\n";
if ($qrows2 >= 1) {print "<td>" . "$" . number_format(trim($row2a['RTOTAPR']), 2, ".", ",") . "</td>\n";}
if ($qrows2 >= 2) {print "<td>" . "$" . number_format(trim($row2b['RTOTAPR']), 2, ".", ",") . "</td>\n";}
if ($qrows2 >= 3) {print "<td>" . "$" . number_format(trim($row2c['RTOTAPR']), 2, ".", ",") . "</td>\n";}
if ($qrows2 >= 4) {print "<td>" . "$" . number_format(trim($row2d['RTOTAPR']), 2, ".", ",") . "</td>\n";}
print "</tr>\n<tr class='ui-state-hover'>\n";

print "<td>2009 Total Appraisal:</td>\n";
print "<td>" . "$" . number_format(trim($row1['2009TOTAPR']), 2, ".", ",") . "</td>\n";
if ($qrows2 >= 1) {print "<td>" . "$" . number_format(trim($row2a['2009TOTAPR']), 2, ".", ",") . "</td>\n";}
if ($qrows2 >= 2) {print "<td>" . "$" . number_format(trim($row2b['2009TOTAPR']), 2, ".", ",") . "</td>\n";}
if ($qrows2 >= 3) {print "<td>" . "$" . number_format(trim($row2c['2009TOTAPR']), 2, ".", ",") . "</td>\n";}
if ($qrows2 >= 4) {print "<td>" . "$" . number_format(trim($row2d['2009TOTAPR']), 2, ".", ",") . "</td>\n";}
print "</tr>\n<tr>\n";

print "<td>2009 Appraisal Per Square Foot:</td>\n";
$apsf = 0;
if ($row1['SFLA'] > 0) {$apsf = trim($row1['2009TOTAPR']) / trim($row1['SFLA']);}
print "<td>$" . number_format($apsf, 2, ".", ",") . "</td>\n";
if ($qrows2 >= 1) {
    $apsf = 0;
    if ($row2a['SFLA'] > 0) {$apsf = trim($row2a['2009TOTAPR']) / trim($row2a['SFLA']);}
    print "<td>$" . number_format($apsf, 2, ".", ",") . "</td>\n";
}
if ($qrows2 >= 2) {
    $apsf = 0;
    if ($row2b['SFLA'] > 0) {$apsf = trim($row2b['2009TOTAPR']) / trim($row2b['SFLA']);}
    print "<td>$" . number_format($apsf, 2, ".", ",") . "</td>\n";
}
if ($qrows2 >= 3) {
    $apsf = 0;
    if ($row2c['SFLA'] > 0) {$apsf = trim($row2c['2009TOTAPR']) / trim($row2c['SFLA']);}
    print "<td>$" . number_format($apsf, 2, ".", ",") . "</td>\n";
}
if ($qrows2 >= 4) {
    $apsf = 0;
    if ($row2d['SFLA'] > 0) {$apsf = trim($row2d['2009TOTAPR']) / trim($row2d['SFLA']);}
    print "<td>$" . number_format($apsf, 2, ".", ",") . "</td>\n";
}
print "</tr>\n";

print "\n</tbody></table>\n";

// end the print_area
print "</div>\n";

require "closedb.php";
require "analyticsTracking.php";
?>
    </body>
</html>

