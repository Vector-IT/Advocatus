var precioViejo;
var blnEdit = false;
var intPagina = 1;

$(document).ready(function() {
    $("#actualizando").hide();
	$("#divMsj").hide();

    $(".editable").click( function() {
        precioViejo = $(this).html();
        $(this).attr("contenteditable", true);
    });

    $(".editable").blur( function() {
        var numeProd = this.id.replace("ImpoVent", "");
        var nombProd = $("#NombProd" + numeProd).html();
        var impoVent = $(this).html();

        if (precioViejo != impoVent) {
            $("#txtHint").html(nombProd + "<br>Nuevo precio: " + impoVent);
            $("#divMsj").removeClass("alert-danger");
            $("#divMsj").addClass("alert-success");
            $("#divMsj").show();
        }
    });

	$("#frmproductos").submit(function() {
	    aceptarproductos();
	});

	$("button[type='reset']").on("click", function(event){
		event.preventDefault();
		$("#frmproductos")[0].reset();
		$("textarea.autogrow").removeAttr("style");
		$(".divPreview").html("");
	});

	
});

function listarproductos() {
	$("#actualizando").show();

	var filtros = {};
	if ($("#search-NumeProd").val() != "") {
        filtros["NumeProd"] = {
        	"type": "number",
        	"operator":"=",
        	"join":"and",
        	"value":$("#search-NumeProd").val()
        }
	}
	if ($("#search-NombProd").val() != "") {
        filtros["NombProd"] = {
        	"type": "text",
        	"operator":"LIKE",
        	"join":"and",
        	"value":$("#search-NombProd").val()
        }
	}

	$.post("php/tablaHandler.php",
		{ operacion: "10"
			, tabla: "productos"
			, filtro: filtros
			, pagina: intPagina
		},
		function(data) {
			$("#actualizando").hide();
			$("#divDatos").html(data);
		    
		}
	);
}

function preview(event, divPreview) {
	divPreview.html("");
	var id = divPreview.attr("id").substr(10);

	var files = event.target.files; //FileList object

	for(var i = 0; i< files.length; i++)
	{
		var file = files[i];

		//Solo imagenes
		if(!file.type.match("image"))
			continue;

		var picReader = new FileReader();

		picReader.addEventListener("load",function(event){

			var picFile = event.target;

			divPreview.append('<img id="img' + divPreview.children().length + '" class="thumbnail" src="' + picFile.result + '" />');

			$("#btnBorrar" + id).show();
			$("#hdn" + id + "Clear").val("0");
		});

		//Leer la imagen
		picReader.readAsDataURL(file);
	}
}

function borrarproductos(strID){
	if (confirm("Desea borrar el producto " + $("#NombProd" + strID).html() + "?" )) {
		$("#hdnOperacion").val("2");
		$("#NumeProd").val(strID);
		aceptarproductos();
	}
}

function editarproductos(strID){
	if (strID > 0) {
	$("#searchForm").fadeOut(function() {
	    $("#frmproductos").fadeIn(function() {
		$("html, body").animate({
			scrollTop: $("#frmproductos").offset().top
		}, 1000);
		$("#hdnOperacion").val("1");
		blnEdit = true;
		$("#frmproductos").find(".form-control[type!='hidden'][disabled!=disabled][readonly!=readonly]:first").focus()
		$("#NumeProd").val($("#NumeProd" + strID).text());
		$("#NombProd").val($("#NombProd" + strID).text());
		$("#DescProd").val($("#DescProd" + strID).val());
		$("#DescProd").autogrow({vertical: true, horizontal: false, minHeight: 36});
		$("#CantProd").val($("#CantProd" + strID).text());
		$("#ImpoComp").val($("#ImpoComp" + strID).text());
		$("#ImpoVent").val($("#ImpoVent" + strID).text());
		$("#Novedad").prop("checked", Boolean(parseInt($("#Novedad" + strID).val())));
		$("#Promocion").prop("checked", Boolean(parseInt($("#Promocion" + strID).val())));
		$("#Destacado").prop("checked", Boolean(parseInt($("#Destacado" + strID).val())));
		$("#NumeEsta").val($("#NumeEsta" + strID).val());
		
	    });
	});
	}
	else {
		if (strID == 0) {
	        $("#searchForm").fadeOut(function() {
			    $("#frmproductos").fadeIn(function() {
				    $("#frmproductos").find(".form-control[type!='hidden'][disabled!=disabled][readonly!=readonly]:first").focus()
	                
			    });
			});
		}
		else {
			$("#frmproductos").fadeOut(function() {$("#searchForm").fadeIn();});
		}

		$("#hdnOperacion").val("0");
		blnEdit = false;
		$(".divPreview").html("");
		$("#NumeProd").val("");
		$("#NombProd").val("");
		$("#DescProd").val("");
		$("#DescProd").autogrow({vertical: true, horizontal: false, minHeight: 36});
		$("#CantProd").val("");
		$("#ImpoComp").val("");
		$("#ImpoVent").val("");
		$("#Novedad").val("");
		$("#Promocion").val("");
		$("#Destacado").val("");
		$("#NumeEsta").val("1");
	}
}

function aceptarproductos(){
	$("#actualizando").show();
	var frmData = new FormData();
	if ($("#hdnOperacion").val() != "2") {
		if (typeof validar == "function") {
			if (!validar())
				return;
		}
	}
	frmData.append("operacion", $("#hdnOperacion").val());
	frmData.append("tabla", "productos");
	frmData.append("NumeProd", $("#NumeProd").val());
	frmData.append("NombProd", $("#NombProd").val());
	frmData.append("DescProd", $("#DescProd").val());
	frmData.append("CantProd", $("#CantProd").val());
	frmData.append("ImpoComp", $("#ImpoComp").val());
	frmData.append("ImpoVent", $("#ImpoVent").val());
	frmData.append("Novedad", $("#Novedad").prop("checked") ? 1 : 0);
	frmData.append("Promocion", $("#Promocion").prop("checked") ? 1 : 0);
	frmData.append("Destacado", $("#Destacado").prop("checked") ? 1 : 0);
	frmData.append("NumeEsta", $("#NumeEsta").val());

	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			$("#txtHint").html(xmlhttp.responseText);
	
			if (xmlhttp.responseText.indexOf("Error") == -1) {
				$("#divMsj").removeClass("alert-danger");
				$("#divMsj").addClass("alert-success");
				$(".selectpicker").selectpicker("deselectAll");
				editarproductos(-1);
				listarproductos();
			}
			else {
				$("#divMsj").removeClass("alert-success");
				$("#divMsj").addClass("alert-danger");
			}
	
			$("#actualizando").hide();
			$("#divMsj").show();
		}
	};
	
	xmlhttp.open("POST","php/tablaHandler.php",true);
	xmlhttp.send(frmData);
}

function verImagenes(strID) {
    location.href= "objeto/productosimagenes&NumeProd=" + strID;
}

function selectCate(strID) {
	$("#NumeCate"+strID).toggleClass("active");

	if ($("#NumeCate"+strID).hasClass("active")) {
		$("#NumeCate"+strID).append('<i class="fa fa-check" aria-hidden="true" style="float: right;"></i>');
	}
	else {
		$("#NumeCate"+strID).find("i.fa-check").remove();
	}
	event.preventDefault();
}