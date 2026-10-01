/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* MODAL GERIR */
/* global LOCAL, siglaApp, tokenApp, $autoCompleteCandidato */

//if (window.location.href.indexOf('login') == -1)
//    $.timeoutDialog({timeout: getTimeOut(), countdown: 60, logout_redirect_url: (getUrl() + 'portal/login'), restart_on_yes: false});

if (window.location.href.indexOf('#') !== -1 && window.location.href.indexOf('login') == -1)
    window.location.href = window.location.href.split('#')[0];

function retornaAutoCompleteAjax($function, $parametros) {
    $autoComplete = new Array();
    $.ajax({
        type: 'POST',
        url: urlAjax('modal/' + $function),
        dataType: 'json',
        async: false,
        data: $parametros,
        success: function (r) {
            $autoComplete = r.dados;
        }
    });
    return $autoComplete;
}

function autoCompleteAjax($idBusca, $autoComplete) {
    $('#' + $idBusca).typeahead({items: 3, accent: false, hint: false, highlight: true, minLength: 1, source: $autoComplete});
    $('#' + $idBusca).focus();
}

function limparCamposModal($b, $t, $m) {
    $("#" + $b).val("");
    removeErro($t, $m);
}

$('#modalCarregarPdf').on('hide.bs.modal', function () {
    document.getElementById('iframePdf').src = "";
    $('#modalCarregarAjax').modal('hide');
});

function tiraMeteSpanBtnModal($idBtn, $idSpan, $acao) {
    $('#' + $idSpan).html(($acao == 1 ? '' : '<img src="' + getUrl() + 'assets/img/loader.gif" style="height: 20px; width: 20px;" />'));
    $('#' + $idBtn).css('display', ($acao == 1 ? 'inline' : 'none'));
}
