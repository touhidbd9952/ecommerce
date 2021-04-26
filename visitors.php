
<?php

function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}

?>


<?php
/*************************************************************************
php easy :: online visitors counter scripts set - PHP Include Version
*************************************************************************/
 
function CountVisitors() {
	
$dbfile = "visitors.txt"; // path to data file
$expire = 300; // average time in seconds to consider someone online before removing from the list
 
if(!file_exists($dbfile)) {
die("Error: Data file " . $dbfile . " NOT FOUND!");
}
 
if(!is_writable($dbfile)) {
die("Error: Data file " . $dbfile . " is NOT writable! Please CHMOD it to 666!");
}	

$cur_ip = getIP();
$cur_time = time();
$dbary_new = array();
 
$dbary = unserialize(file_get_contents($dbfile));
if(is_array($dbary)) 
{
	while(list($user_ip, $user_time) = each($dbary)) 
	{
		if(($user_ip != $cur_ip) && (($user_time + $expire) > $cur_time)) 
		{
			$user_location = ip_info("Visitor", "City").' '.ip_info("Visitor", "Country");
			
			$dbary_new[$user_ip] = $user_time.','.$user_location;
		}
	}
}
$dbary_new[$cur_ip] = $cur_time.','.ip_info("Visitor", "City").' '.ip_info("Visitor", "Country"); // add record for current user
 
$fp = fopen($dbfile, "w");
//echo $dbfile.'<br>';
//echo serialize($dbary_new);exit;
fputs($fp, serialize($dbary_new)); //serialize
fclose($fp);

//$out = count($dbary_new); 
$out = count($dbary); 
//$out = sprintf("%03d", count($dbary_new)); // format the result to display 3 digits with leading 0's

return $out;
}
 
function getIP() {
if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
elseif(isset($_SERVER['REMOTE_ADDR'])) $ip = $_SERVER['REMOTE_ADDR'];
else $ip = "0";
return $ip;
}
 
$visitors_online = CountVisitors(); 
?>
