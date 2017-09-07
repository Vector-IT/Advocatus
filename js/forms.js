$(function() {
    var $formLogin = $('#login-form');
    var $formLost = $('#lost-form');
    var $formRegister = $('#register-form');
    var $divForms = $('#div-forms');

    var $modalAnimateTime = 300;
    var $msgAnimateTime = 150;
    var $msgShowTime = 2000;

    $("form").submit(function () {
        switch(this.id) {
            case "login-form":
                $.post("php/usuariosProcesar.php", {
                        operacion: "1",
                        usuario: $(this).find("#nombUser").val().trim(),
                        password: $(this).find("#nombPass").val().trim(),
                        remember: ($(this).find("#chkRemember").prop("checked") ? 1 : 0),
                    },
                    function (data) {
                        if (data.estado === true) {
                            msgChange($('#divLoginMsg'), $('#iconLogin'), $('#txtLoginMsg'), "alert-success", "glyphicon-ok", data.msg, $("#login-modal"), false, true);
                        }
                        else {
                            msgChange($('#divLoginMsg'), $('#iconLogin'), $('#txtLoginMsg'), "alert-danger", "glyphicon-remove", data.msg, $("#login-modal"), false);
                        }
                    }
                );
                break;

            case "lost-form":
                var $ls_email=$(this).find('#lost_email').val();
                if ($ls_email == "ERROR") {
                    msgChange($('#div-lost-msg'), $('#icon-lost-msg'), $('#text-lost-msg'), "error", "glyphicon-remove", "Send error");
                } else {
                    msgChange($('#div-lost-msg'), $('#icon-lost-msg'), $('#text-lost-msg'), "success", "glyphicon-ok", "Send OK");
                }
                break;

            case "register-form":
                $.post("php/usuariosProcesar.php", {
                        operacion: "2",
                        NombPers: $(this).find("#NombPers").val().trim().replace("'", ""),
                        TeleUser: $(this).find("#TeleUser").val().trim().replace("'", ""),
                        MailUser: $(this).find("#MailUser").val().trim().replace("'", ""),
                        DireUser: $(this).find("#DireUser").val().trim().replace("'", ""),
                        CodiPost: $(this).find("#CodiPost").val().trim().replace("'", ""),
                        NumeProv: $(this).find("#NumeProv").val(),
                        NombUser: $(this).find("#NombUser").val().trim().replace("'", ""),
                        NombPass: $(this).find("#NombPass").val().trim().replace("'", "")
                    },
                    function (data) {
                        if (data.estado === true) {
                            msgChange($('#divRegisterMsg'), $('#iconRegister'), $('#txtRegisterMsg'), "alert-success", "glyphicon-ok", data.msg, $("#login-modal"), true);
                        }
                        else {
                            msgChange($('#divRegisterMsg'), $('#iconRegister'), $('#txtRegisterMsg'), "alert-danger", "glyphicon-remove", data.msg, $("#login-modal"), false);
                        }
                    }
                );
                break;
            
            case "frmEnvio":
                $.post("php/usuariosProcesar.php", {
                        operacion: "3",
                        NombPers: $(this).find("#NombPers").val().trim().replace("'", ""),
                        TeleUser: $(this).find("#TeleUser").val().trim().replace("'", ""),
                        MailUser: $(this).find("#MailUser").val().trim().replace("'", ""),
                        DireUser: $(this).find("#DireUser").val().trim().replace("'", ""),
                        CodiPost: $(this).find("#CodiPost").val().trim().replace("'", ""),
                        NumeProv: $(this).find("#NumeProv").val(),
                    },
                    function (data) {
                        if (data.estado === true) {
                            location.reload();
                        }
                    }
                );
                break;

            default:
                return true;
        }
        return false;
    });
    
    $('#login_register_btn').click( function () { modalAnimate($formLogin, $formRegister) });
    $('#register_login_btn').click( function () { modalAnimate($formRegister, $formLogin); });
    $('#login_lost_btn').click( function () { modalAnimate($formLogin, $formLost); });
    $('#lost_login_btn').click( function () { modalAnimate($formLost, $formLogin); });
    $('#lost_register_btn').click( function () { modalAnimate($formLost, $formRegister); });
    $('#register_lost_btn').click( function () { modalAnimate($formRegister, $formLost); });
    
    function modalAnimate ($oldForm, $newForm) {
        var $oldH = $oldForm.height();
        var $newH = $newForm.height();
        $divForms.css("height",$oldH);
        $oldForm.fadeToggle($modalAnimateTime, function(){
            $divForms.animate({height: $newH}, $modalAnimateTime, function(){
                $newForm.fadeToggle($modalAnimateTime);
            });
        });
    }
    
    function msgFade ($msgId, $msgText) {
        $msgId.fadeOut($msgAnimateTime, function() {
            $(this).html($msgText).fadeIn($msgAnimateTime);
        });
    }
    
    function msgChange($divTag, $iconTag, $textTag, $divClass, $iconClass, $msgText, $modal, close = true, reload = false) {
        var $msgOld = "";
        msgFade($textTag, $msgText);
        
        $divTag.addClass("alert " + $divClass);
        
        $iconTag.addClass("glyphicon " + $iconClass);
        
        setTimeout(function() {
            $divTag.removeClass();
            $iconTag.removeClass();
            $textTag.html($msgOld);

            if (close) {
                $modal.modal('hide');
            }

            if (reload) {
                location.reload();
            }
  		}, $msgShowTime);
    }
});