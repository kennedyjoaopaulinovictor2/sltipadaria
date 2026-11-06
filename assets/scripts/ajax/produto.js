/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

$tabelaLimparListarProdutos = ['tabelaProduto', 'msgProdutoTabela'];

function mostrarFormularioProduto() {
    $('#divProdutoTabela').hide();
    $('#divProdutoForm').show();
    $('#msgProdutoForm').empty();
    $('html, body').animate({scrollTop: 0}, 'slow');
}

function cancelarFormularioProduto() {
    $('#formProduto')[0].reset();
    $('#codProduto').val('');
    $('#imagemProdutoPreview').attr('src', url + 'assets/img/notImg.png');
    $('#tituloFormProduto').text('Novo Produto');
    $('#msgProdutoForm').empty();
    $('#formProduto').validate().resetForm();
    $('#divProdutoForm').hide();
    $('#divProdutoTabela').show();
}

function novoProduto() {
    cancelarFormularioProduto();
    mostrarFormularioProduto();
}

function listarProdutos() {
    limparListar($tabelaLimparListarProdutos);
    ajaxApp(
            'produto/listarProdutos',
            'c=' + $('#categoriaFiltroProduto').val() + '&e=' + $('#estadoFiltroProduto').val(),
            function (r) {
                if (r.r === 0) {
                    if (r.dados.length) {
                        $tabela.fnAddData(r.dados);
                    }
                } else {
                    msgErroSpan('msgProdutoTabela', 'danger', 'Erro 00' + r.r, 'Não foi possível listar os produtos.');
                }
            }
    );
}

function editarProduto(cod) {
    ajaxApp('produto/buscarProduto', 'cod=' + cod, function (r) {
        if (r.r !== 0) {
            msgErroSpan('msgProdutoTabela', 'danger', 'Erro 00' + r.r, 'Produto não encontrado.');
            return;
        }

        var produto = r.produto;
        $('#codProduto').val(produto.cod);
        $('#codigoProduto').val(produto.codigo);
        $('#nomeProduto').val(produto.nome);
        $('#precoProduto').val(produto.preco);
        $('#categoriaProduto').val(produto.cod_categoria);
        $('#imagemProdutoPreview').attr('src', url + produto.url_file);
        $('#tituloFormProduto').text('Editar Produto');
        mostrarFormularioProduto();
    });
}

function salvarProduto() {
    var dados = new FormData($('#formProduto')[0]);
    ajaxFormData('produto/salvarProduto', dados, function (r) {
        if (r.r === 0) {
            cancelarFormularioProduto();
            listarProdutos();
            msgErroSpan('msgProdutoTabela', 'success', 'Sucesso', 'Produto guardado.');
        } else if (r.r === -3) {
            msgErroSpan('msgProdutoForm', 'warning', 'Correção', 'Já existe um produto com esse código.');
        } else if (r.r === -5) {
            msgErroSpan('msgProdutoForm', 'warning', 'Correção', 'A categoria selecionada já não está disponível.');
        } else if (r.r === -7) {
            msgErroSpan('msgProdutoForm', 'danger', 'Imagem inválida', r.mensagem || 'Verifique o formato e o tamanho da imagem.');
        } else if (r.r === -8) {
            msgErroSpan('msgProdutoForm', 'danger', 'Erro de gravação', r.mensagem);
        } else if (r.r === -9) {
            msgErroSpan('msgProdutoForm', 'danger', 'Acesso negado', 'A sua sessão não permite gerir produtos.');
        } else {
            msgErroSpan('msgProdutoForm', 'danger', 'Erro 00' + r.r, 'Produto não guardado.');
        }
    });
}

function alterarEstadoProduto(cod, estadoAtual, nome) {
    var novoEstado = estadoAtual === 0 ? 1 : 0;
    swal({
        title: 'Alterar estado do produto?',
        text: 'Tens certeza que queres ' + (novoEstado === 1 ? 'desactivar' : 'activar') + ' o produto "' + nome + '"?',
        icon: 'warning',
        allowOutsideClick: false,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'SIM',
        cancelButtonText: 'NÃO'
    }).then(function (result) {
        if (result.value) {
            ajaxApp('produto/alterarEstadoProduto', 'cod=' + cod + '&estado=' + novoEstado, function (r) {
                if (r.r === 0) {
                    listarProdutos();
                    msgErroSpan('msgProdutoTabela', 'success', 'Sucesso', 'Estado do produto actualizado.');
                } else {
                    msgErroSpan('msgProdutoTabela', 'danger', 'Erro 00' + r.r, 'Não foi possível alterar o estado do produto.');
                }
            });
        }
    });
}

$('#imagemProduto').change(function () {
    var arquivo = this.files && this.files[0];
    if (arquivo && window.FileReader) {
        var leitor = new FileReader();
        leitor.onload = function (evento) {
            $('#imagemProdutoPreview').attr('src', evento.target.result);
        };
        leitor.readAsDataURL(arquivo);
    }
});

$('#formProduto').validate({
    rules: {
        codigoProduto: {maxlength: 50},
        nomeProduto: {required: true, maxlength: 255},
        precoProduto: {required: true, number: true, min: 0.01},
        categoriaProduto: {required: true},
        imagemProduto: {extension: 'jpg|jpeg|png|gif'}
    },
    submitHandler: function () {
        salvarProduto();
    }
});

$('#btnListarProdutos').click(listarProdutos);
$('.filtroProduto').change(listarProdutos);

if ($('#tabelaProduto').length && $('#msgProdutoTabela').length) {
    $(function () {
        $tabela = $('#tabelaProduto').dataTable({
            'bSort': false,
            'bFilter': false,
            'bInfo': true,
            'bPaginate': true,
            'bLengthChange': true,
            'iDisplayLength': 10,
            'bRetrieve': true,
            'oLanguage': {
                'sEmptyTable': '<center>Nenhum produto encontrado.</center>',
                'sInfo': '_END_ de _TOTAL_ Produtos',
                'sInfoEmpty': ''
            }
        });
        listarProdutos();
    });
}