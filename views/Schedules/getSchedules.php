<?php
$reportRange=$data['reportRange'];
$getUserActive=$data['getUserActive'];
var_dump($getUserActive);
$getListUserPeriod=$data['listUserPeriod'];
$getListDatePeriod=array_reverse($data['getListDatePeriod']);
/*$id=$_SESSION['logged']['id'];
$getDataSchedules=$data['getDataSchedules'];
$getNumberHolidaysForUser=$data['getNumberHolidaysForUser'];
$getPublicsDaysForUser=$data['getPublicDaysForUser'];
$reallyHoursWorkedInTheRange=$data['reallyHoursWorkedInTheRange'];
$hoursWorkedInTheRange=$data['hoursWorkedInTheRange'];
$typeOfDays=$data['typeOfDays'];
$insertDataForm=$data['insertDataForm'];*/
?>
<!-- page content -->

    <div class="right_col" role="main">
<?php 

?>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Insertar Horas</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Settings 1</a>
                                    </li>
                                    <li><a href="#">Settings 2</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <?php
                        if(isset($insertDataForm)){echo $insertDataForm;}
                        ?>
                        <form action="index.php?controller=Schedules&action=getSchedules" method="POST" accept-charset="utf-8" class="form-horizontal form-label-left">
                            <div class="form-group">
                                <div class="col-md-2 col-sm-4 col-xs-12 form-group has-feedback" style="padding-right:0px">
                                    <select class="form-control" name="idUserSetDate" style="padding-right:0px">
                                        <?php
                                        foreach($getUserActive as $option => $values){
                                            echo '<option value="'.$getUserActive[$option]['id'].'" ';
                                            echo ($getUserActive[$option]['id']==$id)?'selected>':'>';
                                            echo $getUserActive[$option]['firstname'].' '.$getUserActive[$option]['lastname'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-4 col-xs-12 form-group has-feedback" style="padding-right:0px">
                                    <input type="date" class="form-control" value="<?php echo today(); ?>" placeholder="date" name="dateSetDate" style="padding-right:0px;padding-left:45px;">
                                    <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                                </div>
                                <div class="col-md-1 col-sm-4 col-xs-12 form-group has-feedback" style="padding-right:0px">
                                    <input type="time" value="09:00:00" class="form-control" placeholder="Entrada" name="inputSetDate" style="padding:6px 0px 6px 6px">
                                </div>
                                <div class="col-md-1 col-sm-4 col-xs-12 form-group has-feedback" style="padding-right:0px">
                                    <input type="time" value="18:00:00" class="form-control " placeholder="Salida" name="outputSetDate" style="padding:6px 0px 6px 6px">
                                </div>
                                <div class="col-md-1 col-sm-4 col-xs-12 form-group has-feedback" style="padding-right:0px">
                                    <input type="time" value="00:30:00" class="form-control" placeholder="Parada" name="breakSetDate" style="padding:6px 0px 6px 6px">
                                </div>
                                <div class="col-md-5 col-sm-9 col-xs-12">
                                    <div class="checkbox">
                                        <?php
                                        foreach($typeOfDays as $type => $values){ ?>
                                            <label class="col-md-3 col-sm-6 col-xs-12" style="padding:0px">
                                                <input type="radio" class="flat" name="typeOfDay" value="<?php echo $typeOfDays[$type]['id']; ?>">
                                                <?php echo $typeOfDays[$type]['taxonomy_modified']; ?>
                                            </label>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <input type="text" value="<?php echo $_POST['reportrange']; ?>" name="reportrange" style="visibility: hidden">
                                <div class="col-md-12 col-sm-12 col-xs-12"> </div>
                                <div class="form-group"></div>
                                <div class="ln_solid"></div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12 col-md-3">
                                    <button class="btn btn-primary" type="reset">Reset</button>
                                    <button type="submit" name="submitInfoHoursWorked" value="submittedForm" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <!-- form date pickers -->
                <div class="x_panel" style="">
                    <div class="x_title">
                        <h2>Tablón de Horas</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Settings 1</a>
                                    </li>
                                    <li><a href="#">Settings 2</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div id="range" class="col-md-8" style="padding:0px;">Durante el periodo

                            <?php echo '<span id="firstdate">'.$reportRange['firstDate']."</span>"." - ".
                                '<span id="seconddate">'.$reportRange['secondDate']."</span>"; ?>
                                se trabajan <?php echo shortHour($getListUserPeriod[$_SESSION['logged']['id']]['reallyHoursWorkedInTheRangeEstimated']);?> horas.<br>
                                Vacaciones generadas:
                                <?php
                                    $daysAccumulatedVacation=$getListUserPeriod[$id]['daysAccumulatedVacation'];
                                    echo $daysAccumulatedVacation;
                                ?>
                                (<?php   echo $_SESSION['logged']['days_holidays_year']; ?> dias/año)<br>
                            Vacaciones consumidas:
                                <?php
                                    $holidaysConsumed=$getListUserPeriod[$id]['numberHolidaysForUser'];
                                    echo $holidaysConsumed;?>
                            (Restan:
                                <?php echo $daysAccumulatedVacation-$holidaysConsumed;?> dias)
                        </div>
                        <div class="col-md-4" style="padding:0px;">
                            <form action="index.php?controller=Schedules&action=getSchedules" method="POST" class="form-horizontal" name="schedules_date_range_picker" >
                                <div class="input-prepend input-group pull-right">                                     
                                    <div class="input-prepend input-group" style="padding-right:0px">
                                        <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                        <input id="daterangepicker" type="text" class="form-control" placeholder="Rango de Fechas"  name="reportrange" style="padding-right:5px;padding-left:5px;">
         
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php
                    if($getUser=='empty'){
                        echo "<h2>Sin resultados</h2>";
                    }else{
                        ?>
                        <div class="col-md-12" style="display:inline-block;overflow-x: scroll;">
                        <table id="table" class="table table-hover table-striped" cellspacing="0" width="100%">
                            <thead>

                                <tr>
                                    <th rowspan="2">Fecha</th>
                                    <?php

                                    foreach($getListUserPeriod as $idUser => $value){
                                        echo    '<th colspan="2">'.$getListUserPeriod[$idUser]['contact_name'].'</th>
                                                  <th >'.$getListUserPeriod[$idUser]['percentHoursWorked'].'%</th>
                                                  <th >'.shortHour($getListUserPeriod[$idUser]['summaryHoursWorkedReally']).'</th>';
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <?php
                                    foreach($getListUserPeriod as $idUser => $value){
                                        echo '  <th>Inicio</th>
                                                <th>Fin</th>
                                                <th>Parada</th>
                                                <th>Horas</th>';
                                    }
                                    ?>                                
                                </tr>
                            </thead>
                            <tbody>
                                    <?php
                                    foreach ($getListDatePeriod as $date =>$values){
                                        echo "  <tr>
                                                    <td nowrap>".$date."</td>";
                                        foreach ($getListUserPeriod as $idUser){
                                            $id=$idUser['id'];
                                            $dataInformationSchedules=$getDataSchedules[$id]['datesInfo'][$date];
                                            if(isset($getDataSchedules[$id]['datesInfo'][$date]['input'])){
                                                switch($dataInformationSchedules['typeofday']){
                                                    case "work_day":
                                                        echo    "<td>".shortHour($dataInformationSchedules['input'])."</td>";
                                                        echo    "<td>".shortHour($dataInformationSchedules['output'])."</td>";
                                                        echo    "<td>".shortHour($dataInformationSchedules['break'])."</td>";
                                                        echo    "<td>".shortHour($dataInformationSchedules['totalhour'])."</td>";
                                                        break;
                                                    case "vacation_day":
                                                        echo    '<td colspan="3" class="warning"> Vacaciones </td>';
                                                        echo    '<td class="warning">'.shortHour($dataInformationSchedules['totalhour'])."</td>";
                                                        break;
                                                    case "public_day":
                                                        echo    '<td colspan="3" class="danger"> Festivo </td>';
                                                        echo    '<td class="danger">'.shortHour($dataInformationSchedules['totalhour'])."</td>";
                                                        break;                                                    
                                                }
                                           }else{
                                                echo    "<td> - </td>";
                                                echo    "<td> - </td>";
                                                echo    "<td> - </td>";
                                                echo    "<td> - </td>";                                            
                                            }
                                        }               
                                        echo "</tr>" ;              
                                    }
                                    ?>
                            </tbody>
                        </table></div>
                    <?php
                    }
                    ?>
                    </div>
                <!-- /form datepicker -->
               <!-- form datetimepicker -->
                </div>
            </div>
        </div>
    </div>

<!-- /page content -->

