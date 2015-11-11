<?php

// http://mcphp/PHPDEV/appraisal/ms-geocodeservice.php?address=1646 Linden Memphis TN

if (!empty($_POST)){
    $address = $_POST["address"];
}ELSE{
    $address = $_GET["address"];
}



//Virtual Earth Platform ID goes here.
$vepUID = '138482';

//Virtual Earth Platform password goes here.
$vepPWD = 'Teraaii0-maps';

$veToken = GetToken($vepUID,$vepPWD);
echo 'DEBUG: $veToken<br />';
echo "<pre>";
var_dump($veToken);
echo "</pre>";

//Get geocode
//Create soap client
$client = new SoapClient('ms-geocodeservice.wsdl');

//create credentials object and fill properties http://msdn.microsoft.com/en-us/library/cc966923.aspx
$credentials = array('Token' => $veToken);

//set geocoding query
$query = $address;
//$query = '4252 rainey woods, memphis, tn'; //one geocoderesult, multiple geocodelocation
//$query = 'springfield'; //multiple geocoderesult, single geocodelocation
echo 'DEBUG $query: ' . $query . '<br /><br />';

//create geocoderequest object and fill properties http://msdn.microsoft.com/en-us/library/cc980924.aspx
$geocodeRequest = array(
'Credentials' => $credentials,
'Query' => $query,
'Options' => array('Count' => 5));

//build geocode methods 'request' parameter
$geocode = array('request' => $geocodeRequest);

//GeoCode method http://msdn.microsoft.com/en-us/library/cc966817.aspx
$result = $client->Geocode($geocode);
echo 'DEBUG: $result<br />';
echo "<pre>";
var_dump($result);
echo "</pre>";

//Get GeocodeResponse  object from $result http://msdn.microsoft.com/en-us/library/cc980928.aspx
//get first GeocodeResult object as there will be more than one, starting with the most relevant. http://msdn.microsoft.com/en-us/library/cc980950.aspx
//get the GeocodeLocation object http://msdn.microsoft.com/en-us/library/cc966778.aspx

//test if one geocoderesult (non-array) or more (array)
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

//get the DisplayName of first match
echo 'DEBUG Displayname: ' . $a->DisplayName . '<br />';
echo 'DEBUG FormattedAddress: ' . $a->Address->FormattedAddress . '<br />';
echo 'DEBUG AddressLine: ' . $a->Address->AddressLine . '<br />';
echo 'DEBUG Locality: ' . $a->Address->Locality . '<br />';
echo 'DEBUG AdminDistrict: ' . $a->Address->AdminDistrict . '<br />';
echo 'DEBUG PostalCode: ' . $a->Address->PostalCode . '<br />';
echo 'DEBUG CountyRegion: ' . $a->Address->CountryRegion . '<br />';
echo 'DEBUG District: ' . $a->Address->District . '<br />';
echo 'DEBUG PostalTown: ' . $a->Address->PostalTown . '<br />';

//get the first Latitude value
echo 'DEBUG Latitude: ' . $b->Latitude . '<br />';

//get the first Longitude value
echo 'DEBUG Longitude: ' . $b->Longitude . '<br />';

//used to get Virtual Earth webservice token, returns token as string
function GetToken($vepUID,$vepPWD)
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
