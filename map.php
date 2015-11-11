<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<!--
map bible...
http://dev.live.com/virtualearth/sdk/

this will handle up to five addresses and map them

good info at the time of construction on map control:
http://www.viawindowslive.com/Articles/VirtualEarth/GettingStartedwithVersion6.aspx

-->

<head>
    <title>The CA - Shelby, TN, Reappraisal - Map Location</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- ALWAYS 1 ... css -->
<?php
require "cssfiles.php";
?>

    <!-- ALWAYS 2 ... collect arguments and correct errors -->
<?php
// php a couple of times to capture GET and POST
//   is all that is needed beyond the javascript
$loctext01 = $_POST["loctext01"];
if ($loctext01 == '') {$loctext01 = $_GET["loctext01"];}
$loctext02 = $_POST["loctext02"];
if ($loctext02 == '') {$loctext02 = $_GET["loctext02"];}
$loctext03 = $_POST["loctext03"];
if ($loctext03 == '') {$loctext03 = $_GET["loctext03"];}
$loctext04 = $_POST["loctext04"];
if ($loctext04 == '') {$loctext04 = $_GET["loctext04"];}
$loctext05 = $_POST["loctext05"];
if ($loctext05 == '') {$loctext05 = $_GET["loctext05"];}
?>

    <!-- ALWAYS 3 ... js -->
<?php
require "jscripts.php";
?>

    <!-- ALWAYS 4 ... jQuery -->
    <script type="text/javascript">
        <!--

        jQuery(function() {

            getMap();

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

        });

        -->
    </script>

    <!-- prevent the bird's eye popup message -->
    <style type="text/css">
        .header {
            visibility: hidden;
        }
        #MSVE_obliqueNotification {
            visibility: hidden;
        }
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
<?php
if ($loctext01) {print "locArr[0] = new Array ('" . $loctext01 . "', 'images//R.gif');\n";}
if ($loctext02) {print "locArr[1] = new Array ('" . $loctext02 . "', 'images//1.gif');\n";}
if ($loctext03) {print "locArr[2] = new Array ('" . $loctext03 . "', 'images//2.gif');\n";}
if ($loctext04) {print "locArr[3] = new Array ('" . $loctext04 . "', 'images//3.gif');\n";}
if ($loctext05) {print "locArr[4] = new Array ('" . $loctext05 . "', 'images//4.gif');\n";}
?>

        map = new VEMap('mymap');
        map.LoadMap(Mem, 10, '', false);
        layer = new VEShapeLayer();
        map.AddShapeLayer(layer);

        // click and find ... http://blogs.msdn.com/virtualearth/archive/2008/05/19/getting-addresses-using-aerial-photos.aspx
        map.AttachEvent("onclick", PixelClick);

        if (locArr.length > 0) {setPin(0);}
    }

    function PixelClick(e)
    {
        if (e.shiftKey)
        {
            var x = e.mapX;
            var y = e.mapY;
            pixel = new VEPixel(x, y);
            LL = map.PixelToLatLong(pixel);

            map.FindLocations(LL, GetResults);
        }
    }

    function GetResults(locations)
    {
        if(locations)
        {
            document.getElementById('loctext01').value = locations[0].Name;
        }
        else
        {
            document.getElementById('loctext01').value = "Address Unknown";
        }
    }

    function setPin(aLoc) {
        try {
            map.Find(null, locArr[aLoc][0], null, null, null, null, null, null, null, null,
            function(shapeLayer, findResults, places, moreResults, errorMsg) {
                if (null != places) {
                    var pin = new VEShape(VEShapeType.Pushpin, places[0].LatLong);
                    pin.SetCustomIcon("<div style='float:left;filter:alpha(opacity=100);-moz-opacity:1.0;opacity:1.0;'><img src='"+locArr[aLoc][1]+"'></div>");
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

<center>
<form id="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<table class='ui-widget property-request' width='100%'>
    <thead class='ui-widget-header property-request'>
        <tr>
            <th colspan="2">MAP A LOCATION</th>
        </tr>
    </thead>
    <tbody class='ui-widget-content property-request'>
    <tr>
        <td colspan="2">
            <div class='ui-widget' id="mymap" style="visibility: visible; position: relative; width: 100%; height: 350px; border: 1px solid;">
            </div>
        </td>
    </tr>
    <tr>
        <td><input class='fg-button ui-state-default ui-priority-primary ui-corner-all' type="submit" value="MAP ADDRESS" /></td>
        <td>
        <input type="text" size="40" id="loctext01" name="loctext01" value="<?php echo $loctext01; ?>" /></td>
    </tr>
    </tbody>
</table>
</form>
</center>
<?php
require ("analyticsTracking.php");
?>

</body>

</html>
