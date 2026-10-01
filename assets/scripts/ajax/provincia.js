/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

//PROVINCIA GERIR TELEFONES ESCOLAS
var contarNE = 1;
function btnAddUpdateEscola($id, $classes, $cod = '') {
    $("#neps" + $id).text('');
    $testNome = ($("#nep" + $id).val().length > 2 ? /^[A-Za-z0-9áàâãéèêëíïîìóòôõöúçñÁÀÂÃÉÈËÍÏÎÓÒÔÕÖÚÇÑ\-'º.\s]+$/.test($("#nep" + $id).val()) : false);
    if ($("#nep" + $id).val().length < 3 || $testNome == false) {
        $("#neps" + $id).text($testNome == false ? 'Só letras e nºs' : 'Nome Invalido');
        $("#nep" + $id).focus();
        return;
    } else {
        $yy = -2;
        $("#neps" + $id).text('');
        $.ajax({type: 'POST', url: urlAjax('escola/validaNomeEscola'), dataType: 'json', async: false, data: "n=" + $("#nep" + $id).val() + "&m=" + $("#muniLEGD").val() + '&c=' + $cod,
            success: function (k) {
                $yy = k.r;
            }});
        if ($yy != 0) {
            $("#neps" + $id).text((parseInt($yy) == 33 ? 'Só letras e nºs' : 'Escola já Existe'));
            $("#nep" + $id).focus();
            return;
        }
    }
    $url = '';
    $total = 0;
    $("#ceps" + $id).text('');
    for (var i = 0; i < parseInt($classes); i++) {
        if ($('#cep' + $id + i).is(':checked')) {
            $url += ('&c' + ($total) + '=' + $('#cep' + $id + i).val());
            $total++;
        }
    }
    if (!$url) {
        $("#ceps" + $id).text('Selecione a(s) Classe(s)');
        $("#cep" + $id).focus();
        return;
    }

    $("#feps" + $id).text('');
    if (!$("#fep" + $id).val() || $("#fep" + $id).val().length < 9 || /[0-9]{9}/.test($("#fep" + $id).val()) == false || $("#fep" + $id).val().substring(0, 1) != '9') {
        $("#feps" + $id).text('Telefone Invalido');
        $("#fep" + $id).focus();
    } else {
        $yy = -2;
        $("#feps" + $id).text('');
        $.ajax({type: 'POST', url: urlAjax('escola/validaTelefoneEscola'), dataType: 'json', async: false, data: "f=" + $("#fep" + $id).val() + '&c=' + $cod,
            success: function (k) {
                $yy = k.r;
            }});
        if ($yy != 0) {
            $("#feps" + $id).text('Telefone já Associado');
            return;
        }
    }
    if (!$("#neps" + $id).text() && !$("#ceps" + $id).text() && !$("#feps" + $id).text()) {
        ajaxApp(
                'escola/addUpdateEscola',
                'm=' + $("#muniLEGD").val() + '&e=' + $("#natuLEGD").val() + '&n=' + $("#nep" + $id).val() + '&t=' + $("#fep" + $id).val() + '&z=' + $total + $url + '&cod=' + $cod,
                function (r) {
                    if (r.r == 0) {
                        $("#nep" + $id).prop('disabled', true);
                        $("#fep" + $id).prop('disabled', true);
                        for (var i = 0; i < parseInt($classes); i++)
                            $('#cep' + $id + i).prop('disabled', true);
                        $("#btneps" + $id).text(($cod ? 'Actualizada' : 'Inserida'));
                        $("#btnep" + $id).remove();
                    } else {
                        alertSwal('ERRO 00' + r.r + '! Dados da Escola Escola "' + $("#nep" + $id).val() + '" não submetida.');
                    }
                }
        );
}
}
function limparListarEscolasGerirDados() {
    limpaElementHtml('msgTabelaLEGD_totalEGD_totalESFGD_totalECFGD');
    $tabela = $("#tabelaLEGD").dataTable({"bSort": false, "bFilter": false, "bInfo": true, "bPaginate": false, "bLengthChange": true, "iDisplayLength": 5, "aoColumns": [{"sWidth": "5%"}, {"sWidth": "50%"}, {"sWidth": "20%"}, {"sWidth": "15%"}, {"sWidth": "10%"}], "bRetrieve": true, "oLanguage": {"sEmptyTable": "<center>Sem Registos. Clique no Botão Listar Escolas.</center>", "sInfo": "_END_ de _TOTAL_ Escolas", "sInfoEmpty": ""}});
//    $tabela = $("#tabelaLEGD").dataTable({"bSort": false, "bInfo": false, "bPaginate": false, "bRetrieve": true});
    $('#selNEGD').prop('disabled', true);
    $('#divAddEscola').css('display', 'none');
    configDynamicTable();
    $tabela.fnClearTable();
}
function listarEscolasGerirDados() {
    limparListarEscolasGerirDados();
    ajaxApp(
            'escola/listarEscolasEditarGerirDados',
            'p=' + $("#provLEGD").val() + '&m=' + $("#muniLEGD").val() + '&n=' + $("#natuLEGD").val(),
            function (r) {
                if (r.r == 0) {
                    $tabela.fnAddData(r.dados);
                    $(".classdfone").inputmask({"mask": "999999999"});
                    $('#totalEGD').html(r.dados.length);
                    $('#totalESFGD').html(r.t[0]);
                    $('#totalECFGD').html(r.t[1]);
                } else {
                    msgErroSpan('msgTabelaLEGD', 'danger ', 'ERRO 00' + r.r, 'N&atilde;o encontro nenhum registo. (Selecione Para Adicionar Nova Escola)');
                }
            }
    );
    $('#divAddEscola').css('display', 'block');
    $('#selNEGD').prop('disabled', false);
}
$('.classLEGD').change(function () {
    limparListarEscolasGerirDados();
});
$('#btnLEGD').click(function () {
    listarEscolasGerirDados();
});
$('#selNEGD').change(function () {
    if ($('#selNEGD').val() == '1') {
        $t = ($('#totalEGD').html() ? parseInt($('#totalEGD').html()) : 0);
        ajaxApp('escola/buscaAddRowNovaEscola', "c=" + contarNE + '&r=' + $t, function (r) {
            if (r.r == 0) {
                $('#tabelaLEGD').dataTable().fnAddData(r.row);
                $(".classdfone").inputmask({"mask": "999999999"});
                $('#nep0' + (parseInt(contarNE))).focus();
                $('#totalEGD').html($t + 1);
                $('#totalESFGD').html(parseInt($('#totalESFGD').html() ? $('#totalESFGD').html() : 0) + 1);
                contarNE++;
            } else {
                alertSwal('Erro ' + r.r + '! Não é Possível criar novas Escolas');
            }
        }, false, false);

    } else if ($('#selNEGD').val() == '2' || $('#selNEGD').val() == '3') {
        ajaxApp('escola/preparaGerarPdf', "m=" + $('#muniLEGD').val() + "&n=" + $('#natuLEGD').val(), function (r) {
            if (r.r == 0) {
                carregarPdf(($('#selNEGD').val() == '2' ? ('lista_escolas/' + r.p + '/' + r.m + '/' + r.e) : ('lista_estatistica_escolas/' + r.p + '/' + r.t)));
            } else {
                alertSwal('Erro ' + r.r + '! Não é Possível Gerar Pdf');
            }
        }, false, false);
    }
    $('#selNEGD').val('');

});
if ($('#tabelaLEGD').size() > 0 && $('#msgTabelaLEGD').size() > 0) {
    $(function () {
        listarEscolasGerirDados();
    });
}