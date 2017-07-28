<!-- page content -->

  <body class="login">
      <?php
      if(isset($_SESSION["error_login"])){ ?>
          <div class="alert alert-warning alert-dismissable content" style="position: absolute; top: 4px; right: 4px;">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <?php
              echo($_SESSION["error_login"]);
              ?>
          </div>
      <?php }?>
    <div>
        <a class="hiddenanchor" id="signup"></a>
        <a class="hiddenanchor" id="signin"></a>
        <div class="login_wrapper" style="margin-top: 80px;">

            <div class="animate form login_form">
                <section class="login_content" style="padding-top: 0px;">
                    <form action="index.php?controller=login&action=Login" method="POST" accept-charset="utf-8">
                      <h1>Login Form</h1>
                      <div>
                        <input name="name_user" type="name" class="form-control" placeholder="Nombre de Usuario" required />
                      </div>
                      <div>
                        <input name="password_user" type="password" class="form-control" placeholder="Password del Usuario" required />
                      </div>
                      <div>
                          <input class="btn btn-default submit" type="submit" name="submit" >
                        <a class="reset_pass" href="#">Lost your password?</a>
                      </div>
                    </form>
                    <div class="separator">
                        <br />
                        <div>
                            <h1><i class="fa fa-paw"></i> Gentelella Alela!</h1>
                            <p>©2016 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                        </div>
                    </div>
                </section>





