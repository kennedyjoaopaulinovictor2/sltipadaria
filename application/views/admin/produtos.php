<div class="row" id="divProdutoForm" style="display: none;">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title"><div class="caption"><i class="icon-edit"></i> <span id="tituloFormProduto">Novo Produto</span></div></div>
            <div class="portlet-body form">
                <form id="formProduto" method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="form-body">
                        <?=
                        elementsHidden(array("codProduto")) . '<span id="msgProdutoForm"></span>' .
                        '<div class="row"><div class="col-md-3">' . elementoForm('Código (opcional)', 'codigoProduto', '<input class="form-control" $idname maxlength="50" type="text" />', '', '', '', '1') . '</div>' .
                        '<div class="col-md-5">' . elementoForm('Produto', 'nomeProduto', '<input class="form-control" $idname maxlength="255" type="text" />', '', '', '', '1') . '</div>' .
                        '<div class="col-md-2">' . elementoForm('Preço (Kz)', 'precoProduto', '<input class="form-control" $idname type="number" min="0.01" step="0.01" />', '', '', '', '1') . '</div>' .
                        '<div class="col-md-2">' . elementoForm('Categoria', 'categoriaProduto', '<select class="form-control" $idname>' . optionSelect(array('#_Selecione')) . optionSelectEntidade($categorias, array('cod', 'nome')) . '</select>', '', '', '', '1') . '</div></div>' .
                        '<div class="row"><div class="col-md-4">' . elementoForm('Imagem (JPG, PNG ou GIF; m&aacute;x. 2 MB)', 'imagemProduto', '<input class="form-control" $idname type="file" accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif" />', '', '', '', '1') . '</div>' .
                        '<div class="col-md-8"><label class="control-label">Pr&eacute;-visualiza&ccedil;&atilde;o</label><div><img id="imagemProdutoPreview" src="' . base_url(IMG_NOT_IMG) . '" alt="Pré-visualização do produto" style="max-width:100px;max-height:100px" /></div></div></div>'
                        ?>
                    </div>
                    <?= btnFormActions("cancelarFormularioProduto();"); ?>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row" id="divProdutoTabela">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption"><i class="icon-list"></i>Produtos</div>
                <div class="actions">
                    <button class="btn green" type="button" onclick="novoProduto();"><i class="icon-plus"></i> Criar Novo</button>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <?=
                    '<div class="row"><div class="col-md-3">' . elementoForm('Categoria', 'categoriaFiltroProduto', '<select class="form-control filtroProduto" $idname><option value="all">Todas</option>' . optionSelectEntidade($categorias, array('cod', 'nome')) . '</select>', '', '', '', '1') . '</div>' .
                    '<div class="col-md-3">' . elementoForm('Estado', 'estadoFiltroProduto', '<select class="form-control filtroProduto" $idname><option value="all">Todos</option><option value="0">Activos</option><option value="1">Inactivos</option></select>', '', '', '', '1') . '</div>' .
                    '<div class="col-md-2">' . elementoForm('Clique Para', 'btnListarProdutos', '<button type="button" $idname class="btn-block btn btn-primary">Listar</button>', '', '', '', '1') . '</div></div>'
                    ?>
                    <span id="msgProdutoTabela"></span>
                    <?= criaTabela('tabelaProduto', array(array('style="text-align:center"', '#'), array('', 'Código'), array('', 'Imagem'), array('', 'Produto'), array('', 'Categoria'), array('', 'Preço'), array('', 'Estado'), array('style="text-align:center"', 'Opera&ccedil;&atilde;o'))); ?>
                </div>
            </div>
        </div>
    </div>
</div>
