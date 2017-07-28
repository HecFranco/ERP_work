<?php
/* 1. Creo el nuevo modelo que conectara a la base de datos
 * 2. Posteriormente, dentro del controlador del modelo (controllers/Usuarios.php) 
 * genero la función index() que me traerá los datos
 * 3. Genero la nueva vista dónde se mostraran los usuarios
 */
class HomeModel extends Model{
   public function getUsuarios(){
       echo "Hola Home";
   }
}
