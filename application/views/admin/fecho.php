<?= elementsHidden(array('codUtilizador_' . codUtilizador())) ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption"><i class="icon-list"></i> Fechos Di&aacute;rios de Produtos</div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <?=
                    '<div class="row"><div class="col-md-2">' . elementoForm('Data do Fecho', 'dataFecho', '<input value="' . date('Y-m-d') . '" class="form-control" $idname type="text" data-historico="1" readonly />', '', '', '', '1') . '</div>' .
                    '<div class="col-md-2">' . elementoForm('Clique', 'btnFecho', '<button type="button" $idname class="btn btn-primary" style="display:block">Listar</button>', '', '', '', '1') . '</div></div>' .
                    '<div style="margin:10px 0">Qtd Produzida: <code id="totalPP">0</code>; Qtd Vendida: <code id="totalPV">0</code>; Qtd Restante: <code id="totalPR">0</code>; Valor Total: <code id="totalVT">0</code></div>'
                    ?>
                    <span id="msgTabelaFecho" data-contexto="admin"></span>
                    <?= criaTabela('tabelaFecho', array(array('', '#'), array('', 'Produto'), array('', 'Pre&ccedil;o'), array('', 'Qtd Produzida'), array('', 'Qtd Vendida'), array('', 'Qtd Restante'), array('', 'Valor Total'))); ?>
                </div>
            </div>
        </div>
    </div>
</div>
