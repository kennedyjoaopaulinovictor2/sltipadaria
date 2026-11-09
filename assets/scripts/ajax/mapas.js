function formatarValorMapa(valor) {
    return (parseFloat(valor) || 0).toLocaleString('pt-PT', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function tratarErroAcessoMapa(codigo) {
    if (codigo === -21) {
        window.location.assign(getUrl() + 'portal/login');
        return true;
    }
    if (codigo === -22) {
        alertSwal('Não tem permissão para gerir mapas.');
        return true;
    }
    return false;
}

function exportarTodosMapas() {
    var $abaActiva = $('.tab-content > .tab-pane.active');
    var seletorMes = $abaActiva.attr('id') === 'tabMapaSaidas' ? '#mesMapaSaida' :
            ($abaActiva.attr('id') === 'tabMapaFechoValor' ? '#mesFechoValor' : '#mesFechoMapa');
    var mes = $(seletorMes).val();
    if (!mes) {
        alertSwal('Selecione o mês dos mapas.');
        return;
    }
    window.location.assign(urlAjax('padaria/exportarTodosMapas') + '?mes=' + encodeURIComponent(mes));
}

function carregarMapaFecho() {
    ajaxApp('padaria/listarMapaFecho', {mes: $('#mesFechoMapa').val()}, function (r) {
        if (r.r !== 0) {
            if (!tratarErroAcessoMapa(r.r))
                alertSwal(r.r === 2 ? 'Selecione um mês válido, até o mês atual.' : 'Não foi possível carregar o mapa.');
            return;
        }

        $('#tituloMapaFecho').text('Mapa de Controlo de Produtos Restantes - ' + r.mes.split('-').reverse().join('/'));
        var $cabecalho = $('#tabelaMapaFecho thead').empty();
        var $linhaGrupos = $('<tr>');
        var $linhaCampos = $('<tr>');
        $('<th>', {rowspan: 2}).text('Data').appendTo($linhaGrupos);
        $.each(r.produtos, function (_, produto) {
            $('<th>', {colspan: 4, class: 'text-center'}).text(produto.nome).appendTo($linhaGrupos);
            $.each(['Restante', 'Qtd Produzida', 'Qtd Vendida', 'Total Vendido (AKZ)'], function (_, titulo) {
                $('<th>').text(titulo).appendTo($linhaCampos);
            });
        });
        $('<th>', {rowspan: 2}).text('Total Dia (AKZ)').appendTo($linhaGrupos);
        $('<th>', {rowspan: 2}).text('Funcionário').appendTo($linhaGrupos);
        $cabecalho.append($linhaGrupos, $linhaCampos);

        var $corpo = $('#tabelaMapaFecho tbody').empty();
        $.each(r.dias, function (data, dia) {
            var $linha = $('<tr>').attr('data-data', data);
            $('<td>').text(data.split('-').reverse().join('/')).appendTo($linha);
            $.each(r.produtos, function (_, produto) {
                var valor = dia.valores[produto.cod];
                if (dia.editavel) {
                    var $entrada = $('<input>', {
                        type: 'number',
                        min: 1,
                        max: 5500,
                        step: 1,
                        class: 'form-control input-sm text-right mapa-producao',
                        'data-cod-produto': produto.cod
                    }).val(valor ? valor.produzida : 0);
                    $('<td>').text(valor ? valor.restante : '-').appendTo($linha);
                    $('<td>').append($entrada).appendTo($linha);
                    $('<td>').text(valor ? valor.vendida : '-').appendTo($linha);
                    $('<td>').text(valor ? formatarValorMapa(valor.total) : '-').appendTo($linha);
                } else if (valor) {
                    $('<td>').text(valor.restante).appendTo($linha);
                    $('<td>').text(valor.produzida).appendTo($linha);
                    $('<td>').text(valor.vendida).appendTo($linha);
                    $('<td>').text(formatarValorMapa(valor.total)).appendTo($linha);
                } else {
                    $('<td>').text('-').appendTo($linha);
                    $('<td>').text('-').appendTo($linha);
                    $('<td>').text('-').appendTo($linha);
                    $('<td>').text('-').appendTo($linha);
                }
            });
            $('<td>').text(dia.total === null ? '' : formatarValorMapa(dia.total)).appendTo($linha);
            $('<td>').text(Object.keys(dia.funcionarios).join(', ')).appendTo($linha);
            $corpo.append($linha);
        });
    }, false, false);
}

function guardarProducaoMapa($entrada) {
    var $linha = $entrada.closest('tr');
    var quantidade = $entrada.val();
    if (!/^[1-9]\d{0,3}$/.test(quantidade) || parseInt(quantidade, 10) > 5500) {
        alertSwal('Introduza uma quantidade inteira entre 1 e 5500.');
        return;
    }

    $entrada.prop('disabled', true);
    ajaxApp('padaria/guardarProducaoMapa', {
        p: $entrada.attr('data-cod-produto'),
        d: $linha.attr('data-data'),
        q: quantidade
    }, function (r) {
        if (r.r === 0) {
            $('#msgMapaFecho').text('Quantidade produzida guardada.');
            carregarMapaFecho();
        } else {
            if (tratarErroAcessoMapa(r.r))
                return;
            $entrada.prop('disabled', false);
            alertSwal(r.r === 3 ? 'Verifique a data e a quantidade introduzidas.' : 'Não foi possível guardar a quantidade produzida.');
        }
    }, false, false);
}

function carregarMapaSaidas() {
    ajaxApp('padaria/listarMapaSaidas', {mes: $('#mesMapaSaida').val()}, function (r) {
        if (r.r !== 0) {
            if (!tratarErroAcessoMapa(r.r))
                alertSwal(r.r === 2 ? 'Selecione um mês válido, até o mês atual.' : 'Não foi possível carregar o mapa.');
            return;
        }

        $('#tituloMapaSaidas').text('Mapa de Controlo de Saída de Valores - ' + r.mes.split('-').reverse().join('/'));
        var maxSaidas = 0;
        $.each(r.dias, function (_, dia) {
            maxSaidas = Math.max(maxSaidas, dia.saidas.length);
        });

        var $cabecalho = $('#tabelaMapaSaidas thead').empty();
        var $linhaGrupos = $('<tr>');
        var $linhaCampos = $('<tr>');
        $('<th>', {rowspan: 2}).text('Data').appendTo($linhaGrupos);
        for (var indice = 1; indice <= maxSaidas; indice++) {
            $('<th>', {colspan: 2, class: 'text-center'}).text('Saída ' + indice).appendTo($linhaGrupos);
            $('<th>').text('Valor (AKZ)').appendTo($linhaCampos);
            $('<th>').text('Motivo').appendTo($linhaCampos);
        }
        $('<th>', {rowspan: 2}).text('Total Dia (AKZ)').appendTo($linhaGrupos);
        $cabecalho.append($linhaGrupos, $linhaCampos);

        var $corpo = $('#tabelaMapaSaidas tbody').empty();
        var totalMes = 0;
        $.each(r.dias, function (_, dia) {
            var $linha = $('<tr>').attr('data-data', dia.data);
            $('<td>').text(dia.dataTexto).appendTo($linha);
            $.each(dia.saidas, function (_, saida) {
                $('<td>').append($('<input>', {
                    type: 'text',
                    class: 'form-control input-sm mapa-saida-valor',
                    value: (parseFloat(saida.valor) || 0).toFixed(2).replace('.', ','),
                    'data-cod': saida.cod,
                    'aria-label': 'Valor da saída'
                })).appendTo($linha);
                $('<td>').append($('<input>', {
                    type: 'text',
                    maxlength: 200,
                    class: 'form-control input-sm mapa-saida-motivo',
                    value: saida.motivo,
                    'data-cod': saida.cod,
                    'aria-label': 'Motivo da saída'
                })).appendTo($linha);
            });
            for (var vazio = dia.saidas.length; vazio < maxSaidas; vazio++) {
                $('<td>').appendTo($linha);
                $('<td>').appendTo($linha);
            }
            $('<td>').text(formatarValorMapa(dia.total)).appendTo($linha);
            totalMes += dia.total;
            $corpo.append($linha);
        });
        $('#totalMapaSaidas').text(formatarValorMapa(totalMes));
    }, false, false);
}

function guardarSaidaMapa($entrada) {
    var $linha = $entrada.closest('tr');
    var cod = $entrada.attr('data-cod');
    var $valor = $linha.find('.mapa-saida-valor[data-cod="' + cod + '"]');
    var $motivo = $linha.find('.mapa-saida-motivo[data-cod="' + cod + '"]');
    $valor.prop('disabled', true);
    $motivo.prop('disabled', true);
    ajaxApp('padaria/guardarSaidaMapa', {
        cod: cod,
        m: $motivo.val(),
        v: $valor.val()
    }, function (r) {
        if (r.r === 0) {
            $('#msgMapaSaidas').text('Saída actualizada.');
            carregarMapaSaidas();
        } else {
            if (tratarErroAcessoMapa(r.r))
                return;
            $valor.prop('disabled', false);
            $motivo.prop('disabled', false);
            alertSwal(r.r === 3 ? 'Verifique o valor e o motivo introduzidos.' : 'Não foi possível actualizar a saída.');
        }
    }, false, false);
}

$(function () {
    carregarMapaFecho();
    $('#mesFechoMapa').change(carregarMapaFecho);
    $('#mesMapaSaida').change(carregarMapaSaidas);
    $('#tabMapaSaidasLink').click(carregarMapaSaidas);
    $('#btnExportarTodosMapas').click(exportarTodosMapas);
    $(document).on('keydown', '.mapa-producao', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            guardarProducaoMapa($(this));
        }
    });
    $(document).on('keydown', '.mapa-saida-valor, .mapa-saida-motivo', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            guardarSaidaMapa($(this));
        }
    });
});
