<?php
class Home extends Controller{    
    function __construct() {
        parent::__construct();
    }
    public function index(){
        //Cargo el modelo
        $Loader=new LoadModel("HomeModel");
        // creo una nueva instancia del modelo
        $HomeModel = new HomeModel();
        // cargo la función que me traerá los datos
        // LLevo los datos a la nueva vista
        $ViewHome = new Layout("Home/index.php");
    }
}

