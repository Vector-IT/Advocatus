<?php
	require_once 'php/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <meta name="author" content="">
      <title>Promociones - ADVOCATUS | Editorial · Librería</title>

      <?php include 'php/links-header.php'; ?>

   </head>
   <body>

    <?php include 'php/header.php'; ?>

         <!-- ORDEN DE COMPRA -->
         <div id="mi-carrito" class="container">
            <div class="row">
               <div class="col-lg-6">
                  <h1>Mi carrito de compras</h1>
               </div>
               <div class="col-lg-6"><a href="#" class="btn-carrito-negro pushRight">Realizar compra</a></div>
            </div>
            <div class="row">
               <div class="col-lg-3 noPadding">
                  <h2>Producto</h2>
                  <article>
                     <img class="img-producto-carrito" src="./img/item-producto/foto-producto-destacada.jpg" alt="">
                  </article>
               </div>
               <div class="col-lg-3 noPadding">
                  <h2>Descripción</h2>
                  <article id="1">
                     <p class="info-producto">RAZONAMIENTO JUDICIAL EN MATERIA PENAL</p>
                  </article>
               </div>
               <div class="col-lg-2 noPadding">
                  <h2>Cantidad</h2>
                  <article id="1">
                     <p class="info-producto">1</p>
                  </article>
               </div>
               <div class="col-lg-2 noPadding">
                  <h2>Precio</h2>
                  <article id="1">
                     <p class="info-producto">$1104</p>
                  </article>
               </div>
               <div class="col-lg-2 noPadding">
                  <h2>Total</h2>
                  <article id="1">
                     <p class="info-producto">$1104</p>
                  </article>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-12 eliminar-item"> <a href="">ELIMINAR ITEM x</a></div>
            </div>
            <div class="row">
               <div class="col-lg-6">
                  <h4>Agregar código de bonificación</h4>
                  <input class="codigo-bonificacion" value="" placeholder="Ingrese su código aquí"> <a href="#" class="btn-carrito-negro">Aplicar</a>  
               </div>
               <div class="col-sm-6">
                  <div class="col-xs-6">
                     <h3>SUBTOTAL</h3>
                     <h3>ENVIO</h3>
                     <h3>TOTAL:</h3>
                  </div>
                  <div class="col-xs-6">
                     <h3 class="alignRight">$1104</h3>
                     <h3 class="alignRight">GRATIS</h3>
                     <h3 class="alignRight">$1104</h3>
                  </div>
               </div>
            </div>
            <br/><br/>
            <div class="row">
               <div class="col-lg-6"> </div>
               <div class="col-lg-6"><a href="#" class="btn-carrito-negro pushRight">Realizar compra</a></div>
            </div>
            <br/><br/>
            <div class="col-lg-11">
               <div class="row">
                  <div class="col-lg-3">
                     <p class="envios">Envíos a todo el país</p>
                  </div>
                  <div class="col-lg-9"><img class="img-center" src="./img/mi-carrito/formas-de-pago.png" alt=""></div>
               </div>
            </div>
            <div class="col-lg-1"></div>
         </div>
         <!-- /.ORDEN DE COMPRA -->

      </div>
 
       <?php include 'php/footer.php'; ?>
       <?php include 'php/scripts-footer.php'; ?>

   </body>
</html>