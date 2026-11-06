<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class Produto extends CI_Controller {

    public function __construct() {
        parent::__construct();
        includeModals(array('produto'));
    }

    private function autorizado() {
        return !empty(codUtilizador())
                && userSession() === (APP_ID_NOME . codUtilizador())
                && userModulo() === 'ADMINISTRADOR';
    }

    private function escapar($valor) {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }

    public function listarProdutos() {
        if (!$this->autorizado()) {
            retornAjax(array('r' => -9));
        }

        $estado = $this->input->post('e');
        $categoria = $this->input->post('c');
        if (!in_array($estado, array('all', '0', '1'), TRUE)
                || ($categoria !== 'all' && !empty($categoria) && !isNumeric($categoria))) {
            retornAjax(array('r' => -2));
        }

        $produtos = $this->produto_m->getProdutos($estado, ($categoria === 'all' ? '' : $categoria));
        $lista = array();
        foreach (($produtos ? $produtos : array()) as $index => $produto) {
            $cod = (int) $produto['cod'];
            $nome = $this->escapar($produto['nome']);
            $nomeJs = json_encode($produto['nome'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
            $imagem = base_url($produto['url_file']);
            $eliminado = (int) $produto['eliminado'];
            $estadoHtml = ($eliminado === 0
                    ? '<span class="label label-success">Activo</span>'
                    : '<span class="label label-danger">Inactivo</span>');
            $acaoEstado = spanActionTable(
                    ($eliminado === 0 ? 2 : 1),
                    'alterarEstadoProduto(' . $cod . ',' . $eliminado . ',' . $nomeJs . ')',
                    '',
                    ($eliminado === 0 ? 'Desactivar' : 'Activar')
            );
            $acoes = spanActionTable(1, 'editarProduto(' . $cod . ')') . $acaoEstado;

            $lista[] = array(
                '<center>' . ($index + 1) . '</center>',
                $this->escapar($produto['codigo']),
                '<img src="' . $this->escapar($imagem) . '" alt="' . $nome . '" style="max-width:60px;max-height:60px" />',
                $nome,
                $this->escapar($produto['categoria']),
                moeda_akz($produto['preco']),
                $estadoHtml,
                '<center>' . $acoes . '</center>'
            );
        }

        retornAjax(array('r' => 0, 'dados' => $lista));
    }

    public function buscarProduto() {
        if (!$this->autorizado()) {
            retornAjax(array('r' => -9));
        }
        $cod = $this->input->post('cod');
        if (!isNumeric($cod)) {
            retornAjax(array('r' => -2));
        }

        $produto = $this->produto_m->getProduto($cod);
        retornAjax(!empty($produto) ? array('r' => 0, 'produto' => $produto) : array('r' => -4));
    }

    private function processarImagem() {
        if (!isset($_FILES['imagemProduto']) || $_FILES['imagemProduto']['error'] === UPLOAD_ERR_NO_FILE) {
            return array('r' => 0, 'url' => NULL);
        }

        $diretorioRelativo = APP_DIR_FILE_IMG . 'produtos/';
        $diretorio = FCPATH . $diretorioRelativo;
        if (!is_dir($diretorio) && !mkdir($diretorio, DIR_WRITE_MODE, TRUE) && !is_dir($diretorio)) {
            log_message('error', 'Não foi possível criar o diretório de imagens de produtos: ' . $diretorio);
            return array('r' => -8);
        }
        if (!is_writable($diretorio)) {
            log_message('error', 'O diretório de imagens de produtos não permite gravação: ' . $diretorio);
            return array('r' => -8);
        }

        $this->load->library('upload');
        $this->upload->initialize(array(
            'upload_path' => $diretorio,
            'allowed_types' => 'jpg|jpeg|png|gif',
            'max_size' => 2048,
            'file_name' => 'produto_' . md5(uniqid(mt_rand(), TRUE)),
            'remove_spaces' => TRUE,
            'file_ext_tolower' => TRUE
        ), TRUE);

        if (!$this->upload->do_upload('imagemProduto')) {
            return array('r' => -7);
        }

        $arquivo = $this->upload->data('file_name');
        return array('r' => 0, 'url' => $diretorioRelativo . $arquivo);
    }

    private function removerImagemEnviada($url) {
        $caminho = FCPATH . $url;
        if (is_file($caminho) && !unlink($caminho)) {
            log_message('error', 'Não foi possível remover a imagem de produto enviada sem gravação: ' . $caminho);
        }
    }

    public function salvarProduto() {
        if (!$this->autorizado()) {
            retornAjax(array('r' => -9));
        }

        $cod = trim((string) $this->input->post('codProduto'));
        $codigo = trim((string) $this->input->post('codigoProduto'));
        $nome = trim((string) $this->input->post('nomeProduto'));
        $preco = str_replace(',', '.', trim((string) $this->input->post('precoProduto')));
        $categoria = $this->input->post('categoriaProduto');

        if ((!empty($cod) && !isNumeric($cod))
                || strlen($codigo) > 50
                || empty($nome)
                || (function_exists('mb_strlen') ? mb_strlen($nome, 'UTF-8') : strlen($nome)) > 255
                || !preg_match('/^\d+(?:\.\d{1,2})?$/', $preco)
                || (float) $preco <= 0
                || (float) $preco > 99999999.99
                || !isNumeric($categoria)) {
            retornAjax(array('r' => -2));
        }

        $imagem = $this->processarImagem();
        if ($imagem['r'] !== 0) {
            $mensagem = ($imagem['r'] === -8
                    ? 'O diretório de imagens está indisponível para gravação.'
                    : 'A imagem deve ser JPG, PNG ou GIF e não pode exceder 2 MB.');
            retornAjax(array('r' => $imagem['r'], 'mensagem' => $mensagem));
        }

        $dados = array(
            'codigo' => $codigo,
            'nome' => $nome,
            'preco' => $preco,
            'cod_categoria' => (int) $categoria,
            'cod_utilizador' => (int) codUtilizador()
        );
        if (!empty($imagem['url'])) {
            $dados['url_file'] = $imagem['url'];
        }

        $resultado = $this->produto_m->salvarProduto($dados, $cod);
        if ($resultado !== 0 && !empty($imagem['url'])) {
            $this->removerImagemEnviada($imagem['url']);
        }
        retornAjax(array('r' => $resultado));
    }

    public function alterarEstadoProduto() {
        if (!$this->autorizado()) {
            retornAjax(array('r' => -9));
        }

        $cod = $this->input->post('cod');
        $estado = $this->input->post('estado');
        if (!isNumeric($cod) || !in_array($estado, array('0', '1'), TRUE)) {
            retornAjax(array('r' => -2));
        }

        retornAjax(array('r' => $this->produto_m->alterarEstado($cod, $estado)));
    }
}
