<?php
class Configuration extends Controller{    
    function __construct() {
        parent::__construct();
    }
    public function getTaxonomies(){
        //Cargo el modelo
        $Loader=new LoadModel("ConfigurationModel");
        // creo una nueva instancia del modelo
        $ConfigurationModel = new ConfigurationModel();
        // cargo la función que me traerá los datos
        $Taxonomies = $ConfigurationModel->getTaxonomies();
        // LLevo los datos a la nueva vista
        $ViewConfiguration = new Layout("Configuration/getTaxonomies.php", compact("Taxonomies"));
    }
}

