<?= elementsHidden(array('codUtilizador_' . codUtilizador())) ?>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption"><i class="icon-list"></i>Mapas</div>
        <div class="actions">
            <button type="button" class="btn green" id="btnExportarTodosMapas"><i class="icon-download"></i> Exportar Todos Mapas</button>
        </div>
    </div>
    <div class="portlet-body form">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="tabbable tabbable-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tabMapaFecho" id="tabMapaFechoLink" data-toggle="tab">Fecho de Produtos</a></li>
                            <li><a href="#tabMapaSaidas" id="tabMapaSaidasLink" data-toggle="tab">Sa&iacute;das de Valores</a></li>
                            <li><a href="#tabMapaFechoValor" id="tabFechoValor" data-toggle="tab">Fecho de Valores</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabMapaFecho">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="modal-title text-center"><strong id="tituloMapaFecho">Mapa de Controlo de Produtos Restantes</strong></h4>
                                        <div class="row" style="margin-bottom: 15px">
                                            <div class="col-md-3">
                                                <label for="mesFechoMapa">M&ecirc;s do Mapa</label>
                                                <input id="mesFechoMapa" class="form-control" type="month" value="<?= date('Y-m') ?>" max="<?= date('Y-m') ?>" />
                                            </div>
                                            <div class="col-md-3">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-success btn-block" id="btnMapaFecho"><i class="icon-download"></i> Gerar Excel</button>
                                            </div>
                                        </div>
                                        <div class="table-scrollable">
                                            <table class="table table-bordered table-condensed" id="tabelaMapaFecho">
                                                <thead></thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabMapaSaidas">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="modal-title text-center"><strong id="tituloMapaSaidas">Mapa de Controlo de Sa&iacute;da de Valores</strong></h4>
                                        <div class="row" style="margin-bottom: 15px">
                                            <div class="col-md-3">
                                                <label for="mesMapaSaida">M&ecirc;s do Mapa</label>
                                                <input id="mesMapaSaida" class="form-control" type="month" value="<?= date('Y-m') ?>" max="<?= date('Y-m') ?>" />
                                            </div>
                                            <div class="col-md-3">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-success btn-block" id="btnMapaSaida"><i class="icon-download"></i> Gerar Excel</button>
                                            </div>
                                        </div>
                                        <div class="text-right">Total do m&ecirc;s: <strong id="totalMapaSaidas">0,00</strong></div>
                                        <div class="table-scrollable">
                                            <table class="table table-bordered table-condensed" id="tabelaMapaSaidas">
                                                <thead></thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabMapaFechoValor">
                                <?= get_instance()->load->view('atendimento/fecho_valor_mapa', array(), TRUE) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
