<?php
/* 1. Creo el nuevo modelo que conectara a la base de datos
 * 2. Posteriormente, dentro del controlador del modelo (controllers/Usuarios.php) 
 * genero la función index() que me traerá los datos
 * 3. Genero la nueva vista dónde se mostraran los usuarios
 */
class SchedulesModel extends Model{
    public function __construct(){
        parent::__construct();
    }
    //extrae el listado de días de vacaciones de un usuario dentro de un periodo (SIN DEPENDENCIAS)
    public function getHolidaysForUser($firstDate,$secondDate,$id_User){
        $vacation_day="vacation_day";
        $sqlData="SELECT 
            hours_worked.date
        FROM hours_worked 
        INNER JOIN typedata ON hours_worked.id_day_type=typedata.id
        WHERE hours_worked.id_user = :id_User AND typedata.taxonomy= :vacation_day ";
        $pstData=$this->db->prepare($sqlData);
        $pstData->bindParam(":id_User", $id_User, PDO::PARAM_INT);
        $pstData->bindParam(":vacation_day", $vacation_day, PDO::PARAM_STR);
        $pstData->execute();
        $HolidaysForUser=$pstData->fetchAll();
        return $HolidaysForUser;
    }
    //extrae el número de días de vacaciones de un usuario dentro de un periodo (CON DEPENDENCIAS)
    public function getNumberHolidaysForUser($firstDate,$secondDate,$id_User){
        $HolidaysForUser=$this->getHolidaysForUser($firstDate,$secondDate,$id_User);
        $NumberHolidaysForUser=count($HolidaysForUser);
        return $NumberHolidaysForUser;
    }
    public function getArrayListUserPeriod($firstDate,$secondDate){
        //Lista de Usuarios
        $sqlUser="SELECT DISTINCT
                users.nickname,
                contacts.firstname,
                contacts.lastname,
                users.id,
                users.highdate,
                users.days_holidays_year
                FROM hours_worked 
                INNER JOIN users ON hours_worked.id_user = users.id
                INNER JOIN contacts ON users.id_contact=contacts.id
                WHERE hours_worked.date >= :firstDate AND hours_worked.date <= :secondDate
                ORDER BY users.nickname ASC";
        $pstUser=$this->db->prepare($sqlUser);
        $pstUser->bindParam(":secondDate", $secondDate, PDO::PARAM_STR);
        $pstUser->bindParam(":firstDate", $firstDate, PDO::PARAM_STR);
        $pstUser->execute();
        $ArrayListUserPeriod=$pstUser->fetchAll();
        return $ArrayListUserPeriod;
    }
    public function getPublicDaysForUser($firstDate,$secondDate,$id_User){
        $public_day="public_day";
        $sqlData="SELECT 
            hours_worked.date,
            hours_worked.id_day_type
        FROM hours_worked 
        INNER JOIN typedata ON hours_worked.id_day_type=typedata.id
        WHERE hours_worked.id_user = :id_User 
        AND typedata.taxonomy= :public_day 
        AND hours_worked.date >= :firstDate 
        AND hours_worked.date <= :secondDate";
        $pstData=$this->db->prepare($sqlData);
        $pstData->bindParam(":id_User", $id_User, PDO::PARAM_INT);
        $pstData->bindParam(":public_day", $public_day, PDO::PARAM_STR);
        $pstData->bindParam(":secondDate", $secondDate, PDO::PARAM_STR);
        $pstData->bindParam(":firstDate", $firstDate, PDO::PARAM_STR);
        $pstData->execute();
        $publicDaysForUser=$pstData->fetchAll();
        $dataPublicDays=array();
        foreach($publicDaysForUser as $row => $data){
            $hoursWorkedForDayOfWeek=$_SESSION['logged']['weekly_schedule'];
            $date=$publicDaysForUser[$row]['date'];
            $dataPublicDays[$date]=hoursWorkedInTheRangeEstimated($date,$date,$hoursWorkedForDayOfWeek);
        }
        $dataPublicDays['totalHoursPublicsDays']=addHoursArray($dataPublicDays);
        return $dataPublicDays;
    }
/*
AÑADIR LA INFORMACIÓN DE CADA USUARIO COMPLEMENTARIA QUE ESTABA EN EL CONTROLADOR

*/    
    public function getListUserPeriod($firstDate,$secondDate){
        // Extraemos datos de $_SESSION 
        $today=today();
        $id=$_SESSION['logged']['id'];
        $highdate=$_SESSION['logged']['highdate'];
        $days_holidays_year=$_SESSION['logged']['days_holidays_year'];
        $getNumberHolidaysForUser = $this->getNumberHolidaysForUser($highdate, $today, $id);
        $getPublicDaysForUser = $this->getPublicDaysForUser($firstDateDB, $secondDateDB, $id);
        $hoursWorkedInTheRangeEstimated = hoursWorkedInTheRangeEstimated($firstDateDB, $secondDateDB, $weeklySchedule);
        $weeklySchedule=$_SESSION['logged']['weekly_schedule'];
        // creo el array con los datos ordenados para mostrarlos primeramente con los datos del usuario logueado
        $listUserPeriod[$id]['id']=$id;
        $listUserPeriod[$id]['nickname']=$_SESSION['logged']['nickname'];
        $listUserPeriod[$id]['contact_name']=$_SESSION['logged']['firstname']." ".$_SESSION['logged']['lastname'];
        $listUserPeriod[$id]['highdate']=$highdate;
        $listUserPeriod[$id]['days_holidays_year'] = $days_holidays_year;
        $listUserPeriod[$id]['daysAccumulatedVacation'] = daysAccumulatedVacation($highdate, $days_holidays_year);
        $listUserPeriod[$id]['days_holidays_year']=$days_holidays_year;
        $listUserPeriod[$id]['getNumberHolidaysForUser']=$getNumberHolidaysForUser;
        $listUserPeriod[$id]['getPublicDaysForUser']=$getPublicDaysForUser;
        $listUserPeriod[$id]['hoursWorkedInTheRangeEstimated']=$hoursWorkedInTheRangeEstimated;
        $listUserPeriod[$id]['$weeklySchedule']=$weeklySchedule;
        $arrayListUserPeriod=$this->getArrayListUserPeriod($firstDate,$secondDate);
        $ThirdModel = new ThirdModel();
        $arrayListUserActive=$ThirdModel->getUser("TRUE");
        // si existe $arrayListUserPeriod y no está vacio reordenamelo
        if(isset($arrayListUserPeriod[0])){
            foreach ($arrayListUserPeriod as $row =>$value){
                $id=$arrayListUserPeriod[$row]['id'];
                $listUserPeriod[$id]['id']=$id;
                $nickname=$arrayListUserPeriod[$row]['nickname'];
                $contact_name=$arrayListUserPeriod[$row]['firstname']." ".$arrayListUserPeriod[$row]['lastname'];
                if($id==$_SESSION['logged']['id']) {
                    $highdate=$_SESSION['logged']['highdate'];
                    $days_holidays_year=$_SESSION['logged']['days_holidays_year'];
                }else{
                    $highdate=$arrayListUserPeriod[$row]['highdate'];
                    $days_holidays_year=$arrayListUserPeriod[$row]['days_holidays_year'];
                }
                $getNumberHolidaysForUser=$arrayListUserPeriod[$row]['getNumberHolidaysForUser'];
                $getPublicDaysForUser=$arrayListUserPeriod[$row]['getPublicDaysForUser'];
                $weeklySchedule=$arrayListUserPeriod[$row]['weeklySchedule'];
                $hoursWorkedInTheRangeEstimated=hoursWorkedInTheRangeEstimated($firstDateDB, $secondDateDB, $weeklySchedule);
                $reallyHoursWorkedInTheRangeEstimated = calculationsTime($getPublicDaysForUser['totalHoursPublicsDays'], $hoursWorkedInTheRangeEstimated, "Resta");
                $listUserPeriod[$id]['nickname']=$nickname; 
                $listUserPeriod[$id]['contact_name']=$contact_name; 
                $listUserPeriod[$id]['highdate']=$highdate;
                $listUserPeriod[$id]['days_holidays_year'] = $days_holidays_year; 
                $listUserPeriod[$id]['daysAccumulatedVacation'] = daysAccumulatedVacation($highdate, $days_holidays_year);
                $listUserPeriod[$id]['days_holidays_year']=$days_holidays_year;
                $listUserPeriod[$id]['getNumberHolidaysForUser']=$getNumberHolidaysForUser;
                $listUserPeriod[$id]['getPublicDaysForUser']=$getPublicDaysForUser;
                $listUserPeriod[$id]['hoursWorkedInTheRangeEstimated']=$hoursWorkedInTheRangeEstimated;
                $listUserPeriod[$id]['$weeklySchedule']=$weeklySchedule;
            }
        }else{
            foreach ($arrayListUserActive as $row =>$value){
                $id=$arrayListUserActive[$row]['id'];
                $listUserPeriod[$id]['id']=$id;
                $nickname=$arrayListUserActive[$row]['nickname'];
                $contact_name=$arrayListUserActive[$row]['firstname']." ".$arrayListUserActive[$row]['lastname'];
                $highdate=$arrayListUserActive[$row]['highdate'];
                $days_holidays_year=$arrayListUserActive[$row]['days_holidays_year'];
                $getNumberHolidaysForUser=$arrayListUserPeriod[$row]['getNumberHolidaysForUser'];
                $getPublicDaysForUser=$arrayListUserPeriod[$row]['getPublicDaysForUser'];
                $weeklySchedule=$arrayListUserPeriod[$row]['weeklySchedule'];
                $hoursWorkedInTheRangeEstimated=hoursWorkedInTheRangeEstimated($firstDateDB, $secondDateDB, $weeklySchedule);
                $reallyHoursWorkedInTheRangeEstimated = calculationsTime($getPublicDaysForUser['totalHoursPublicsDays'], $hoursWorkedInTheRangeEstimated, "Resta");
                $listUserPeriod[$id]['nickname']=$nickname; 
                $listUserPeriod[$id]['contact_name']=$contact_name; 
                $listUserPeriod[$id]['highdate']=$highdate;
                $listUserPeriod[$id]['days_holidays_year'] = $days_holidays_year; 
                $listUserPeriod[$id]['daysAccumulatedVacation'] = daysAccumulatedVacation($highdate, $days_holidays_year);
                $listUserPeriod[$id]['days_holidays_year']=$days_holidays_year;
                $listUserPeriod[$id]['getNumberHolidaysForUser']=$getNumberHolidaysForUser;
                $listUserPeriod[$id]['getPublicDaysForUser']=$getPublicDaysForUser;
                $listUserPeriod[$id]['hoursWorkedInTheRangeEstimated']=$hoursWorkedInTheRangeEstimated;
                $listUserPeriod[$id]['$weeklySchedule']=$weeklySchedule;
            }                    
        }
        //var_dump($listUserPeriod);
        return $listUserPeriod;
    }
    public function getArrayListDatePeriod($firstDateDB,$secondDateDB){
//      var_dump($firstDateDB);
//      var_dump($secondDateDB);
        //Lista de fechas
        $sqlDate="SELECT DISTINCT
                hours_worked.date
                FROM hours_worked 
                WHERE hours_worked.date >= :firstDate AND hours_worked.date <= :secondDate
                ORDER BY hours_worked.date ASC";
        $pstDate=$this->db->prepare($sqlDate);
        $pstDate->bindParam(":secondDate", $secondDateDB, PDO::PARAM_STR);
        $pstDate->bindParam(":firstDate", $firstDateDB, PDO::PARAM_STR);
        $pstDate->execute();
        $listDate=$pstDate->fetchAll(); 
//      var_dump($listDate); exit;
        return $listDate;

    }
    public function getListDatePeriod($firstDateDB,$secondDateDB){
        $arrayListDatePeriod=$this->getArrayListDatePeriod($firstDateDB,$secondDateDB);
        if(isset($arrayListDatePeriod[0])){
            foreach ($arrayListDatePeriod as $row =>$value){
                $date=$arrayListDatePeriod[$row]['date'];
                $newListDate[$date]=array();
            } 
        }else{
            $arrayListDatePeriod=allDaysInARangeOnlyWorkingDays($firstDateDB,$secondDateDB);
            $ListDatePeriod=array();
            foreach($arrayListDatePeriod as $row =>$value){
                $ListDatePeriod[$row]['date']=$arrayListDatePeriod[$row];
            }
            $arrayListDatePeriod=$ListDatePeriod;
            foreach ($arrayListDatePeriod as $row =>$value){
                $date=$arrayListDatePeriod[$row]['date'];
                $newListDate[$date]=array();
            } 
        }
//        var_dump($newListDate);
        return $newListDate;
    }


}
