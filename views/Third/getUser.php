<?php
$User=$data["getUser"];
?>
<!-- page content -->
<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>LISTA DE USUARIOS</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="index.php?controller=Third&action=getUser&active=true">Activos</a>
                            </li>
                            <li><a href="index.php?controller=Third&action=getUser&active=false">Activos / Inactivos</a>
                            </li>
                        </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
            <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <p class="text-muted font-13 m-b-30">
                      The Buttons extension for DataTables provides a common set of options, API methods and styling to display buttons on a page that will interact with a DataTable. The core library provides the based framework upon which plug-ins can built.
                </p>
                <table id="datatable-buttons" class="table table-hover table-striped dt-responsive nowrap table-condensed" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>NickName</th>
                            <th>Estado</th>
                            <th>Rol</th>
                            <th>Empresa</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Fecha Alta</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
for ($row=0;$row<=(count($User)-1);$row++){
    echo "<tr>
            <td>".$User[$row]['nickname']."</td>
            <td>".$User[$row]['state_modified']."</td>
            <td>".$User[$row]['rol_modified']."</td>
            <td>".$User[$row]['tradename']."</td>
            <td>".$User[$row]['firstname']."</td>
            <td>".$User[$row]['lastname']."</td>
            <td>".datetimeToDate($User[$row]['highdate'])."</td>
        </tr>" ;              
}
    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="<?php url_base();?>public/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<!-- /page content -->

