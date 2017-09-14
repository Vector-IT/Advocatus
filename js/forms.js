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
                msgChange($('#divLostMsg'), $('#iconLost'), $('#txtLostMsg'), "alert-info", "glyphicon-refresh gly-spin", "Espere un momento", $("#login-modal"), true);

                var respuesta = grecaptcha.getResponse(recaptcha1);
                $.post(
                    'php/recaptcha.php',
                    {
                        'response': respuesta
                    },
                    function (data) {
                        if (data.success) {
                            $formLost.find("#btnRecuperar").fadeOut();

                            grecaptcha.reset(recaptcha1);

                            $.post("php/usuariosProcesar.php", {
                                    operacion: "4",
                                    MailUser: $formLost.find("#lost_email").val().trim().replace("'", ""),
                                },
                                function (data) {
                                    $formLost.find("#btnRecuperar").fadeIn();
                                    
                                    if (data.estado === true) {
                                        msgChange($('#divLostMsg'), $('#iconLost'), $('#txtLostMsg'), "alert-success", "glyphicon-ok", data.msg, $("#login-modal"), true);
                                    }
                                    else {
                                        msgChange($('#divLostMsg'), $('#iconLost'), $('#txtLostMsg'), "alert-danger", "glyphicon-remove", data.msg, $("#login-modal"), false);
                                    }
                                }
                            );
                        }
                        else {
                            msgChange($('#divLostMsg'), $('#iconLost'), $('#txtLostMsg'), "alert-danger", "glyphicon-remove", "Error de captcha!", $("#login-modal"), false);
                        }
                    }
                );
                break;

            case "register-form":
                if ($(this).find("#NombPass").val().trim().replace("'", "") != $(this).find("#NombPass2").val().trim().replace("'", "")) {
                    msgChange($('#divRegisterMsg'), $('#iconRegister'), $('#txtRegisterMsg'), "alert-danger", "glyphicon-remove", "Las contraseñas no coinciden!", $("#login-modal"), false);
                    break;
                }
                msgChange($('#divRegisterMsg'), $('#iconRegister'), $('#txtRegisterMsg'), "alert-info", "glyphicon-refresh gly-spin", "Espere un momento", $("#login-modal"), false, false, false);
                $.post(
                    'php/recaptcha.php',
                    {
                        'response': grecaptcha.getResponse(recaptcha2)
                    },
                    function (data) {
                        if (data.success) {
                            grecaptcha.reset(recaptcha2);

                            $.post("php/usuariosProcesar.php", {
                                    operacion: "2",
                                    NombPers: $("#register-form").find("#NombPers").val().trim().replace("'", ""),
                                    TeleUser: $("#register-form").find("#TeleUser").val().trim().replace("'", ""),
                                    MailUser: $("#register-form").find("#MailUser").val().trim().replace("'", ""),
                                    DireUser: $("#register-form").find("#DireUser").val().trim().replace("'", ""),
                                    CodiPost: $("#register-form").find("#CodiPost").val().trim().replace("'", ""),
                                    NombLoca: $("#register-form").find("#NombLoca").val().trim().replace("'", ""),
                                    NumeProv: $("#register-form").find("#NumeProv").val(),
                                    NombUser: $("#register-form").find("#NombUser").val().trim().replace("'", ""),
                                    NombPass: $("#register-form").find("#NombPass").val().trim().replace("'", "")
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
                        }
                        else {
                            msgChange($('#divRegisterMsg'), $('#iconRegister'), $('#txtRegisterMsg'), "alert-danger", "glyphicon-remove", "Error de captcha!", $("#login-modal"), false);
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
                        NombLoca: $(this).find("#NombLoca").val().trim().replace("'", ""),
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
    
    function msgChange($divTag, $iconTag, $textTag, $divClass, $iconClass, $msgText, $modal, closeModal = true, reloadPage = false, ocultarMsg = true) {
        var $msgOld = "";
        msgFade($textTag, $msgText);
        
        $divTag.attr('class', 'alert '+ $divClass);
        
        $iconTag.attr('class', "glyphicon " + $iconClass);
        
        if (ocultarMsg) {
            setTimeout(function() {
                $divTag.attr('class', 'alert');
                $iconTag.removeClass();
                $textTag.html($msgOld);

                if (closeModal) {
                    $modal.modal('hide');
                }

                if (reloadPage) {
                    location.reload();
                }
            }, $msgShowTime);
        }
    }
});