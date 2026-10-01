<div class="row" id="divCurriculoForm" style="display: none;">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title"><div class="caption"><i class="icon-edit"></i> Efectuar Fecho</div></div>
            <div class="portlet-body form">
                <form id="formCurriculo" method="post" autocomplete="off">
                    <div class="form-body">
                        <?=
                        elementsHidden(array("codCur")) . '<span id="msgCurriculoForm"></span>' .
                        '<div class="row"><div class="col-md-3">' . elementoForm('Plano', 'planoCur', '<select class="form-control" $idname>' . optionSelect(array('#'))  . '</select>', '', '', '', '1') . '</div>' .
                        '<div class="col-md-2">' . elementoForm('Classe', 'classeCur', '<select class="form-control" $idname>' . optionSelect(array('#'))  . '</select>', '', '', '', '1') . '</div>' .
                        '<div class="col-md-2">' . elementoForm('Classifica&ccedil;&atilde;o', 'notaCur', '<select class="form-control" $idname>' . optionSelect(array('#', '1_Exame Nacional', '2_Lan&ccedil;amento de Nota')) . '</select>', '', '', '', '1') . '</div>' .
                        '<div class="col-md-3">' . elementoForm('Disciplina', 'discCur', '<select class="form-control" $idname>' . optionSelect(array('#'))  . '</select>', '', '', '', '1') . '</div>' .
                        '<div class="col-md-2">' . elementoForm('Código', 'codigoCur', '<input title="Código" class="form-control" $idname maxlength="4" type="number" onkeypress="return inputSoNumero(event)" />', '', '', '', '1') . '</div></div>'
                        ?>
                    </div>
                    <?= btnFormActions("ocultaDiv('divCurriculoForm', 'msgCurriculoForm');resetForm('formCurriculo','codCurriculo');mostraDiv('divCurriculoTabela','msgCurriculoTabela');"); ?>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row" id="divCurriculoTabela">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption"><i class="icon-list"></i>Fechos</div>
                <div class="actions">
                    <button class="btn green" onclick="ocultaDivForm('divCurriculoTabela', 'msgCurriculoTabela');mostraDivForm('divCurriculoForm', 'msgCurriculoForm');"><i class="icon-plus"></i> Criar Novo</button>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <?=
                    '<div class="row"><div class="col-md-2">' . elementoForm('Classe', 'classeLPE', '<select class="form-control" $idname></select>', '', '', '', '1') . '</div>' .
                    '<div class="col-md-3">' . elementoForm('Plano', 'planoLPE', '<select class="form-control" $idname></select>', '', '', '', '1') . '</div>' .
                    '<div class="col-md-2">' . elementoForm('Clique Para', 'btnLPE', '<button type="button" $idname class="btn-block btn btn-primary">Listar</button>', '', '', '', '1') . '</div></div>'
                    ?>
                    <span id="msgCurriculoTabela"></span>
                    <?= criaTabela('tabelaCurriculo', array(array('style="text-align:center"', '#'), array('', 'Código'), array('', 'Disciplina'), array('', 'Classifica&ccedil;&atilde;o'), array('', 'Estado'), array('style="text-align:center"', 'Opera&ccedil;&atilde;o'))); ?>
                </div>
            </div>
        </div>
    </div>
</div>
