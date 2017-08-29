$(function() {
    var $formLogin = $('#login-form');
    var $formLost = $('#lost-form');
    var $formRegister = $('#register-form');
    var $divForms = $('#div-forms');

    var $modalAnimateTime = 300;
    var $msgAnimateTime = 150;
    var $msgShowTime = 3000;

    $("form").submit(function () {
        switch(this.id) {
            case "login-form":
                var $lg_username=$('#login_username').val();
                var $lg_password=$('#login_password').val();
                if ($lg_username == "ERROR") {
                    msgChange($('#div-login-msg'), $('#icon-login-msg'), $('#text-login-msg'), "error", "glyphicon-remove", "Login error");
                } else {
                    msgChange($('#div-login-msg'), $('#icon-login-msg'), $('#text-login-msg'), "success", "glyphicon-ok", "Login OK");
                }
                break;

            case "lost-form":
                var $ls_email=$('#lost_email').val();
                if ($ls_email == "ERROR") {
                    msgChange($('#div-lost-msg'), $('#icon-lost-msg'), $('#text-lost-msg'), "error", "glyphicon-remove", "Send error");
                } else {
                    msgChange($('#div-lost-msg'), $('#icon-lost-msg'), $('#text-lost-msg'), "success", "glyphicon-ok", "Send OK");
                }
                break;

            case "register-form":
                var nombPers = $("#NombPers").val().trim().replace("'", "");
                var valor = "ERROR";

                if (valor == "ERROR") {
                    msgChange($('#divRegisterMsg'), $('#iconRegister'), $('#txtRegisterMsg'), "alert-danger", "glyphicon-remove", "Register error", $("#login-modal"), false);
                } else {
                    msgChange($('#divRegisterMsg'), $('#iconRegister'), $('#txtRegisterMsg'), "alert-success", "glyphicon-ok", "Usuario registrado", $("#login-modal"), true);
                }
                break;
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
            $(this).text($msgText).fadeIn($msgAnimateTime);
        });
    }
    
    function msgChange($divTag, $iconTag, $textTag, $divClass, $iconClass, $msgText, $modal, close) {
        var $msgOld = "";
        msgFade($textTag, $msgText);
        
        $divTag.addClass("alert " + $divClass);
        
        $iconTag.addClass("glyphicon " + $iconClass);
        
        setTimeout(function() {
            $divTag.removeClass();
            $iconTag.removeClass();
            $textTag.text($msgOld);

            if (close) {
                $modal.modal('hide');
            }
  		}, $msgShowTime);
    }
});