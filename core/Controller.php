<?php
// http://midominio.com/index.php?controller=controlador&action=metodo_a_llamar
class Controller{
	function __construct(){
        //Recibimos mediante la variable action el controlador que vamos a usar.
		if($_GET && isset($_GET["action"])){
			$action=$_GET["action"];
			if(method_exists($this, $action)){
				$this->$action();
			}else{
				die("Sitio no encontrado (controller/controllers.php).");
			}
		}else{
			if(method_exists($this, "index")){
				$this->index();
			}else{
				die("Índice no definido (controller/controllers.php).");
			}
		}
	}
}