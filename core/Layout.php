<?php
class Layout{
    function __construct($view, $data=null){
        require("Config.php");

        $titlePage = $view;     // Define el título de la página en la que nos encontramos
        $page=$view;            // Señalará en NaV nuestra ubicación modificando la clase
        
// Decidimos si mostrar el HEADER, si estamos logueados y el archivo existe
        if(file_exists("./views/".$view)){
            if(file_exists("./views/Layout/".$header)){
                require("./views/Layout/".$header);                
            }else{
                die("Header no encontrado");
            }
            if(isset($_SESSION["logged"])){
                if(file_exists("./views/Layout/".$nav)){
                    require("./views/Layout/".$nav);                
                }else{
                    die("Nav no encontrado");
                } 
            }
/* VISTA ASIGNADA */
            require ("./views/".$view);
            
// Decidimos si mostrar el FOOTER, si estamos logueados y el archivo existe
            if(isset($_SESSION["logged"])){
                if(file_exists("./views/Layout/".$footer)){
                    require("./views/Layout/".$footer);                
                }else{
                    die("Footer no encontrado");
                }
            }
        }else{
            die("sitio no encontrado (View o Layout)");
        }
    }
}

