<?php
require("core/Controller.php");
require("core/Config.php");
require("core/Views.php");
require("core/Model.php");
require("core/LoadModel.php");
require("core/Layout.php");
require("core/Functions.php");

if(!session_start()){
    session_start();
}
?>
    <!-- Script para notificaciones -->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function() {
            $(".content").fadeOut(1500);
        },3000);
    });
</script>
    <!-- Fin de Script para notificaciones -->
<?php
// Usuario Logueado ?
// Está definida La variable / existe sesión?
// Existe el Controlador ?
if(isset($_SESSION["logged"])){
    if(isset($_GET) && isset($_GET["controller"])){
        $default_controller=$_GET["controller"];
        // evitamos al volver hacia atrás podernos loguear de nuevo
        if($_GET["controller"]=="Login" && $_GET["action"]=="index"){
            $default_controller="Home";
            $_GET["controller"]=$default_controller;
        }
        if(file_exists("controllers/".$_GET["controller"].".php")){
            require("controllers/".$_GET["controller"].".php");       
        }else{
            die("controlador no encontrado ({$_GET["controller"]})");
        }
    }else{
        $default_controller = "Home";
        if(file_exists("controllers/".$default_controller.".php")){
            require("controllers/".$default_controller.".php");       
        }else{
            die("controlador por defecto no encontrado ({$default_controller})");
        }
    }
}else{
    $default_controller ="Login"; 
    if(file_exists("controllers/".$default_controller.".php")){
        require("controllers/".$default_controller.".php");       
    }else{
        die("controlador por defecto no encontrado ({$default_controller})");
    }
}
//Cargo el controlador por defecto
$UsingSController=new $default_controller();
