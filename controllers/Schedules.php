<?php
class Schedules extends Controller{    
    function __construct() {
        parent::__construct();
    }
    public function getPeriod(){
//Extraemos el primer y ultimo día del mes para mostrar el rango inicial cuando no exista $_POST
        if(isset($_POST['reportrange'])){
            $extractDateRangeView=extractDateRangeView($_POST['reportrange']);
            $firstDate=$extractDateRangeView['0'];
            $secondDate=$extractDateRangeView['1'];
//            var_dump($firstDate);
//            var_dump($secondDate);
        }else{
//Calculamos el día de Hoy en formato vista
            $today = date('d/m/Y',strtotime(today()));
            $dataDate=dataDate($today);
            $firstDate=$dataDate['dateFirstDayMonth'];   
            $secondDate=$dataDate['dateLastDayMonth']; 
//            var_dump($firstDate);
//            var_dump($secondDate);
        }
        $firstDateDB=changeFormatDateViewtoBD($firstDate);
        $secondDateDB=changeFormatDateViewtoBD($secondDate);
        $reportRange=['firstDate'=>$firstDate,'secondDate'=>$secondDate];
        return $reportRange;
    }

    public function getSchedules(){
//Cargo el modelo
        $LoaderSchedules=new LoadModel("SchedulesModel");
        $LoaderThird=new LoadModel("ThirdModel");
        // creo una nueva instancia del modelo
        $SchedulesModel = new SchedulesModel();
        $ThirdModel = new ThirdModel();

//obtengo el rango de fechas
        $reportRange=$this->getPeriod();
        //extraigo las fechas del rango para convertirlas en fechas aptas para la BD
        $firstDateView=$reportRange['firstDate'];
        $secondDateView=$reportRange['secondDate'];
        $firstDateDB=changeFormatDateViewtoBD($firstDateView);
        $secondDateDB=changeFormatDateViewtoBD($secondDateView);
//        var_dump($firstDateDB);var_dump($secondDateDB);

        $getUserActive=$ThirdModel->getUser("TRUE");
        $listUserPeriod=$SchedulesModel->getListUserPeriod($firstDateDB,$secondDateDB);
        $getListDatePeriod = $SchedulesModel->getListDatePeriod($firstDateDB,$secondDateDB);
        // cargo la función que me traerá los datos
        $ViewSchedules = 
            new Layout("Schedules/getSchedules.php",
            compact(
                "reportRange",
                "getUserActive",
                "listUserPeriod",
                "getListDatePeriod"
                )
            );
    }

}

