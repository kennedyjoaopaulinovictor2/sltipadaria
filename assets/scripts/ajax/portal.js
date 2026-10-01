/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */
function buscarOptionsMunicipioComuna($busca, $tipo = 1) {
    $('#' + ($tipo == 1 ? 'munCEP' : 'comCEP')).prop('disabled', true);
    ajaxApp(
            'portal/buscarOptionsMunicipioComuna', "p=" + $busca + 't=' + $tipo,
            function (r) {
                if (r.r !== 0) {
                    alertSwal('Erro 00' + r.r + '! Institutos da Provincia não Encontrados', 'modalOfertaFormativa');
                } else {
                    document.getElementById('instConCurIns').innerHTML = ('<option value="">Seleccione</option>' + r.selectI);
                    document.getElementById('instConCurIns').disabled = false;
//                    document.getElementById('btnCurIns').disabled = false;
//                    $('#instConCurIns').select2("val", r.codI);
                }
            }, false, false);
}
function limparTabelaOfertasFormativas() {
    $tabela = $("#tabelaCursosITP").dataTable({"bSort": false, "bFilter": false, "bInfo": true, "bPaginate": true, "bLengthChange": false, "iDisplayLength": 5, "bRetrieve": true, "oLanguage": {"sEmptyTable": "<center>Sem registros. Seleccione a Província e o Instituto.</center>", "sInfo": "_END_ de _TOTAL_ Cursos", "sInfoEmpty": ""}});
    configDynamicTable();
    $tabela.fnClearTable();
}
function consultarOfertasFormativas() {
    limparTabelaOfertasFormativas();
    if ($('#instConCurIns').val()) {
        $.ajax({
            type: 'POST',
            url: urlAjax('candidatura/consultarCursosPortal'),
            dataType: 'json',
            data: 'i=' + $('#instConCurIns').val(),
            beforeSend: function () {
                $('#carrOFporAjx').html('<img src="' + getUrl() + 'assets/img/loader.gif" style="height: 20px; width: 20px;" />');
            },
            complete: function () {
                $('#carrOFporAjx').html('');
            },
            success: function (r) {
                if (r.r !== 0) {
                    alertSwal('Erro 00' + r.r + '! Nenhum Registo Encontrado', 'modalOfertaFormativa');
                } else {
                    $tabela.fnAddData(r.dados);
                }
            }
        });
    }
}
$('#modalOfertaFormativa').on('shown.bs.modal', function () {
    limparTabelaOfertasFormativas();
    document.getElementById('instConCurIns').disabled = true;
//    document.getElementById('btnCurIns').disabled = true;
    $('#provConCurIns').val('');
    $('#instConCurIns').val('');
});
$('provCEP').change(function () {
    buscarOptionsInstitutosProvincia();
    limparTabelaOfertasFormativas();
});
$('#instConCurIns').change(function () {
    limparTabelaOfertasFormativas();
    if ($('#instConCurIns').val())
        consultarOfertasFormativas();
});
//$('#btnCurIns').click(function () {
//    consultarOfertasFormativas();
//});
