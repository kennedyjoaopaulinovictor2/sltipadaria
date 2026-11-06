/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */


//GERIR VENDA
var contarNE = 1;
function addUpdateVendaAjax($p, $c, $t) {
    ajaxApp(
            'padaria/addUpdateVenda',
            'u=' + $("#codUtilizador").val() + '&p=' + $p + '&t=' + $t + '&q=' + $("#" + ($t == '1' ? 'qv' : 'qa') + $c).val(),
            function (r) {
                if (r.r == 0) {
                    $("#qh" + $c).html(r.q + '<br><span class="label label-sm label-' + ($t == '1' ? 'success">Venda' : 'danger">Anulo') + ': ' + $("#" + ($t == '1' ? 'qv' : 'qa') + $c).val() + '</span>');
                    if ($t == '1')
                        $("#qu" + $c).html(($t == '1' ? '<span class="label label-sm label-danger">Última: ' + $("#qv" + $c).val() + '</span>' : ''));
                    $("#vh" + $c).text(r.v);
                    $("#qv" + $c).val('');
                    $("#qa" + $c).val('');
                } else {
                    alertSwal('ERRO 00' + r.r + '! Operação não registada.');
                }
            }
    );
}
function addUpdateVenda($p, $c, $t) {
    if (($("#qv" + $c).val() && $t == '1') || ($("#qa" + $c).val() && $t == '2')) {
        if ($t == '1') {
            addUpdateVendaAjax($p, $c, $t);
        } else {
            if (confirm("Tens certeza que anular \"" + $("#qa" + $c).val() + " " + $("#np" + $c).text() + "\" vendido(s)?")) {
                addUpdateVendaAjax($p, $c, $t);
            }
//            swal({title: "Tens certeza que anular \"" + $("#qa" + $c).val() + "\" \"" + $("#np" + $c).val() + "\" vendidos?", icon: "warning", allowOutsideClick: false, target: document.getElementById($modal), showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'SIM', cancelButtonText: 'NÃO'}).then((result) => {if(result.value){addUpdateVendaAjax($p, $c, $t);}});
        }
    }
}
function limparListarVenderProdutos() {
    limpaElementHtml('msgTabelaVP');
    //, "aoColumns": [{"sWidth": "5%"}, {"sWidth": "50%"}, {"sWidth": "20%"}, {"sWidth": "15%"}, {"sWidth": "10%"}]
    $tabela = $("#tabelaVender").dataTable({"bSort": false, "bFilter": false, "bInfo": false, "bPaginate": false, "bLengthChange": true, "iDisplayLength": 5, "bRetrieve": true, "oLanguage": {"sEmptyTable": "<center>Sem Produtos. Registados.</center>", "sInfo": "_END_ de _TOTAL_ Produtos", "sInfoEmpty": ""}});
    configDynamicTable();
    $tabela.fnClearTable();
}
function listarVenderProdutos() {
    limparListarVenderProdutos();
    ajaxApp(
            'padaria/listarVenderProdutos',
            'u=' + $("#codUtilizador").val(),
            function (r) {
                if (r.r == 0) {
                    $tabela.fnAddData(r.dados);
                } else {
                    msgErroSpan('msgTabelaVP', 'danger ', 'ERRO 00' + r.r, 'N&atilde;o encontro nenhum registo');
                }
            }
    );
}
if ($('#tabelaVender').size() > 0 && $('#msgTabelaVP').size() > 0 && $('#codUtilizador').size() > 0) {
    $(function () {
        listarVenderProdutos();
    });
}

//GERIR SAIDAS
var contarNLA = 1;
function btnAddSaidaValor($id) {
    $("#vsvs" + $id).text('');
    if (!$("#vsv" + $id).val()) {
        $("#vsvs" + $id).text('Digite o valor da saída');
        $("#vsv" + $id).focus();
    }
    $("#msvs" + $id).text('');
    $testNome = ($("#msv" + $id).val().length > 2 ? /^[A-Za-z0-9áàâãéèêëíïîìóòôõöúçñÁÀÂÃÉÈËÍÏÎÓÒÔÕÖÚÇÑ\-\s]+$/.test($("#msv" + $id).val()) : false);
    if ($("#msv" + $id).val().length < 3 || $testNome == false) {
        $("#msvs" + $id).text('Digite o Motivo da Saída');
        $("#msv" + $id).focus();
    }
    if (!$("#vsvs" + $id).text() && !$("#msvs" + $id).text()) {
        ajaxApp(
                'padaria/addSaidaValor',
                {
                    v: $("#vsv" + $id).val(),
                    m: $("#msv" + $id).val(),
                    t: $("#totalV").html(),
                    u: $("#codUtilizador").val()
                },
                function (r) {
                    if (r.r == 0) {
                        listarGerirSaida();
                    } else {
                        alertSwal('ERRO 00' + r.r + '! Operação não executada.');
                    }
                }
        );
    }
}
function apagarSaidaValor($id, $cod) {
    swal({title: "Tens certeza que queres Eliminar a Saida da linha \"" + $id + "\"?", icon: "warning", allowOutsideClick: false, showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'SIM', cancelButtonText: 'NÃO'}).then((result) => {
        if (result.value) {
            ajaxApp(
                    'padaria/apagarSaidaValor',
                    "cod=" + $cod + '&u=' + $("#codUtilizador").val(),
                    function (r) {
                        if (r.r == 0) {
                            alertSwal('Sucesso! Linha Eliminada');
                            listarGerirSaida();
                        } else {
                            alertSwal('Erro ' + r.r + '! Linha não Eliminada');
                        }
                    }
            );
        }
    });
}
function limparListarGerirSaida() {
    limpaElementHtml('msgTabelaSaida_totalS_totalV');
    $tabela = $("#tabelaSaida").dataTable({"bSort": false, "bFilter": false, "bInfo": false, "bPaginate": false, "bLengthChange": true, "iDisplayLength": 5, "bRetrieve": true, "oLanguage": {"sEmptyTable": "<center>Sem Registos.</center>", "sInfo": "_END_ de _TOTAL_ Registos", "sInfoEmpty": ""}});
    configDynamicTable();
    $tabela.fnClearTable();
}
function listarGerirSaida() {
    limparListarGerirSaida();
    ajaxApp(
            'padaria/listarRegistarSaida',
            'u=' + $("#codUtilizador").val(),
            function (r) {
                if (r.r == 0) {
                    $tabela.fnAddData(r.dados);
                    $('#totalS').html(r.dados.length);
                    $('#totalV').html(r.v);
                } else if (r.r == 4) {
                    $('#totalS').html('0');
                    $('#totalV').html('0,00');
                } else {
                    msgErroSpan('msgTabelaSaida', 'danger ', 'ERRO 00' + r.r, 'N&atilde;o encontro nenhum registo. (Selecione Para Adicionar Nova Saída de Valores)');
                }
            }
    );
}
$('#btnAddSaida').click(function () {
    var totalSaidas = $('#totalS').html() ? parseInt($('#totalS').html(), 10) : 0;
    ajaxApp('padaria/buscaAddRowNovoSaida', 'c=' + contarNLA + '&r=' + totalSaidas, function (r) {
        if (r.r == 0) {
            $('#tabelaSaida').dataTable().fnAddData(r.row);
            $('#vsv' + contarNLA).autoNumeric('init');
            $('#vsv' + contarNLA).focus();
            $('#totalS').html(totalSaidas + 1);
            contarNLA++;
            limpaElementHtml('msgTabelaSaida');
        } else {
            alertSwal('Erro ' + r.r + '! Não é possível criar nova saída');
        }
    }, false, false);
});
$('#tabSaida').click(function () {
    listarGerirSaida();
});

//GERIR FECHO
if ($("#dataFecho").size() > 0) {
    var date = new Date();
    var configDataFecho = {
        format: "yyyy-mm-dd",
        endDate: new Date(date.getFullYear(), date.getMonth(), date.getDate()),
        autoclose: true
    };
    if ($("#dataFecho").data('historico') != 1) {
        configDataFecho.startDate = new Date(date.getFullYear(), 0, 01);
    }
    $("#dataFecho").datepicker(configDataFecho);
}

function updateVendaFecho($p, $c) {
    if ($("#qp" + $c).val()) {
        ajaxApp(
                'padaria/addUpdateFecho',
                'u=' + $("#codUtilizador").val() + '&p=' + $p + '&d=' + $("#dataFecho").val() + '&q=' + $("#qp" + $c).val(),
                function (r) {
                    if (r.r == 0) {
                        $("#qr" + $c).text(r.q);
                    } else {
                        alertSwal('ERRO 00' + r.r + '! Operação não registada.');
                    }
                }
        );
    }
}
function limparListarGerirFecho() {
    limpaElementHtml('msgTabelaFecho_totalPP_totalPV_totalPR_totalVT');
    $tabela = $("#tabelaFecho").dataTable({"bSort": false, "bFilter": false, "bInfo": false, "bPaginate": false, "bLengthChange": true, "iDisplayLength": 5, "bRetrieve": true, "oLanguage": {"sEmptyTable": "<center>Sem Registos.</center>", "sInfo": "_END_ de _TOTAL_ Registos", "sInfoEmpty": ""}});
    configDynamicTable();
    $tabela.fnClearTable();
}
function listarGerirFecho() {
    limparListarGerirFecho();
    ajaxApp(
            'padaria/listarGerirFecho',
            'u=' + $("#codUtilizador").val() + '&d=' + $("#dataFecho").val(),
            function (r) {
                if (r.r == 0) {
                    $tabela.fnAddData(r.dados);
                    $('#totalPP').html(r.p);
                    $('#totalPV').html(r.v);
                    $('#totalPR').html(r.q);
                    $('#totalVT').html(r.t);
                } else {
                    msgErroSpan('msgTabelaFecho', 'danger ', 'ERRO 00' + r.r, 'N&atilde;o encontro nenhum registo.');
                }
            }
    );
}
$('#tabFecho').click(function () {
    listarGerirFecho();
});
$('#btnFecho').click(function () {
    listarGerirFecho();
});
$('#btnMapaFecho').click(function () {
    var mes = $('#mesFechoMapa').val();
    if (!mes) {
        alertSwal('Selecione o mês do mapa.');
        return;
    }
    window.location.assign(urlAjax('padaria/exportarMapaFecho') + '?mes=' + encodeURIComponent(mes));
});
$('#btnMapaSaida').click(function () {
    var mes = $('#mesMapaSaida').val();
    if (!mes) {
        alertSwal('Selecione o mês do mapa.');
        return;
    }
    window.location.assign(urlAjax('padaria/exportarMapaSaidasValores') + '?mes=' + encodeURIComponent(mes));
});

var camposFechoValor = ['moeda_10', 'moeda_20', 'moeda_50', 'moeda_100', 'moeda_200', 'dinheiro_200', 'dinheiro_500', 'dinheiro_1000', 'dinheiro_2000', 'dinheiro_5000', 'tpa', 'notas'];
function atualizarTotalFechoValor($linha) {
    var total = 0;
    var valoresMoedas = {
        moeda_10: 10,
        moeda_20: 20,
        moeda_50: 50,
        moeda_100: 100,
        moeda_200: 200,
        dinheiro_200: 200,
        dinheiro_500: 500,
        dinheiro_1000: 1000,
        dinheiro_2000: 2000,
        dinheiro_5000: 5000
    };

    $.each(valoresMoedas, function (campo, valorUnidade) {
        total += (parseInt($linha.find('[data-campo="' + campo + '"]').val(), 10) || 0) * valorUnidade;
    });
    total += parseFloat($linha.find('[data-campo="tpa"]').val()) || 0;
    total += parseFloat($linha.find('[data-campo="notas"]').val()) || 0;

    $linha.find('.total-fecho-valor').text(total.toLocaleString('pt-PT', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));
}

function listarGerirFechoValor() {
    var mes = $('#mesFechoValor').val();
    ajaxApp('padaria/listarGerirFechoValor', {mes: mes}, function (r) {
        if (r.r !== 0) {
            alertSwal(r.r === 2 ? 'Selecione um mês válido, até o mês atual.' : 'Sessão expirada. Entre novamente na aplicação.');
            return;
        }
        $('#tituloFechoValor').text('Fecho de Valores Padaria- ' + r.mes.split('-').reverse().join('/'));
        var $corpo = $('#corpoTabelaFechoValor').empty();
        $.each(r.dias, function (_, dia) {
            var $linha = $('<tr>').attr('data-data', dia.data);
            $('<td>').text(dia.dataTexto).appendTo($linha);
            $.each(camposFechoValor, function (_, campo) {
                var monetario = campo === 'tpa' || campo === 'notas';
                var $entrada = $('<input>', {
                    type: monetario ? 'number' : 'text',
                    min: '0',
                    step: monetario ? '0.01' : '1',
                    class: 'form-control input-sm text-right',
                    'data-campo': campo
                }).val(dia[campo]).prop('disabled', !dia.editavel).prop('readonly', dia[campo + '_guardado']);
                $entrada.attr('data-guardado', dia[campo + '_guardado'] ? '1' : '0');
                if (monetario) {
                    $entrada.attr('inputmode', 'decimal');
                } else {
                    $entrada.attr({'inputmode': 'numeric', 'maxlength': '9', 'data-ultimo-valor': dia[campo] || ''});
                }
                $('<td>').append($entrada).appendTo($linha);
            });
            $('<td>').addClass('total-fecho-valor text-right').appendTo($linha);
            atualizarTotalFechoValor($linha);
            // $('<td>').addClass('atendente-fecho-valor').text(dia.atendente).appendTo($linha);
            $corpo.append($linha);
        });
    }, false, false);
}
$('#tabFechoValor').click(function () {
    listarGerirFechoValor();
});
$('#mesFechoValor').change(function () {
    listarGerirFechoValor();
});
$('#btnExportarFechoValor').click(function () {
    var mes = $('#mesFechoValor').val();
    if (!mes) {
        alertSwal('Selecione o mês do fecho.');
        return;
    }
    window.location.assign(urlAjax('padaria/exportarFechoValor') + '?mes=' + encodeURIComponent(mes));
});
$(document).on('dblclick', '#corpoTabelaFechoValor input', function () {
    var $entrada = $(this);
    if (!$entrada.prop('disabled') && $entrada.prop('readonly') && $entrada.attr('data-guardado') === '1') {
        $entrada.prop('readonly', false).attr('data-editando', '1').trigger('focus');
    }
});
$(document).on('input', '#corpoTabelaFechoValor input', function () {
    var $entrada = $(this);
    if ($entrada.attr('data-campo') !== 'tpa' && $entrada.attr('data-campo') !== 'notas') {
        if (/^\d{0,9}$/.test($entrada.val()))
            $entrada.attr('data-ultimo-valor', $entrada.val());
        else
            $entrada.val($entrada.attr('data-ultimo-valor'));
    }
    atualizarTotalFechoValor($entrada.closest('tr'));
});
$(document).on('keydown', '#corpoTabelaFechoValor input', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        guardarFechoValorCelula($(this));
        return false;
    }
    var campo = $(this).attr('data-campo');
    var monetario = campo === 'tpa' || campo === 'notas';
    var teclasControlo = ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'];
    if (event.ctrlKey || event.metaKey || teclasControlo.indexOf(event.key) !== -1)
        return;
    if (!monetario && !/^\d$/.test(event.key))
        event.preventDefault();
});
function guardarFechoValorCelula($entrada) {
    var editar = $entrada.attr('data-editando') === '1';
    if ($entrada.prop('disabled') || ($entrada.prop('readonly') && !editar))
        return;
    var $linha = $entrada.closest('tr');
    var campo = $entrada.attr('data-campo');
    var valor = $entrada.val();
    var monetario = campo === 'tpa' || campo === 'notas';
    if (valor !== '' && !monetario && !/^\d{1,9}$/.test(valor)) {
        alertSwal('Introduza um número inteiro não negativo.');
        return;
    }
    if (monetario && !$entrada.get(0).checkValidity()) {
        alertSwal('Introduza um valor monetário válido.');
        return;
    }
    $entrada.prop('disabled', true);
    ajaxApp('padaria/guardarFechoValor', {d: $linha.attr('data-data'), campo: campo, valor: valor, editar: editar ? '1' : '0'}, function (r) {
        if (r.r === 0) {
            $linha.find('.atendente-fecho-valor').text(r.atendente);
            $entrada.val(valor === '' ? '0' : valor).attr('data-guardado', '1').removeAttr('data-editando').prop('disabled', false).prop('readonly', true);
        } else if (r.r === 4) {
            alertSwal('Esta célula já foi guardada.');
            listarGerirFechoValor();
        } else {
            $entrada.prop('disabled', false).prop('readonly', false);
            alertSwal(r.r === 3 ? 'Verifique os valores inseridos.' : 'Não foi possível guardar o fecho de valor.');
        }
    }, false, false);
}
