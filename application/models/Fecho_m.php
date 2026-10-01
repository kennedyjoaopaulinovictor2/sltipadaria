<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class Fecho_m extends CI_Model {

    public function getPlanos($columns = '', $codClasse = '') {
        $query = $this->db->query("SELECT " . ($columns ? $columns : 'p.cod,p.opcao,p.estado,p.cod_curso,c.nome nomeCurso') . " FROM plano p" . (!empty($codClasse) ? " JOIN curricular a ON a.cod_plano=p.cod AND a.cod_classe=$codClasse AND a.eliminado='0'" : "") . " LEFT JOIN curso c ON c.cod=p.cod_curso WHERE p.eliminado='0' GROUP BY p.cod ORDER BY p.opcao");
        return ($query->num_rows() > 0 ? $query->result() : NULL);
    }

    public function getCurriculares($p, $c) {
        $query = $this->db->query("SELECT c.cod,c.codigo,d.nome,c.estado,c.classificacao FROM curricular c JOIN disciplina d ON d.cod=c.cod_disciplina WHERE c.eliminado='0' AND c.cod_plano=$p AND c.cod_classe=$c ORDER BY c.codigo");
        return ($query->num_rows() > 0 ? $query->result_array() : NULL);
    }

    public function insertCurriculo() {
        $r = $this->configuracao_m->checkValorExistTable('curricular', ("(codigo=" . $this->db->escape(filterString($this->input->post('codigoCur'))) . " OR (cod_disciplina=" . $this->db->escape($this->input->post('discCur')) . " AND cod_classe=" . $this->db->escape($this->input->post('classeCur')) . " AND cod_plano=" . $this->db->escape($this->input->post('planoCur')) . ")) AND eliminado='0'"));
        if ($r == 1)
            $r = ($this->db->insert('curricular', array('codigo' => filterString($this->input->post('codigoCur')), 'classificacao' => $this->input->post('notaCur'), 'cod_disciplina' => $this->input->post('discCur'), 'cod_classe' => $this->input->post('classeCur'), 'cod_plano' => $this->input->post('planoCur'))) ? 0 : -1);
        return $r;
    }

    public function getCurriculo($cod) {
        return $this->db->query("SELECT cod,codigo,classificacao,cod_disciplina,cod_classe,cod_plano FROM curricular WHERE cod=$cod AND eliminado='0' LIMIT 1")->row();
    }

    public function updateCurriculo() {
        $r = $this->configuracao_m->checkValorExistTable('curricular', ("(codigo=" . $this->db->escape(filterString($this->input->post('codigoCur'))) . " OR (cod_disciplina=" . $this->db->escape($this->input->post('discCur')) . " AND cod_classe=" . $this->db->escape($this->input->post('classeCur')) . " AND cod_plano=" . $this->db->escape($this->input->post('planoCur')) . ")) AND eliminado='0' AND cod!=" . $this->input->post('codCur')));
        if ($r == 1)
            $r = ($this->db->update('curricular', array('codigo' => filterString($this->input->post('codigoCur')), 'classificacao' => $this->input->post('notaCur'), 'cod_disciplina' => $this->input->post('discCur'), 'cod_classe' => $this->input->post('classeCur'), 'cod_plano' => $this->input->post('planoCur')), "cod=" . $this->input->post('codCur')) ? 0 : -1);
        return $r;
    }

    public function deleteCurriculo($cod) {
        return $this->db->update('curricular', array('eliminado' => '1', 'estado' => '2'), "cod=" . $cod) ? 1 : 0;
    }
}
