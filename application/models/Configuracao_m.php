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
class Configuracao_m extends CI_Model {

    public function getTextSmsByTipo($tipo) {
        $query = $this->db->query(
                "SELECT texto FROM sms_texto WHERE tipo=? ORDER BY cod DESC LIMIT 1",
                array($tipo)
        );
        return ($query->num_rows() > 0 ? $query->row()->texto : NULL);
    }

    public function insertSmsEnviada($texto, $telefone, $tipoSms, $retorno, $codE = NULL, $anoL = NULL) {
        return $this->db->insert('sms_enviada', array(
            'ano_lectivo' => (!empty($anoL) ? $anoL : NULL),
            'texto' => $texto,
            'telefone' => $telefone,
            'tipo_sms' => $tipoSms,
            'retorno_app' => substr((string) $retorno, 0, 255),
            'cod_entidade' => (!empty($codE) ? $codE : NULL),
            'cod_utilizador' => (!empty(codUtilizador()) ? codUtilizador() : NULL)
        ));
    }

    public function getCountTabela($tabela, $where = '', $fun = "COUNT(*)", $alis = '') {
        $query = $this->db->query("SELECT " . $fun . ($alis ? ' as ' . $alis : ' as contSp') . " FROM $tabela " . (!empty($where) ? "WHERE $where " : "") . "LIMIT 1");
        if ($query->num_rows() > 0) {
            return $query->row()->contSp;
        }
        return 0;
    }

    public function countColunasTabela($tb) {
        return count($this->db->list_fields($tb));
    }

    public function getDataHoraSql() {
        return $this->db->query("SELECT CURRENT_TIMESTAMP as dataHora LIMIT 1")->row()->dataHora;
    }

    public function getRandSenhaSql() {
        return $this->db->query("SELECT SUBSTRING(MD5(RAND()) FROM 1 FOR 8) senha LIMIT 1")->row()->senha;
    }

    function execQueryGetDataArray($query) {
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
    }

    public function getDadosTabela($tabela, $columns, $where = '', $order = '') {
        if (!empty($columns)) {
            $query = $this->db->query("SELECT $columns FROM $tabela " . ($where ? "WHERE $where" : "") . ($order ? " ORDER BY $order" : ""));
            if ($query->num_rows() > 0)
                return $query->result();
        }
        return NULL;
    }

    public function checkValorExistTable($table, $where, $cod = '') {
        return ($this->db->query("SELECT * FROM $table WHERE " . (!empty($cod) ? "cod!=$cod AND " : "") . $where)->num_rows() > 0 ? -5 : 1);
    }

    public function checkTableExistByName($table, $nome, $cod = '', $where = '') {
        return ($this->db->query("SELECT * FROM $table WHERE " . (!empty($cod) ? " cod!=$cod AND " : "") . (empty($where) ? (" eliminado='0' AND nome=" . $this->db->escape($nome)) : $where))->num_rows() > 0) ? -5 : 1;
    }

}
