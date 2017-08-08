function locateIp($ip){
    $d = file_get_contents("http://iplocationtools.com/ip_query.php?ip=$ip&output=raw");
    if (!$d)
        return false; // error al abrir conexion
    $d= explode(",",$d);
    if ($d[1] != 'OK')
        return false; // codigo de estatus no valido
    $country_code = $d[2];
    $country_name = $d[3];
    $region_name = $d[5];
    $city = $d[6];
    //$zippostalcode = $answer->ZipPostalCode;
    $latitude = $d[8];
    $longitude = $d[9];
    // Devuelve datos como array
    return array('latitude' => $latitude, 'longitude' => $longitude, 'city' => $city, 'region_name' => $region_name, 'country_name' => $country_name, 'country_code' => $country_code, 'ip' => $ip);
}

// Para obtener la IP del visitante
function getIP(){
    if( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] )) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if( isset( $_SERVER ['HTTP_VIA'] ))  $ip = $_SERVER['HTTP_VIA'];
    else if( isset( $_SERVER ['REMOTE_ADDR'] ))  $ip = $_SERVER['REMOTE_ADDR'];
    else $ip = null ;
    return $ip;
}

// Para obtener la IP del propio servidor
function ownIP(){
 $ip= file_get_contents('http://myip.eu/');
 $ip= substr($ip,strpos($ip,'<font size=5>')+14);
 $ip= substr($ip,0,strpos($ip,'<br'));
 return $ip;
}

// Obtiene la información y la muestra
$rec= locateIp(getIP());

// es posible que la API de Google necesite una key. Solicitar en
// http://code.google.com/intl/ca/apis/maps/signup.html
// y ubicar la clave tras "&key=CLAVE"
echo '<img style="border:1px solid black;" src="http://maps.google.com/staticmap?center='.$rec[latitude].','.$rec[longitude].'&markers='.$rec[latitude].','.$rec[longitude].',tinyblue&zoom=11&size=200x200&key=" /><br/>',$rec['city'], ',', $rec['country_code'];
