<?php
	session_start();
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
      <title>Contacto - ADVOCATUS | Editorial · Librería</title>

      <?php include 'php/links-header.php'; ?>

   </head>
   <body>
      <?php include 'php/header.php'; ?>

      <!-- CONTENIDO -->
      <div id="contacto">
         <div class="container-full fondo">
            <div class="row noMargin">
               <div class="col-lg-7">       
               </div>
               <div class="col-lg-5">  
               </div>
            </div>
         </div>
         <div class="container-full formulario">
            <div class="row noMargin">
               <div class="col-lg-5">
               </div>
               <div class="col-lg-6">
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
                     <div class="col-lg-4">
                        <p><span>Sucursal Centro</span><br/>
                           Obispo Trejo 181 - Córdoba <br/>
                           +54 (0351) 446-1931
                        </p>
                        <a href="mailto:administracion@eadvocatus.com.ar" target="_top">administracion@eadvocatus.com.ar</a>
                     </div>
                     <div class="col-lg-4">
                        <p><span>Sucursal Tribunales</span><br/>
                           Montevideo 635 - Córdoba <br/>
                           +54 (0351) 426-1177 / 446-1932  <br/>
                           351 657-1003 (WSP)
                        </p>
                        <a href="mailto:editorial@eadvocatus.com.ar" target="_top">editorial@eadvocatus.com.ar </a>
                        <a href="mailto:info@eadvocatus.com.ar" target="_top">info@eadvocatus.com.ar</a>
                     </div>
                     <div class="col-lg-4">
                        <p><span>Sucursal UBP</span><br/>
                           Universidad Blas Pascal <br/>
                           Av. Donato Alvarez 380 
                        </p>
                        <a href="mailto:ubp@eadvocatus.com.ar" target="_top">ubp@eadvocatus.com.ar</a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-1">
               </div>
            </div>
         </div>
      </div>
      <!-- /.CONTENIDO -->


      <?php include 'php/footer.php'; ?>
      <?php include 'php/scripts-footer.php'; ?>

      <!-- Contact Form JavaScript -->
      <script src="js/jqBootstrapValidation.js"></script>
      <script src="js/contact_me.js"></script>
   </body>
</html>