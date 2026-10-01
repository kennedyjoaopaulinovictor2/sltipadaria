/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



//EFETUAR LOGIN
var count = 0;
var Login = function () {
    return {
        init: function () {
            $s = getUrl() + "assets/img/bg_ny.jpg";
            $f = '.jpg';
            $.backstretch([$s], {fade: 1000, duration: 8000});
            $("#username").focus();
        }
    };
}();
function efetuarLogin() {
    $c = 0;
    ajaxApp('utilizador/efetuarLogin', $('#formLogin').serialize(),
            function (r) {
                if (r.r == 0) {
                    $c = 1;
                    window.location.assign(r.local);
                } else {
                    efeitoFade('erroLogin1');
                    ocultaDiv('erroLogin');
                    ocultaDiv('erroLogin2');
                }
            }
    );
    count++;
    if (count == 10) {
        efeitoFade('erroLogin');
        ocultaDiv('erroLogin1');
        ocultaDiv('erroLogin2');
        $('#erroLogin').css('display', 'block');
        $('#erroLogin1').css('display', 'none');
        $('#erroLogin2').css('display', 'none');
        $('#divLoginContro').css('display', 'none');
    }
}
$("#formLogin").validate({
    rules: {username: {required: true}, password: {required: true}},
    messages: {username: {required: "Campo Obrigat&oacute;rio"}, password: {required: "Campo Obrigat&oacute;rio"}},
    submitHandler: function () {
        efetuarLogin();
    }
});

function precisaAjuda($idSpan) {
    msgErroSpan($idSpan, 'info', '', 'Fale com Suporte Técnica');
}