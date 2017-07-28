<?php
$taxonomies=$data['Taxonomies'];
?>
<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Taxonomías <small>empleadas para categorizar elementos</small></h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <?php
                foreach($taxonomies as $category => $values){
                    echo ' 
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>'.$taxonomies[$category]['typedata_category_modified'].'<small>Try hovering over the rows</small></h2>
                                        <ul class="nav navbar-right panel_toolbox">
                                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="#">Settings 1</a></li>
                                                    <li><a href="#">Settings 2</a></li>
                                                </ul>
                                            </li>
                                            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content" style="display:none">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Taxonomía</th>
                                                <th>Categoria</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ';
                               foreach($taxonomies[$category]['list_taxonomies'] as $row => $list){
                                                echo '  <tr>    
                                                            <th scope="row">'.($row+1).'</th>
                                                                <td>'.$list['taxonomy_modified'].'</td>
                                                                <td>'.$taxonomies[$category]['typedata_category_modified'].'</td>
                                                        </tr>';
                                            }

                          echo '        </tbody>
                                    </table>    
                                </div>
                            </div>
                        </div>';
                if($category%2==0){echo '<div class="clearfix"></div>';};
                }
                ?>
              
            </div>
          </div>
        </div>
        <!-- /page content -->

