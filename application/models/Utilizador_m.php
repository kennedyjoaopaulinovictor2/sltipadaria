<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Utilizador_m extends CI_Model {

    public function login($username) {
        $query = $this->db->query("SELECT u.cod, u.password, u.estado, m.local, m.acesso, u.cod_modulo FROM utilizador u JOIN modulo m ON m.cod=u.cod_modulo WHERE u.eliminado='0' AND SHA1(u.username)=SHA1(" . $this->db->escape($username) . ") LIMIT 1");
        return ($query->num_rows() > 0 ? $query->row() : NULL);
    }

    public function insertLogin($codU, $codM, $op = 1) {
        return ($this->db->insert('login', array('cod_utilizador' => $codU, 'cod_modulo' => $codM, 'operacao' => $op)) ? 1 : -1);
    }

    public function checkInTelefone($num, $cod = '') {
        $query = $this->db->query("SELECT cod FROM pessoa WHERE " . (!empty($cod) ? ("cod!=" . $cod . " AND ") : "") . $this->db->escape($num) . " IN(telefone)");
        return ($query->num_rows() > 0 ? 1 : 0);
    }

    public function checkSenhaAtual($senhaA) {
        $query = $this->db->query("SELECT password FROM utilizador WHERE cod=" . codUtilizador() . " LIMIT 1");
        return ((!empty($query->row()) && password_verify($senhaA, $query->row()->password)) ? 0 : 1);
    }

    public function getNomeUser($cod) {
        return (!empty(intval($cod)) ? $this->db->query("SELECT p.nome FROM utilizador u, pessoa p WHERE u.cod=$cod AND u.cod_pessoa=p.cod LIMIT 1")->row()->nome : '');
    }

    public function getModuloUser($cod) {
        return (!empty(intval($cod)) ? $this->db->query("SELECT m.local FROM utilizador u, modulo m WHERE u.cod=$cod AND u.cod_modulo=m.cod LIMIT 1")->row()->local : '');
    }

    public function getSenhaUser($cod) {
        return (!empty(intval($cod)) ? $this->db->query("SELECT password FROM utilizador WHERE cod=$cod LIMIT 1")->row()->password : '');
    }

    public function getAcessoModulo($local) {
        return (!empty($local) ? $this->db->query("SELECT acesso FROM modulo WHERE local='$local' LIMIT 1")->row()->acesso : '');
    }

    public function getDadoUtilizador($cod, $column) {
        if (!empty(intval($cod)) && !empty($column)) {
            $query = $this->db->query("SELECT $column FROM utilizador u, pessoa p, modulo m WHERE u.cod=$cod AND u.cod_pessoa=p.cod AND u.cod_modulo=m.cod LIMIT 1");
            return ($query->num_rows() > 0 ? $query->row() : NULL);
        }
        return NULL;
    }

    public function setSenha($cod, $senhaA, $senhaN) {
        if (!empty(intval($cod)) && !empty($senhaA) && !empty($senhaN)) {
            $query = $this->db->query("SELECT password FROM utilizador WHERE cod=$cod LIMIT 1");
            if (!empty($query->row()) && encryptor($senhaN) == $query->row()->password)
                return 8;
            if (!empty($query->row()) && encryptor($senhaA) == $query->row()->password)
                return $this->db->query($this->db->update_string('utilizador', array('password' => encryptor($senhaN)), "cod=$cod")) ? 0 : 1;
        }
        return 6;
    }

    public function getPessoas($column) {
        $query = $this->db->query("SELECT $column FROM pessoa p JOIN utilizador u ON u.cod_pessoa=p.cod WHERE u.cod_modulo!=2 AND u.cod!=1 ORDER BY p.nome");
        return ($query->num_rows() > 0 ? $query->result() : NULL);
    }

    /* $codsNotIn = 1,2,3 or 1 */

    public function getModulos($column, $codsNotIn = '') {
        $query = $this->db->query("SELECT $column FROM modulo" . (!empty($codsNotIn) ? " WHERE cod NOT IN($codsNotIn)" : "") . " ORDER BY FIELD(cod,3,4,5,6,2,7,8)");
        return ($query->num_rows() > 0 ? $query->result() : NULL);
    }

    public function getUtilizadores($m, $e) {
        $query = $this->db->query("SELECT u.cod, p.nome, p.telefone, u.estado, u.username, u.tipo, mo.nome modulo, pr.nome provincia, CONCAT(pm.nome,' ',m.nome) municipio FROM utilizador u JOIN modulo mo ON mo.cod=u.cod_modulo JOIN pessoa p ON p.cod=u.cod_pessoa LEFT JOIN app_cidade pr ON pr.cod=u.cod_provincia LEFT JOIN app_cidade m ON m.cod=u.cod_municipio LEFT JOIN app_cidade pm ON pm.cod=m.cod_provincia WHERE u.eliminado='0' AND u.cod!='1' AND u.cod_modulo=$m AND u.estado=$e ORDER BY p.nome");
        return ($query->num_rows() > 0 ? $query->result_array() : NULL);
    }

    public function getPessoa($column, $cod) {
        return $this->db->query("SELECT $column FROM pessoa WHERE cod=$cod LIMIT 1")->row();
    }

    public function getMunicipiosUtilizador($p) {
        $query = $this->db->query("SELECT cod,nome FROM app_cidade WHERE cod_provincia=$p AND tipo=3 ORDER BY nome");
        return ($query->num_rows() > 0 ? $query->result() : NULL);
    }

    public function checkInsertUpdateUtilizador($cod = '', $telefone = '', $email = '', $username = '', $codP = '', $codM = '') {
        $user = $this->db->query("SELECT u.cod,p.email,p.telefone,u.username,cod_pessoa,cod_modulo FROM utilizador u JOIN pessoa p ON p.cod=u.cod_pessoa WHERE" . (!empty($cod) ? " u.cod!=$cod AND" : "") . " (" . ("p.telefone=" . $this->db->escape($telefone)) . (!empty($email) ? " OR p.email=" . $this->db->escape($email) : "") . (!empty($username) ? " OR u.username=" . $this->db->escape($username) : "") . (!empty($codP) ? " OR (cod_pessoa=$codP AND cod_modulo=$codM)" : "") . ") LIMIT 1");
        if ($user->num_rows() > 0)
            return ((!empty($username) && $user->username == $username) ? -5 : (!empty($telefone) && $user->telefone == $telefone ? -6 : (!empty($email) && $user->email == $email ? -7 : ((!empty($codP) && !empty($codM) && $user->cod_pessoa == $codP && $user->cod_modulo == $codM) ? -8 : 1))));
        return 1;
    }

    public function insertUtilizador() {
        $r = $this->checkInsertUpdateUtilizador('', filterString($this->input->post('telefone')), (!empty($this->input->post('email')) ? filterString($this->input->post('email')) : ''), (!empty($this->input->post('username')) ? filterString($this->input->post('username')) : ''));
        if ($r == 1) {
            $this->db->trans_start();
            $pessoa = array('nome' => filterString($this->input->post('nome')), 'telefone' => $this->input->post('telefone'), 'email' => (!empty($this->input->post('email')) ? $this->input->post('email') : NULL));
            ($this->input->post('pessoa') ? $this->db->update('pessoa', $pessoa, "cod=" . $this->input->post('pessoa')) : $this->db->insert('pessoa', $pessoa));
            $codP = ($this->input->post('pessoa') ? $this->input->post('pessoa') : $this->db->insert_id());
            $username = (!empty($this->input->post('username')) ? filterString($this->input->post('username')) : NULL);
            if ($codP) {
                $senha = ($this->input->post('smsUser') == 2 ? rand_string(8) : '12345678');
                $this->db->insert('utilizador', array('username' => $username, 'password' => encryptor($senha), 'tipo' => $this->input->post('tipoUser'), 'cod_pessoa' => $codP, 'cod_modulo' => $this->input->post('modulo'), 'cod_provincia' => (!empty($this->input->post('provUti')) ? $this->input->post('provUti') : NULL), 'cod_municipio' => (!empty($this->input->post('muniUti')) ? $this->input->post('muniUti') : NULL)));
                if (empty($username)) {
                    $username = (strlen((string) $this->db->insert_id()) >= 8 ? $this->db->insert_id() : (rand_number(8 - strlen((string) $this->db->insert_id())) . '' . $this->db->insert_id()));
                    $this->db->update('utilizador', array('username' => $username), 'cod=' . $this->db->insert_id());
                }
                if ($this->input->post('smsUser') == 2)
                    enviarSMS(filterString($this->input->post('telefone')), strReplaceTextSMS(buscaSmsTipo(1), array('$login_Utilizador: ' . $username . '\nSenha: ' . $senha)), '1', codUtilizador());
            }
            $r = ($this->db->trans_complete() ? array($username, $this->input->post('pessoa') ? '' : $codP) : 0);
        }
        return $r;
    }

    public function getUtilizador($column, $cod) {
        $query = $this->db->query("SELECT $column FROM utilizador u, pessoa p, modulo m WHERE u.cod=$cod AND u.eliminado='0' AND u.cod_pessoa=p.cod AND u.cod_modulo=m.cod LIMIT 1");
        return ($query->num_rows() > 0 ? $query->row() : NULL);
    }

    public function updateUtilizador() {
        $r = $this->checkInsertUpdateUtilizador($this->input->post('codUtilizador'), filterString($this->input->post('telefone')), (!empty($this->input->post('email')) ? filterString($this->input->post('email')) : ''), (!empty($this->input->post('username')) ? filterString($this->input->post('username')) : ''));
        if ($r == 1) {
            $this->db->update('pessoa', array('nome' => filterString($this->input->post('nome')), 'telefone' => $this->input->post('telefone'), 'email' => (!empty($this->input->post('email')) ? $this->input->post('email') : NULL)), "cod=" . $this->input->post('codPEdit'));
            $this->db->update('utilizador', array('username' => $this->input->post('username'), 'tipo' => $this->input->post('tipoUser'), 'cod_modulo' => $this->input->post('modulo'), 'cod_provincia' => (!empty($this->input->post('provUti')) ? $this->input->post('provUti') : NULL), 'cod_municipio' => (!empty($this->input->post('muniUti')) ? $this->input->post('muniUti') : NULL)), "cod=" . $this->input->post('codUtilizador'));
            $r = 0;
        }
        return $r;
    }

    public function deleteUtilizador($cod) {
        return $this->db->update('utilizador', array('eliminado' => '1', 'estado' => '2'), "cod=" . $cod) ? 1 : 0;
    }

    public function updateEstadoUtilizador($cod, $estado) {
        return $this->db->update('utilizador', array('estado' => ($estado == '1' ? '2' : '1')), "cod=" . $cod) ? 1 : 0;
    }

    public function updateSenhaUtilizador($cod, $sen) {
        $user = $this->db->query("SELECT u.cod,u.username,p.telefone FROM utilizador u JOIN pessoa p ON p.cod=u.cod_pessoa WHERE u.cod=$cod LIMIT 1");
        if ($user->num_rows() > 0) {
            $senha = ($sen == 2 ? rand_string(8) : '12345678');
            $r = $this->db->update('utilizador', array('password' => encryptor($senha)), "cod=" . $user->row()->cod) ? 1 : 0;
            if ($sen == 2)
                enviarSMS($user->row()->telefone, strReplaceTextSMS(buscaSmsTipo(1), array('$login_utilizador: ' . $user->row()->username . '\nsenha: ' . $senha)), '1', codUtilizador());
            return ($r == 1 ? 0 : -1);
        }
        return -4;
    }

    public function updatePerfilEscola() {
//        return $this->db->query("UPDATE utilizador u JOIN pessoa p ON p.cod=u.cod_pessoa SET p.nome=" . $this->db->escape(filterString($this->input->post('nome'))) . ",p.telefone=" . $this->db->escape(filterString($this->input->post('telefone'))) . ",u.tipo=" . intval($this->input->post('cargo')) . ", u.password=" . $this->db->escape(password_hash(filterString($this->input->post('passwordN')), PASSWORD_DEFAULT)) . " WHERE u.cod=" . codUtilizador()) ? 0 : 1;
        return $this->db->query("UPDATE utilizador u JOIN pessoa p ON p.cod=u.cod_pessoa SET p.nome=" . $this->db->escape(filterString($this->input->post('nome'))) . ",p.telefone=" . $this->db->escape(filterString($this->input->post('telefone'))) . ",u.tipo=" . intval($this->input->post('cargo')) . " WHERE u.cod=" . codUtilizador()) ? 0 : 1;
    }
}
