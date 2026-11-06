<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Configuracao
 *
 * @author Sampaio André
 */
class Padaria_m extends CI_Model {

    public function insertUpdateVenda() {
        $this->db->trans_start();
        $codU = $this->input->post('u');
        $produ = $this->db->query("SELECT p.preco,v.cod codV,v.qtd,v.valor FROM produto p LEFT JOIN venda_dia v ON v.cod_produto=p.cod AND v.data_venda=CURRENT_DATE WHERE p.cod=" . $this->db->escape($this->input->post('p')) . " AND p.eliminado='0' LIMIT 1");
        if ($codU && $produ->num_rows() > 0) {
            //VENDER
            if ($this->input->post('t') == 1) {
                if (empty($produ->row()->codV)) {
                    $this->db->insert('venda_dia', array('qtd' => $this->input->post('q'), 'valor' => ($produ->row()->preco * $this->input->post('q')), 'cod_produto' => $this->input->post('p'), 'cod_utilizador' => $this->input->post('u')));
                } else {
                    $this->db->update('venda_dia', array('qtd' => ($produ->row()->qtd + $this->input->post('q')), 'valor' => ($produ->row()->valor + ($produ->row()->preco * $this->input->post('q')))), 'cod=' . $produ->row()->codV);
                }
            } else {//ANUAR VENDA
                if (!empty($produ->row()->codV)) {
                    $v = ($produ->row()->valor - ($produ->row()->preco * $this->input->post('q')));
                    $q = ($produ->row()->qtd - $this->input->post('q'));
                    $this->db->update('venda_dia', array('qtd' => $q < 0 ? 0 : $q, 'valor' => $v < 0 ? 0 : $v), 'cod=' . $produ->row()->codV);
                } else
                    return -44;
            }
            $this->db->insert('venda_registo', array('qtd' => $this->input->post('q'), 'valor' => ($produ->row()->preco * $this->input->post('q')), 'estado' => $this->input->post('t'), 'cod_produto' => $this->input->post('p'), 'cod_utilizador' => $this->input->post('u')));
            $produ = $this->db->query("SELECT qtd,valor FROM venda_dia  WHERE cod_produto=" . $this->db->escape($this->input->post('p')) . " AND data_venda=CURRENT_DATE LIMIT 1");
            return array($this->db->trans_complete() ? 0 : -1, $produ->row());
        } else {
            return -43;
        }
    }

    public function getProdutosVender() {
        $query = $this->db->query("SELECT p.cod,p.codigo,p.nome,p.url_file,p.preco,v.qtd qtdV, v.valor valV FROM produto p JOIN categoria c ON c.cod=p.cod_categoria LEFT JOIN venda_dia v ON v.cod_produto=p.cod AND DATE(v.data_venda)=CURRENT_DATE WHERE p.eliminado='0' GROUP BY p.cod ORDER BY c.cod, p.nome;");
        return ($query->num_rows() > 0 ? $query->result_array() : NULL);
    }

    public function insertSaidaValor() {
        $dataHora = dataHoraBD();
        return $this->db->insert('saida', array(
            'saida' => 1,
            'eliminado' => '0',
            'data' => $dataHora,
            'data_cricao' => $dataHora,
            'motivo' => filterString($this->input->post('m')),
            'valor' => moedaBd($this->input->post('v')),
            'cod_utilizador' => $this->input->post('u')
        )) ? 0 : 1;
    }

    public function deleteSaidaValor($u) {
        return$this->db->update('saida', array('eliminado' => '1', 'cod_utilizador' => $u), 'cod=' . $this->input->post('cod')) ? 0 : 1;
    }

    public function getSaidasValores() {
        $query = $this->db->query("SELECT cod,motivo,valor,data_cricao FROM saida WHERE eliminado='0' AND saida=1 AND DATE(data)=CURRENT_DATE ORDER BY data DESC, motivo");
        return ($query->num_rows() > 0 ? $query->result_array() : NULL);
    }

    public function getSaidasValoresMes($dataInicio, $dataFim) {
        $query = $this->db->query("SELECT cod,DATE(data) data_saida,motivo,valor FROM saida WHERE eliminado='0' AND saida=1 AND data >= ? AND data < DATE_ADD(?, INTERVAL 1 DAY) ORDER BY data,cod", array($dataInicio, $dataFim));
        return ($query->num_rows() > 0 ? $query->result_array() : NULL);
    }

    public function editarSaidaValor($cod, $motivo, $valor, $codUtilizador) {
        return $this->db->update('saida', array(
            'motivo' => $motivo,
            'valor' => $valor,
            'cod_utilizador' => $codUtilizador
        ), array('cod' => $cod, 'eliminado' => '0', 'saida' => '1')) ? 0 : 1;
    }

    public function getFechosValorMes($dataInicio, $dataFim) {
        $query = $this->db->query("SELECT f.* FROM fecho_valor f WHERE f.data BETWEEN ? AND ? ORDER BY f.data", array($dataInicio, $dataFim));
        $fechos = array();
        foreach ($query->result_array() as $fecho)
            $fechos[$fecho['data']] = $fecho;
        return $fechos;
    }

    public function guardarFechoValorCelula($data, $campo, $valor, $codUtilizador, $editar = FALSE) {
        $camposPermitidos = array('moeda_10', 'moeda_20', 'moeda_50', 'moeda_100', 'moeda_200', 'dinheiro_200', 'dinheiro_500', 'dinheiro_1000', 'dinheiro_2000', 'dinheiro_5000', 'tpa', 'notas');
        if (!in_array($campo, $camposPermitidos, TRUE))
            return 3;

        $this->db->trans_begin();
        $existente = $this->db->query("SELECT campos_guardados FROM fecho_valor WHERE data=? FOR UPDATE", array($data))->row();
        if ($existente) {
            $camposGuardados = array_filter(explode(',', $existente->campos_guardados));
            if (in_array($campo, $camposGuardados, TRUE)) {
                if (!$editar) {
                    $this->db->trans_rollback();
                    return 4;
                }
                $gravado = $this->db->update('fecho_valor', array($campo => $valor, 'cod_utilizador' => $codUtilizador), array('data' => $data));
            } else {
                $camposGuardados[] = $campo;
                $gravado = $this->db->update('fecho_valor', array($campo => $valor, 'campos_guardados' => implode(',', $camposGuardados), 'cod_utilizador' => $codUtilizador), array('data' => $data));
            }
        } else {
            $gravado = $this->db->insert('fecho_valor', array('data' => $data, $campo => $valor, 'campos_guardados' => $campo, 'cod_utilizador' => $codUtilizador));
        }

        if (!$gravado || !$this->db->trans_status()) {
            $this->db->trans_rollback();
            return 1;
        }
        return $this->db->trans_commit() ? 0 : 1;
    }

    public function insertUpdateFecho($codUtilizador = NULL, $codProduto = NULL, $data = NULL, $quantidade = NULL) {
        $codU = ($codUtilizador === NULL ? $this->input->post('u') : $codUtilizador);
        $codProduto = ($codProduto === NULL ? $this->input->post('p') : $codProduto);
        $data = ($data === NULL ? $this->input->post('d') : $data);
        $quantidade = ($quantidade === NULL ? $this->input->post('q') : $quantidade);
        $this->db->trans_begin();
        $produ = $this->db->query("SELECT p.preco,v.cod codV,v.qtd,v.qtd_produzida,v.valor FROM produto p JOIN venda_dia v ON v.cod_produto=p.cod AND v.data_venda=" . $this->db->escape($data) . " WHERE p.cod=" . $this->db->escape($codProduto) . " LIMIT 1 FOR UPDATE");
        if (empty($codU) || $produ->num_rows() === 0) {
            $this->db->trans_rollback();
            return -43;
        }

        $venda = $produ->row();
        $restante = ($quantidade >= $venda->qtd ? $quantidade - $venda->qtd : 0);
        $atualizado = $this->db->update('venda_dia', array('qtd_produzida' => $quantidade, 'data_fecho' => dataHoraBD(), 'cod_utilizador_fecho' => $codU), array('cod' => $venda->codV));
        if (!$atualizado || !$this->db->trans_status()) {
            $this->db->trans_rollback();
            return -1;
        }
        if (!$this->db->trans_commit())
            return -1;
        return array(0, ($restante . ' - ' . moeda_akz($restante * $venda->preco)));
    }

    public function insertUpdateFechoAdmin($codUtilizador, $codProduto, $data, $quantidade) {
        $this->db->trans_begin();
        $produto = $this->db->query("SELECT preco FROM produto WHERE cod=? AND eliminado='0' LIMIT 1 FOR UPDATE", array($codProduto))->row();
        if (empty($codUtilizador) || !$produto) {
            $this->db->trans_rollback();
            return -43;
        }

        $venda = $this->db->query("SELECT cod,qtd FROM venda_dia WHERE cod_produto=? AND DATE(data_venda)=? ORDER BY cod DESC LIMIT 1 FOR UPDATE", array($codProduto, $data))->row();
        $restante = max((int) $quantidade - ($venda ? (int) $venda->qtd : 0), 0);
        $dadosFecho = array('qtd_produzida' => $quantidade, 'data_fecho' => dataHoraBD(), 'cod_utilizador_fecho' => $codUtilizador);
        if ($venda) {
            $gravado = $this->db->update('venda_dia', $dadosFecho, array('cod' => $venda->cod));
        } else {
            $gravado = $this->db->insert('venda_dia', array_merge($dadosFecho, array(
                'data_venda' => $data,
                'qtd' => 0,
                'valor' => 0,
                'cod_produto' => $codProduto,
                'cod_utilizador' => $codUtilizador
            )));
        }

        if (!$gravado || !$this->db->trans_status()) {
            $this->db->trans_rollback();
            return -1;
        }
        if (!$this->db->trans_commit())
            return -1;
        return array(0, ($restante . ' - ' . moeda_akz($restante * $produto->preco)));
    }

    public function getProdutosFecho($d) {
        $query = $this->db->query("SELECT p.cod,p.codigo,p.nome,p.url_file,p.preco,v.qtd qtdV, v.qtd_produzida qtdP, v.valor valV FROM produto p JOIN categoria c ON c.cod=p.cod_categoria JOIN venda_dia v ON v.cod_produto=p.cod AND DATE(v.data_venda)=" . $this->db->escape($d) . " GROUP BY p.cod ORDER BY c.cod, p.nome;");
        return ($query->num_rows() > 0 ? $query->result_array() : NULL);
    }

    public function getProdutosFechoAdmin($data) {
        $query = $this->db->query("SELECT p.cod,p.codigo,p.nome,p.url_file,p.preco,COALESCE(v.qtd,0) qtdV,COALESCE(v.qtd_produzida,0) qtdP,COALESCE(v.valor,0) valV FROM produto p LEFT JOIN categoria c ON c.cod=p.cod_categoria LEFT JOIN (SELECT cod_produto,MAX(qtd) qtd,MAX(qtd_produzida) qtd_produzida,MAX(valor) valor FROM venda_dia WHERE DATE(data_venda)=? GROUP BY cod_produto) v ON v.cod_produto=p.cod WHERE p.eliminado='0' ORDER BY c.cod,p.nome", array($data));
        return ($query->num_rows() > 0 ? $query->result_array() : NULL);
    }

    public function getMapaMensalFecho($dataInicio, $dataFim) {
        $query = $this->db->query("SELECT p.cod,p.nome,v.cod cod_venda,v.data_venda,v.qtd_produzida,v.qtd qtd_vendida,v.valor total_vendido,GREATEST(v.qtd_produzida-v.qtd,0) qtd_restante,pe.nome funcionario FROM produto p JOIN categoria c ON c.cod=p.cod_categoria LEFT JOIN venda_dia v ON v.cod_produto=p.cod AND v.data_venda BETWEEN ? AND ? LEFT JOIN utilizador u ON u.cod=v.cod_utilizador_fecho LEFT JOIN pessoa pe ON pe.cod=u.cod_pessoa WHERE p.eliminado='0' OR v.cod IS NOT NULL ORDER BY c.cod,p.nome,v.data_venda", array($dataInicio, $dataFim));
        return ($query->num_rows() > 0 ? $query->result_array() : NULL);
    }
}
