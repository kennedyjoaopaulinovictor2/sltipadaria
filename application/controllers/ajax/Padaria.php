<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Configuracao
 *
 * @author Sampaio André
 */
class Padaria extends CI_Controller {

    private $writerExportacaoCombinada = NULL;

    public function __construct() {
        parent::__construct();
        includeModals(array('padaria', 'configuracao'));
    }

    private function acessoAdminMapas() {
        return !empty(codUtilizador()) && userSession() == (APP_ID_NOME . codUtilizador()) && userModulo() === 'ADMINISTRADOR';
    }

    private function dataMapaValida($data) {
        if (!is_string($data))
            return FALSE;
        $dataValidada = DateTime::createFromFormat('!Y-m-d', $data);
        return $dataValidada && $dataValidada->format('Y-m-d') === $data && $data <= date('Y-m-d');
    }

    private function mesMapaValido($mes) {
        return is_string($mes) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $mes) && $mes <= date('Y-m');
    }

    private function obterWriterExportacao() {
        return ($this->writerExportacaoCombinada instanceof XLSXWriter ? $this->writerExportacaoCombinada : new XLSXWriter());
    }

    function addUpdateVenda() {
        if (!empty($this->input->post('u')) && !empty($this->input->post('p')) && !empty($this->input->post('t')) && !empty($this->input->post('q'))) {
            if (isNumeric($this->input->post('u')) && isNumeric($this->input->post('p')) && isNumeric($this->input->post('t')) && isNumeric($this->input->post('q'))) {
                $r = $this->padaria_m->insertUpdateVenda();
                retornAjax(is_array($r) ? array('r' => $r[0], 'q' => $r[1]->qtd, 'v' => moeda_akz($r[1]->valor)) : array('r' => $r));
            } else
                retornAjax(array('r' => '23'));
        } else
            retornAjax(array('r' => '2'));
    }

    public function listarVenderProdutos() {
        if (!empty($this->input->post('u')) && isNumeric($this->input->post('u'))) {
            $produtos = $this->padaria_m->getProdutosVender();
            if (!empty($produtos)) {
                $lista = array();
                $c = 0;
                foreach ($produtos as $produto) {
                    $lista[$c++] = array('<center>' . $c . '<center>',
                        '<div class="user-info"><img alt="" src="' . base_url($produto['url_file']) . '" class="img-responsive" width="70" height="70" /><div class="details"><div><a href="#" id="np' . $c . '">' . $produto['nome'] . '</a></div></div></div>',
//                            '<div class="tile image selected"><div class="tile-body"><img src="' . $produto['url_file'] . '"  width="50" height="50"></div><div class="tile-object"><div class="name">' . $produto['nome'] . '</div></div></div>',
                        moeda_akz($produto['preco']),
                        '<span id="qh' . $c . '">' . $produto['qtdV'] . '</span>',
                        '<span id="vh' . $c . '">' . $produto['valV'] . '</span>',
                        '<input id="qv' . $c . '" type="number" class="form-control" min="1" max="500" maxlength="3" style="border: 1px solid #468847" onkeypress="return inputSoNumero(event)" onkeydown="if((window.event?event.keyCode:event.which)==13){addUpdateVenda(\'' . $produto['cod'] . '\',' . $c . ',1);}">',
                        '<input id="qa' . $c . '" type="number" class="form-control" min="1" max="500" maxlength="3" style="border: 1px solid #b94a48" onkeypress="return inputSoNumero(event)" onkeydown="if((window.event?event.keyCode:event.which)==13){addUpdateVenda(\'' . $produto['cod'] . '\',' . $c . ',2);}">'
                    );
                }
                retornAjax(array('dados' => $lista, 'r' => (!empty($lista) ? 0 : 1)));
            } else
                retornAjax(array('r' => 4));
        } else
            retornAjax(array('r' => 2));
    }

    function addSaidaValor() {
        if (!empty($this->input->post('v')) && !empty($this->input->post('m')) && !empty($this->input->post('u'))) {
            if (isNumeric($this->input->post('u')) && preg_match("/^[A-Za-z0-9áàâãéèêëíïîìóòôõöúçñÁÀÂÃÉÈËÍÏÎÓÒÔÕÖÚÇÑ\-\s]+$/", $this->input->post('m'))) {
                $r = $this->padaria_m->insertSaidaValor();
                retornAjax(array('r' => $r, 'd' => ($r == 0 ? dataHoraBD() : ''), 't' => ($r == 0 ? moeda_akz(empty($this->input->post('t')) ? $this->input->post('v') : (moedaBd($this->input->post('t')) + moedaBd($this->input->post('v')))) : '')));
            } else
                retornAjax(array('r' => '23'));
        } else
            retornAjax(array('r' => '2')); //-DADOS INVALIDOS
    }

    function apagarSaidaValor() {
        if (!empty($this->input->post('cod')) && $this->input->post('u')) {
            if (isNumeric($this->input->post('cod')) && isNumeric($this->input->post('u'))) {
                retornAjax(array('r' => $this->padaria_m->deleteSaidaValor($this->input->post('u'))));
            } else
                retornAjax(array('r' => '23'));
        } else
            retornAjax(array('r' => '2')); //-DADOS INVALIDOS
    }

    public function buscaAddRowNovoSaida() {
        if (!empty($this->input->post('c'))) {
            $c = $this->input->post('c');
            $r = (empty($this->input->post('r')) ? 1 : $this->input->post('r') + 1);
            $row = array('<center>' . $r . '<center>',
                '<input id="vsv' . $c . '" type="text" placeholder="Digite o Valor a Sair" class="form-control input-sm" style="text-align:right" data-a-sep="." data-a-dec=","><span id="vsvs' . $c . '" class="help-block" style="color:#b94a48;"></span>',
                '<input id="msv' . $c . '" type="text" class="form-control" placeholder="Motivo da Saida do Valor" style="width:100%;height:30px"><span id="msvs' . $c . '" class="help-block" style="color:#b94a48;"></span>',
                '<span id="dsv' . $c . '" ></span>',
                spanActionTable(1, 'btnAddSaidaValor(' . $c . ')', ('btnsv' . $c), 'Registar', 'Clique para Submeter', 'green', 'icon-plus') . '<span id = "btnsvs' . $c . '" class = "label label-sm label-default"></span>'
            );
            retornAjax(array('r' => 0, 'row' => $row));
        } else
            retornAjax(array('r' => '2'));
    }

    public function listarRegistarSaida() {
        if (!empty($this->input->post('u')) && isNumeric($this->input->post('u'))) {
            $saidas = $this->padaria_m->getSaidasValores();
            if (!empty($saidas)) {
                $lista = array();
                $c = $v = 0;
                foreach ($saidas as $saida) {
                    $lista[$c++] = array('<center>' . $c . '<center>', moeda_akz($saida['valor']), $saida['motivo'], $saida['data_cricao'], '');
                    $v += $saida['valor'];
                }
                retornAjax(array('dados' => $lista, 'r' => (!empty($lista) ? 0 : 1), 'v' => moeda_akz($v)));
            } else
                retornAjax(array('r' => 4));
        } else
            retornAjax(array('r' => 2));
    }

    function addUpdateFecho() {
        if (!empty($this->input->post('u')) && !empty($this->input->post('p')) && !empty($this->input->post('d')) && !empty($this->input->post('q'))) {
            if (isNumeric($this->input->post('u')) && isNumeric($this->input->post('p')) && isNumeric($this->input->post('q'))) {
                $r = $this->padaria_m->insertUpdateFecho();
                retornAjax(is_array($r) ? array('r' => $r[0], 'q' => $r[1]) : array('r' => $r));
            } else
                retornAjax(array('r' => '23'));
        } else
            retornAjax(array('r' => '2'));
    }

    public function listarGerirFecho() {
        if (!empty($this->input->post('u')) && !empty($this->input->post('d'))) {
            $produtos = $this->padaria_m->getProdutosFecho($this->input->post('d'));

            if (!empty($produtos)) {
                $lista = array();
                $c = $p = $v = $r = $t = $vr = 0;
                $hoje = $this->input->post('d') == date('Y-m-d');
                foreach ($produtos as $produto) {
                    $res = (!empty($produto['qtdP']) && $produto['qtdP'] >= $produto['qtdV'] ? $produto['qtdP'] - $produto['qtdV'] : 0);
                    $lista[$c++] = array('<center>' . $c . '<center>',
                        '<div class="user-info"><img alt="" src="' . base_url($produto['url_file']) . '" class="img-responsive" width="70" height="70" /><div class="details"><div><a href="#" id="np' . $c . '">' . $produto['nome'] . '</a></div></div></div>',
                        moeda_akz($produto['preco']),
                        ($hoje ? '<input id="qp' . $c . '" type="number" value="' . $produto['qtdP'] . '" class="form-control" min="1" max="5500" maxlength="4" onkeypress="return inputSoNumero(event)" onkeydown="if((window.event?event.keyCode:event.which)==13){updateVendaFecho(\'' . $produto['cod'] . '\',' . $c . ');}">' : $produto['qtdP']),
                        '<span id="qv' . $c . '">' . $produto['qtdV'] . '</span>',
                        '<span id="qr' . $c . '" class="text-danger">' . $res.' '. moeda_akz($res*$produto['preco']) . '</span>',
                        moeda_akz($produto['valV']),
                    );
                    $p = (!empty($produto['qtdP']) ? ($produto['qtdP'] + $p) : $p);
                    $v = (!empty($produto['qtdV']) ? ($produto['qtdV'] + $v) : $v);
                    $r = (!empty($produto['qtdP']) && $produto['qtdP'] >= $produto['qtdV'] ? ($r + ($produto['qtdP'] - $produto['qtdV'])) : $r);
                    $t = (!empty($produto['valV']) ? $produto['valV'] + $t : $t);
                    $vr = (!empty($r) ? $vr + ($r * $produto['preco']) : $vr);
                }
                retornAjax(array('dados' => $lista, 'r' => (!empty($lista) ? 0 : 1), 'p' => $p, 'v' => $v, 'q' => ($r.' '. moeda_akz($vr)), 't' => moeda_akz($t)));
            } else
                retornAjax(array('r' => 4));
        } else
            retornAjax(array('r' => 2));
    }

    public function listarMapaFecho() {
        if (!$this->acessoAdminMapas())
            retornAjax(array('r' => -21));

        $mes = $this->input->post('mes', TRUE);
        $mes = (empty($mes) ? date('Y-m') : $mes);
        if (!$this->mesMapaValido($mes))
            retornAjax(array('r' => 2));

        $dataInicio = $mes . '-01';
        $dataFim = date('Y-m-t', strtotime($dataInicio));
        $registos = $this->padaria_m->getMapaMensalFecho($dataInicio, $dataFim);
        $produtos = array();
        $dias = array();
        $data = new DateTime($dataInicio);
        $ultimoDia = new DateTime($dataFim);
        for ($dia = clone $data; $dia <= $ultimoDia; $dia->modify('+1 day'))
            $dias[$dia->format('Y-m-d')] = array('valores' => array(), 'funcionarios' => array(), 'total' => NULL);

        foreach ((array) $registos as $registo) {
            $codProduto = $registo['cod'];
            $produtos[$codProduto] = array('cod' => $codProduto, 'nome' => $registo['nome']);
            if (!empty($registo['data_venda'])) {
                $dataVenda = $registo['data_venda'];
                $dias[$dataVenda]['valores'][$codProduto] = array(
                    'codVenda' => $registo['cod_venda'],
                    'produzida' => (int) $registo['qtd_produzida'],
                    'restante' => (int) $registo['qtd_restante'],
                    'vendida' => (int) $registo['qtd_vendida'],
                    'total' => (float) $registo['total_vendido']
                );
                $dias[$dataVenda]['total'] = (float) $dias[$dataVenda]['total'] + (float) $registo['total_vendido'];
                if (!empty($registo['funcionario']))
                    $dias[$dataVenda]['funcionarios'][$registo['funcionario']] = $registo['funcionario'];
            }
        }

        retornAjax(array('r' => 0, 'mes' => $mes, 'produtos' => array_values($produtos), 'dias' => $dias));
    }

    public function guardarProducaoMapa() {
        if (!$this->acessoAdminMapas())
            retornAjax(array('r' => -21));

        $codProduto = $this->input->post('p', TRUE);
        $data = $this->input->post('d', TRUE);
        $quantidade = $this->input->post('q', TRUE);
        if (!preg_match('/^[1-9]\d*$/', (string) $codProduto) || !$this->dataMapaValida($data) || !preg_match('/^[1-9]\d{0,3}$/', (string) $quantidade) || (int) $quantidade > 5500)
            retornAjax(array('r' => 3));

        $resultado = $this->padaria_m->insertUpdateFecho(codUtilizador(), (int) $codProduto, $data, (int) $quantidade);
        retornAjax(array('r' => is_array($resultado) ? $resultado[0] : $resultado, 'q' => is_array($resultado) ? $resultado[1] : ''));
    }

    public function listarMapaSaidas() {
        if (!$this->acessoAdminMapas())
            retornAjax(array('r' => -21));

        $mes = $this->input->post('mes', TRUE);
        $mes = (empty($mes) ? date('Y-m') : $mes);
        if (!$this->mesMapaValido($mes))
            retornAjax(array('r' => 2));

        $dataInicio = $mes . '-01';
        $dataFim = date('Y-m-t', strtotime($dataInicio));
        $registos = $this->padaria_m->getSaidasValoresMes($dataInicio, $dataFim);
        $dias = array();
        $data = new DateTime($dataInicio);
        $ultimoDia = new DateTime($dataFim);
        for ($dia = clone $data; $dia <= $ultimoDia; $dia->modify('+1 day'))
            $dias[$dia->format('Y-m-d')] = array('data' => $dia->format('Y-m-d'), 'dataTexto' => $dia->format('d/m/Y'), 'saidas' => array(), 'total' => 0);

        foreach ((array) $registos as $registo) {
            if (!isset($dias[$registo['data_saida']]))
                continue;
            $dias[$registo['data_saida']]['saidas'][] = array(
                'cod' => $registo['cod'],
                'valor' => (float) $registo['valor'],
                'motivo' => $registo['motivo']
            );
            $dias[$registo['data_saida']]['total'] += (float) $registo['valor'];
        }

        retornAjax(array('r' => 0, 'mes' => $mes, 'dias' => array_values($dias)));
    }

    public function guardarSaidaMapa() {
        if (!$this->acessoAdminMapas())
            retornAjax(array('r' => -21));

        $cod = $this->input->post('cod', TRUE);
        $motivo = trim((string) $this->input->post('m', TRUE));
        $valor = trim((string) $this->input->post('v', TRUE));
        if (strpos($valor, ',') !== FALSE) {
            $valorBanco = moedaBd($valor);
            $valorValido = preg_match('/^(?:\d{1,3}(?:\.\d{3})+|\d+)(?:,\d{1,2})?$/', $valor);
        } else {
            $valorBanco = $valor;
            $valorValido = preg_match('/^\d{1,10}(?:\.\d{1,2})?$/', $valor);
        }
        if (!preg_match('/^[1-9]\d*$/', (string) $cod) || $motivo === '' || !preg_match("/^[A-Za-z0-9áàâãéèêëíïîìóòôõöúçñÁÀÂÃÉÈËÍÏÎÌÓÒÔÕÖÚÇÑ\-\s]+$/", $motivo) || !$valorValido || !preg_match('/^\d{1,10}(\.\d{1,2})?$/', $valorBanco) || (float) $valorBanco <= 0)
            retornAjax(array('r' => 3));

        $resultado = $this->padaria_m->editarSaidaValor((int) $cod, filterString($motivo), $valorBanco, codUtilizador());
        retornAjax(array('r' => $resultado));
    }

    public function listarGerirFechoValor() {
        $codUtilizador = codUtilizador();
        if (empty($codUtilizador))
            retornAjax(array('r' => -21));

        $mes = $this->input->post('mes', TRUE);
        $mes = (empty($mes) ? date('Y-m') : $mes);
        if (!is_string($mes) || !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $mes) || $mes > date('Y-m'))
            retornAjax(array('r' => 2));
        $dataInicio = $mes . '-01';
        $dataFim = date('Y-m-t', strtotime($dataInicio));
        $registos = $this->padaria_m->getFechosValorMes($dataInicio, $dataFim);
        $campos = array('moeda_10', 'moeda_20', 'moeda_50', 'moeda_100', 'moeda_200', 'dinheiro_200', 'dinheiro_500', 'dinheiro_1000', 'dinheiro_2000', 'dinheiro_5000', 'tpa', 'notas');
        $contexto = $this->input->post('contexto', TRUE);
        $adminPodeEditarHistorico = $contexto === 'admin' && userModulo() === 'ADMINISTRADOR';
        $dias = array();
        $data = new DateTime($dataInicio);
        $ultimoDia = new DateTime($dataFim);
        $hoje = date('Y-m-d');
        for ($dia = clone $data; $dia <= $ultimoDia; $dia->modify('+1 day')) {
            $dataDia = $dia->format('Y-m-d');
            $registo = isset($registos[$dataDia]) ? $registos[$dataDia] : array();
            $editavel = ($adminPodeEditarHistorico ? $dataDia <= $hoje : $dataDia === $hoje);
            $linha = array('data' => $dataDia, 'dataTexto' => $dia->format('d/m/Y'), 'atendente' => isset($registo['atendente']) ? $registo['atendente'] : '', 'editavel' => $editavel);
            $camposGuardados = isset($registo['campos_guardados']) ? explode(',', $registo['campos_guardados']) : array();
            foreach ($campos as $campo) {
                $salvo = in_array($campo, $camposGuardados, TRUE);
                $linha[$campo] = ($salvo && isset($registo[$campo]) ? $registo[$campo] : '');
                $linha[$campo . '_guardado'] = $salvo;
            }
            $dias[] = $linha;
        }
        retornAjax(array('r' => 0, 'mes' => $mes, 'dias' => $dias));
    }

    public function guardarFechoValor() {
        $codUtilizador = codUtilizador();
        $data = $this->input->post('d', TRUE);
        if (empty($codUtilizador))
            retornAjax(array('r' => -21));
        if (!is_string($data))
            retornAjax(array('r' => 2));
        $dataValidada = DateTime::createFromFormat('!Y-m-d', (string) $data);
        if (!$dataValidada || $dataValidada->format('Y-m-d') !== $data || substr($data, 0, 7) > date('Y-m') || $data > date('Y-m-d'))
            retornAjax(array('r' => 2));
        $contexto = $this->input->post('contexto', TRUE);
        $adminPodeEditarHistorico = $contexto === 'admin' && userModulo() === 'ADMINISTRADOR';
        if (!$adminPodeEditarHistorico && $data !== date('Y-m-d'))
            retornAjax(array('r' => 2));

        $campo = $this->input->post('campo', TRUE);
        $camposInteiros = array('moeda_10', 'moeda_20', 'moeda_50', 'moeda_100', 'moeda_200', 'dinheiro_200', 'dinheiro_500', 'dinheiro_1000', 'dinheiro_2000', 'dinheiro_5000');
        $camposPermitidos = array_merge($camposInteiros, array('tpa', 'notas'));
        if (!in_array($campo, $camposPermitidos, TRUE))
            retornAjax(array('r' => 3));

        $valor = $this->input->post('valor', TRUE);
        $valor = ($valor === NULL || $valor === '' ? '0' : trim((string) $valor));
        if (in_array($campo, $camposInteiros, TRUE)) {
            if (!preg_match('/^\d{1,9}$/', $valor))
                retornAjax(array('r' => 3, 'campo' => $campo));
            $valor = (int) $valor;
        } else {
            if (strpos($valor, ',') !== FALSE)
                $valor = moedaBd($valor);
            if (!preg_match('/^\d{1,10}(\.\d{1,2})?$/', $valor))
                retornAjax(array('r' => 3, 'campo' => $campo));
        }

        $editar = $this->input->post('editar', TRUE) === '1';
        $resultado = $this->padaria_m->guardarFechoValorCelula($data, $campo, $valor, $codUtilizador, $editar);
        retornAjax(array('r' => $resultado, 'atendente' => ($resultado == 0 ? nomeUtilizador() : '')));
    }

    public function exportarFechoValor() {
        $codUtilizador = codUtilizador();
        $mes = $this->input->get('mes', TRUE);
        if (empty($codUtilizador))
            show_error('Sessão expirada.', 403);
        if (!is_string($mes) || !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $mes) || $mes > date('Y-m'))
            show_error('Mês inválido.', 400);

        $dataInicio = $mes . '-01';
        $dataFim = date('Y-m-t', strtotime($dataInicio));
        $fechos = $this->padaria_m->getFechosValorMes($dataInicio, $dataFim);
        $campos = array('moeda_10', 'moeda_20', 'moeda_50', 'moeda_100', 'moeda_200', 'dinheiro_200', 'dinheiro_500', 'dinheiro_1000', 'dinheiro_2000', 'dinheiro_5000', 'tpa', 'notas');

        require_once FCPATH . 'excel/xlsxwriter.class.php';
        $writer = $this->obterWriterExportacao();
        $sheet = 'Fecho de Valor';
        $tiposColuna = array('DATA' => 'string');
        foreach ($campos as $campo)
            $tiposColuna[$campo] = (in_array($campo, array('tpa', 'notas'), TRUE) ? 'price' : 'integer');
        $tiposColuna['ATENDENTE'] = 'string';
        $larguras = array_merge(array(15), array(12, 12, 12, 12, 12, 12, 12, 14, 14, 14, 18, 18, 24));
        $writer->writeSheetHeader($sheet, $tiposColuna, array('suppress_row' => TRUE, 'widths' => $larguras, 'freeze_rows' => 3, 'freeze_columns' => 1, 'orientation' => 'landscape', 'fit_to_page' => TRUE));

        $meses = array(1 => 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
        $totalColunas = count($tiposColuna);
        $linhaTitulo = array_fill(0, $totalColunas, '');
        $linhaTitulo[0] = 'MAPA DE CONTROLE DE VALORES - PADARIA - ' . $meses[(int) substr($mes, 5, 2)] . ' ' . substr($mes, 0, 4);
        $writer->writeSheetRow($sheet, $linhaTitulo, array('font-style' => 'bold', 'font-size' => 16, 'halign' => 'center'));
        $writer->markMergedCell($sheet, 0, 0, 0, $totalColunas - 1);

        $linhaGrupo = array_fill(0, $totalColunas, '');
        $linhaGrupo[0] = 'Data';
        $linhaGrupo[1] = 'MOEDA (KZ)';
        $linhaGrupo[6] = 'DINHEIRO (CASH) KZ';
        $linhaGrupo[11] = 'TPA';
        $linhaGrupo[12] = 'NOTAS';
        $linhaGrupo[13] = 'ATENDENTE';
        $writer->markMergedCell($sheet, 1, 0, 2, 0);
        $writer->markMergedCell($sheet, 1, 1, 1, 5);
        $writer->markMergedCell($sheet, 1, 6, 1, 10);
        for ($coluna = 11; $coluna <= 13; $coluna++)
            $writer->markMergedCell($sheet, 1, $coluna, 2, $coluna);
        $writer->writeSheetRow($sheet, $linhaGrupo, array('font-style' => 'bold', 'fill' => '#B4C7E7', 'halign' => 'center'));

        $cabecalho = array('', '10,00', '20,00', '50,00', '100,00', '200,00', '200,00', '500,00', '1 000,00', '2 000,00', '5 000,00', '', '', '');
        $writer->writeSheetRow($sheet, $cabecalho, array('font-style' => 'bold', 'fill' => '#D9E2F3', 'halign' => 'center'));

        $data = new DateTime($dataInicio);
        $ultimoDia = new DateTime($dataFim);
        for ($dia = clone $data; $dia <= $ultimoDia; $dia->modify('+1 day')) {
            $dataDia = $dia->format('Y-m-d');
            $fecho = isset($fechos[$dataDia]) ? $fechos[$dataDia] : array();
            $camposGuardados = isset($fecho['campos_guardados']) ? explode(',', $fecho['campos_guardados']) : array();
            $linha = array($dia->format('d/m/Y'));
            foreach ($campos as $campo)
                $linha[] = (in_array($campo, $camposGuardados, TRUE) && isset($fecho[$campo]) ? $fecho[$campo] : '');
            $linha[] = isset($fecho['atendente']) ? $fecho['atendente'] : '';
            $writer->writeSheetRow($sheet, $linha);
        }

        $nomeFicheiro = 'fecho-valor-' . $mes . '.xlsx';
        if ($this->writerExportacaoCombinada instanceof XLSXWriter)
            return;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nomeFicheiro . '"');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        $writer->writeToStdOut();
        exit;
    }

    public function exportarMapaFecho() {
        $mes = $this->input->get('mes', TRUE);
        if (empty(codUtilizador()))
            show_error('Sessão expirada.', 403);
        if (!is_string($mes) || !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $mes))
            show_error('Mês inválido.', 400);

        $dataInicio = $mes . '-01';
        $dataFim = $mes . '-' . date('t', strtotime($dataInicio));
        $registos = $this->padaria_m->getMapaMensalFecho($dataInicio, $dataFim);
        $produtos = array();
        $dias = array();
        $data = new DateTime($dataInicio);
        $ultimoDia = new DateTime($dataFim);
        for ($dia = clone $data; $dia <= $ultimoDia; $dia->modify('+1 day'))
            $dias[$dia->format('Y-m-d')] = array('valores' => array(), 'funcionarios' => array(), 'total' => NULL);

        foreach ((array) $registos as $registo) {
            $codProduto = $registo['cod'];
            $produtos[$codProduto] = array('nome' => $registo['nome']);
            if (!empty($registo['data_venda'])) {
                $dataVenda = $registo['data_venda'];
                $dias[$dataVenda]['valores'][$codProduto] = array(
                    'restante' => (int) $registo['qtd_restante'],
                    'vendida' => (int) $registo['qtd_vendida'],
                    'total' => (float) $registo['total_vendido']
                );
                $dias[$dataVenda]['total'] = (float) $dias[$dataVenda]['total'] + (float) $registo['total_vendido'];
                if (!empty($registo['funcionario']))
                    $dias[$dataVenda]['funcionarios'][$registo['funcionario']] = $registo['funcionario'];
            }
        }

        require_once FCPATH . 'excel/xlsxwriter.class.php';
        $writer = $this->obterWriterExportacao();
        $sheet = 'Mapa Mensal';
        $tiposColuna = array('DATA' => 'string');
        $larguras = array(14);
        foreach ($produtos as $cod => $produto) {
            $tiposColuna['restante_' . $cod] = 'integer';
            $tiposColuna['vendida_' . $cod] = 'integer';
            $tiposColuna['total_' . $cod] = 'price';
            $larguras = array_merge($larguras, array(12, 14, 18));
        }
        $tiposColuna['total_dia'] = 'price';
        $tiposColuna['FUNCS'] = 'string';
        $larguras = array_merge($larguras, array(18, 24));
        $writer->writeSheetHeader($sheet, $tiposColuna, array('suppress_row' => TRUE, 'widths' => $larguras, 'freeze_rows' => 4, 'freeze_columns' => 1, 'orientation' => 'landscape', 'fit_to_page' => TRUE));

        $totalColunas = count($tiposColuna);
        $linhaTitulo = array_fill(0, $totalColunas, '');
        $meses = array(1 => 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
        $linhaTitulo[0] = 'MAPA DE CONTROLE DE PRODUTOS RESTANTES - PADARIA - ' . $meses[(int) substr($mes, 5, 2)] . ' ' . substr($mes, 0, 4);
        $writer->writeSheetRow($sheet, $linhaTitulo, array('font-style' => 'bold', 'font-size' => 16, 'halign' => 'center'));
        $writer->markMergedCell($sheet, 0, 0, 0, $totalColunas - 1);

        $linhaGrupo = array_fill(0, $totalColunas, '');
        $colunaTotalDia = 1 + (count($produtos) * 3);
        $colunaFuncionarios = $colunaTotalDia + 1;
        $writer->markMergedCell($sheet, 1, 0, 2, 0);
        if (count($produtos) > 0) {
            $linhaGrupo[1] = 'PRODUTOS';
            $writer->markMergedCell($sheet, 1, 1, 1, $colunaTotalDia - 1);
        }
        $linhaGrupo[$colunaTotalDia] = 'TOTAL DIA';
        $linhaGrupo[$colunaFuncionarios] = 'FUNCS';
        $writer->markMergedCell($sheet, 1, $colunaTotalDia, 2, $colunaTotalDia);
        $writer->markMergedCell($sheet, 1, $colunaFuncionarios, 2, $colunaFuncionarios);
        $writer->writeSheetRow($sheet, $linhaGrupo, array('font-style' => 'bold', 'halign' => 'center'));

        $linhaProdutos = array_fill(0, $totalColunas, '');
        $coluna = 1;
        foreach ($produtos as $cod => $produto) {
            $linhaProdutos[$coluna] = $produto['nome'];
            $writer->markMergedCell($sheet, 2, $coluna, 2, $coluna + 2);
            $coluna += 3;
        }
        $writer->writeSheetRow($sheet, $linhaProdutos, array('font-style' => 'bold', 'fill' => '#B4C7E7', 'halign' => 'center'));

        $cabecalho = array('Data');
        foreach ($produtos as $produto)
            $cabecalho = array_merge($cabecalho, array('Restante', 'Qtd Vendida', 'Total Vendido (AKZ)'));
        $cabecalho = array_merge($cabecalho, array('', ''));
        $writer->writeSheetRow($sheet, $cabecalho, array('font-style' => 'bold', 'fill' => '#D9E2F3', 'halign' => 'center', 'wrap_text' => TRUE));
        foreach ($dias as $dataDia => $dadosDia) {
            $linha = array(date('d/m/Y', strtotime($dataDia)));
            foreach ($produtos as $cod => $produto) {
                $valores = isset($dadosDia['valores'][$cod]) ? $dadosDia['valores'][$cod] : NULL;
                $linha[] = $valores ? $valores['restante'] : '';
                $linha[] = $valores ? $valores['vendida'] : '';
                $linha[] = $valores ? $valores['total'] : '';
            }
            $linha[] = $dadosDia['total'] === NULL ? '' : $dadosDia['total'];
            $linha[] = implode(', ', $dadosDia['funcionarios']);
            $writer->writeSheetRow($sheet, $linha);
        }

        $nomeFicheiro = 'mapa-fecho-' . $mes . '.xlsx';
        if ($this->writerExportacaoCombinada instanceof XLSXWriter)
            return;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nomeFicheiro . '"');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        $writer->writeToStdOut();
        exit;
    }

    public function exportarMapaSaidasValores() {
        $mes = $this->input->get('mes', TRUE);
        if (empty(codUtilizador()))
            show_error('Sessão expirada.', 403);
        if (!is_string($mes) || !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $mes))
            show_error('Mês inválido.', 400);

        $dataInicio = $mes . '-01';
        $dataFim = $mes . '-' . date('t', strtotime($dataInicio));
        $registos = $this->padaria_m->getSaidasValoresMes($dataInicio, $dataFim);
        $dias = array();
        $data = new DateTime($dataInicio);
        $ultimoDia = new DateTime($dataFim);
        for ($dia = clone $data; $dia <= $ultimoDia; $dia->modify('+1 day'))
            $dias[$dia->format('Y-m-d')] = array('saidas' => array(), 'total' => 0);

        $maxSaidas = 0;
        foreach ((array) $registos as $registo) {
            $dataSaida = $registo['data_saida'];
            if (!isset($dias[$dataSaida]))
                continue;
            $dias[$dataSaida]['saidas'][] = array('valor' => (float) $registo['valor'], 'motivo' => $registo['motivo']);
            $dias[$dataSaida]['total'] += (float) $registo['valor'];
            $maxSaidas = max($maxSaidas, count($dias[$dataSaida]['saidas']));
        }

        require_once FCPATH . 'excel/xlsxwriter.class.php';
        $writer = $this->obterWriterExportacao();
        $sheet = 'Saidas de Valores';
        $tiposColuna = array('DATA' => 'string');
        $larguras = array(15);
        for ($indice = 1; $indice <= $maxSaidas; $indice++) {
            $tiposColuna['valor_' . $indice] = 'price';
            $tiposColuna['motivo_' . $indice] = 'string';
            $larguras = array_merge($larguras, array(16, 24));
        }
        $tiposColuna['TOTAL'] = 'price';
        $larguras[] = 18;
        $writer->writeSheetHeader($sheet, $tiposColuna, array('suppress_row' => TRUE, 'widths' => $larguras, 'freeze_rows' => 4, 'freeze_columns' => 1, 'orientation' => 'landscape', 'fit_to_page' => TRUE));

        $totalColunas = count($tiposColuna);
        $colunaTotal = $totalColunas - 1;
        $meses = array(1 => 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
        $linhaTitulo = array_fill(0, $totalColunas, '');
        $linhaTitulo[0] = 'MAPA DE CONTROLE DE SAÍDA DE VALORES - DEPÓSITO - ' . $meses[(int) substr($mes, 5, 2)] . ' ' . substr($mes, 0, 4);
        $writer->writeSheetRow($sheet, $linhaTitulo, array('font-style' => 'bold', 'font-size' => 16, 'halign' => 'center'));
        $writer->markMergedCell($sheet, 0, 0, 0, $totalColunas - 1);

        $linhaSecao = array_fill(0, $totalColunas, '');
        if ($maxSaidas > 0) {
            $linhaSecao[1] = 'SAÍDAS DE VALORES';
            $writer->markMergedCell($sheet, 1, 1, 1, $colunaTotal - 1);
        } else {
            $linhaSecao[0] = 'SAÍDAS DE VALORES';
            $writer->markMergedCell($sheet, 1, 0, 1, $colunaTotal);
        }
        $writer->writeSheetRow($sheet, $linhaSecao, array('font-style' => 'bold', 'halign' => 'center'));

        $linhaGrupo = array_fill(0, $totalColunas, '');
        $linhaGrupo[0] = 'Data';
        $writer->markMergedCell($sheet, 2, 0, 3, 0);
        for ($indice = 1; $indice <= $maxSaidas; $indice++) {
            $colunaValor = 1 + (($indice - 1) * 2);
            $linhaGrupo[$colunaValor] = 'SAÍDA ' . $indice;
            $writer->markMergedCell($sheet, 2, $colunaValor, 2, $colunaValor + 1);
        }
        $linhaGrupo[$colunaTotal] = 'TOTAL';
        $writer->markMergedCell($sheet, 2, $colunaTotal, 3, $colunaTotal);
        $writer->writeSheetRow($sheet, $linhaGrupo, array('font-style' => 'bold', 'fill' => '#B4C7E7', 'halign' => 'center'));

        $cabecalho = array('');
        for ($indice = 1; $indice <= $maxSaidas; $indice++)
            $cabecalho = array_merge($cabecalho, array('VALOR', 'MOTIVO'));
        $cabecalho[] = '';
        $writer->writeSheetRow($sheet, $cabecalho, array('font-style' => 'bold', 'fill' => '#D9E2F3', 'halign' => 'center'));

        foreach ($dias as $dataDia => $dadosDia) {
            $linha = array(date('d/m/Y', strtotime($dataDia)));
            foreach ($dadosDia['saidas'] as $saida)
                $linha = array_merge($linha, array($saida['valor'], $saida['motivo']));
            $camposVazios = ($maxSaidas - count($dadosDia['saidas'])) * 2;
            if ($camposVazios > 0)
                $linha = array_merge($linha, array_fill(0, $camposVazios, ''));
            $linha[] = empty($dadosDia['saidas']) ? '' : $dadosDia['total'];
            $writer->writeSheetRow($sheet, $linha);
        }

        $nomeFicheiro = 'mapa-saidas-valores-' . $mes . '.xlsx';
        if ($this->writerExportacaoCombinada instanceof XLSXWriter)
            return;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nomeFicheiro . '"');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        $writer->writeToStdOut();
        exit;
    }

    public function exportarTodosMapas() {
        if (!$this->acessoAdminMapas())
            show_error('Sessão expirada ou sem permissão para exportar mapas.', 403);

        $mes = $this->input->get('mes', TRUE);
        if (!$this->mesMapaValido($mes))
            show_error('Mês inválido.', 400);
        require_once FCPATH . 'excel/xlsxwriter.class.php';
        $this->writerExportacaoCombinada = new XLSXWriter();
        $this->exportarFechoValor();
        $this->exportarMapaFecho();
        $this->exportarMapaSaidasValores();

        $conteudo = $this->writerExportacaoCombinada->writeToString();
        if (!is_string($conteudo) || $conteudo === '')
            show_error('Não foi possível gerar o ficheiro Excel com os três mapas.', 500);

        $meses = array(
            1 => 'Janeiro', 'Fevereiro', 'Março', 'Abril',
            'Maio', 'Junho', 'Julho', 'Agosto',
            'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        );
        $nomeFicheiro = 'Mapas-' . $meses[(int) substr($mes, 5, 2)] . '-' . substr($mes, 0, 4) . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nomeFicheiro . '"');
        header('Content-Length: ' . strlen($conteudo));
        header('Cache-Control: no-store, no-cache, must-revalidate');
        echo $conteudo;
        exit;
    }
}
