    <!-- HEADER -->
    <nav id="menu-principal" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-nav">
        <!-- Links Header -->
        <div class="links-header">
          <div class="row noMargin">
            <div class="col-lg-12">
              <a class="lista-precios pull-right" href="#">Lista de Precios</a>
              <a class="mis-compras pull-right" href="mi-carrito.php"> Mis Compras</a>
              <div class="logueo pull-right"><a href="#login-modal" data-toggle="modal" class="navbar-link" role="button">Ingresar</a></div>
            </div>
          </div>
        </div>
        <!-- Menu Mobile & Logo -->
        <div class="navbar-header  col-md-3">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php"><img class="img-responsive img-center" src="./img/home/logo.jpg" alt=""></a>
        </div>
        <!-- Buscador -->
        <div class="buscador col-md-9 pull-right">
          <div class="input-group" id="adv-search">
            <input type="text" class="form-control busqueda" placeholder="" />
            <div class="input-group-btn">
              <div class="btn-group" role="group">
                <div class="dropdown dropdown-lg">
                  <button type="submit" class="btn btn-primary">Buscar</button>
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span>Búsqueda Avanzada</span></button>
                  <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <form class="form-horizontal" role="form">
                      <div class="form-group">
                          <label for="filter">Filtrar por</label>
                          <select class="form-control">
                            <option value="0" selected>Todos</option>
                            <option value="1">Destacados</option>
                            <option value="2">Promociones</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="contain">Autor</label>
                          <input class="form-control" type="text" />
                        </div>
                        <div class="form-group">
                          <label for="contain">Contiene las palabras</label>
                        <input class="form-control" type="text" />
                      </div>
                      <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Menú -->
        <div class="col-md-10 pull-right collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li>
              <a href="index.php">Home</a>
            </li>
            <li>
              <a href="quienes-somos.php">Quienes Somos</a>
            </li>
            <li>
              <a href="productos.php">Productos</a>
            </li>
            <li>
              <a href="novedades.php">Novedades</a>
            </li>
            <li>
              <a href="promociones.php">Promociones</a>
            </li>
            <li>
              <a href="contacto.php">Contacto</a>
            </li>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
      </div>
      <!-- /.container -->
    </nav>
    <!-- /.HEADER -->
    <!-- MODAL LOGIN -->
    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" align="center">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
            </button>
          </div>
          <!-- Begin # DIV Form -->
          <div id="div-forms">
            <!-- Begin # Login Form -->
            <form id="login-form">
              <h4 class="modal-title">Iniciar Sesión</h4>
              <div class="modal-body">
                <input id="login_username" class="form-control" type="text" placeholder="Usuario" required style="text-transform: none !important;">
                <input id="login_password" class="form-control" type="password" placeholder="Contraseña" required style="text-transform: none !important;">
                <div class="checkbox">
                  <label>
                  <input type="checkbox"> Recordarme
                  </label>
                </div>
              </div>
              <div class="modal-footer" style="border-top: none !important;">
                <div>
                  <button type="submit" class="btn btn-small pull-left">Iniciar Sesión</button>
                </div>
                <div>
                  <button id="login_lost_btn" type="button" class="btn btn-link">Recuperar contraseña</button>
                  <button id="login_register_btn" type="button" class="btn btn-link" style="color: #BCAA66 !important;">Registrarse</button>
                </div>
              </div>
            </form>
            <!-- End # Login Form -->
            <!-- Begin | Lost Password Form -->
            <form id="lost-form" style="display:none;">
              <h4 class="modal-title">Recuperar Contraseña</h4>
              <div class="modal-body">
                <div id="div-lost-msg">
                  <span id="text-lost-msg">Ingrese su e-mail</span>
                </div>
                <input id="lost_email" class="form-control" type="text" placeholder="E-Mail" style="text-transform: none !important;" required>
              </div>
              <div class="modal-footer"  style="border-top: none !important;">
                <div>
                  <button type="submit" class="btn btn-small pull-left">Enviar</button>
                </div>
                <div>
                  <button id="lost_login_btn" type="button" class="btn btn-link">Iniciar Sesión</button>
                  <button id="lost_register_btn" type="button" class="btn btn-link">Registrarse</button>
                </div>
              </div>
            </form>
            <!-- End | Lost Password Form -->
            <!-- Begin | Register Form -->
            <form id="register-form" style="display:none;">
             <h4 class="modal-title" style="">Registrarse</h4>
              <div class="modal-body">
                <div id="div-register-msg" class="form-group">
                  <p style="color: #fff !important; font-size: 12px; padding-left: 15px; line-height: 16px !important;"></p>
                  <div class="row">
                    <div class="col-md-6">
                      <label for="nombreAgencia">Datos de la Agencia </label>  
                      <input type="text" class="form-control form-custom" id="nombreAgencia" name="name" placeholder="Nombre Comercial de la Agencia *" required>
                      <label for="direccion"> </label>
                      <input type="text" class="form-control form-custom" id="direccion" name="name" placeholder="Dirección Completa *" required>
                      <label for="telefonoAgencia"> </label>
                      <input type="text" class="form-control form-custom" id="telefonoAgencia" name="name" placeholder="Teléfono *" required>
                      <label for="paginaWeb"> </label>
                      <input type="text" class="form-control form-custom" id="paginaWeb" name="name" placeholder="Página Web">
                    </div>
                    <div class="col-md-6">
                      <label for="razonSocial"> </label>
                      <input type="text" class="form-control form-custom" id="razonSocial" name="name" placeholder="Razón Social de la Agencia *" required>
                      <label for="iata"> </label>
                      <input type="text" class="form-control form-custom" id="iata" name="name" placeholder="IATA">
                      <label for="numeroSectur"> </label>
                      <input type="text" class="form-control form-custom" id="numeroSectur" name="name" placeholder="Número de alta en SECTUR">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <br>
                      <label for="nombreAdmin">Datos del Contacto Administrativo</label>
                      <input type="text" class="form-control form-custom" id="nombreAdmin" name="name" placeholder="Nombre Completo *" required>
                      <label for="telefonoAdmin"> </label>
                      <input type="text" class="form-control form-custom" id="telefonoAdmin" name="name" placeholder="Teléfono *" required>
                      <label for="emailAdmin"> </label>   
                      <input type="email" class="form-control form-custom" id="emailAdmin" placeholder="Correo Electrónico *" required>
                    </div>
                    <div class="col-md-6">
                      <br> 
                      <label for="nombreVentas">Datos del Contacto de Ventas</label>
                      <input type="text" class="form-control form-custom" id="nombreVentas" name="name" placeholder="Nombre Completo *" required>
                      <label for="telefonoVentas"> </label>
                      <input type="text" class="form-control form-custom" id="telefonoVentas" name="name" placeholder="Teléfono *" required>
                      <label for="emailVentas"> </label>   
                      <input type="email" class="form-control form-custom" id="emailVentas" placeholder="Correo Electrónico *" required>
                    </div>
                  </div>
                  <br>
                  <button type="submit" class="btn btn-small center-block"><span style="font-size: 12px;">Registrarse</span></button>  
                  <br>
                  <p>Ya eres un usuario registrado? <button id="register_login_btn" type="button" class="btn btn-link">Iniciar sesión</button>	</p>
                </div>
              </div>
            </form>
            <!-- End | Register Form -->
          </div>
          <!-- End # DIV Form -->
        </div>
      </div>
    </div>
    <!-- /.MODAL LOGIN -->