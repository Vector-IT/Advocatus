<?php
	$face = buscarDato("SELECT ValoConf FROM configuraciones WHERE NombConf = 'FACEBOOK'");
	$twit = buscarDato("SELECT ValoConf FROM configuraciones WHERE NombConf = 'TWITTER'");
	$inst = buscarDato("SELECT ValoConf FROM configuraciones WHERE NombConf = 'INSTAGRAM'");
	$goog = buscarDato("SELECT ValoConf FROM configuraciones WHERE NombConf = 'GOOGLE+'");

	if (!isset($orden)) $orden = "1";
	if (!isset($subcat)) $subcat = "";
	
	$numeUser = isset($_COOKIE["v-commerce_numeUser"])? $_COOKIE["v-commerce_numeUser"]: '';
	if ($numeUser != '') {
		cargarUsuario($numeUser);
	} else {
		if(isset($_COOKIE["v-commerce_numeInvi"])) {
			$_SESSION["NumeInvi"] = $_COOKIE["v-commerce_numeInvi"];
			$_SESSION["NumeCarr"] = buscarDato("SELECT NumeCarr FROM carritos WHERE NumeEstaCarr = 1 AND NumeInvi = ". $_SESSION["NumeInvi"]);
		}
	}

	if (isset($_SESSION["NumeCarr"])) {
		$cantCarrito = buscarDato("SELECT COUNT(*) FROM carritosdetalles WHERE NumeCarr = ". $_SESSION["NumeCarr"]);
	}
	else {
		$cantCarrito = 0;
	}

?>
<!-- HEADER -->
<nav id="menu-principal" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
<div class="container-nav">
	<!-- Links Header -->
	<div class="links-header">
		<div class="row noMargin">
			<div class="col-lg-12">
				<a class="lista-precios pull-right" href="<?php echo $raiz?>descargas/Lista Advocatus  Nacional Agosto 2017 - Nº 46  - Revisada, Ordenada - 08 de Agosto 2017.xls">Lista de Precios</a>
				<a class="mis-compras pull-right" href="mi-carrito.php">Mi Carrito(<span class="cantProds"><?php echo $cantCarrito?></span>)</a>
				<div id="divLogin" class="logueo pull-right" <?php echo (isset($_SESSION['is_logged_in'])? 'style="display: none;"': '')?>><a href="#login-modal" data-toggle="modal" class="navbar-link" role="button">Ingresar</a></div>
				<div id="divLogout" class="logueo pull-right" <?php echo (!isset($_SESSION['is_logged_in'])? 'style="display: none;"': '')?>><a href="php/logout.php" class="navbar-link clickable" title="Cerrar sesión"><?php echo (isset($_SESSION['is_logged_in'])? $_SESSION["NombPers"]: '')?></a></div>
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
	<div class="redes-sociales col-md-2 pull-right">
		<?php 
		if ($face != '') echo $crlf.'<a href="'. $face .'" target="_blank"><img class="" src="./img/home/fb.png" alt=""></a>';
		if ($twit != '') echo $crlf.'<a href="'. $twit .'" target="_blank"><img class="" src="./img/home/t.png" alt=""></a>';
		if ($inst != '') echo $crlf.'<a href="'. $inst .'" target="_blank"><img class="" src="./img/home/i.png" alt=""></a>';
		if ($goog != '') echo $crlf.'<a href="'. $goog .'" target="_blank"><img class="" src="./img/home/g.png" alt=""></a>';
		?>
	</div>
	<!-- Buscador -->
	<div class="buscador col-md-7">
		<!-- <form id="frmBusqueda" class="form-horizontal" role="form"> -->
			<div class="input-group" id="adv-search">
				<input type="text" name="texto1" id="bsqTexto1" class="form-control busqueda" placeholder="" value="<?php echo (isset($_REQUEST["texto"])? $_REQUEST["texto"]: '')?>" />
				<div class="input-group-btn">
					<div class="btn-group" role="group">
						<div class="dropdown dropdown-lg">
							<button type="submit" class="btn btn-primary" onclick="busqSimple()";>Buscar</button>
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span>Búsqueda Avanzada</span></button>
							<div class="dropdown-menu dropdown-menu-right" role="menu">
								<form id="frmBusqueda" class="form-horizontal" role="form" action="productos.php">
									<input type="hidden" name="buscar" id="bsqBuscar" value="1" />
									<div class="form-group">
										<label for="filter">Filtrar por</label>
										<select class="form-control" name="tipo" id="bsqTipo">
											<option value="" <?php echo (isset($_REQUEST["tipo"]) && $_REQUEST["tipo"] == ""? 'selected': '')?>>Todos</option>
											<option value="1" <?php echo (isset($_REQUEST["tipo"]) && $_REQUEST["tipo"] == "1"? 'selected': '')?>>Destacados</option>
											<option value="2" <?php echo (isset($_REQUEST["tipo"]) && $_REQUEST["tipo"] == "2"? 'selected': '')?>>Promociones</option>
										</select>
									</div>
									<div class="form-group">
										<label for="filter">Filtrar por Editorial</label>
										<select class="form-control" name="editorial" id="bsqEditorial">
											<?php echo cargarCombo("SELECT NumeAtriOpci, Valor FROM atributosopciones WHERE NumeAtri = 81 ORDER BY Valor", "NumeAtriOpci", "Valor", (isset($_REQUEST["editorial"])? $_REQUEST["editorial"]: ""), true, "Seleccione...", "")?>
										</select>
									</div>
									<div class="form-group">
										<label for="filter">Filtrar por Categoría</label>
										<select class="form-control" name="categoria" id="bsqCategoria">
											<?php echo cargarCombo("SELECT NumeCate, NombCate FROM categorias WHERE NumePadr IS NULL ORDER BY NombCate", "NumeCate", "NombCate", (isset($_REQUEST["categoria"])? $_REQUEST["categoria"]: ""), true, "Seleccione...", "")?>
										</select>
									</div>
									<div class="form-group">
										<label for="contain">Autor</label>
										<input class="form-control" type="text" name="autor" id="bsqAutor" value="<?php echo (isset($_REQUEST["autor"])? $_REQUEST["autor"]: '')?>" />
									</div>
									<div class="form-group">
										<label for="contain">Fecha de edición</label>
										<input class="form-control" type="text" name="fecha" id="bsqFecha" value="<?php echo (isset($_REQUEST["fecha"])? $_REQUEST["fecha"]: '')?>" />
									</div>
									<div class="form-group">
										<label for="contain">Contiene las palabras</label>
										<input class="form-control" type="text" name="texto" id="bsqTexto" value="<?php echo (isset($_REQUEST["texto"])? $_REQUEST["texto"]: '')?>" />
									</div>
									<input type="hidden" name="orden" id="bsqOrden" value="<?php echo $orden?>" />
									<input type="hidden" name="subcat" id="bsqSubcat" value="<?php echo $subcat?>" />
									<input type="hidden" name="cate" id="bsqCate" value="" />
									<button type="submit" class="btn btn-primary" onclick="busqAvanzada();"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- </form> -->
	</div>
	<!-- Menú -->
	<div class="col-sm-12 col-md-10 pull-right collapse navbar-collapse" id="bs-example-navbar-collapse-1">
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
			<form id="login-form" method="post">
				<h4 class="modal-title">Iniciar Sesión</h4>
				<div class="modal-body">
					<input class="form-control" id="nombUser" type="text" placeholder="Usuario" required style="text-transform: none !important;">
					<input class="form-control" id="nombPass" type="password" placeholder="Contraseña" required style="text-transform: none !important;">
					<div class="checkbox">
						<label>
						<input type="checkbox" id="chkRemember"> Recordarme
						</label>
					</div>
					<div id="divLoginMsg" class="" role="alert">
						<span id="iconLogin" class="" aria-hidden="true"></span>&nbsp;<span id="txtLoginMsg"></span>
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
					<div class="row">
						<div class="col-md-12">
							<label for="NombPers">Nombre completo *</label>
							<input type="text" class="form-control form-custom" id="NombPers" placeholder="Nombre Completo *" required>
							<label for="TeleUser">Teléfono *</label>
							<input type="text" class="form-control form-custom" id="TeleUser" placeholder="Teléfono *" required>
							<label for="MailUser">Mail *</label>   
							<input type="email" class="form-control form-custom" id="MailUser" placeholder="Correo Electrónico *" required>
							<div class="row">
								<div class="col-md-8">
									<label for="DireUser">Dirección</label>
									<input type="text" class="form-control form-custom" id="DireUser" placeholder="Dirección *" required>
								</div>
								<div class="col-md-4">
									<label for="CodiPost">Código Postal</label>
									<input type="text" class="form-control form-custom" id="CodiPost" placeholder="Código Postal" required>
								</div>
							</div>
							<label for="NumeProv">Provincia *</label>
							<select class="form-control form-custom" id="NumeProv" required>
							<?php echo cargarCombo("SELECT NumeProv, NombProv FROM provincias ORDER BY NombProv", "NumeProv", "NombProv");?>
							</select>
							<label for="NombUser">Usuario *</label>
							<input type="text" class="form-control form-custom" id="NombUser" placeholder="Usuario *" required>
							<label for="NombPass">Contraseña *</label>
							<input type="password" class="form-control form-custom" id="NombPass" placeholder="Contraseña *" required>
						</div>
					</div>
					<div id="divRegisterMsg" class="" role="alert">
						<span id="iconRegister" class="" aria-hidden="true"></span>&nbsp;<span id="txtRegisterMsg"></span>
					</div>
					<button type="submit" class="btn btn-small center-block"><span style="font-size: 12px;">Registrarse</span></button>  
					<br>
					<p>Ya eres un usuario registrado? <button id="register_login_btn" type="button" class="btn btn-link">Iniciar sesión</button>    </p>
				</div>
			</form>
			<!-- End | Register Form -->
		</div>
		<!-- End # DIV Form -->
	</div>
</div>
</div>
<!-- /.MODAL LOGIN -->