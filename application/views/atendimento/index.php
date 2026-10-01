<?= headerInicio(array('AREA DE ATENDIMENTO'), menu(), 2) ?>
<div class="modal fade" id="modalVenda" style="margin-top:5%" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-full has-error">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title text-center"><strong>Registar Venda de Produtos <?= date('d/m/Y') ?></strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <span id="msgTabelaVP"></span>
                                <?=
                                elementsHidden(array("codUtiVender_". codUtilizador())) .
                                criaTabela('tabelaVender', array(array('', '#'), array('', 'Produto'), array('', 'Preço'), array('', 'Qtd Vendidas'), array('', 'Valor Hoje'), array('', 'Qtd Vender'), array('', 'Qtd Anular')), '', '', '', '');
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--<div class="modal-footer"><button type="button" class="btn default" data-dismiss="modal">Fechar</button></div>-->
        </div>
    </div>
</div>
<div class="modal fade" id="modalSaida" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog  modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title text-center"><strong>Registar Saída de Valores <?= date('d/m/Y') ?></strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <?= criaTabela('tabelaSaidas', array(array('', '#'), array('', 'Motivo'), array('', 'Valor'), array('', 'Operacao')), '', '', '', ''); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn default" data-dismiss="modal">Fechar</button></div>
        </div>
    </div>
</div>
