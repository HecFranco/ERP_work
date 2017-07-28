<?php
class Third extends Controller{    
    function __construct() {
        parent::__construct();
    }
    public function getUser(){
        $active=$_GET["active"];
        ($active=="true")?$active=(bool)"true":$active=!((bool)"true");
        //Cargo el modelo
        $Loader=new LoadModel("ThirdModel");
        // creo una nueva instancia del modelo
        $ThirdModel = new ThirdModel();
        // cargo la función que me traerá los datos
        $getUser = $ThirdModel->getUser($active);
        // LLevo los datos a la nueva vista
        $ViewThird = new Layout("Third/getUser.php", compact("getUser"));
    }
}

