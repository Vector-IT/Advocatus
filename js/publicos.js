$(document).ready(function () {
    $(".clickable").click(function () {
		var url = $(this).data("url");

		var js = $(this).data("js");

		var result;
		//Ejecuto primero el js

		if (js != undefined) {
			result = eval(js);
		}

		if ((url != undefined) && ((result == undefined) || (result == true))) {
			location.href = url;
		}

	});

});

function busqSimple() {
	$("#bsqTipo").val("");
	$("#bsqEditorial").val("");
	$("#bsqCategoria").val("");
	$("#bsqAutor").val("");
	$("#bsqFecha").val("");
	$("#bsqTexto").val($("#bsqTexto1").val());
	$("#bsqSubcat").val("");

	$("#frmBusqueda").submit();
}

function busqAvanzada() {
	$("#bsqSubcat").val("");
}


//Sidebar - Carrito
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

function agregarProd(strID, cantProd) {
	if ($("#divLogin").css("display") == "none") {
		$.post("php/carritosProcesar.php", { 
			"operacion": "1",
			"NumeProd": strID,
			"CantProd": cantProd
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
