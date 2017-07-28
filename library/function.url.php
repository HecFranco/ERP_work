<?php
function url_base(){
    return $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_NAME"]."/";
}

/* función que generará las urls amigables por ti, basándonos en el título del artículo. */
function seo_url($vp_string){
   $vp_string = trim($vp_string);
   $vp_string = html_entity_decode($vp_string);
   $vp_string = strip_tags($vp_string);
   $vp_string = strtolower($vp_string);
   $vp_string = preg_replace('~[^ a-z0-9_.]~', ' ', $vp_string);
   $vp_string = preg_replace('~ ~', '-', $vp_string);
   $vp_string = preg_replace('~-+~', '-', $vp_string);
   $vp_string .= "/";
   return $vp_string;
}
// función anterior se le pasará el título del artículo como una cadena y devolverá la cadena de la url amigable. Tal que así:
// my-SEO-URL/
// Tendrás que almacenar esta URL en la misma columna que hemos creado en el paso anterior.
?>
