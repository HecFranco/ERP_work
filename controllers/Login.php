<?php
class Login extends Controller{ 
    public function __construct(){
        parent::__construct();
    }
    public function index(){
        $Model = new LoadModel("LoginModel");
        $Layout= new Layout("Login/index.php");
    }
    public function login(){
        
        if(isset($_POST["submit"])){
            $name = trim($_POST["name_user"]);
            $password = md5($_POST["password_user"]);
            //Cargo el modelo
            $Loader=new LoadModel("LoginModel");
            // creo una nueva instancia del modelo
            $LoginModel=new LoginModel();
            // cargo la función que me traerá los datos
            $LoginUser=$LoginModel->getUser($name,$password); 
            if($LoginUser && count($LoginUser) == 1){
                $_SESSION["logged"] = $LoginUser[0];
                $_SESSION["logged"]['Time']=date("Y-m-d G:i:s");
                if(isset($_SESSION["error_login"])){
                    unset($_SESSION["error_login"]);
                }
                header("Location: index.php?controller=Home&action=index");
            }else{
                $_SESSION["error_login"] = "Login incorrecto !!";
            }
        }
        header("Location: index.php");
    }
    public function logout(){
        if(isset($_SESSION["logged"])){
            	unset($_SESSION["logged"]);
        }
        header("Location: index.php?controller=Login&action=index");
    }
}

