<?php
function dieMsg($wayout, $aMsg)
{
    print "<div class='ui-widget body-class'>\n" .
  "<br />\n" .
  "<div class='ui-state-error ui-corner-all' style='padding: 0 .7em;'>\n" .
  "<p><span class='ui-icon ui-icon-alert' style='float: left; margin-right: .3em;'></span>\n" .
  "<strong>Alert:</strong> " . $aMsg . " <a href='" . $wayout . "' target='_parent'>(Click to Start Over)</a></p>\n" .
  "</div>\n" .
  "<br />\n" .
  "</div>";
    die('');
}
?>
