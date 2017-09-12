    <!-- FOOTER -->
    <div class="container-full">
      <footer>
        <div class="row noMargin">
          <div class="col-sm-1">
          </div>
          <div class="col-sm-10">
            <div class="col-sm-4">
              <a href="#"><img src="./img/home/logo-footer.png" alt="" style="margin-bottom: 25px;"></a>
              <div class="col-sm-9 redes-sociales">
                <?php 
                if ($face != '') echo $crlf.'<a href="'. $face .'" target="_blank"><img class="img-center" src="./img/home/social_fb.png" alt=""></a>';
                if ($twit != '') echo $crlf.'<a href="'. $twit .'" target="_blank"><img class="img-center" src="./img/home/social_tw.png" alt=""></a>';
                if ($inst != '') echo $crlf.'<a href="'. $inst .'" target="_blank"><img class="img-center" src="./img/home/social_inst.png" alt=""></a>';
                if ($goog != '') echo $crlf.'<a href="'. $goog .'" target="_blank"><img class="img-center" src="./img/home/social_g.png" alt=""></a>';
                ?>
              </div>
              <div class="col-sm-3">
                <a href="http://qr.afip.gob.ar/?qr=pFPVKyHQXZNOU1w81V302A,," target="_blank">
                  <img class="img-responsive" src="./img/afip-dataweb.jpg" alt="" style="max-width: 60px;">
                </a>
              </div> 
            </div>
            <div class="col-sm-4">
              <div class="col-sm-6">
                <ul>
                  <li><a href="index.php">Home</a></li>
                  <li><a href="quienes-somos.php">Quienes Somos</a></li>
                  <li><a href="productos.php">Productos</a></li>
                </ul>
              </div>
              <div class="col-sm-6">
                <ul>
                  <li><a href="novedades.php">Novedades</a></li>
                  <li><a href="promociones.php">Promociones</a></li>
                  <li><a href="contacto.php">Contacto</a></li>
                </ul>
              </div>
            </div>
            <div class="col-sm-4">
              <a class="btn-suscripcion" href="#">SUSCRIBIRSE AL BOLETIN DE NOTICIAS</a>
              <a class="btn-envios" href="#">ENVIOS</a>
            </div>
          </div>
          <div class="col-sm-1">
          </div>
        </div>
        <!-- /.row -->
        <div class="row noMargin subfooter">
          <div class="col-sm-1"></div>
          <div class="col-sm-10">
          <div class="row noMargin noPadding">
          <div class="col-lg-3">
           <p class="tels">Tel.: +54 351 446 - 1931 / 1932</p>  
          </div>
          <div class="col-lg-6">
            <a href="https://www.mercadopago.com.ar/promociones" target="_blank"><img class="img-responsive img-center" src="./img/home/tarjetas-footer.png" alt="" style="max-width: 420px;"></a>
          </div>
          <div class="col-lg-3">
            <p class="copyright">EDITORIAL ADVOCATUS LIBRERIA. ©2017</p> 
          </div>
          </div>
          </div>
          <div class="col-sm-1"></div>
        </div>
        <!-- /.row -->
      </footer>
    </div>
    <!-- /.FOOTER -->