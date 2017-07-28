<?php
	namespace VectorForms;

	ini_set("log_errors", 1);
	ini_set("error_log", "php-error.log");
	
	require_once 'datosdb.php';
	require_once 'vectorForms.php';

	//Datos de configuracion iniciales
	$config = new VectorForms($dbhost, $dbschema, $dbuser, $dbpass, $raiz, "Advocatus - e-commerce", "", true);
	$config->tbLogin = 'usuarios';
	$config->theme = 'dark';
	//$config->cssFiles = ['admin/css/custom/custom.css'];

	/**
	 * Items de menu adicionales
	 */
	$config->menuItems = [
			new MenuItem("Configuraciones", '', '', 'fa-cogs', 1, true, false),
			new MenuItem("Reportes", 'reportes.php', '', 'fa-slideshare', '', false, false),
			new MenuItem("Salir del Sistema", 'logout.php', '', 'fa-sign-out', '', false, false)
	];

	/**
	 * TABLAS
	 */

	/**
	 * USUARIOS
	 */
	$tabla = new Tabla("usuarios", "usuarios", "Usuarios", "el Usuario", true, "objeto/usuarios", "fa-users");
	$tabla->labelField = "NombPers";
	$tabla->numeCarg = 1;
	$tabla->isSubItem = true;

	$tabla->addField("NumeUser", "number", 0, "Número", false, true, true);
	$tabla->addField("NombPers", "text", 200, "Nombre Completo");
	$tabla->addField("NombUser", "text", 0, "Usuario");
	$tabla->fields['NombUser']['cssControl'] = "ucase";
	$tabla->fields['NombUser']['cssList'] = "ucase";

	$tabla->addField("NombPass", "password", 0, "Contraseña", true, false, false, false);
	$tabla->fields["NombPass"]['isMD5'] = true;
	$tabla->addField("NumeCarg", "select", 0, "Cargo", true, false, false, true, '', '', 'cargos', 'NumeCarg', 'NombCarg', '', 'NombCarg');
	$tabla->addField("NumeEsta", "select", 0, "Estado", true, false, false, true, '1', '', 'estados', 'NumeEsta', 'NombEsta', '', 'NombEsta');
	$tabla->fields["NumeEsta"]["condFormat"] = 'return ($fila[$field["name"]] == 0);';
	$tabla->fields["NumeEsta"]["classFormat"] = 'txtRed';

	$config->tablas["usuarios"] = $tabla;

	/**
	* PAISES
	*/
	$tabla = new Tabla("paises", "paises", "Paises", "el país", true, "objeto/paises/", "fa-cubes");
	$tabla->labelField = "NombPais";
	$tabla->isSubItem = true;

	$tabla->addFieldId("NumePais", "Número");
	$tabla->addField("NombPais", "text", 200, "Nombre");
	$tabla->fields["NombPais"]["cssControl"] = "ucase";
	$tabla->fields["NombPais"]["cssList"] = "ucase";

	$tabla->addField("NumeEsta", "select", 0, "Estado", true, false, false, true, '1', '', 'estados', 'NumeEsta', 'NombEsta', '', 'NombEsta');
	$tabla->fields["NumeEsta"]["condFormat"] = 'return ($fila[$field["name"]] == 0);';
	$tabla->fields["NumeEsta"]["classFormat"] = 'txtRed';

	$config->tablas["paises"] = $tabla;

	/**
	 * PROVINCIAS
	 */
	$tabla = new Tabla("provincias", "provincias", "Provincias", "la provincia", true, "objeto/provincias/", "fa-linode");
	$tabla->labelField = "NombProv";
	$tabla->isSubItem = true;

	$tabla->addFieldId("NumeProv", "Número");
	$tabla->addField("NumePais", "select", 60, "País", true, false, false, true, '9', '', 'paises', 'NumePais', 'NombPais', '', 'NombPais');
	$tabla->addField("NombProv", "text", 200, "Nombre");
	$tabla->fields["NombProv"]["cssControl"] = "ucase";
	$tabla->fields["NombProv"]["cssList"] = "ucase";

	$tabla->addField("NumeEsta", "select", 0, "Estado", true, false, false, true, '1', '', 'estados', 'NumeEsta', 'NombEsta', '', 'NombEsta');
	$tabla->fields["NumeEsta"]["condFormat"] = 'return ($fila[$field["name"]] == 0);';
	$tabla->fields["NumeEsta"]["classFormat"] = 'txtRed';

	$config->tablas["provincias"] = $tabla;

	/**
	* PRODUCTOS
	*/
	$tabla = new Tabla("productos", "productos", "Productos", "el producto", true, "objeto/productos/", "fa-paper-plane");
	$tabla->labelField = "NombProd";
	$tabla->jsFiles = ["admin/js/custom/productos.js"];
	$tabla->btnList = [
			array(
				'id'=> 'btnImg',
				'titulo'=> '<i class="fa fa-image fa-fw" aria-hidden="true"></i>',
				'class'=> 'btn-primary',
				'onclick'=> 'verImagenes'
			)
	];

	$tabla->addFieldId("NumeProd", "Número");
	$tabla->addField("NombProd", "text", 200, "Nombre");
	$tabla->addField("DescProd", "textarea", 400, "Descripción");
	$tabla->fields["DescProd"]["isHiddenInList"] = true;

	$tabla->addField("ImpoVent", "number", 0, "Precio");
	$tabla->fields["ImpoVent"]["step"] = "0.1";
	$tabla->fields["ImpoVent"]["txtAlign"] = "right";
	$tabla->fields["ImpoVent"]["cssList"] = "editable";
	
	$tabla->addField("Novedad", "checkbox", 0, "Es Novedad?");
	$tabla->addField("Promocion", "checkbox", 0, "Es Promoción?");
	$tabla->addField("Destacado", "checkbox", 0, "Es Destacado?");

	$tabla->addField("NumeEsta", "select", 0, "Estado", true, false, false, true, '1', '', 'estados', 'NumeEsta', 'NombEsta', '', 'NombEsta');
	$tabla->fields["NumeEsta"]["condFormat"] = 'return ($fila[$field["name"]] == 0);';
	$tabla->fields["NumeEsta"]["classFormat"] = 'txtRed';

	$config->tablas["productos"] = $tabla;

	// PRODUCTOS IMAGENES
	$tabla = new Tabla("productosimagenes", "productosimagenes", "Imágenes de Producto", "la imagen", false, "objeto/productosimagenes/", "fa-image");
	$tabla->masterTable = "productos";
	$tabla->masterFieldId = "NumeProd";
	$tabla->masterFieldName = "NombProd";

	$tabla->order = 'NumeOrde';
	$tabla->orderField = 'NumeOrde';
	
	$tabla->addFieldId('NumeImag', 'number', true, true);

	$tabla->addField('NumeOrde', 'number', 0, 'Orden');
	$tabla->fields["NumeOrde"]["showOnForm"] = false;

	$tabla->addField("NumeProd", "number", 0, 'Producto');
	$tabla->fields["NumeProd"]["isHiddenInForm"] = true;
	$tabla->fields["NumeProd"]["isHiddenInList"] = true;

	$tabla->addFieldFileImage('RutaImag', 'Imagen', 'imgProductos');
	
	$config->tablas["productosimagenes"] = $tabla;
?>