<div class="row">
    <div class="col-md-6">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="icon-reorder"></i><?= $utilizador->nome ?></div>
            </div>
            <div class="portlet-body">
                <div class="panel-group accordion" id="accordion1">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_1">Dados do Utilizador</a>
                            </h4>
                        </div>
                        <div id="collapse_1" class="panel-collapse <?= empty($senha) ? 'in' : 'collapse' ?>">
                            <div class="panel-body">
                                <table class="table table-striped table-bordered table-advance table-hover">
                                    <thead>
                                        <tr>
                                            <th colspan="2"><i class="icon-user"></i> Dados Pessoais</th>
                                            <th colspan="2"><i class="icon-edit-sign"></i> Dados Funcionais</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="shortRight text-info">N&ordm; BI/ID:</td>
                                            <td></td>
                                            <td class="shortRight text-info">Utilizador:</td>
                                            <td><?= $utilizador->username ?></td>
                                        </tr>
                                        <tr>
                                            <td class="shortRight text-info">Telefone:</td>
                                            <td><?= $utilizador->telefone ?></td>
                                            <td class="shortRight text-info">Estado:</td>
                                            <td><?= $utilizador->estado == '1' ? '<span class="label label-success">Acivo</span>' : '<span class="label label-warning">Inactivo</span>'; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="shortRight text-info">Email:</td>
                                            <td><?= $utilizador->email ?></td>
                                            <td class="shortRight text-info">M&oacute;dulo:</td>
                                            <td><?= $utilizador->m_nome ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_2">Editar Palavra-Passe</a>
                            </h4>
                        </div>
                        <div id="collapse_2" class="panel-collapse <?= !empty($senha) ? 'in' : 'collapse' ?>">
                            <form id="formUserPerfirEditarSenha" method="post" autocomplete="off">
                                <div class="form-body">
                                    <?php if (!empty($senha)): ?>
                                        <div class="alert alert-danger"><strong>Senha Padr&atilde;o!</strong> Por Seguran&ccedil;a fa&ccedil;a altera&ccedil;&atilde;o.</div>
                                    <?php endif; ?>
                                    <span id="msgSenhaForm"></span>
                                    <?=
                                    '<div class="row"><div class="col-md-12">' . elementoForm('Atual Palavra-Passe', 'passwordA', ' <input type="password" $idname class="form-control" />', '', '', '', '1') . '</div></div>' .
                                    '<div class="row"><div class="col-md-12">' . elementoForm('Nova Palavra-Passe', 'passwordN', ' <input type="password" $idname class="form-control" />', '', '', '', '1') . '</div></div>' .
                                    '<div class="row"><div class="col-md-12">' . elementoForm('Repetir Nova Palavra-Passe', 'passwordC', ' <input type="password" $idname class="form-control" />', '', '', '', '1') . '</div></div>'
                                    ?>
                                </div>
                                <?= btnFormActions(""); ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
