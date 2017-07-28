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
    public function getListUserPeriod($firstDate,$secondDate,$userId){
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
        $listUser=$pstUser->fetchAll();
        //cargo los datos del usuario logueado cuando no cuando no aparece en la búsqueda
        $id=$_SESSION['logged']['id'];
        $highdate=$_SESSION['logged']['highdate'];
        $days_holidays_year=$_SESSION['logged']['days_holidays_year'];
        $newListUser[$id]['days_holidays_year'] = $days_holidays_year;
        $newListUser[$id]['daysAccumulatedVacation'] = daysAccumulatedVacation($highdate, $days_holidays_year);
        foreach ($listUser as $row =>$value){
            $id=$listUser[$row]['id'];
            $nickname=$listUser[$row]['nickname'];
            $contact_name=$listUser[$row]['firstname']." ".$listUser[$row]['lastname'];
            $newListUser[$id]['nickname']=$nickname;
            $newListUser[$id]['id']=$id;
            $newListUser[$id]['contact_name']=$contact_name;
            $newListUser[$id]['highdate']=$highdate;
            if($id==$_SESSION['logged']['id']) {
                $highdate=$_SESSION['logged']['highdate'];
                $days_holidays_year=$_SESSION['logged']['days_holidays_year'];
            }else{
                $highdate=$listUser[$row]['highdate'];
                $days_holidays_year=$listUser[$row]['days_holidays_year'];
            }
            $newListUser[$id]['days_holidays_year'] = $days_holidays_year;
            $newListUser[$id]['daysAccumulatedVacation'] = daysAccumulatedVacation($highdate, $days_holidays_year);
        }
         //si no hay resultados en la búsqueda
        if(!isset($newListUser)){ $newListUser="empty";}
        //var_dump($newListUser);
        return $newListUser;
    }
    public function getListDatePeriod($firstDate,$secondDate){
//      var_dump($firstDate);
//      var_dump($secondDate);
        //Lista de fechas
        $sqlDate="SELECT DISTINCT
                hours_worked.date
                FROM hours_worked 
                WHERE hours_worked.date >= :firstDate AND hours_worked.date <= :secondDate
                ORDER BY hours_worked.date ASC";
        $pstDate=$this->db->prepare($sqlDate);
        $pstDate->bindParam(":secondDate", $secondDate, PDO::PARAM_STR);
        $pstDate->bindParam(":firstDate", $firstDate, PDO::PARAM_STR);
        $pstDate->execute();
        $listDate=$pstDate->fetchAll(); 
//      var_dump( $listDate);
        foreach ($listDate as $row =>$value){
            $date=$listDate[$row]['date'];
            $newListDate[$date]=array();
        }
         //si no hay resultados en la búsqueda
        if(!isset($newListUser)){ $newListUser="empty";}
        return $newListDate;
//      var_dump($listDate); exit;
    }

    public function getSummaryHoursWorked($firstDate,$secondDate,$userId){
        $sqlData = "SELECT 
                   hours_worked.id,
                   hours_worked.input,
                   hours_worked.output,
                   hours_worked.break,
                   typedata.taxonomy as typeOfDay
                FROM hours_worked 
                INNER JOIN users ON hours_worked.id_user = users.id
                INNER JOIN typedata ON hours_worked.id_day_type = typedata.id
                WHERE hours_worked.date >= :firstDate 
                AND hours_worked.date <= :secondDate 
                AND users.id = :id_user";
        $pstData = $this->db->prepare($sqlData);
        $pstData->bindParam(":firstDate", $firstDate, PDO::PARAM_STR);
        $pstData->bindParam(":secondDate", $secondDate, PDO::PARAM_STR);
        $pstData->bindParam(":id_user", $userId, PDO::PARAM_STR);
        $pstData->execute();
        $hours = $pstData->fetchAll();
        if (is_array($hours) || is_object($hours)) {
            foreach ($hours as $date =>$value) {
                $input = $hours[$date]['input'];
                $output = $hours[$date]['output'];
                $break = $hours[$date]['break'];
                $typeOfDay = $hours[$date]['typeOfDay'];
                if($typeOfDay==="vacation_day"){
                    $hoursWorked[$date]="08:30:00";
                }else{
                    $hoursWorked[$date] = hourWorkedOneDay($input, $output, $break, $typeOfDay);
                }
            }
        }
        $summaryHoursWorkedReally=summaryHoursWorkedReally($hoursWorked);
        return $summaryHoursWorkedReally;
    }

    public function getDataSchedules($firstDate,$secondDate,$userId){
        $getListUserPeriod=$this->getListUserPeriod($firstDate,$secondDate,$userId);
        $getListDatePeriod=$this->getListDatePeriod($firstDate,$secondDate);
        $dataSchedules=array();
        //Datos
        foreach($getListUserPeriod as $userId => $userInfo){
            $dataSchedules[$userId]['datesInfo']=$getListDatePeriod;
            foreach($dataSchedules[$userId]['datesInfo'] as $date => $dataDate) {
//              var_dump($User);
                $id_user = $userId;
//              var_dump($id_user);
                $sqlData = "SELECT 
                   hours_worked.id,
                   hours_worked.input,
                   hours_worked.output,
                   hours_worked.break,
                   typedata.taxonomy
                FROM hours_worked 
                INNER JOIN users ON hours_worked.id_user = users.id
                INNER JOIN typedata ON hours_worked.id_day_type = typedata.id
                WHERE hours_worked.date = :date AND users.id = :id_user";
                $pstData = $this->db->prepare($sqlData);
                $pstData->bindParam(":date", $date, PDO::PARAM_STR);
                $pstData->bindParam(":id_user", $id_user, PDO::PARAM_STR);
                $pstData->execute();
                $hours = $pstData->fetchAll();
//              var_dump($hours);
                foreach($hours as $row=>$values){
                    $input=$hours[$row]['input'];
                    $output=$hours[$row]['output'];
                    $break=$hours[$row]['break'];
                    $typeOfDay=$hours[$row]['taxonomy'];
                    $dataSchedules[$userId]['datesInfo'][$date]['input']=$input;
                    $dataSchedules[$userId]['datesInfo'][$date]['output']=$output;
                    $dataSchedules[$userId]['datesInfo'][$date]['break']=$break;
                    $dataSchedules[$userId]['datesInfo'][$date]['totalhour']=hourWorkedOneDay($input,$output,$break,$typeOfDay);
                    $dataSchedules[$userId]['datesInfo'][$date]['typeofday']=$typeOfDay;
//                  var_dump($userId);var_dump($date);var_dump($dataSchedules[$userId]['datesInfo'][$date]);exit;
                }
            }
        }
        //si no hay resultados en la búsqueda
        if(!isset($dataSchedules)){ $dataSchedules="empty";}
//      var_dump($data['2017-06-09']['0']); exit;
        return $dataSchedules;
    }
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
    
    public function getNumberHolidaysForUser($firstDate,$secondDate,$id_User){
        $HolidaysForUser=$this->getHolidaysForUser($firstDate,$secondDate,$id_User);
        $NumberHolidaysForUser=count($HolidaysForUser);
        return $NumberHolidaysForUser;
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
    
    public function getNumberPublicDaysForUser($firstDate,$secondDate,$id_User){
        $PublicDaysForUser=$this->getPublicDaysForUser($firstDate,$secondDate,$id_User);
        $NumberPublicDaysForUser=count($PublicDaysForUser);
        return $NumberPublicDaysForUser;
    }

    public function setUserDataDate($idUserSetDate,$dateUserSetDate,$inputUserSetDate,$outputUserSetDate,$breakUserSetDate,$idTypeOfDay){
        $todayTime=todayTime();
        $sqlData="SELECT 
            hours_worked.date,
            hours_worked.id_user,
            hours_worked.id
        FROM hours_worked 
        WHERE hours_worked.id_user = :id_User 
        AND hours_worked.date= :date ";
        $pstData=$this->db->prepare($sqlData);
        $pstData->bindParam(":id_User", $idUserSetDate, PDO::PARAM_INT);
        $pstData->bindParam(":date", $dateUserSetDate, PDO::PARAM_STR);
        $pstData->execute();
        $existThisData=$pstData->fetchAll();
        if(!isset($existThisData[0])){
            $id=NULL;
            $sqlData="INSERT INTO hours_worked (id, date,input,output,break,id_day_type,id_user,date_entered)
            VALUES  (:id, :dateUserSetDate, :inputUserSetDate, :outputUserSetDate, :breakUserSetDate, :idTypeOfDay, :idUser, :today)";
            $notification= "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" 
                            style='position: absolute;right: 0px;top: 100px;'
                            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                                <span aria-hidden=\"true\">&times;</span>
                            </button>
                            <strong>Envio correcto</strong> 
                            NO existe dicha fecha. 
                            </div>";
        }else{
            $idHoursData=$existThisData[0]['id'];
            $sqlData="UPDATE hours_worked SET 
                    id= :idHoursData,
                    date= :dateUserSetDate,
                    input= :inputUserSetDate,
                    output= :outputUserSetDate,
                    break= :breakUserSetDate,
                    id_day_type= :idTypeOfDay,
                    id_user= :idUser,
                    date_entered= :today,
                    date_consolidation=NULL,
                    date_pay=NULL
                    WHERE hours_worked.id= :idHoursData";
            $notification= "<div class=\"alert alert-success alert-dismissible\" role=\"alert\" 
                            style='position: absolute;right: 0px;top: 100px;'
                            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                                <span aria-hidden=\"true\">&times;</span>
                            </button>
                            <strong>Envio correcto</strong> 
                            Fecha sustituida correctamente. 
                            </div>";
        }
        $pstData=$this->db->prepare($sqlData);
        $pstData->bindParam(":idHoursData", $idHoursData, PDO::PARAM_INT);
        $pstData->bindParam(":id", $id, PDO::PARAM_INT);
        $pstData->bindParam(":idUser", $idUserSetDate, PDO::PARAM_INT);
        $pstData->bindParam(":idTypeOfDay", $idTypeOfDay, PDO::PARAM_INT);
        $pstData->bindParam(":dateUserSetDate", $dateUserSetDate, PDO::PARAM_STR);
        $pstData->bindParam(":inputUserSetDate", $inputUserSetDate, PDO::PARAM_STR);
        $pstData->bindParam(":outputUserSetDate", $outputUserSetDate, PDO::PARAM_STR);
        $pstData->bindParam(":breakUserSetDate", $breakUserSetDate, PDO::PARAM_STR);
        $pstData->bindParam(":today", $todayTime, PDO::PARAM_STR);
        $pstData->execute();
        return $notification;

    }
}
