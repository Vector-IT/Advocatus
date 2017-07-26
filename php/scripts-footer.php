    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Slide Box JavaScript -->
    <script>

    </script>
    <!-- Login Modal JavaScript -->
    <script src="js/login-modal.js" type="text/javascript"></script>
    <!-- Menu Handler JavaScript -->    
    <script>
	$(document).ready(function () {
	var url = window.location;
	// Will only work if string in href matches with location
	$('ul.nav a[href="' + url + '"]').parent().addClass('active');
	// Will also work for relative and absolute hrefs
	$('ul.nav a').filter(function() {
	    return this.href == url;
	}).parent().addClass('active');
	    });
    </script>