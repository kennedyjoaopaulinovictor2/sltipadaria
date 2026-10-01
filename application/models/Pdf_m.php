<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_m extends CI_Model {

    public function getCodRowTableEncryptor($tabela, $cod, $campo = 'cod', $whereAdd = '') {
        if (!empty($cod) && !empty($tabela)) {
            $codBd = encryptor($cod, 2);
            if ($codBd == 'T')
                return 'T';
            else {
                $query = $this->db->query("SELECT $campo as dado FROM $tabela WHERE $campo=" . $this->db->escape($codBd) . (!empty($whereAdd) ? " AND $whereAdd" : "") . " LIMIT 1");
                return ($query->num_rows() > 0 ? $query->row()->dado : '');
            }
        }
        return '';
    }

    public function getCodRowTable($tabela, $cod, $campo = 'cod', $whereAdd = '') {
        if (!empty($cod) && !empty($tabela)) {
            $query = $this->db->query("SELECT $campo as dado FROM $tabela WHERE sha1($campo)=" . $this->db->escape($cod) . (!empty($whereAdd) ? " AND $whereAdd" : "") . " LIMIT 1");
            if ($query->num_rows() > 0) {
                $row = $query->row();
                return $row->dado;
            }
        }
        return '';
    }
}
