<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class Produto_m extends CI_Model {

    public function getCategorias() {
        $query = $this->db->select('cod,nome')
                ->from('categoria')
                ->where('eliminado', 0)
                ->order_by('nome', 'ASC')
                ->get();
        return ($query->num_rows() > 0 ? $query->result() : NULL);
    }

    public function getProdutos($estado = 'all', $codCategoria = '') {
        $this->db->select('p.cod,p.codigo,p.nome,p.url_file,p.preco,p.eliminado,c.nome AS categoria')
                ->from('produto p')
                ->join('categoria c', 'c.cod=p.cod_categoria', 'left')
                ->order_by('c.nome', 'ASC')
                ->order_by('p.nome', 'ASC');

        if ($estado === '0' || $estado === '1') {
            $this->db->where('p.eliminado', (int) $estado);
        }
        if (!empty($codCategoria)) {
            $this->db->where('p.cod_categoria', (int) $codCategoria);
        }

        $query = $this->db->get();
        return ($query->num_rows() > 0 ? $query->result_array() : NULL);
    }

    public function getProduto($cod) {
        $query = $this->db->select('cod,codigo,nome,url_file,preco,cod_categoria,eliminado')
                ->from('produto')
                ->where('cod', (int) $cod)
                ->limit(1)
                ->get();
        return ($query->num_rows() > 0 ? $query->row_array() : NULL);
    }

    public function salvarProduto($dados, $cod = '') {
        if (!empty($cod) && empty($this->getProduto($cod))) {
            return -4;
        }

        $categoria = $this->db->select('cod')
                ->from('categoria')
                ->where('cod', (int) $dados['cod_categoria'])
                ->where('eliminado', 0)
                ->limit(1)
                ->get();
        if ($categoria->num_rows() === 0) {
            return -5;
        }

        if ($dados['codigo'] !== '') {
            $this->db->from('produto')
                    ->where('codigo', $dados['codigo']);
            if (!empty($cod)) {
                $this->db->where('cod !=', (int) $cod);
            }
            if ($this->db->count_all_results() > 0) {
                return -3;
            }
        }

        if (!empty($cod)) {
            unset($dados['cod_utilizador']);
            return $this->db->where('cod', (int) $cod)->update('produto', $dados) ? 0 : -1;
        }

        $dados['url_file'] = (!empty($dados['url_file']) ? $dados['url_file'] : IMG_NOT_IMG);
        $dados['eliminado'] = 0;
        return $this->db->insert('produto', $dados) ? 0 : -1;
    }

    public function alterarEstado($cod, $estado) {
        if (empty($this->getProduto($cod))) {
            return -4;
        }
        return $this->db->where('cod', (int) $cod)
                        ->update('produto', array('eliminado' => (int) $estado)) ? 0 : -1;
    }
}
