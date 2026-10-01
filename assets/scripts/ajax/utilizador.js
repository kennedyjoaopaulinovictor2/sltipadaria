/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//GERIR PERFIL
$("#formUserPerfirEditarSenha").validate({
    rules: {passwordA: {required: true}, passwordN: {required: true, minlength: 8}, passwordC: {required: true, minlength: 8, equalTo: "#passwordN"}},
    messages: {
        passwordA: {required: "Digite a Actual Palavra-Passe"},
        passwordN: {required: "Digite a Nova Palavra-Passe", minlength: "deve ter no minino 8 Caracteres"},
        passwordC: {required: "Repetir a Nova Palavra-Passe", minlength: "deve ter no minino 8 Caracteres", equalTo: "Deve ser igual ao Campo 'Nova Palavra-Passe'"}
    }, submitHandler: function () {
        $('#msgSenhaForm').html('');
        ajaxApp(
                'utilizador/editarSenha',
                $('#formUserPerfirEditarSenha').serialize(),
                function (k) {
                    if (k.r === 0) {
                        msgErroSpan('msgSenhaForm', 'success', 'Sucesso', 'Palavra-Passe Actualizada. Início para voltar');
                        resetForm('formUserPerfirEditarSenha', '', '');
                    } else if (k.r === 5) {
                        msgErroSpan('msgSenhaForm', 'warning', 'Correc&ccedil;&atilde;o', 'Nova Palavra-passe deve ser iguaal a Repetir Palavra-passe');
                    } else if (k.r === 7) {
                        msgErroSpan('msgSenhaForm', 'warning', 'Correc&ccedil;&atilde;o', 'Palavra-passe Actual Incorrecta');
                    } else if (k.r === 8) {
                        msgErroSpan('msgSenhaForm', 'warning', 'Correc&ccedil;&atilde;o', ' Nova Palavra-passe deve ser diferente da antiga');
                    } else {
                        msgErroSpan('msgSenhaForm', 'danger', 'Erro 00' + k.r, ' Inesperado, Palavra-Passe n&atilde;o Actualizada');
                    }
                }
        );
    }
});

$tabelaLimparListarUtilizadores = ['tabelaUtilizador', 'msgUtilizadorTabela'];
if ($('#telefone').size() > 0) {
    $(function () {
        $("#telefone").inputmask({"mask": "999999999"});
    });
}
$("#modulo").change(function () {
    $('#divProv').css('display', ($('#modulo').val() == 5 || $('#modulo').val() == 6 ? 'block' : 'none'));
    $('#provUti').prop('disabled', ($('#modulo').val() == 5 || $('#modulo').val() == 6 ? false : true));
    $('#provUti').val('');
    $('#divMuni').css('display', ($('#modulo').val() == 6 ? 'block' : 'none'));
    $('#muniUti').prop('disabled', ($('#modulo').val() == 6 ? false : true));
    $('#muniUti').val('');
//    $('#username').val($("#modulo").val() ? ($('#telefone').val() + '.' + $('#modulo option:selected').text().substring(0, 5)) : '');
});
$("#provUti").change(function () {
    if ($("#provUti").val() && !$('#muniUti').is(':disabled')) {
        document.getElementById('muniUti').innerHTML = '<option value="">Selecione</option>';
        ajaxApp(
                'utilizador/buscaOptionMunicipios', "p=" + $("#provUti").val(),
                function (r) {
                    if (r.r !== 0) {
                        alertSwal('Erro 00' + r.r + '! Munic&iacute;pios Não Encontrados.');
                    } else {
                        document.getElementById('muniUti').innerHTML += r.select;
                    }
                }, false, false);
    }

});
function validaTelefone($val) {
    $yy = -2;
    $.ajax({type: 'POST', url: urlAjax('utilizador/validaTelefone'), dataType: 'json', async: false, data: "dado=" + $val + '&cod=' + (($('#pessoa').size() > 0 && $('#pessoa').val()) ? $('#pessoa').val() : ''), success: function (k) {
            $yy = k;
        }});
    return $yy;
}
function buscaPessoa() {
    $("#nome,#telefone,#username").val('');
    if ($("#pessoa").val()) {
        ajaxApp(
                'utilizador/buscarPessoa',
                "cod=" + $("#pessoa").val(),
                function (k) {
                    if ($.isNumeric(k)) {
                        msgErroSpan('msgUtilizadorForm', 'danger', 'Erro 00' + k, 'Inesperado, Pessoa <code>' + $('#pessoa option:selected').text() + '</code>  n&atilde;o Encontrada');
                    } else {
                        $("#nome").val(k.nome);
                        $("#telefone").val(k.telefone);
                        $("#email").val(k.email);
                        $('#modulo').focus();
                    }
                }
        );
    }
}
function ocultaMostraDivsUtizador() {
    $t = ($('#divUtilizadorForm').css('display') == 'none' ? 1 : 2);
    resetForm('formUtilizador', 'codUtilizador', 'trUtilizador');
    ocultaDivForm(($t == 1 ? 'divUtilizadorTable' : 'divUtilizadorForm'), ($t == 1 ? 'msgUtilizadorTabela' : 'msgUtilizadorForm'));
    mostraDivForm(($t == 1 ? 'divUtilizadorForm' : 'divUtilizadorTable'), ($t == 1 ? 'msgUtilizadorForm' : 'msgUtilizadorTabela'));
    $("#pessoa").val('');
    $("#pessoa").prop('disabled', false);
    $("#smsUser").prop('disabled', false);
    limparListar($tabelaLimparListarUtilizadores);
}
function smsErroInsertUpdateUtilizador(r) {
    if (r == -5) {
        msgErroSpan('msgUtilizadorForm', 'warning', 'Corre&ccedil;&atilde;o 005', 'J&aacute; existe um Utilizador com o username <code>' + $('#username').val() + '</code>');
        $("#username").focus();
    } else if (r == -6) {
        msgErroSpan('msgUtilizadorForm', 'warning', 'Corre&ccedil;&atilde;o 007', 'J&aacute; existe um Pessoa com N&ordm; de Telefone  <code>' + $('#telefone').val() + '</code>');
        $("#telefone").focus();
    } else if (r == -7) {
        msgErroSpan('msgUtilizadorForm', 'warning', 'Corre&ccedil;&atilde;o 008', ' J&aacute; existe um Pessoa com E-mail  <code>' + $('#email').val() + '</code>');
        $("#email").focus();
    } else if (r == -8) {
        msgErroSpan('msgUtilizadorForm', 'warning', 'Corre&ccedil;&atilde;o 009', ' J&aacute; existe um Utilizador ligado ao &Aacute;rea de Trabalho <code>' + $('#modulo option:selected').text() + '</code> para a Pessoa  <code>' + $('#pessoa option:selected').text() + '</code>');
        $("#pessoa").focus();
    } else {
        msgErroSpan('msgUtilizadorForm', 'danger ', 'Erro 00' + r, $msg + ' n&atilde;o Inserido');
    }
}
function insertUtilizador() {
    $msg = 'Utilizador de <code>' + $('#nome').val() + '</code> da &aacute;rea <code>' + $('#modulo option:selected').text() + '</code>';
    ajaxApp('utilizador/addUtilizador', $('#formUtilizador').serialize(),
            function (r) {
                if (r.r == 0) {
                    $nome = ($('#nome').val() + ' da &aacute;rea ' + $('#modulo option:selected').text() + '; Username: ' + r.u);
                    if (r.p)
                        $('#pessoa').append('<option value="' + r.p + '">' + $('#nome').val() + '</option>');
                    $("#pessoa").val('');
                    $('#nome').val('');
                    $('#telefone').val('');
                    $('#email').val('');
                    $('#username').val('');
                    //                    resetForm('formUtilizador', 'codUtilizador', 'trUtilizador');
                    msgErroSpan('msgUtilizadorForm', 'success', 'Sucesso', $msg + ' Criado');
//                    $('#modulo').val('').trigger('change');
                } else {
                    smsErroInsertUpdateUtilizador(r.r, $msg);
                }
            }
    );
}
function btnUpdateUtilizador($cod, $nome) {
    ajaxApp(
            'utilizador/buscarUtilizador', "cod=" + $cod,
            function (r) {
                if ($.isNumeric(r)) {
                    alertSwal('Erro 00' + r.r + '! Utilizador "' + $nome + '" n&atilde;o encontrado');
                } else {
                    $('#codUtilizador').val($cod);
                    $("#codPEdit").val(r.codPessoa);
                    $("#pessoa").val(r.codPessoa);
                    $("#pessoa").prop('disabled', true);
                    $('#nome').val(r.nome);
                    $('#telefone').val(r.telefone);
                    $('#email').val(r.email);
                    $('#smsUser').val('1');
                    $("#smsUser").prop('disabled', true);
                    $('#modulo').val(r.cod_modulo).trigger('change');
                    if (r.cod_provincia)
                        $("#provUti").val(r.cod_provincia);
                    if (r.cod_municipio)
                        $("#muniUti").val(r.cod_municipio);
                    $('#tipoUser').val(r.tipo);
                    $('#username').val(r.username);
//                    $('#username').prop('disabled', true);
                    ocultaDivForm('divUtilizadorTable', 'msgUtilizadorTabela');
                    mostraDivForm('divUtilizadorForm', 'msgUtilizadorForm');
                }
            }
    );
}
function updateUtilizador() {
    $msg = 'Utilizador de <code>' + $('#nome').val() + '</code> da &aacute;rea <code>' + $('#modulo option:selected').text() + '</code>';
    if ($('#nome').val() != '') {
        ajaxApp('utilizador/editarUtilizador', $('#formUtilizador').serialize(),
                function (r) {
                    if (r.r == 0) {
                        $nome = ($('#nome').val() + ' da &aacute;rea ' + $('#modulo option:selected').text());
                        resetForm('formUtilizador', 'codUtilizador', 'trUtilizador');
                        msgErroSpan('msgUtilizadorForm', 'info', 'Sucesso', $msg + ' Atualizado');
                        $("#pessoa").val('');
                        $("#pessoa").prop('disabled', false);
                        $("#smsUser").prop('disabled', false);
                        $('#modulo').val('');
                        $('#modulo').trigger('change');
                    } else {
                        smsErroInsertUpdateUtilizador(r.r, $msg);
                    }
                }
        );
    } else {
        alertSwal('Selecione uma Pessoa');
    }
}
$("#formUtilizador").validate({
    rules: {
        nome: {required: true, minlength: 5, regExNome: true},
        telefone: {required: true, digits: true, minlength: 9, inica92: true, validaTelefone: true},
        email: "email",
        modulo: "required",
        username: {minlength: 8},
        tipoUser: "required",
        smsUser: "required",
        provUti: "required",
        muniUti: "required"
    }, submitHandler: function () {
        if ($('#codUtilizador').val()) {
            updateUtilizador();
        } else {
            insertUtilizador();
        }
    }
});
function deleteUtilizador($cod, $tr, $nome) {
    swal({title: "Tens certeza que queres Eliminar o Utilizador \"" + $nome + "\"?", icon: "warning", allowOutsideClick: false, showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'SIM', cancelButtonText: 'NÃO'}).then((result) => {
        if (result.value) {
            ajaxApp(
                    'utilizador/apagarUtilizador',
                    "cod=" + $cod,
                    function (r) {
                        if (r > 0) {
                            $('#tabelaUtilizador').dataTable().fnDeleteRow($tr);
                            msgErroSpan('msgUtilizadorTabela', 'success', 'Sucesso', 'Utilizador <code>' + $nome + '</code> Eliminado');
                        } else {
                            msgErroSpan('msgUtilizadorTabela', 'danger', 'Erro 00' + r, 'Utilizador <code>' + $nome + '</code> n&atilde;o Eliminado');
                        }
                    }
            );
        }
    });
}
function updateEstadoUtilizador($cod, $id, $etado, $nome) {
    swal({title: "Você realmente deseja alterar o Estado do Utilizador \"" + $nome + "\"?", icon: "warning", allowOutsideClick: false, showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'SIM', cancelButtonText: 'NÃO'}).then((result) => {
        if (result.value) {
            ajaxApp(
                    'utilizador/editarEstadoUtilizador',
                    "cod=" + $cod + "&estado=" + $etado,
                    function (r) {
                        if (r > 0) {
                            $('#' + $id).html($etado === '1' ? '<span class="label label-danger">Inactivo</span>' : '<span class="label label-success">Activo</span>');
                            if ($etado != '1')
                                $('#senUti' + $id.match(/\d/g)).remove();
                        } else {
                            msgErroSpan('msgUtilizadorTabela', 'warning', 'Falha 00' + r, 'Estado do Utilizador <code>' + $nome + '</code> n&atilde;o Alterdado');
                        }
                    }
            );
        }
    });
}
function reporSenhaUtilizador($c, $cod) {
    if ($('#su' + $c).val()) {
        $s = $('#su' + $c).val();
        swal({title: "Realmente deseja efectuar a operação \"" + $('#su' + $c + ' option:selected').text() + "\" no Utilizador da linha \"" + $c + "\"?", icon: "warning", allowOutsideClick: false, showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'SIM', cancelButtonText: 'NÃO'}).then((result) => {
            if (result.value) {
                ajaxApp(
                        'utilizador/reporSenhaUtilizador',
                        "cod=" + $cod + '&sen=' + $s,
                        function (r) {
                            if (r == 0) {
                                alertSwal('Sucesso! Operação Executada');
                                $('#su' + $c).prop('disabled', true);
                            } else {
                                $('#su' + $c).val('');
                                alertSwal('Erro ' + r + '! Operação não executada');
                            }
                        }
                );
            }
        });
    }

}

$tabelaLimparListarUtilizadores = ['tabelaUtilizador', 'msgUtilizadorTabela'];
function listarUtilizadores() {
    limparListar($tabelaLimparListarUtilizadores);
    ajaxApp(
            'utilizador/listarUtilizador', "m=" + $('#moduloLU').val() + '&e=' + $('#estLU').val(),
            function (r) {
                if (r.r == "0") {
                    $tabela.fnAddData(r.dados);
                } else {
                    msgErroSpan('msgUtilizadorTabela', 'danger', 'Erro 00' + r.r, 'Nenhum Utilizador encontrado');
                }
            }, true, false);
}
$('.classLU').change(function () {
    limparListar($tabelaLimparListarUtilizadores);
});
$('#btnLU').click(function () {
    listarUtilizadores();
});
if ($('#tabelaUtilizador').size() > 0 && $('#msgUtilizadorTabela').size() > 0) {
    $(function () {
        limparListar($tabelaLimparListarUtilizadores);
    });
}

//PERFIL ESCOLA
function validaAtualSenha($val) {
    $yy = -2;
    $.ajax({type: 'POST', url: urlAjax('utilizador/validaAtualSenha'), dataType: 'json', async: false, data: "dado=" + $val, success: function (k) {
            $yy = k;
            if (k == -21)
                window.location.reload();
        }});
    return $yy;
}
$("#formPerilEscola").validate({
    rules: {
        nome: {required: true, minlength: 5, regExNome: true},
        telefone: {required: true, digits: true, minlength: 9, inica92: true, validaTelefone: true},
        cargo: {required: true},
        passwordA: {required: true, minlength: 8, validaAtualSenha: true},
        passwordN: {required: true, minlength: 8},
        passwordC: {required: true, minlength: 8, equalTo: "#passwordN"}
    },
    messages: {
        nome: {required: "Campo Obrigat&oacute;rio", minlength: "M&iacute;nimo 5 caracteres", regExNome: "Nome Invalido"},
        telefone: {required: "Campo Obrigat&oacute;rio", digits: "Telefone Invalido", minlength: "M&iacute;nimo 9 digitos", inica92: "Telefone Invalido", validaTelefone: "J&aacute; Existe na BD"},
        cargo: {required: "Campo Obrigat&oacute;rio"},
//        passwordA: {required: "Campo Obrigat&oacute;rio", minlength: "minino 8 Caracteres", validaAtualSenha: "Palavra-Passe Inv&aacute;lida"},
//        passwordN: {required: "Campo Obrigat&oacute;rio", minlength: "minino 8 Caracteres"},
//        passwordC: {required: "RCampo Obrigat&oacute;rio", minlength: "minino 8 Caracteres", equalTo: "Deve ser igual a 'Nova Palavra-Passe'"}
    }, submitHandler: function () {
        swal({title: "Este registo s&oacute; &eacute; feito uma &uacute;nica vez, e ser&aacute; finalizada a esta sess&atilde;o. Tem certeza que os campos est&atilde;o corretamente preenchidos?", icon: "warning", allowOutsideClick: false, showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'SIM', cancelButtonText: 'NÃO'}).then((result) => {
            if (result.value) {
                ajaxApp(
                        'utilizador/editarPerfilEscola',
                        $('#formPerilEscola').serialize(),
                        function (k) {
                            if (k.r === 0) {
                                window.location.assign(getUrl() + 'portal/login');
                            } else if (k.r == 5) {
                                alertSwal('Correc&ccedil;&atilde;o! Nova Palavra-passe deve ser igual a Repetir Palavra-passe');
                            } else {
                                alertSwal('Erro 00' + k.r + '! Inesperado, Dados do Perfil n&atilde;o Actualizados');
                            }
                        }
                );
            }
        });
    }
});
if ($("#formPerilEscola").size() > 0) {
    $(function () {
        $('#nome').focus();
    });
}