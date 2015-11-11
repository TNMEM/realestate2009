<?php

// Testing...
// http://mcphp/PHPDEV/appraisal/ms-geocodesdk.php?geoAddress=1646 Linden Memphis TN

//$query = "1646 Linden Avenue Memphis TN 38104";
if (!empty($_POST)){
    $query = $_POST["geoAddress"];
}ELSE{
    $query = $_GET["geoAddress"];
}

// 
if ($query != '') {echo retAll($query);}
function retAll($query)
{
    $result = geoParseAdd($query);
    return json_encode($result);
}


// parse nasty address like "1646 linden memphis tn" ...
// ... then return PHP array of following
//    Displayname: 1642 Linden Ave, Memphis, TN 38104-3825
//    FormattedAddress: 1642 Linden Ave, Memphis, TN 38104-3825
//    AddressLine: 1642 Linden Ave
//    Locality: Memphis
//    AdminDistrict: TN
//    PostalCode: 38104-3825
//    CountyRegion: United States
//    District:
//    PostalTown:
//    Latitude: 35.134334
//    Longitude: -90.006907
function geoParseAdd($query)
{
    //Virtual Earth Platform ID goes here.
    $vepUID = '138482';

    //Virtual Earth Platform password goes here.
    $vepPWD = 'Teraaii0-maps';

    $veToken = geoGetToken($vepUID,$vepPWD);
    //echo 'DEBUG: $veToken<br />';
    //echo "<pre>";
    //var_dump($veToken);
    //echo "</pre>";

    //Get geocode
    //Create soap client
    $client = new SoapClient('ms-geocodeservice.wsdl');

    //create credentials object and fill properties http://msdn.microsoft.com/en-us/library/cc966923.aspx
    $credentials = array('Token' => $veToken);

    //$query = '4252 rainey woods, memphis, tn'; //one geocoderesult, multiple geocodelocation
    //$query = 'springfield'; //multiple geocoderesult, single geocodelocation
    //echo 'DEBUG $query: ' . $query . '<br /><br />';

    //create geocoderequest object and fill properties http://msdn.microsoft.com/en-us/library/cc980924.aspx
    $geocodeRequest = array(
  'Credentials' => $credentials,
  'Query' => $query,
  'Options' => array('Count' => 5));

    //build geocode methods 'request' parameter
    $geocode = array('request' => $geocodeRequest);

    //GeoCode method http://msdn.microsoft.com/en-us/library/cc966817.aspx
    $result = $client->Geocode($geocode);
    //echo 'DEBUG: $result<br />';
    //echo "<pre>";
    //var_dump($result);
    //echo "</pre>";

    //Get GeocodeResponse  object from $result http://msdn.microsoft.com/en-us/library/cc980928.aspx
    //get first GeocodeResult object ... the most relevant. http://msdn.microsoft.com/en-us/library/cc980950.aspx
    //get the GeocodeLocation object http://msdn.microsoft.com/en-us/library/cc966778.aspx

    //one geocoderesult (non-array) or more (array)
    if (is_array($result->GeocodeResult->Results->GeocodeResult))
    {
        $a = $result->GeocodeResult->Results->GeocodeResult[0];
    }
    else
    {
        $a = $result->GeocodeResult->Results->GeocodeResult;
    }
    //test if one geocodelocation (non-array) or more (array)
    if (is_array($a->Locations->GeocodeLocation))
    {
        $b = $a->Locations->GeocodeLocation[0];
    }
    else
    {
        $b = $a->Locations->GeocodeLocation;
    }

    // get ready for return of ret
    $ret = array (
  "Displayname" => $a->DisplayName,
  "FormattedAddress" => $a->Address->FormattedAddress,
  "AddressLine" => $a->Address->AddressLine,
  "Locality" => $a->Address->Locality,
  "AdminDistrict" => $a->Address->AdminDistrict,
  "PostalCode" => $a->Address->PostalCode,
  "CountyRegion" => $a->Address->CountryRegion,
  "District" => $a->Address->District,
  "PostalTown" => $a->Address->PostalTown,
  "Latitude" => $b->Latitude,
  "Longitude" => $b->Longitude);

    return $ret;
}


// get Virtual Earth webservice token as string
function geoGetToken($vepUID,$vepPWD)
{
    //create soap client, setting username and password
    $client = new SoapClient('ms-token.wsdl', array('login'=>$vepUID,'password'=>$vepPWD));
    $client_ip = $_SERVER['REMOTE_ADDR']; //not localhost

    //Build the tokenspecification object http://msdn.microsoft.com/en-us/library/cc966768.aspx
    $tokenSpecification = array(
  'ClientIPAddress' => $client_ip,
  'TokenValidityDurationMinutes' => 15);
    $getClientToken = array('specification' => $tokenSpecification);

    //call GetClientToken method of token service http://msdn.microsoft.com/en-us/library/cc980876.aspx
    $result = $client->GetClientToken($getClientToken);

    return $result->GetClientTokenResult;
}

?>
