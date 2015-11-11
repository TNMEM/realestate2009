<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>The CA - Shelby, TN, Appraisal - Enter Property Elements</title>

        <!-- ALWAYS 1 ... css -->
<?php
require "cssfiles.php";
?>
        <!-- ALWAYS 2 ... collect arguments and correct errors -->

<!-- ALWAYS 3 ... js -->
<?php
require "jscripts.php";
?>

        <!-- ALWAYS 4 ... jQuery -->
        <script type="text/javascript">
            <!--
            jQuery(function() {

                getMap();

                // reset th
                jQuery('th').addClass('ui-widget-header property-request');

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
        <script type="text/javascript" src="//dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=6.2"></script>
        <script type="text/javascript">

            var map = null;
            var Mem = new VELatLong(35.152743356008,-90.05084643266);

            function getMap()
            {
                map = new VEMap('mymap');
                map.LoadMap(Mem, 10, '', false);

                // click and find ... http://blogs.msdn.com/virtualearth/archive/2008/05/19/getting-addresses-using-aerial-photos.aspx
                map.AttachEvent("onclick", PixelClick);
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

            // Some jQuery AJAX action
            function GetResults(locations)
            {
                if(locations)
                {
                    jQuery("input#stnumber").val("Fetching address ...");
                    jQuery.getJSON('ms-geocodesdk.php?geoAddress='+locations[0].Name, function(data) {
                        //dump(data,true);
                        jQuery("input#stnumber").val(data.AddressLine);
                    });
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

        <div class='ui-widget' id='mymap' style='visibility: visible; position: relative; width: 100%; height: 200px; border: 1px solid;'></div>
        <form action='listadds.php' method='post'>
            <table class='ui-widget property-request'>
                <thead class='ui-widget-header property-request'>
                    <tr>
                        <th colspan='2'>Enter One or More Property Elements</th>
                    </tr>
                </thead>
                <tbody class='ui-widget-content property-request'>
                    <tr>
                        <td>Street Number:</td>
                        <td><input type="text" size="40" id="stnumber" name="stnumber"/></td>
                    </tr>
                    <tr>
                        <td>Street Name:</td>
                        <td><input type="text" size="40" name="stname"/></td>
                    </tr>
                    <tr>
                        <td>Last Name:</td>
                        <td><input type="text" size="40" name="lname"/></td>
                    </tr>
                    <tr>
                        <td>Parcel ID:</td>
                        <td><input type="text" size="40" name="parcelid"/></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type='submit' class='fg-button ui-state-default ui-priority-primary ui-corner-all' value="Submit"/></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <!--
        <?php
        require ("analyticsTracking.php");
        ?>
        -->
    </body>
</html>
