<?php
	session_start();
	require_once 'php/conexion.php';

	if (!isset($_REQUEST["slug"])) {
		header("Location: ". $raiz ."index.php");
		die();
	}

	$numeProd = buscarDato("SELECT NumeProd FROM productos WHERE SlugProd = '". $_REQUEST["slug"] . "'");

	ejecutarCMD("UPDATE productos SET Vistas = Vistas + 1 WHERE NumeProd = ". $numeProd);
	
	//Producto
	$strSQL = "SELECT NombProd, DescProd, ImpoVent";
	$strSQL.= $crlf."FROM productos";
	$strSQL.= $crlf."WHERE NumeProd = ". $numeProd;
	$tabla = cargarTabla($strSQL);
	$producto = $tabla->fetch_assoc();

	//Atributos
	$strSQL = $crlf."SELECT pa.NumeAtri, a.NombAtri, pa.Valor, a.NumeTipoAtri";
	$strSQL.= $crlf."FROM productosatributos pa";
	$strSQL.= $crlf."INNER JOIN atributos a ON pa.NumeAtri = a.NumeAtri";
	$strSQL.= $crlf."WHERE pa.NumeProd = ". $numeProd;
	$strSQL.= $crlf."ORDER BY a.NumeOrde";
	$atributos = cargarTabla($strSQL);

	//Imagenes
	$strSQL = $crlf."SELECT RutaImag";
	$strSQL.= $crlf."FROM productosimagenes";
	$strSQL.= $crlf."WHERE NumeProd = ". $numeProd;
	$strSQL.= $crlf."ORDER BY NumeOrde";
	$imagenes = cargarTabla($strSQL);
?>
<!doctype html>
<html>
<head>
	<?php include 'php/links-header.php'; ?>

	<!-- Custom CSS -->
	<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link href="css/sidebar.css" rel="stylesheet">

	<script>
		var isClosed = false;
		var trigger;

		$(document).ready(function () {
			trigger = $('.hamburger');
		
			trigger.click(function () {
				hamburger_cross();
			});
		
			$('[data-toggle="offcanvas"]').click(function () {
				$('#wrapper').toggleClass('toggled');
			});  

			$(".overlay").click(function() {
				$('#wrapper').toggleClass('toggled');
				hamburger_cross();
			});
		});

		function hamburger_cross() {
			var overlay = $('.overlay');

			if (isClosed == true) {		  
				overlay.hide();
				trigger.removeClass('is-open');
				trigger.addClass('is-closed');
				isClosed = false;
			} else {   
				overlay.show();
				trigger.removeClass('is-closed');
				trigger.addClass('is-open');
				isClosed = true;
			}
		}

		function agregarProd() {
			if ($("#divLogin").css("display") == "none") {
				$.post("php/carritosProcesar.php", { 
					"operacion": "1",
					"NumeProd": <?php echo $numeProd?>,
					"CantProd": $("#CantProd").val()
					},
					function (data) {
						if (data.estado === true) {
							$("#divCarrito").html(data.html);
							$("#subtotal").html(data.subtotal);
							$("#bonificacion").html(data.bonificacion);
							$("#total").html(data.total);

							$('#wrapper').toggleClass('toggled');
							hamburger_cross();
						}
					}
				);
			}
			else {
				$("#login-modal").modal("show");
			}
		}

		function quitarProd(strID) {
			$.post("php/carritosProcesar.php", { 
				"operacion": "2",
				"NumeProd": strID,
				},
				function (data) {
					if (data.estado === true) {
						$("#divCarrito").html(data.html);
						$("#subtotal").html(data.subtotal);
						$("#bonificacion").html(data.bonificacion);
						$("#total").html(data.total);
					}
				}
			);
		}
	</script>
</head>
<body>
	<div id="wrapper">
		<div class="overlay"></div>
		<!-- Page Content -->
		<div id="page-content-wrapper">

			<?php include 'php/header.php'; ?>

			<!-- CONTENIDO -->
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<nav id="item-producto" class="breadcrumb">
						<a class="breadcrumb-item" href="#">Derecho</a>
						<span class="breadcrumb-item active">Derecho Penal</span>
						</nav>
						<div class="carousel slide article-slide" id="article-photo-carousel">
						<!-- Wrapper for slides -->
						<div class="carousel-inner cont-slider">
						<?php 
							$I = 0;
							while ($fila = $imagenes->fetch_assoc()) {
						?>
							<div class="item <?php echo ($I == 0? 'active': '')?>">
								<img alt="" title="" src="admin/<?php echo $fila["RutaImag"]?>">
							</div>
						<?php 
								$I++;
							}
						?>
						</div>
						<!-- Indicators -->
						<ol class="carousel-indicators">
						<?php 
							$imagenes->data_seek(0);
							$I = 0;
							while ($fila = $imagenes->fetch_assoc()) {
						?>
							<li class="<?php echo ($I == 0? 'active': '')?>" data-slide-to="0" data-target="#article-photo-carousel">
								<img alt="" src="admin/<?php echo $fila["RutaImag"]?>">
							</li>
						<?php
								$I++;
							}
						?>
						</ol>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="info-producto">
						<h1><?php echo $producto["NombProd"]?></h1>
						<div class="star-rating">
							<div class="star-rating__wrap">
								<input class="star-rating__input" id="star-rating-5" type="radio" name="rating" value="5">
								<label class="star-rating__ico fa fa-star-o fa-lg" for="star-rating-5" title="5 estrellas"></label>
								<input class="star-rating__input" id="star-rating-4" type="radio" name="rating" value="4">
								<label class="star-rating__ico fa fa-star-o fa-lg" for="star-rating-4" title="4 estrellas"></label>
								<input class="star-rating__input" id="star-rating-3" type="radio" name="rating" value="3">
								<label class="star-rating__ico fa fa-star-o fa-lg" for="star-rating-3" title="3 estrellas"></label>
								<input class="star-rating__input" id="star-rating-2" type="radio" name="rating" value="2">
								<label class="star-rating__ico fa fa-star-o fa-lg" for="star-rating-2" title="2 estrellas"></label>
								<input class="star-rating__input" id="star-rating-1" type="radio" name="rating" value="1">
								<label class="star-rating__ico fa fa-star-o fa-lg" for="star-rating-1" title="1 estrellas"></label>
							</div>
						</div>
						<p class="clasificacion">Calificación</p>
						<h2>Detalles</h2>
						<p>
							<?php echo $producto["DescProd"]?>
						</p>
						<h2>Información adicional</h2>
					<?php
						while ($fila = $atributos->fetch_assoc()) {
							switch ($fila["NumeTipoAtri"]) {
								case "3": //Archivo
									$valor = '<a href="admin/'. $fila["Valor"] .'" target="_blank">DESCARGA</a>';
									break;
								
								case "7": //Lista
									$numeAtriOpci = buscarDato("SELECT Valor FROM productosatributos WHERE NumeAtri = {$fila["NumeAtri"]} AND NumeProd = {$numeProd}");
									$valor = buscarDato("SELECT ao.Valor FROM atributosopciones ao WHERE NumeAtriOpci = ". $numeAtriOpci);
									break;

								default:
									$valor = $fila["Valor"];
							}
					?>
					<?php if ($valor != "") {?>
						<p><span class="ucase"><?php echo $fila["NombAtri"]?>:</span> <?php echo $valor?></p>
					<?php }?>	
					<?php }?>	
						<div>
							<div class="section">
								<p class="precio">$ <?php echo $producto["ImpoVent"]?></p>
								<div>
									<p class="cantidad">Cantidad</p>
									<div id="cantidad">
									<div class="btn-minus"><span class="glyphicon glyphicon-minus"></span></div>
									<input id="CantProd" value="1" readonly />
									<div class="btn-plus"><span class="glyphicon glyphicon-plus"></span></div>
									</div>
								</div>
								<button type="button" onclick="agregarProd()" class="animated fadeInLeft btn-bordo" >Agregar a carro de compras</button>
								&nbsp;
								<button type="button" class="btn-bordo" data-toggle="modal" data-target="#consultar">Consultar</button> 
								<!-- Modal -->
								<div class="modal fade" id="consultar" tabindex="-1" role="dialog" aria-labelledby="contactoModalLabel" aria-hidden="true">
									<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="contactoModalLabel">Consulta</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<div class="container-fluid formulario">
												<!-- Formulario de Contacto -->
												<div class="row">
												<div class="col-lg-12">
													<!-- Contact Form - Enter your email address on line 19 of the mail/contact_me.php file to make this form work. -->
													<!-- WARNING: Some web hosts do not allow emails to be sent through forms to common mail hosts like Gmail or Yahoo. It's recommended that you use a private domain email address! -->
													<!-- NOTE: To use the contact form, your site must be on a live web host with PHP! The form will not work locally! -->
													<form name="sentMessage" id="contactForm" novalidate>
														<div class="row control-group">
															<div class="form-group col-lg-6 floating-label-form-group controls">
															<label>Nombre</label>
															<input type="text" class="form-control" placeholder="" id="name" required data-validation-required-message="Por favor ingresa tu nombre.">
															<p class="help-block text-danger"></p>
															<label>Email</label>
															<input type="email" class="form-control" placeholder="" id="email" required data-validation-required-message="Por favor ingresa tu email.">
															<p class="help-block text-danger"></p>
															<label>Teléfono</label>
															<input type="tel" class="form-control" placeholder="" id="phone" required data-validation-required-message="Por favor ingresa tu teléfono.">
															<p class="help-block text-danger"></p>
															</div>
															<div class="form-group col-lg-6 floating-label-form-group controls">
															<label>Consulta</label>
															<textarea rows="5" class="form-control" placeholder="" id="message" required data-validation-required-message="Por favor ingresa tu consulta."></textarea>
															<p class="help-block text-danger"></p>
															<button type="submit" class="btn btn-enviar">Enviar</button>
															</div>
														</div>
														<br>
														<div id="success"></div>
													</form>
												</div>
												</div>
												<div class="row">
												<div class="col-lg-2"> </div>
												<div class="col-lg-8 border-top"> 
													<img class="img-center" src="./img/contacto/logo-contacto.png" alt="">
												</div>
												<div class="col-lg-2"> </div>
												</div>
												<div class="row">
												<div class="col-lg-12 alignCenter">
													<p><span>Sucursal Centro</span><br/>
														Obispo Trejo 181 - Córdoba <br/>
														+54 (0351) 446-1931
													</p>
													<a href="mailto:administracion@eadvocatus.com.ar" target="_top">administracion@eadvocatus.com.ar</a><br/><br/>
													<p><span>Sucursal Tribunales</span><br/>
														Montevideo 635 - Córdoba <br/>
														+54 (0351) 426-1177 / 446-1932  <br/>
														351 657-1003 (WSP)
													</p>
													<a href="mailto:editorial@eadvocatus.com.ar" target="_top">editorial@eadvocatus.com.ar </a><br/>
													<a href="mailto:info@eadvocatus.com.ar" target="_top">info@eadvocatus.com.ar</a><br/><br/>
													<p><span>Sucursal UBP</span><br/>
														Universidad Blas Pascal <br/>
														Av. Donato Alvarez 380 
													</p>
													<a href="mailto:ubp@eadvocatus.com.ar" target="_top">ubp@eadvocatus.com.ar</a><br/><br/>
												</div>
												</div>
											</div>
											<div class="col-lg-1">
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
										</div>
									</div>
									</div>
								</div>
							</div>
						</div>
						<ul class="redes-sociales">
							<li><a href="#"><img alt="" src="./img/item-producto/redes-fb.png"></a></li>
							<li><a href="#"><img alt="" src="./img/item-producto/redes-tw.png"></a></li>
							<li><a href="#"><img alt="" src="./img/item-producto/redes-goo.png"></a></li>
							<li><a href="#"><img alt="" src="./img/item-producto/redes-pin.png"></a></li>
						</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- /.CONTENIDO -->

			<?php include 'php/footer.php'; ?>

		</div>
		 <!-- /#page-content-wrapper --> 

		 <!-- Sidebar -->
		 <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
			<ul class="nav sidebar-nav">
				<button type="button" class="hamburger is-closed animated fadeInLeft" data-toggle="offcanvas"> <span class="hamb-top"></span> <span class="hamb-bottom"></span> </button>
				<h1>Carrito de Compras</h1>

				<div id="divCarrito"></div>

				<p class="subtotal">Subtotal: $ <span id="subtotal">0</span></p>
				<p class="bonificacion">Bonificación: <span id="bonificacion">0</span></p>
				<p class="total">Total: $ <span id="total">0</span></p>
				<a href="mi-carrito.php" class="btn-carrito-negro">Comprar</a>  
			</ul>
		 </nav>
		 <!-- /#sidebar-wrapper --> 
	  </div>
	  <!-- /#wrapper --> 

	<?php include 'php/scripts-footer.php'; ?>

	<!-- Contact Form JavaScript -->
	<script src="js/jqBootstrapValidation.js"></script>
	<script src="js/contact_me.js"></script>

	<script>
	// Stop carousel
		$('.carousel').carousel({
		interval: false
		});
		
	</script>

	<script>
		$(document).ready(function(){
		
				//-- Click on CANTIDAD
				$(".btn-minus").on("click",function(){
					var now = $(".section > div > input").val();
					if ($.isNumeric(now)){
						if (parseInt(now) -1 > 0){ now--;}
						$(".section > div > div > input").val(now);
					}else{
						$(".section > div > div > input").val("1");
					}
				})			
				$(".btn-plus").on("click",function(){
					var now = $(".section > div > div > input").val();
					if ($.isNumeric(now)){
						$(".section > div > div > input").val(parseInt(now)+1);
					}else{
						$(".section > div > div > input").val("1");
					}
				})						
			}); 
	</script>
</body>
</html>