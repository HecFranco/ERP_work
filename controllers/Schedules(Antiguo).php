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
    public function getListUserPeriod($firstDate,$secondDate){
        var_dump($firstDate);
        $LoaderSchedules=new LoadModel("SchedulesModel");
        $SchedulesModel= new SchedulesModel();
        $listUserPeriod = $SchedulesModel->getListUserPeriod($firstDate,$secondDate);
        var_dump($listUserPeriod);
        foreach($ListUserPeriod as $user=>$values){
            $id=$ListUserPeriod[$user]['id'];
            $ListUserPeriodOrder[$id]['nickname']=$ListUserPeriod[$user]['nickname'];
        }
        //si no hay resultados en la búsqueda
        if(!isset($ListUserPeriodOrder)){ $ListUserPeriodOrder="empty";}
        var_dump($ListUserPeriodOrder); 
        return $ListUserPeriodOrder;
    }
    
    public function getListDatePeriod($firstDate,$secondDate){
        $LoaderSchedules=new LoadModel("SchedulesModel");
        $SchedulesModel = new SchedulesModel();
        $ListDatePeriod = $SchedulesModel->getListDatePeriod($firstDateDB,$secondDateDB);
//      var_dump($ListDatePeriod); 
        return $ListDatePeriod;
    }
    public function getNumberGeneratedHolidayUser($highDateUser,$today,$idUser){
        $LoaderThird=new LoadModel("ThirdModel");
        $ThirdModel = new ThirdModel();
        $getNumberGeneratedHolidayUser=$ThirdModel->getNumberGeneratedHolidayUser($highDateUser,$today,$idUser);
        return $getNumberGeneratedHolidayUser;
    }
    public function getSchedules(){
        $today=today();
        $idUserLogin=$_SESSION['logged']['id'];
        $highDateUser=$_SESSION['logged']['highdate'];
        $weeklySchedule=$_SESSION['logged']['weekly_schedule'];
        $reportRange=$this->getPeriod();
        $firstDateView=$reportRange['firstDate'];
        $secondDateView=$reportRange['secondDate'];
        $firstDateDB=changeFormatDateViewtoBD($firstDateView);
        $secondDateDB=changeFormatDateViewtoBD($secondDateView);
        //Cargo el modelo
        $LoaderSchedules=new LoadModel("SchedulesModel");
        $LoaderUser=new LoadModel("ThirdModel");
        $LoaderUser=new LoadModel("ConfigurationModel");
        // creo una nueva instancia del modelo
        $SchedulesModel = new SchedulesModel();
        $ThirdModel = new ThirdModel();
        $ConfigurationModel= new ConfigurationModel();
        // cargo la función que me traerá los datos
        $getListUserPeriod = $SchedulesModel->getListUserPeriod($firstDateDB,$secondDateDB,$idUserLogin);
        $getListDatePeriod = $SchedulesModel->getListDatePeriod($firstDateDB,$secondDateDB);
        $getUserActive = $ThirdModel->getUser("true");
        $getDataSchedules = $SchedulesModel->getDataSchedules($firstDateDB, $secondDateDB, $idUser);
        foreach($getListUserPeriod as $idUser => $dataUserSchedules) {
            $highDateUser = $getListUserPeriod[$idUser][`highdate`];
            $getNumberHolidaysForUser = $SchedulesModel->getNumberHolidaysForUser($highDateUser, $today, $idUser);
            $getPublicDaysForUser = $SchedulesModel->getPublicDaysForUser($firstDateDB, $secondDateDB, $idUser);
            $hoursWorkedInTheRangeEstimated = hoursWorkedInTheRangeEstimated($firstDateDB, $secondDateDB, $weeklySchedule);
            $reallyHoursWorkedInTheRangeEstimated = calculationsTime($getPublicDaysForUser['totalHoursPublicsDays'], $hoursWorkedInTheRangeEstimated, "Resta");
            $getListUserPeriod[$idUser]['numberHolidaysForUser'] = $getNumberHolidaysForUser;
            $getListUserPeriod[$idUser]['publicDaysForUser'] = $getPublicDaysForUser;
            $getListUserPeriod[$idUser]['hoursWorkedInTheRangeEstimated'] = $hoursWorkedInTheRangeEstimated;
            $getListUserPeriod[$idUser]['reallyHoursWorkedInTheRangeEstimated'] = $reallyHoursWorkedInTheRangeEstimated;
            $getListUserPeriod[$idUser]['summaryHoursWorkedReally'] = $SchedulesModel->getSummaryHoursWorked($firstDateDB,$secondDateDB,$idUser);
            $summaryHoursWorkedReally=$getListUserPeriod[$idUser]['summaryHoursWorkedReally'] ;
            $getListUserPeriod[$idUser]['percentHoursWorked']=
                round((hourToSecond($summaryHoursWorkedReally)/hourToSecond($reallyHoursWorkedInTheRangeEstimated))*100,2);
        }
        $typeOfDays=$ConfigurationModel->getDataTaxonomy("day_type");
        if(isset($_POST['submitInfoHoursWorked'])) {
            $idUserSetDate=$_POST['idUserSetDate'];
            $dateUserSetDate=$_POST['dateSetDate'];
            $inputUserSetDate=$_POST['inputSetDate'];
            $outputUserSetDate=$_POST['outputSetDate'];
            $breakUserSetDate=$_POST['breakSetDate'];
            $idTypeOfDay=$_POST['typeOfDay'];
            $insertDataForm=$SchedulesModel->setUserDataDate($idUserSetDate,$dateUserSetDate,$inputUserSetDate,$outputUserSetDate,$breakUserSetDate,$idTypeOfDay);
        }
        // LLevo los datos a la nueva vista
        $ViewSchedules = new Layout(
            "Schedules/getSchedules.php",
            compact(
                "reportRange",
                "getListUserPeriod",
                "getListDatePeriod",
                "getUserActive",
                "getDataSchedules",
                "getNumberHolidaysForUser",
                "getPublicDaysForUser",
                "hoursWorkedInTheRange",
                "reallyHoursWorkedInTheRange",
                "typeOfDays",
                "insertDataForm")
        );
    }
    public function setSchedules(){
        //Cargo el modelo
        $LoaderSchedules=new LoadModel("SchedulesModel");
        $LoaderUser=new LoadModel("ThirdModel");
        // creo una nueva instancia del modelo
        $SchedulesModel = new SchedulesModel();
        // cargo la función que insertará los datos
        $insertData=$SchedulesModel->setUserDataDate($id_userActive,$date,$input,$output,$break);


    }
}

