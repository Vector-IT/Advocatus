<?php
	namespace VectorForms;

	ini_set("log_errors", 1);
	ini_set("error_log", "php-error.log");
	
	require_once 'datosdb.php';
	require_once 'vectorForms.php';
	require_once 'custom/productos.php';

	//Datos de configuracion iniciales
	$config = new VectorForms($dbhost, $dbschema, $dbuser, $dbpass, $raiz, "Advocatus - e-commerce", "", true);
	$config->tbLogin = 'usuarios';
	// $config->theme = 'dark';
	// $config->cssFiles = ['admin/css/custom/custom.css'];

	/**
	 * Items de menu adicionales
	 */
	$config->menuItems = [
			new MenuItem("Configuraciones", '', '', 'fa-cogs', 1, true, false),
			new MenuItem("Productos", 'productos.php', '', 'fa-paper-plane', 2, false, true),
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
	$tabla = new Producto("productos", "productos", "Productos", "el producto", true, "productos.php", "fa-paper-plane");
	$tabla->labelField = "NombProd";
	$tabla->order = "NombProd";
	$tabla->isSubMenu = true;
	// $tabla->paginacion = true;
	$tabla->jsFiles = ["admin/js/custom/productos.js"];
	$tabla->btnList = [
			array(
				'id'=> 'btnImg',
				'titulo'=> '<i class="fa fa-image fa-fw" aria-hidden="true"></i>',
				'class'=> 'btn-primary',
				'onclick'=> 'verImagenes'
			)
	];

	$tabla->jsOnList = "armarEditables();";

	$tabla->searchFields = [
		// array("name"=>"NumeProd", "operator"=>"=", "join"=>"and"), 
		array("name"=>"ISBN", "operator"=>"LIKE", "join"=>"and"),
		array("name"=>"NombProd", "operator"=>"LIKE", "join"=>"and"),
		array("name"=>"Autor", "operator"=>"LIKE", "join"=>"and"),
	];

	$tabla->addField("ISBN", "calcfield", 0, "ISBN");
	
	$tabla->addFieldId("NumeProd", "Número", true, true);
	$tabla->addField("NombProd", "text", 200, "Nombre");
	$tabla->addField("DescProd", "textarea", 400, "Descripción");
	$tabla->fields["DescProd"]["isHiddenInList"] = true;

	$tabla->addField("CantProd", "number", 0, "Cantidad");
	$tabla->fields["CantProd"]["step"] = "0.1";
	$tabla->fields["CantProd"]["txtAlign"] = "right";
	$tabla->fields["CantProd"]["cssList"] = "editable";

	$tabla->addField("ImpoComp", "number", 0, "Imp. Compra");
	$tabla->fields["ImpoComp"]["step"] = "0.1";
	$tabla->fields["ImpoComp"]["txtAlign"] = "right";
	$tabla->fields["ImpoComp"]["cssList"] = "editable";

	$tabla->addField("ImpoVent", "number", 0, "Imp. Venta");
	$tabla->fields["ImpoVent"]["step"] = "0.1";
	$tabla->fields["ImpoVent"]["txtAlign"] = "right";
	$tabla->fields["ImpoVent"]["cssList"] = "editable";
	
	$tabla->addField("Novedad", "checkbox", 0, "Es Novedad?");
	$tabla->fields["Novedad"]["txtAlign"] = "center";
	$tabla->addField("Promocion", "checkbox", 0, "Es Promoción?");
	$tabla->fields["Promocion"]["txtAlign"] = "center";
	$tabla->addField("Destacado", "checkbox", 0, "Es Destacado?");
	$tabla->fields["Destacado"]["txtAlign"] = "center";

	$tabla->addField("Autor", "calcfield", 40, "Autor");

	$tabla->addField("NumeEsta", "select", 0, "Estado", true, false, false, true, '1', '', 'estados', 'NumeEsta', 'NombEsta', '', 'NombEsta');
	$tabla->fields["NumeEsta"]["condFormat"] = 'return ($fila[$field["name"]] == 0);';
	$tabla->fields["NumeEsta"]["classFormat"] = 'txtRed';

	$config->tablas["productos"] = $tabla;

	/**
	* ATRIBUTOS
	*/
	$tabla = new Tabla("atributos", "atributos", "Atributos", "el atributo", true, "objeto/atributos/", "fa-info-circle");
	$tabla->isSubItem = true;
	$tabla->labelField = "NombAtri";
	$tabla->order = 'NumeOrde';
	$tabla->orderField = 'NumeOrde';

	$tabla->addFieldId("NumeAtri", "Número", true, true);

	$tabla->addField('NumeOrde', 'number', 0, 'Orden');
	$tabla->fields["NumeOrde"]["showOnForm"] = false;

	$tabla->addField("NombAtri", "text", 60, "Nombre");
	$tabla->addField("NumeTipoAtri", "select", 60, "Tipo de atributo", true, false, false, true, '', '', "tiposatributos", "NumeTipoAtri", "NombTipoAtri", "", "NombTipoAtri");

	$tabla->addField("FlagRequ", "checkbox", 0, "Es Obligatorio?");
	$tabla->fields["FlagRequ"]["txtAlign"] = "center";

	$tabla->addField("NumeEsta", "select", 0, "Estado", true, false, false, true, '1', '', 'estados', 'NumeEsta', 'NombEsta', '', 'NombEsta');
	$tabla->fields["NumeEsta"]["condFormat"] = 'return ($fila[$field["name"]] == 0);';
	$tabla->fields["NumeEsta"]["classFormat"] = 'txtRed';

	$config->tablas["atributos"] = $tabla;

	/**
	* CATEGORIAS
	*/
	$tabla = new Tabla("categorias", "categorias", "Categorías", "la categoría", true, "objeto/categorias/", "fa-code");
	$tabla->isSubItem = true;
	$tabla->labelField = "NombCate";
	$tabla->order = "NumePadr, NombCate";

	$tabla->addFieldId("NumeCate", "Número");
	$tabla->addField("NombCate", "text", 80, "Nombre");
	$tabla->addFieldSelect("NumePadr", 80, "Categoría Padre", true, "", "categorias", "NumeCate", "NombCate", "NumePadr IS NULL", "NombCate", true, "SIN PADRE");

	$config->tablas["categorias"] = $tabla;

	/**
	* PRODUCTOS IMAGENES
	*/
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