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

		if ($('ul.nav.navbar-nav li.active').length == 0) {
			$('ul.nav.navbar-nav a[href="index.php"]').parent().addClass('active');
		}
    });
</script>