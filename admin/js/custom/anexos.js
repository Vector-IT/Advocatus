function verAnexosArchivos(strID) {
    location.href = "objeto/anexosarchivos.php?NumeAnex=" + strID;
}


function cargarPadres() {
    $.post("php/tablaHandler.php", { 
        operacion: "101",
        tabla: "anexosarchivos",
        field: "NumePadr",
        NumeAnex: getVariable("NumeAnex")
        },
        function (data) {
            $('#NumePadr').parent().parent().replaceWith(data);
        }
    );
}