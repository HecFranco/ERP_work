<?php
/* calculos horas */
/*
 * LISTADO DE FUNCIONES
 * hourToSecond($time) -> Convierte horas en segundos
 * secondToHour($time) -> Convierte segundos en horas
 * shortHour($time) -> Acorta el formato de horas de "H:m:s" a "H:m"
 * calculationsTime($time1,$time2,$typeOfCalculation) -> Calcula la suma o resta de tiempos según se indique "Suma" o "Resta"
 * addHoursArray($arrayHours) -> Suma el total de horas de un Array
 * hourWorkedOneDay($start,$end,$break,$typeOfDay='work_day') -> Calcula el número de horas trabajadas en un dia
 */
// Convierte horas en segundos 
function hourToSecond($timeInHours){
	$arrayTime=explode(":",$timeInHours);
    $seconds=$arrayTime[2];
    $minutes=$arrayTime[1];
	$hours=$arrayTime[0];
	$totalSeconds=($hours*(3600))+($minutes*(60))+$seconds;
    return $totalSeconds;
}
// Convierte segundos en horas
function secondToHour($time){
    $hours=floor($time/3600);
	if($hours<10){ $hours="0".$hours;}
    $minutes=floor(($time-($hours*3600))/60);
    if($minutes<10){ $minutes="0".$minutes;}
    $seconds=$time - ($minutes*60)-($hours*3600);
    if($seconds<10){ $seconds="0".$seconds;}
    $calculationTime=$hours.":".$minutes.":".$seconds;
	return $calculationTime;
}
// Acorta el formato de horas de "H:m:s" a "H:m"
function shortHour($time){
    $time=explode(":",$time);
    $hours=$time[0];
    $minutes=$time[1];
    $shortTime=$hours.":".$minutes;
    return $shortTime;
}
// Calcula la suma o resta de tiempos según se indique "Suma" o "Resta"
function calculationsTime($time1,$time2,$typeOfCalculation){
    $time1=hourToSecond($time1);
	  $time2=hourToSecond($time2);
    switch ($typeOfCalculation){
		  case "Suma":
		    $time=$time1+$time2;
        break;
	    case "Resta":
		    $time=abs($time1-$time2);
        break;
	    }
	  $calculationTime=secondToHour($time);    
	  return $calculationTime;
}
// Suma el total de horas de un Array
function addHoursArray($arrayHours){
    $hoursTotal="00:00:00";
    $date=0;
    foreach($arrayHours as $date =>$value){
        $date++;
        if($date==0){
            $hoursTotal=calculationsTime($hoursTotal,$value,"Suma");
        }else{
            $hoursTotal=calculationsTime($hoursTotal,$value,"Suma");
        }
        return $hoursTotal;
    }
}
// Calcula el número de horas trabajadas en un dia
function hourWorkedOneDay($start,$end,$break,$typeOfDay='work_day'){
	$hourWorkedWithoutBreak=calculationsTime($end,$start,"Resta");
	$vacationDayHours="08:30:00";
	if($typeOfDay=="vacation_day"){
		$hourWorkedWithoutBreak=calculationsTime($hourWorkedWithoutBreak,$vacationDayHours,"Suma");
	}
	$hourWorked=calculationsTime($hourWorkedWithoutBreak,$break,"Resta");
	return $hourWorked;
}

?>
