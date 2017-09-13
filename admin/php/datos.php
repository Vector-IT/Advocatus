<?php
	namespace VectorForms;

	ini_set("log_errors", 1);
	ini_set("error_log", "php-error.log");
	
	require_once 'datosdb.php';
	require_once 'vectorForms.php';
	require_once 'custom/productos.php';
	require_once 'custom/categorias.php';
	require_once 'custom/carritos.php';

	//Datos de configuracion iniciales
	$config = new VectorForms($dbhost, $dbschema, $dbuser, $dbpass, $raiz, "Advocatus - e-commerce", "", true);
	$config->tbLogin = 'usuarios';
	// $config->theme = 'dark';
	$config->cssFiles = ['admin/css/custom/custom.css'];

	/**
	 * Items de menu adicionales
	 */
	$config->menuItems = [
			new MenuItem("Configuraciones", '', '', 'fa-cogs', 1, true, false),
			new MenuItem("Productos", 'productos.php', '', 'fa-paper-plane', 2, false, true),
			new MenuItem("Reportes", 'reportes.php', '', 'fa-slideshare', '', false, false),
			new MenuItem("Ir al sitio", $raiz, '', 'fa-bookmark', '', false, false),
			new MenuItem("Salir del Sistema", 'logout.php', '', 'fa-sign-out', '', false, false)
	];

	/**
	 * TABLAS
	 */

	/**
	* CONFIGURACIONES
	*/
	$tabla = new Tabla("configuraciones", "configuraciones", "Configuraciones", "la configuración", true, "objeto/configuraciones.php", "fa-cogs");
	$tabla->labelField = "NombConf";
	$tabla->numeCarg = 2;
	$tabla->numeCargNew = 1;
	$tabla->numeCargDelete = 1;

	$tabla->isSubItem = true;
	$tabla->jsOnNew = '$("#NombConf").attr("readonly", false);';
	$tabla->jsOnEdit = '$("#NombConf").attr("readonly", true);';

	$tabla->addFieldId("NumeConf", "Número");
	$tabla->addField("NombConf", "text", 60, "Nombre", true, true);
	$tabla->addField("ValoConf", "text", 200, "Valor", false);
	
	$config->tablas["configuraciones"] = $tabla;

	/**
	* USUARIOS
	*/
	$tabla = new Tabla("usuarios", "usuarios", "Usuarios", "el Usuario", true, "objeto/usuarios.php", "fa-users");
	$tabla->labelField = "NombPers";
	$tabla->numeCarg = 2;
	$tabla->isSubItem = true;

	$tabla->addField("NumeUser", "number", 0, "Número", false, true, true);
	$tabla->addField("NombPers", "text", 200, "Nombre Completo");
	$tabla->addField("NombUser", "text", 0, "Usuario");
	$tabla->fields['NombUser']['cssControl'] = "ucase";
	$tabla->fields['NombUser']['cssList'] = "ucase";

	$tabla->addField("NombPass", "password", 0, "Contraseña", true, false, false, false);
	$tabla->fields["NombPass"]['isMD5'] = true;
	$tabla->addField("NumeCarg", "select", 0, "Cargo", true, false, false, true, '', '', 'cargos', 'NumeCarg', 'NombCarg', 'NumeEsta = 1', 'NombCarg');

	$tabla->addField("MailUser", "mail", 50, "Mail");
	$tabla->addField("TeleUser", "text", 50, "Teléfono");
	$tabla->addField("DireUser", "text", 50, "Dirección");
	$tabla->addField("CodiPost", "text", 50, "Código Postal");
	$tabla->addFieldSelect("NumeProv", 50, "Provincia", true, "", "provincias", "", "NumeProv", "NombProv", "", "", "NombProv");

	$tabla->addField("NumeEsta", "select", 0, "Estado", true, false, false, true, '1', '', 'estados', 'NumeEsta', 'NombEsta', '', 'NombEsta');
	$tabla->fields["NumeEsta"]["condFormat"] = 'return ($fila[$field["name"]] == 0);';
	$tabla->fields["NumeEsta"]["classFormat"] = 'txtRed';

	$config->tablas["usuarios"] = $tabla;

	/**
	* SLIDERS
	*/
	$tabla = new Tabla("sliders", "sliders", "Sliders", "el Slider", true, "objeto/sliders.php", "fa-picture-o");
	$tabla->labelField = "NombSlid";
	$tabla->isSubItem = true;
	$tabla->numeCargNew = 1;
	$tabla->numeCargEdit = 1;
	$tabla->numeCargDelete = 1;
	
	$tabla->jsFiles = ["admin/js/custom/sliders.js"];

	$tabla->btnList = [
		array(
			'id'=> 'btnImg',
			'titulo'=> 'Imágenes',
			'texto'=> '<i class="fa fa-image fa-fw" aria-hidden="true"></i>',
			'class'=> 'btn-primary',
			'onclick'=> 'verImagenes'
		)
	];

	$tabla->addFieldId("NumeSlid", "Número");
	$tabla->addField("NombSlid", "text", 50, "Nombre");

	$config->tablas["sliders"] = $tabla;

	/**
	* SLIDERS IMAGENES
	*/
	$tabla = new Tabla("slidersimagenes", "slidersimagenes", "Imágenes del Slider", "la Imagen", false, "objeto/slidersimagenes.php", "fa-picture-o");
	$tabla->masterTable = "sliders";
	$tabla->masterFieldId = "NumeSlid";
	$tabla->masterFieldName = "NombSlid";
	$tabla->orderField = "NumeOrde";

	$tabla->addFieldId("NumeImag", "Número", true, true);
	$tabla->addField("NumeSlid", "number", 0, "Slider");
	$tabla->fields["NumeSlid"]["isHiddenInList"] = true;
	$tabla->fields["NumeSlid"]["isHiddenInForm"] = true;
	
	$tabla->addField("NumeOrde", "number", 0, "Orden");
	$tabla->fields["NumeOrde"]["showOnForm"] = false;

	$tabla->addFieldFileImage("RutaImag", "Imagen", "imgSliders");

	$config->tablas["slidersimagenes"] = $tabla;

	/**
	* CARGOS
	*/
	$tabla = new Tabla("cargos", "cargos", "Cargos", "el Cargo", false);
	$tabla->labelField = "NombCarg";
	$tabla->order = "NumeCarg";

	$tabla->addFieldId("NumeCarg", "Número");
	$tabla->addField("NombCarg", "text", 50, "Nombre");
	$tabla->addField("NumeEsta", "select", 0, "Estado", true, false, false, true, '1', '', 'estados', 'NumeEsta', 'NombEsta', '', 'NombEsta');
	$tabla->fields["NumeEsta"]["condFormat"] = 'return ($fila[$field["name"]] == 0);';
	$tabla->fields["NumeEsta"]["classFormat"] = 'txtRed';

	$config->tablas["cargos"] = $tabla;

	/**
	* PAISES
	*/
	$tabla = new Tabla("paises", "paises", "Paises", "el país", true, "objeto/paises.php", "fa-cubes");
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
	$tabla = new Tabla("provincias", "provincias", "Provincias", "la provincia", true, "objeto/provincias.php", "fa-linode");
	$tabla->labelField = "NombProv";
	$tabla->isSubItem = true;
	
	$tabla->searchFields = [
		// array("name"=>"NumeProd", "operator"=>"=", "join"=>"and"), 
		array("name"=>"NombProv", "operator"=>"LIKE", "join"=>"and"),
	];

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
			'titulo'=> 'Imágenes',
			'texto'=> '<i class="fa fa-image fa-fw" aria-hidden="true"></i>',
			'class'=> 'btn-primary',
			'onclick'=> 'verImagenes'
		)
	];

	$tabla->jsOnLoad = "armarEditables();";
	$tabla->jsOnList = "armarEditables();";

	$tabla->searchFields = [
		// array("name"=>"NumeProd", "operator"=>"=", "join"=>"and"), 
		array("name"=>"ISBN", "operator"=>"LIKE", "join"=>"and"),
		array("name"=>"NombProd", "operator"=>"LIKE", "join"=>"and"),
		array("name"=>"Autor", "operator"=>"LIKE", "join"=>"and"),
	];

	$tabla->addFieldId("NumeProd", "Número", true, true);
	
	// $tabla->addField("ISBN", "calcfield", 0, "ISBN");
	$tabla->addFieldSelect("ISBN", 80, "ISBN", true, "", "productosatributos", "pai", "NumeProd", "Valor", "", "pai.NumeAtri IN (SELECT NumeAtri FROM atributos WHERE UPPER(NombAtri) = 'ISBN')", "");
	$tabla->fields["ISBN"]["name"] = 'NumeProd';
	$tabla->fields["ISBN"]["nameAlias"] = 'ISBN';
	$tabla->fields["ISBN"]["showOnForm"] = false;
	
	$tabla->addField("NombProd", "text", 200, "Nombre");
	$tabla->addField("SlugProd", "text", 200, "Slug");
	$tabla->fields["SlugProd"]["isHiddenInList"] = true;

	$tabla->addField("DescProd", "textarea", 400, "Descripción");
	$tabla->fields["DescProd"]["isHiddenInList"] = true;

	$tabla->addField("Peso", "number", 0, "Peso");
	$tabla->fields["Peso"]["step"] = "0.0001";
	$tabla->fields["Peso"]["txtAlign"] = "right";
	$tabla->fields["Peso"]["showOnForm"] = false;
	
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
	
	$tabla->addField("Promocion", "checkbox", 0, "Es Promoción?");
	$tabla->fields["Promocion"]["txtAlign"] = "center";
	$tabla->addField("Destacado", "checkbox", 0, "Es Destacado?");
	$tabla->fields["Destacado"]["txtAlign"] = "center";

	// $tabla->addField("Autor", "calcfield", 40, "Autor");
	$tabla->addFieldSelect("Autor", 80, "Autor", true, "", "productosatributos", "paa", "NumeProd", "Valor", "", "paa.NumeAtri IN (SELECT NumeAtri FROM atributos WHERE UPPER(NombAtri) = 'AUTOR')", "");
	$tabla->fields["Autor"]["name"] = "NumeProd";
	$tabla->fields["Autor"]["nameAlias"] = "AUTOR";
	$tabla->fields["Autor"]["showOnForm"] = false;

	$tabla->addField("NumeEsta", "select", 0, "Estado", true, false, false, true, '1', '', 'estados', 'NumeEsta', 'NombEsta', '', 'NombEsta');
	$tabla->fields["NumeEsta"]["condFormat"] = 'return ($fila[$field["name"]] == 0);';
	$tabla->fields["NumeEsta"]["classFormat"] = 'txtRed';

	$config->tablas["productos"] = $tabla;

	/**
	* ATRIBUTOS
	*/
	$tabla = new Tabla("atributos", "atributos", "Atributos", "el atributo", true, "objeto/atributos.php", "fa-info-circle");
	$tabla->isSubItem = true;
	$tabla->labelField = "NombAtri";
	$tabla->order = 'NumeOrde';
	$tabla->orderField = 'NumeOrde';

	$tabla->jsFiles = ["admin/js/custom/atributos.js"];
	$tabla->btnList = [
		array(
			'id'=> 'btnOpciones',
			'titulo'=> 'Opciones',
			'texto'=> '<i class="fa fa-list fa-fw" aria-hidden="true"></i>',
			'class'=> 'btn-primary',
			'onclick'=> 'verOpciones'
		)
	];

	$tabla->jsOnLoad = "habilitarOpciones();";
	$tabla->jsOnList = "habilitarOpciones();";

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
	* ATRIBUTOS OPCIONES
	*/
	$tabla = new Tabla("atributosopciones", "atributosopciones", "Opciones", "la opción", false);
	$tabla->order = "Valor";
	$tabla->masterTable = "atributos";
	$tabla->masterFieldId = "NumeAtri";
	$tabla->masterFieldName = "NombAtri";

	$tabla->addFieldId("NumeAtriOpci", "Numero", true, true);
	$tabla->addField("NumeAtri", "number");
	$tabla->fields["NumeAtri"]["isHiddenInList"] = true;
	$tabla->fields["NumeAtri"]["isHiddenInForm"] = true;

	$tabla->addField("Valor", "text", 80, "Valor");

	$config->tablas["atributosopciones"] = $tabla;

	/**
	* CATEGORIAS
	*/
	$tabla = new Categoria("categorias", "categorias", "Categorías", "la categoría", true, "objeto/categorias.php", "fa-code");
	$tabla->isSubItem = true;
	$tabla->labelField = "NombCate";
	$tabla->order = "categorias.NumePadr, categorias.NombCate";

	$tabla->addFieldId("NumeCate", "Número");
	$tabla->addField("NombCate", "text", 80, "Nombre");
	$tabla->addFieldSelect("NumePadr", 80, "Categoría Padre", false, "", "categorias", "cate", "NumeCate", "NombCate", "cate.NumePadr IS NULL", "", "NombCate", true, "SIN PADRE");
	$tabla->fields["NumePadr"]["nameAlias"] = "NombCate2";

	$config->tablas["categorias"] = $tabla;

	/**
	* PRODUCTOS IMAGENES
	*/
	$tabla = new Tabla("productosimagenes", "productosimagenes", "Imágenes de Producto", "la imagen", false, "objeto/productosimagenes.php", "fa-image");
	$tabla->masterTable = "productos";
	$tabla->masterFieldId = "NumeProd";
	$tabla->masterFieldName = "NombProd";

	$tabla->order = 'NumeOrde';
	$tabla->orderField = 'NumeOrde';
	
	$tabla->addFieldId('NumeImag', 'Número', true, true);

	$tabla->addField('NumeOrde', 'number', 0, 'Orden');
	$tabla->fields["NumeOrde"]["showOnForm"] = false;

	$tabla->addField("NumeProd", "number", 0, 'Producto');
	$tabla->fields["NumeProd"]["isHiddenInForm"] = true;
	$tabla->fields["NumeProd"]["isHiddenInList"] = true;

	$tabla->addFieldFileImage('RutaImag', 'Imagen', 'imgProductos');
	
	$config->tablas["productosimagenes"] = $tabla;

	/**
	* PRODUCTOS NOVEDADES
	*/
	$tabla = new Tabla("productosnovedades", "productosnovedades", "Novedades", "el producto", true, "objeto/productosnovedades.php", "fa-newspaper-o");
	$tabla->isSubItem = true;

	$tabla->order = 'NumeOrde';
	$tabla->orderField = 'NumeOrde';
	$tabla->orderFieldAppend = false;
	
	$tabla->addFieldId("CodiIden", "Número", true, true);

	$tabla->addField('NumeOrde', 'number', 0, 'Orden');
	$tabla->fields["NumeOrde"]["showOnForm"] = false;

	$tabla->addFieldSelect("NumeProd", 80, "Producto", true, "", "productos", "", "NumeProd", "NombProd", "NumeProd NOT IN (SELECT NumeProd FROM productosnovedades)", "", "NombProd");
	$tabla->addField("FechNove", "datetime", 0, "Fecha");
	$tabla->fields["FechNove"]["showOnForm"] = false;

	$config->tablas["productosnovedades"] = $tabla;

	/**
	* SHIPPING
	*/
	$tabla = new Tabla("shipping", "shipping", "Costos Envíos", "el precio", true, "objeto/shipping.php", "fa-truck");
	$tabla->labelField = "NumeProv";
	$tabla->order = "NumeProv, PesoShip";

	$tabla->searchFields = [
		array("name"=>"NumeProv", "operator"=>"=", "join"=>"and"),
		array("name"=>"PesoShip", "operator"=>"=", "join"=>"and"),
	];

	$tabla->addFieldId("CodiIden", "Número", true, true);
	$tabla->addFieldSelect("NumeProv", 50, "Provincia", false, "", "provincias", "", "NumeProv", "NombProv", "", "", "NombProv", true, "TODAS LAS PROVINCIAS");
	$tabla->addField("PesoShip", "number", 0, "Peso (en Kgs)");
	$tabla->fields["PesoShip"]["step"] = "0.001";
	$tabla->fields["PesoShip"]["txtAlign"] = "right";

	$tabla->addField("ImpoShip", "number", 0, "Importe");
	$tabla->fields["ImpoShip"]["step"] = "0.001";
	$tabla->fields["ImpoShip"]["txtAlign"] = "right";

	$config->tablas["shipping"] = $tabla;

	/**
	* CARRITOS
	*/
	$tabla = new Carrito("carritos", "carritos", "Carritos de compra", "el carrito", true, "objeto/carritos.php", "fa-shopping-cart");
	$tabla->order = "FechCarr DESC";
	
	$tabla->allowNew = false;
	$tabla->allowEdit = false;
	$tabla->allowDelete = false;

	$tabla->searchFields = [
		array("name"=>"NumeEstaCarr", "operator"=>"=", "join"=>"and"),
	];

	$tabla->jsFiles = ["admin/js/custom/carritos.js"];
	$tabla->jsOnLoad = "checkButtons();";
	$tabla->jsOnList = "checkButtons();";

	$tabla->btnList = [
		array(
			'id'=> 'btnUsuarios',
			'titulo'=> 'Ver Usuario',
			'texto'=> '<i class="fa fa-users fa-fw" aria-hidden="true"></i>',
			'class'=> 'btn-info',
			'onclick'=> 'verUsuario'
		),
		array(
			'id'=> 'btnDetalles',
			'titulo'=> 'Ver Productos',
			'texto'=> '<i class="fa fa-paper-plane fa-fw" aria-hidden="true"></i>',
			'class'=> 'btn-info',
			'onclick'=> 'verProductos'
		),
		array(
			'id'=> 'btnProcesar',
			'titulo'=> 'Procesar',
			'texto'=> '<i class="fa fa-check-square-o fa-fw" aria-hidden="true"></i>',
			'class'=> 'btn-danger',
			'onclick'=> 'procesar'
		),
	];

	$tabla->modalList = ["php/modals/carritos.php"];

	$tabla->addFieldId("NumeCarr", "Número");
	$tabla->addField("FechCarr", "datetime", 0, "Fecha");
	$tabla->addFieldSelect("NumeUser", 0, "Usuario", true, "", "usuarios", "", "NumeUser", "NombPers", "", "", "NombPers");
	$tabla->fields["NumeUser"]["nameAlias"] = "NombPersUser";

	$tabla->addFieldSelect("NumeInvi", 0, "Invitado", true, "", "invitados", "", "NumeInvi", "NombPers", "", "", "NombPers");
	$tabla->fields["NumeInvi"]["nameAlias"] = "NombPersInvi";

	$tabla->addField("ImpoSubt", "number", 0, "Sub-Total");
	$tabla->fields["ImpoSubt"]["txtAlign"] = "right";
	$tabla->fields["ImpoSubt"]["isHiddenInList"] = true;
	
	$tabla->addField("ImpoShip", "number", 0, "Envio");
	$tabla->fields["ImpoShip"]["txtAlign"] = "right";
	$tabla->fields["ImpoShip"]["isHiddenInList"] = true;
	
	$tabla->addField("ImpoDesc", "number", 0, "Descuento");
	$tabla->fields["ImpoDesc"]["txtAlign"] = "right";
	$tabla->fields["ImpoDesc"]["isHiddenInList"] = true;
	
	$tabla->addField("ImpoTota", "number", 0, "Total");
	$tabla->fields["ImpoTota"]["txtAlign"] = "right";
	
	$tabla->addFieldSelect("FlagShip", 0, "Envío", true, "", "tiposenvios", "", "NumeShip", "NombShip", "", "", "");

	$tabla->addFieldSelect("NumeEstaCarr", 0, "Estado", true, "", "estadoscarritos", "", "NumeEstaCarr", "NombEstaCarr", "", "", "NombEstaCarr");
	
	$tabla->addField("ID_MP", "text", 0, "ID MP");
	$tabla->fields["ID_MP"]["isHiddenInList"] = true;

	$tabla->addField("NombPers", "text", 0, "Envío - Persona");
	$tabla->fields["NombPers"]["nameAlias"] = "NombPersCarr";
	
	$tabla->addField("MailUser", "mail", 0, "Envío - Mail");
	$tabla->addField("TeleUser", "text", 0, "Envío - Teléfono");
	$tabla->addField("DireUser", "text", 0, "Envío - Dirección");
	$tabla->addField("CodiPost", "text", 0, "Envío - Código Postal");
	$tabla->addFieldSelect("NumeProv", 0, "Envío - Provincia", true, "", "provincias", "", "NumeProv", "NombProv", "", "", "NombProv");
	
	$tabla->addField("NumeFact", "text", 0, "Factura");
	$tabla->addField("ObseCarr", "textarea", 0, "Observaciones");

	$config->tablas["carritos"] = $tabla;

	/**
	* DETALLES CARRITOS
	*/
	$tabla = new Tabla("carritosdetalles", "carritosdetalles", "Productos del carrito", "el producto", false, "", "fa-paper-plane");
	$tabla->labelField = "NumeProd";
	$tabla->masterTable = "carritos";
	$tabla->masterFieldId = "NumeCarr";
	$tabla->masterFieldName = "NumeCarr";
	
	$tabla->footerField = "ImpoTota";

	$tabla->allowNew = false;
	$tabla->allowEdit = false;
	$tabla->allowDelete = false;

	$tabla->addFieldId("CodiIden", "Código", true, true);
	
	$tabla->addField("NumeCarr", "number", 0, "Carrito");
	$tabla->fields["NumeCarr"]["isHiddenInList"] = true;
	$tabla->fields["NumeCarr"]["isHiddenInForm"] = true;

	$tabla->addFieldSelect("NumeProd", 0, "Producto", true, "", "productos", "", "NumeProd", "NombProd", "", "", "NombProd");
	
	$tabla->addField("CantProd", "number", 0, "Cantidad");
	$tabla->fields["CantProd"]["txtAlign"] = "right";
	
	$tabla->addField("ImpoUnit", "number", 0, "Importe unitario");
	$tabla->fields["ImpoUnit"]["txtAlign"] = "right";

	$tabla->addField("ImpoTota", "number", 0, "Total");
	$tabla->fields["ImpoTota"]["txtAlign"] = "right";

	$config->tablas["carritosdetalles"] = $tabla;

	unset($tabla);
?>