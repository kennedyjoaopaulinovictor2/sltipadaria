/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

/* GERIR PLANOS */
function buscarOptionsPlanosConfig($idSele, $codC, $tipo = 1) {
    document.getElementById($idSele).innerHTML = ($tipo == 2 ? '<option value="">Selecione</option>' : '');
    ajaxApp(
            'plano/buscarOptionsPlanos', "c=" + $codC,
            function (r) {
                if (r.r !== 0) {
                    alertSwal('Erro 00' + r.r + '! Planos Não Encontrados.');
                } else {
                    document.getElementById($idSele).innerHTML += r.select;
                }
            }, false, false);
}
$('#notaCur').change(function () {
    $('#codCur').focus();
});
function insertCurriculo() {
    ajaxApp(
            'plano/addCurriculo', $('#formCurriculo').serialize(),
            function (r) {
                $smsPlano = 'Plano de Estudo <code>' + $('#discCur option:selected').text() + '</code> - <code>' + $('#classeCur option:selected').text() + '</code> - <code>' + $('#notaCur option:selected').text() + '</code> C&oacute;digo <code>' + $('#codigoCur').val() + '</code>';
                if (r.r == 0) {
                    msgErroSpan('msgCurriculoForm', 'success', 'Sucesso', $smsPlano + ' Criado');
                    $('#discCur').val('');
                    $('#codCur').val('');
                } else if (r.r == -5) {
                    msgErroSpan('msgCurriculoForm', 'warning', 'Corre&ccedil;&atilde;o 005', 'J&aacute; existe a ' + $smsPlano);
                } else {
                    msgErroSpan('msgCurriculoForm', 'danger ', 'Erro 00' + r.r, $smsPlano + ' n&atilde;o Criado');
                }
            });
}
function btnUpdateCurriculo($cod, $nome) {
    ajaxApp('plano/buscaCurriculo', 'cod=' + $cod,
            function (r) {
                if ($.isNumeric(r)) {
                    msgErroSpan('msgCurriculoTabela', 'danger', 'Erro 00' + r, 'Plano de Estudo <code>' + $nome + '</code> n&atilde;o encontrado');
                } else {
                    $('#codCur').val($cod);
                    $('#planoCur').val(r.cod_plano);
                    $('#classeCur').val(r.cod_classe);
                    $('#notaCur').val(r.classificacao);
                    $('#discCur').val(r.cod_disciplina);
                    $('#codigoCur').val(r.codigo);
                    mostraDivForm('divCurriculoForm', 'msgCurriculoForm');
                    ocultaDivForm('divCurriculoTabela', 'msgCurriculoTabela');
                    $('html, body').animate({scrollTop: 0}, 'slow');
                }
            }
    );
}
function updateCurriculo() {
    ajaxApp('plano/editarCurriculo', $('#formCurriculo').serialize(),
            function (r) {
                $smsPlano = 'Plano de Estudo <code>' + $('#discCur option:selected').text() + '</code> - <code>' + $('#classeCur option:selected').text() + '</code> - <code>' + $('#notaCur option:selected').text() + '</code> C&oacute;digo <code>' + $('#codigoCur').val() + '</code>';
                if (r.r == 0) {
                    msgErroSpan('msgCurriculoForm', 'info', 'Sucesso', $smsPlano + ' Actualizado');
                    resetForm('formCurriculo', 'codCur');
                } else if (r.r == -5) {
                    msgErroSpan('msgCurriculoForm', 'warning', 'Corre&ccedil;&atilde;o 005', 'J&aacute; existe o ' + $smsPlano);
                } else {
                    msgErroSpan('msgCurriculoForm', 'danger ', 'Erro 00' + r.r, $smsPlano + ' n&atilde;o Actualizado');
                }
            });
}
$("#formCurriculo").validate({
    rules: {planoCur: {required: true}, classeCur: {required: true}, discCur: {required: true}, notaCur: {required: true}, codigoCur: {minlength: 1, maxlength: 3, digits: true}},
    submitHandler: function () {
        if ($('#codCur').val()) {
            updateCurriculo();
        } else {
            insertCurriculo();
        }
    }
});
function deleteCurriculo($cod, $tr, $nome) {
    swal({title: "Tens certeza que queres Eliminar a Linha do Plano \"" + $nome + "\"?", icon: "warning", allowOutsideClick: false, showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'SIM', cancelButtonText: 'NÃO'}).then((result) => {
        if (result.value) {
            ajaxApp('plano/apagarCurriculo', "cod=" + $cod,
                    function (r) {
                        if (r > 0) {
                            $('#tabelaCurriculo').dataTable().fnDeleteRow($tr);
                            msgErroSpan('msgCurriculoTabela', 'success', 'Sucesso', 'Plano <code>' + $nome + '</code> Eliminado');
                        } else {
                            msgErroSpan('msgCurriculoTabela', 'danger', 'Erro 00' + r, 'Plano <code>' + $nome + '</code> n&atilde;o Eliminado');
                        }
                    }
            );
        }
    });
}

$tabelaLimparListarCurricular = ['tabelaCurriculo', 'msgCurriculoTabela'];
function listarPlanoEstudo() {
    limparListar($tabelaLimparListarCurricular);
    ajaxApp('plano/listarCurricular', 'c=' + $("#classeLPE").val() + '&p=' + $("#planoLPE").val(),
            function (r) {
                if (r.r == 0) {
                    $tabela.fnAddData(r.dados);
                } else {
                    msgErroSpan('msgCurriculoTabela', 'danger ', 'ERRO 00' + r.r, 'N&atilde;o encontro nenhum registo');
                }
            }
    );
}
$('#tabPlanoEstudo').click(function () {
    buscarOptionsPlanosConfig('planoLPE', $('#classeLPE').val());
    limparListar($tabelaLimparListarCurricular);
});
$('#classeLPE').change(function () {
    buscarOptionsPlanosConfig('planoLPE', $('#classeLPE').val());
    limparListar($tabelaLimparListarCurricular);
});
$('#planoLPE').change(function () {
    limparListar($tabelaLimparListarCurricular);
});
$('#btnLPE').click(function () {
    listarPlanoEstudo();
});

if ($('#tabelaCurriculo').size() > 0 && $('#msgCurriculoTabela').size() > 0) {
    $(function () {
        buscarOptionsPlanosConfig('planoLPE', $('#classeLPE').val());
        limparListar($tabelaLimparListarCurricular);
    });
}