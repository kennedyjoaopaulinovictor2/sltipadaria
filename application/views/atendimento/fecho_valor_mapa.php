<div class="row">
    <div class="col-md-12">
        <h4 class="modal-title text-center"><strong id="tituloFechoValor">Fecho de Valores Padaria- <?= date('m/Y') ?></strong></h4>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-md-3">
                <label for="mesFechoValor">M&ecirc;s do Fecho</label>
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
