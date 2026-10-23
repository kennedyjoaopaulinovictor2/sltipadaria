<?= elementsHidden(array("codUtilizador_" . codUtilizador())) ?>
<div class="row">
    <div class="col-md-12">
        <div class="tabbable tabbable-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab0" data-toggle="tab">Registar Venda</a></li>
                <li><a href="#tab1" id="tabSaida" data-toggle="tab">Sa&iacute;da de Valor</a></li>
                <li><a href="#tab2" id="tabFecho" data-toggle="tab">Gerir Fecho padaria</a></li>
                <li><a href="#tab3" id="tabFechoValor" data-toggle="tab">Gerir Fecho Valor padaria</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab0">                  
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="modal-title text-center"><strong>Registar Venda de Produtos <?= date('d/m/Y') ?></strong></h4>
                            <span id="msgTabelaVP"></span>
                            <?=
                            criaTabela('tabelaVender', array(array('', '#'), array('', 'Produto'), array('', 'Preço'), array('', 'Qtd Vendidas'), array('', 'Valor Hoje'), array('', 'Qtd Vender'), array('', 'Qtd Anular')), '', '', '', '');
                            ?>                       
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab1">
    <div class="row">
        <div class="col-md-12">
            <h4 class="modal-title text-center">
                <strong>Registar Saídas de Valor <?= date('d/m/Y') ?></strong>
            </h4>
            <span id="msgTabelaSaida"></span>
            
            <?=
            '<div style="margin-bottom: 10px; text-align: left">
                Total: <code id="totalS">0</code>, Valor: <code id="totalV">0</code>
            </div>' .
            '<div class="row" style="margin-bottom: 15px"><div class="col-md-3">' . elementoForm('Mês do Mapa', 'mesMapaSaida', '<input value="' . date('Y-m') . '" class="form-control" $idname type="month" />', '', '', '', '1') . '</div>' .
            '<div class="col-md-3"><label>&nbsp;</label><button type="button" class="btn btn-success btn-block" id="btnMapaSaida"><i class="icon-download"></i> Gerar Mapa de Saídas</button></div></div>' .
            criaTabela('tabelaSaida', array(
                array('', '#'), 
                array('', 'Valor'), 
                array('', 'Motivo'), 
                array('', 'Data')
            ), '', '', '', '') .
            '<div class="row" style="margin-top: 15px;">
                <div class="col-md-12">
                    <button type="button" class="btn btn-success" id="btnAddSaida">
                        <i class="fa fa-plus"></i> Adicionar Saída
                    </button>
                </div>
            </div>'
            ?>
        </div>
    </div>
</div>
                <div class="tab-pane" id="tab2">      
                    <?=
                    '<div class="row"><div class="col-md-2">' . elementoForm('Data do Fecho', 'dataFecho', '<input value="' . date('Y-m-d') . '" class="form-control" $idname type="text" readonly />', '', '', '', '1') . '</div>' .
                    '<div class="col-md-2">' . elementoForm('Clique', 'btnFecho', '<button type="button" $idname class="btn btn-primary" style="display:block">Listar</button>', '', '', '', '1') . '</div>' .
                    '<div class="col-md-3">' . elementoForm('Mês do Mapa', 'mesFechoMapa', '<input value="' . date('Y-m') . '" class="form-control" $idname type="month" />', '', '', '', '1') . '</div>' .
                    '<div class="col-md-2"><label>&nbsp;</label><button type="button" class="btn btn-success btn-block" id="btnMapaFecho"><i class="icon-download"></i> Gerar Mapa</button></div></div>';
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <span id="msgTabelaFecho"></span>
                            <?=
                            '<div style="margin-bottom: 10px; text-align: left">Qtd Produzida: <code id="totalPP">0</code>; Qtd Vendida: <code id="totalPV">0</code>; Qtd Restante: <code id="totalPR">0</code>; Valor Total: <code id="totalVT">0</code></div>' .
                            criaTabela('tabelaFecho', array(array('', '#'), array('', 'Produto'), array('', 'Preço'), array('', 'Qtd Produzida'), array('', 'Qtd Vendidas'), array('', 'Qtd Restada'), array('', 'Valor Total')), '', '', '', '');
                            ?>                       
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab3">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="modal-title text-center"><strong id="tituloFechoValor">Fecho de Valores Padaria- <?= date('m/Y') ?></strong></h4>
                            <div class="row" style="margin-bottom: 15px">
                                <div class="col-md-3">
                                    <label for="mesFechoValor">Mês do Fecho</label>
                                    <input id="mesFechoValor" class="form-control" type="month" value="<?= date('Y-m') ?>" max="<?= date('Y-m') ?>" />
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-success btn-block" id="btnExportarFechoValor"><i class="icon-download"></i> Gerar Excel</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed" id="tabelaFechoValor">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">Data</th>
                                            <th colspan="5" class="text-center">MOEDA (KZ)</th>
                                            <th colspan="5" class="text-center">DINHEIRO (CASH) KZ</th>
                                            <th rowspan="2">TPA</th>
                                            <th rowspan="2">NOTAS</th>
                                            <th rowspan="2">TOTAL DINHEIRO</th>
                                            <th rowspan="2">TOTAL</th>
                                        </tr>
                                        <tr>
                                            <th>10,00</th><th>20,00</th><th>50,00</th><th>100,00</th><th>200,00</th>
                                            <th>200,00</th><th>500,00</th><th>1 000,00</th><th>2 000,00</th><th>5 000,00</th>
                                        </tr>
                                    </thead>
                                    <tbody id="corpoTabelaFechoValor"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>