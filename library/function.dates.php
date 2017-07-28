<?php
/* calculos fechas */
/*
 * LISTADO DE FUNCIONES
 * today() -> Devuelve la fecha actual (Y-m-d)
 * todayTime() -> Devuelve la fecha actual (Y-m-d h:i:s)
 * isFuture($date) -> Devuelve si una fecha introducida es futura (TRUE) o pasada (FALSE) (hoy es pasado)
 * oneMonthAgo($date) -> Devuelve la fecha introducida menos un mes (Y-m-d)
 * dateTimeToDate($datetime) -> Devuelve una fecha en formato Base de Datos
 * firstAndLastDayOfMonth($date) -> Devuelve un array con el primer y último día del mes de la fecha introducida
 * changeFormatDateBDToView($dateDB) -> Cambia el formato de una fecha a modo vista. Convierte el formato de fecha 17-08-2017 en 17/08/2017
 * changeFormatDateViewToBD($dateView) -> Cambia el formato de una fecha a modo base de datos. Convierte el formato de fecha 17/08/2017 en 17-08-2017
 * extractDateRangeView($daterangepicker) -> Devuelve un array con las dos fechas de un rango de fechas
 * dataRangeNumberOfDaysOfWeek($firstDateDB,$secondDateDB) -> Recibe dos fechas y devuelve un array con el número de días de la semana que hay en ese intervalo
 * addOneDay($date) -> Devuelve una fecha con un día más
 * subtractOneDay($date) -> Devuelve una fecha con un día menos
 * hoursWorkedInTheRangeEstimated($firstDateDB,$secondDateDB,$hoursWorkedForDayOfWeek) -> Devuelve el total de horas mes trabajadas necesita un array con el horario $hoursWorkedForDayOfWeek que empieza por domingo
 * summaryHoursWorkedReally ($arrayHours) -> Devuelve el total de horas trabajadas introducidas mediante un array
 * daysAccumulatedVacation ($dateStartToWork,$daysHolidaysYear) -> Devuelve el total de días de vacaciones generadas hasta final de año
 * daysAccumulatedVacationReally ($dateStartToWork,$daysHolidaysYear) -> Devuelve el total de días de vacaciones generadas hasta la fecha actual
 * daysApart($firstDateDB,$secondDateDB) -> Devuelve el número de dias que hay entre dos fechas
 * isWeekend($date) -> Devuelve que día de la semana es una fecha (0-Lunes)
 * allDaysInARange($firstDateDB,$secondDateDB) -> Devuelve todos las fechas existentes dentro de un rango de dos fechas
 * allDaysInARangeOnlyWorkingDays($firstDateDB,$secondDateDB)-> Devuelve todas las fechas laborables dentro de un rango de dos fechas
 */
// Devuelve la fecha actual (Y-m-d)
function today(){
    $today=date("Y-m-d");  
    return $today;
}
// Devuelve la fecha actual (Y-m-d h:i:s)
function todayTime(){
    $today=date("Y-m-d h:i:s");
    return $today;
}
// Devuelve si una fecha introducida es futura (TRUE) o pasada (FALSE) (hoy es pasado)
function isFuture($date){
    $today=date("Y-m-d");
    $diffDays=(strtotime($today)-strtotime($date))/(60*60*24);
    $isFuture=($diffDays<0)?"TRUE":"FALSE";
    return $isFuture;
}

// Devuelve la fecha introducida menos un mes (Y-m-d)
function oneMonthAgo($date){
    $todayYear = date("Y",strtotime($date));
    $todayMonth = date("m",strtotime($date));
    $todayDay = date("d",strtotime($date));
    $oneMonthAgo=$todayYear."-".($todayMonth-1)."-".($todayDay+1);
    $dateRangePicker = date("Y-m-d",strtotime($oneMonthAgo));  
    return $dateRangePicker;
}
// Devuelve una fecha en formato Base de Datos
function dateTimeToDate($datetime){
    $date=date("Y-m-d",strtotime($datetime));
	return $date;
}
// Devuelve un array con el primer y último día del mes de la fecha introducida
function firstAndLastDayOfMonth($date){
	// número de días que tiene el mes dado
	$numberDaysMonth = date ( "t", strtotime ($date) );
	// número mes del año
	$monthN = substr ($date,-7,2);
	// año número 4 dígitos
	$year4 = substr ($date,-4);
	// Fecha primer día del primer día del mes
	$dateFirstDayMonth=date("d/m/Y",mktime(0, 0, 0, $monthN, 1, $year4));
	// Fecha último día del primer día del mes
    $dateLastDayMonth=date("d/m/Y",mktime(0, 0, 0, $monthN, $numberDaysMonth, $year4));
	// RESUMEN DE DATOS
    $data_Date=[
		'dateFirstDayMonth'=>$dateFirstDayMonth,
		'dateLastDayMonth'=>$dateLastDayMonth
	];
	return $data_Date;
}
// Cambia el formato de una fecha a modo vista.
// Convierte el formato de fecha 17-08-2017 en 17/08/2017
function changeFormatDateBDToView($dateBD){
    $dateArray=implode("-", $dateBD);
    $year=$dateArray[0];
    $month=$dateArray[1];
    $day=$dateArray[2];
    $dateView=$day."/".$month."/".$year;
	return $dateView;
}
// Cambia el formato de una fecha a modo base de datos
// Convierte el formato de fecha 17/08/2017 en 17-08-2017
function changeFormatDateViewToBD($dateView){
    $dateArray=explode("/",$dateView);
    $day=$dateArray[0];
    $month=$dateArray[1];
    $year=$dateArray[2];
    $dateBD=$year."-".$month."-".$day;
    return $dateBD;
}
// Devuelve un array con las dos fechas de un rango de fechas
function extractDateRangeView($daterangepicker){
	$start=substr($daterangepicker,0,10);
	$end=substr($daterangepicker,13,24);
	$daterangepicker=array($start,$end);
	return $daterangepicker;
}

// Recibe dos fechas y devuelve un array con el número de días de la semana que hay en ese intervalo
function dataRangeNumberOfDaysOfWeek($firstDateDB,$secondDateDB){
	$firstDateDB=date("Y-m-d",strtotime($firstDateDB));
	$firstDayOfWeek=date("w",strtotime($firstDateDB));
	$secondDateDB=date("Y-m-d",strtotime($secondDateDB));
	$secondForDay=60*60*24; //segundos que tiene un día
	$interval=(strtotime($secondDateDB)-strtotime($firstDateDB))/$secondForDay;
	$interval=abs($interval);
    $numberDaysWeekForInterval=[0,0,0,0,0,0,0];
	$numberOfWeek=0;
	for ($i=0;$i<$interval;$i++){
		$dayToCalculate=$i+$firstDayOfWeek;
		$dayWeek=round((($dayToCalculate/7)-floor($dayToCalculate/7))*7);
		if($dayWeek==$firstDayOfWeek){ $numberOfWeek++;	}
		$numberDaysWeekForInterval[$dayWeek]=$numberOfWeek;
	}
	return $numberDaysWeekForInterval;
}

// Devuelve una fecha con un día más
function addOneDay($date){
    $date = strtotime ( '+1 day' , strtotime ( $date ) ) ;
    $date = date ( 'Y-m-j' , $date );
    return $date;
}
// Devuelve una fecha con un día menos
function subtractOneDay($date){
    $date = strtotime ( '-1 day' , strtotime ( $date ) ) ;
    $date = date ( 'Y-m-j' , $date );
    return $date;
}

// Devuelve el total de horas mes trabajadas necesita un array con el horario $hoursWorkedForDayOfWeek que empieza por domingo
function hoursWorkedInTheRangeEstimated($firstDateDB,$secondDateDB,$hoursWorkedForDayOfWeek){
	$hoursWorkedInTheRange=0;
    $secondDateDB=date("Y-m-d",strtotime($secondDateDB));
    $secondDateDB=addDay($secondDateDB);
    $dataRangeNumberOfDaysOfWeek=dataRangeNumberOfDaysOfWeek($firstDateDB,$secondDateDB);
	foreach($hoursWorkedForDayOfWeek as $day =>$value){
		$secondWorkedDay=hourToSecond($value);
		$secondWorkedDay= $dataRangeNumberOfDaysOfWeek[$day] * $secondWorkedDay;
    	$hoursWorkedInTheRange=$hoursWorkedInTheRange + $secondWorkedDay ;
	}
    $hoursWorkedInTheRange=secondToHour($hoursWorkedInTheRange);
	return $hoursWorkedInTheRange;
}
// Devuelve el total de horas trabajadas introducidas mediante un array
function summaryHoursWorkedReally ($arrayHours){
    $hoursWorkedInTheRange=0;
	if (is_array($arrayHours) || is_object($arrayHours)){
		foreach ($arrayHours as $day => $value) {
            $hourToSecond = hourToSecond($value);
            $hoursWorkedInTheRange = $hourToSecond + $hoursWorkedInTheRange;
        }
	}
	return secondToHour($hoursWorkedInTheRange);
}
// Devuelve el total de días de vacaciones generadas hasta final de año
function daysAccumulatedVacation ($dateStartToWork,$daysHolidaysYear){
    $today=today();
    $thisYearIs=date("Y",strtotime($today));
    $lastDayThisYear=$thisYearIs."-12-31";
    $daysWorked=(strtotime($lastDayThisYear)-strtotime($dateStartToWork))/(60*60*24);
    $daysAccumulatedVacation=round(($daysWorked/365.25)*$daysHolidaysYear);
    return $daysAccumulatedVacation;
}
// Devuelve el total de días de vacaciones generadas hasta la fecha actual
function daysAccumulatedVacationReally ($dateStartToWork,$daysHolidaysYear){
    $today=today();
    $daysWorked=(strtotime($today)-strtotime($dateStartToWork))/(60*60*24);
    $daysAccumulatedVacation=round(($daysWorked/365.25)*$daysHolidaysYear);
    return $daysAccumulatedVacationReally;
}
// Devuelve el número de dias que hay entre dos fechas
function daysApart($firstDateDB,$secondDateDB){
	return (strtotime($secondDateDB)-strtotime($firstDateDB))/(60*60*24);
}

// Devuelve que día de la semana es una fecha (0-Lunes)
function isWeekend($date){
	$numberWeekDate=date('N', strtotime($date));
	return $numberWeekDate; 
}
// Devuelve todos las fechas existentes dentro de un rango de dos fechas
function allDaysInARange($firstDateDB,$secondDateDB){
	$numberOfDays=daysApart($firstDateDB,$secondDateDB);	
	$listOfDays[0]=$firstDateDB;
	for($i=0;$i<$numberOfDays;$i++){
		$listOfDays[$i+1]=addOneDay($listOfDays[$i]);
	}
	return $listOfDays;
}

// Devuelve todas las fechas laborables dentro de un rango de dos fechas
function allDaysInARangeOnlyWorkingDays($firstDateDB,$secondDateDB){
	$days=allDaysInARange($firstDateDB,$secondDateDB);	
	$allDaysInARangeOnlyWorkingDays=array();
	foreach($days as $date=>$value){
		if(isWeekend($days[$date])<6){
			array_push($allDaysInARangeOnlyWorkingDays,$days[$date]);
		}
	}
	return $allDaysInARangeOnlyWorkingDays;
}
?>

