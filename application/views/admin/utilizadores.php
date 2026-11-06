<div class="row"  id="divUtilizadorForm" style="display:none">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title"><div class="caption"><i class="icon-user"></i> Utilizador</div></div>
            <div class="portlet-body form">
                <form id="formUtilizador" method="post" autocomplete="off">
                    <div class="form-body">
                        <?=
                        elementsHidden(array("codUtilizador", "trUtilizador", "codPEdit")) . '<span id="msgUtilizadorForm"></span>' .
                        '<div class="row"><div class="col-md-3">' . elementoForm('Pessoa existente (opcional)', 'pessoa', '<select class="form-control" $idname onchange="buscaPessoa();">' . optionSelect(array('#_Nova pessoa')) . optionSelectEntidade($pessoas, array('cod', 'nome')) . '</select>', '', '', '', '1') . '<small class="help-block"></small></div>' .
                        '<div class="col-md-3">' . elementoForm('Nome', 'nome', '<input class="form-control" $idname type="text" maxlength="255" placeholder="Primero e o Último Nome" onblur="textoIniciasMaiuscula(\'#nome\');" />', '', '', '', '1') . '</div>' .
                        '<div class="col-md-3">' . elementoForm('Telefone (opcional)', 'telefone', '<input class="form-control" $idname type="text" maxlength="9" placeholder="N&ordm; de Telefone" />', '', '', '', '1') . '</div>' .
                        '<div class="col-md-3">' . elementoForm('E-mail (opcional)', 'email', '<input class="form-control" $idname type="email" maxlength="255" placeholder="Correio Electrónico" />', '', '', '', '1') . '</div></div>' .
                        '<div class="row"><div class="col-md-3">' . elementoForm('M&oacute;dulo', 'modulo', ' <select class="form-control" $idname>' . optionSelect(array('#')) . optionSelectEntidade($modulos, array('cod', 'nome')) . '</select>', '', '', '', '1') . '</div>' .
                        '<div class="col-md-3">' . elementoForm('SMS Acesso', 'smsUser', '<select class="form-control" $idname>' . optionSelect(array('1_Não Enviar', '2_Sim Enviar')) . '</select>', '', '', '', '1') . '</div>' .
                        '<div class="col-md-3">' . elementoForm('Utilizador (opcional)', 'username', '<input class="form-control" $idname type="text" maxlength="255" placeholder="Nome de Utilizador" />', '', '', '', '1') . '</div></div>'
                        ?>
                    </div>
                    <?= btnFormActions("ocultaMostraDivsUtizador()"); ?>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row" id="divUtilizadorTable">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption"><i class="icon-group"></i> Utilizadores</div>
                <div class="actions"><button class="btn green" onclick="ocultaMostraDivsUtizador();"><i class="icon-plus"></i> Criar Novo</button></div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <?=
                    '<div class="row"><div class="col-md-3">' . elementoForm('Acesso', 'moduloLU', '<select class="form-control classLU" $idname>' . optionSelectEntidade($modulos, array('cod', 'nome')) . '</select>', '', '', '', '1') . '</div>' .
                    '<div class="col-md-2">' . elementoForm('Estado', 'estLU', '<select class="form-control classLU" $idname>' . optionSelect(array('all_Todos', '1_Activos', '2_Inactivos'), 'all') . '</select>', '', '', '', '1') . '</div>' .
                    '<div class="col-md-2">' . elementoForm('Clique Para', 'btnLU', '<button type="button" $idname class="btn-block btn btn-primary">Listar</button>', '', '', '', '1') . '</div></div>'
                    ?>
                    <span id="msgUtilizadorTabela"></span>
                    <?= criaTabela('tabelaUtilizador', array(array('class="text-center"', '#'), array('', 'Nome'), array('', 'Telefone'), array('', 'Acesso'), array('', 'Estado'), array('', 'Senha'), array('', 'Opera&ccedil;&atilde;o'))); ?>
                </div>
            </div>
        </div>
    </div>
</div>