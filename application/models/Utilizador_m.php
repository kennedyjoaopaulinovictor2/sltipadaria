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
        $sql = "SELECT cod FROM pessoa WHERE telefone=?";
        $params = array($num);
        if (!empty($cod)) {
            $sql .= " AND cod<>?";
            $params[] = $cod;
        }
        $query = $this->db->query($sql, $params);
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
        $query = $this->db->query("SELECT DISTINCT $column FROM pessoa p ORDER BY p.nome");
        return ($query->num_rows() > 0 ? $query->result() : NULL);
    }

    /* $codsNotIn = 1,2,3 or 1 */

    public function getModulos($column, $codsNotIn = '') {
        $query = $this->db->query("SELECT $column FROM modulo" . (!empty($codsNotIn) ? " WHERE cod NOT IN($codsNotIn)" : "") . " ORDER BY FIELD(cod,3,4,5,6,2,7,8)");
        return ($query->num_rows() > 0 ? $query->result() : NULL);
    }

    public function getUtilizadores($m, $e) {
        $sql = "SELECT u.cod, p.nome, p.telefone, u.estado, u.username, u.tipo, mo.nome modulo FROM utilizador u JOIN modulo mo ON mo.cod=u.cod_modulo JOIN pessoa p ON p.cod=u.cod_pessoa WHERE u.eliminado='0' AND u.cod_modulo=?";
        $params = array($m);
        if ($e !== 'all') {
            $sql .= " AND u.estado=?";
            $params[] = $e;
        }
        $query = $this->db->query($sql . " ORDER BY p.nome", $params);
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
        if (!empty($username)) {
            $usernameQuery = $this->db->query(
                    "SELECT cod FROM utilizador WHERE username=? " . (!empty($cod) ? "AND cod<>? " : "") . "LIMIT 1",
                    (!empty($cod) ? array($username, $cod) : array($username))
            );
            if ($usernameQuery->num_rows() > 0)
                return -5;
        }

        $checks = array();
        $params = array();
        if (!empty($telefone)) {
            $checks[] = 'p.telefone=?';
            $params[] = $telefone;
        }
        if (!empty($email)) {
            $checks[] = 'p.email=?';
            $params[] = $email;
        }
        if (!empty($codP) && !empty($codM)) {
            $checks[] = '(u.cod_pessoa=? AND u.cod_modulo=?)';
            $params[] = $codP;
            $params[] = $codM;
        }
        if (empty($checks))
            return 1;

        $where = (!empty($cod) ? 'u.cod<>? AND ' : '') . '(' . implode(' OR ', $checks) . ')';
        if (!empty($cod))
            array_unshift($params, $cod);
        $query = $this->db->query("SELECT u.cod,p.email,p.telefone,u.username,u.cod_pessoa,u.cod_modulo FROM utilizador u JOIN pessoa p ON p.cod=u.cod_pessoa WHERE u.eliminado='0' AND $where", $params);
        foreach ($query->result() as $user) {
            if (!empty($codP) && !empty($codM) && $user->cod_pessoa == $codP && $user->cod_modulo == $codM)
                return -8;
            if (!empty($telefone) && $user->telefone == $telefone && (empty($codP) || $user->cod_pessoa != $codP))
                return -6;
            if (!empty($email) && $user->email == $email && (empty($codP) || $user->cod_pessoa != $codP))
                return -7;
        }
        return 1;
    }

    public function insertUtilizador() {
        $pessoaSelecionada = $this->input->post('pessoa');
        $modulo = $this->input->post('modulo');
        $telefone = trim((string) $this->input->post('telefone'));
        $email = trim((string) $this->input->post('email'));
        $username = trim((string) $this->input->post('username'));
        if (!$this->db->where('cod', $modulo)->count_all_results('modulo'))
            return -9;
        if (!empty($pessoaSelecionada) && !$this->db->where('cod', $pessoaSelecionada)->count_all_results('pessoa'))
            return -10;
        if ($telefone !== '' && $this->checkInTelefone($telefone, $pessoaSelecionada) !== 0)
            return -6;
        $r = $this->checkInsertUpdateUtilizador('', filterString($telefone), ($email !== '' ? filterString($email) : ''), ($username !== '' ? filterString($username) : ''), $pessoaSelecionada, $modulo);
        if ($r != 1)
            return $r;

        $this->db->trans_begin();
        $pessoa = array('nome' => filterString($this->input->post('nome')), 'telefone' => ($telefone !== '' ? $telefone : NULL), 'email' => ($email !== '' ? $email : NULL));
        if ($pessoaSelecionada) {
            $pessoaGuardada = $this->db->update('pessoa', $pessoa, array('cod' => $pessoaSelecionada));
            $codP = $pessoaSelecionada;
        } else {
            $pessoaGuardada = $this->db->insert('pessoa', $pessoa);
            $codP = $this->db->insert_id();
        }
        if (!$pessoaGuardada || !$codP || !$this->db->trans_status()) {
            $this->db->trans_rollback();
            return 0;
        }

        $senha = ($this->input->post('smsUser') == 2 ? rand_string(8) : '12345678');
        $username = ($username !== '' ? filterString($username) : NULL);
        $utilizadorGuardado = $this->db->insert('utilizador', array(
            'username' => $username,
            'password' => encryptor($senha),
            'cod_pessoa' => $codP,
            'cod_modulo' => $modulo
        ));
        if (!$utilizadorGuardado || !$this->db->trans_status()) {
            $this->db->trans_rollback();
            return -5;
        }

        $codUtilizadorNovo = $this->db->insert_id();
        if (empty($username)) {
            $username = 'u' . str_pad((string) $codUtilizadorNovo, 7, '0', STR_PAD_LEFT);
            if (!$this->db->update('utilizador', array('username' => $username), array('cod' => $codUtilizadorNovo))
                    || !$this->db->trans_status()) {
                $this->db->trans_rollback();
                return -5;
            }
        }

        if ($this->input->post('smsUser') == 2) {
            $smsTexto = $this->configuracao_m->getTextSmsByTipo(2);
            if (empty($smsTexto) || strpos($smsTexto, '$user') === false || strpos($smsTexto, '$pass') === false) {
                $this->db->trans_rollback();
                return -12;
            }
            $smsTexto = str_replace(array('$user', '$pass'), array((string) $username, $senha), $smsTexto);
            $sms = enviarSMS($telefone, $smsTexto, '1', NULL);
            if (empty($sms['success'])) {
                $this->db->trans_rollback();
                $this->configuracao_m->insertSmsEnviada($smsTexto, $telefone, '1', $sms['code'], NULL);
                log_message('error', 'Criação de utilizador: TelcoSMS rejeitou ou não confirmou o envio (código ' . $sms['code'] . ').');
                return -11;
            }
        }

        if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            return 0;
        }
        if (!$this->db->trans_commit())
            return 0;
        return array($username, ($pessoaSelecionada ? '' : $codP));
    }

    public function getUtilizador($column, $cod) {
        $query = $this->db->query("SELECT $column FROM utilizador u, pessoa p, modulo m WHERE u.cod=$cod AND u.eliminado='0' AND u.cod_pessoa=p.cod AND u.cod_modulo=m.cod LIMIT 1");
        return ($query->num_rows() > 0 ? $query->row() : NULL);
    }

    public function updateUtilizador() {
        $codUtilizador = $this->input->post('codUtilizador');
        $codPessoa = $this->input->post('codPEdit');
        $modulo = $this->input->post('modulo');
        $telefone = trim((string) $this->input->post('telefone'));
        $email = trim((string) $this->input->post('email'));
        $username = trim((string) $this->input->post('username'));
        if (!$this->db->where('cod', $modulo)->count_all_results('modulo')
                || !$this->db->where('cod', $codPessoa)->count_all_results('pessoa'))
            return -10;
        if ($telefone !== '' && $this->checkInTelefone($telefone, $codPessoa) !== 0)
            return -6;
        $r = $this->checkInsertUpdateUtilizador($codUtilizador, filterString($telefone), ($email !== '' ? filterString($email) : ''), ($username !== '' ? filterString($username) : ''), $codPessoa, $modulo);
        if ($r == 1) {
            $this->db->trans_begin();
            $pessoaAtualizada = $this->db->update('pessoa', array('nome' => filterString($this->input->post('nome')), 'telefone' => ($telefone !== '' ? $telefone : NULL), 'email' => ($email !== '' ? $email : NULL)), array('cod' => $codPessoa));
            $utilizadorAtualizado = $this->db->update('utilizador', array('username' => ($username !== '' ? filterString($username) : NULL), 'cod_modulo' => $modulo, 'cod_provincia' => (!empty($this->input->post('provUti')) ? $this->input->post('provUti') : NULL), 'cod_municipio' => (!empty($this->input->post('muniUti')) ? $this->input->post('muniUti') : NULL)), array('cod' => $codUtilizador));
            $r = ($pessoaAtualizada && $utilizadorAtualizado && $this->db->trans_status() ? ($this->db->trans_commit() ? 0 : 1) : 1);
            if ($r !== 0)
                $this->db->trans_rollback();
        }
        return $r;
    }

    public function deleteUtilizador($cod) {
        return $this->db->update('utilizador', array('eliminado' => '1', 'estado' => '2'), array('cod' => $cod, 'cod !=' => '1')) ? 1 : 0;
    }

    public function updateEstadoUtilizador($cod, $estado) {
        return $this->db->update('utilizador', array('estado' => ($estado == '1' ? '2' : '1')), array('cod' => $cod, 'cod !=' => '1')) ? 1 : 0;
    }

    public function updateSenhaUtilizador($cod, $sen) {
        $user = $this->db->query("SELECT u.cod,u.username,u.password,p.telefone FROM utilizador u JOIN pessoa p ON p.cod=u.cod_pessoa WHERE u.cod=? AND u.cod!='1' AND u.eliminado='0' LIMIT 1", array($cod));
        if ($user->num_rows() > 0) {
            $senha = ($sen == 2 ? rand_string(8) : '12345678');
            $smsTexto = NULL;
            if ($sen == 2) {
                if (empty($user->row()->telefone))
                    return -6;
                $smsTexto = $this->configuracao_m->getTextSmsByTipo(2);
                if (empty($smsTexto) || strpos($smsTexto, '$user') === false || strpos($smsTexto, '$pass') === false)
                    return -7;
                $smsTexto = str_replace(
                        array('$user', '$pass'),
                        array((string) $user->row()->username, $senha),
                        $smsTexto
                );
            }

            $this->db->trans_begin();
            if (!$this->db->update('utilizador', array('password' => encryptor($senha)), array('cod' => $user->row()->cod))
                    || !$this->db->trans_status()) {
                $this->db->trans_rollback();
                return -1;
            }
            if ($sen == 2) {
                $sms = enviarSMS($user->row()->telefone, $smsTexto, '1', NULL);
                if (empty($sms['success'])) {
                    $restored = $this->db->update('utilizador', array('password' => $user->row()->password), array('cod' => $user->row()->cod));
                    if (!$restored || !$this->db->trans_status()) {
                        $this->db->trans_rollback();
                        return -1;
                    }
                    $this->db->trans_commit();
                    log_message('error', 'Reposição de senha: TelcoSMS rejeitou ou não confirmou o envio (código ' . $sms['code'] . ').');
                    return -5;
                }
            }
            if (!$this->db->trans_status()) {
                $this->db->trans_rollback();
                return -1;
            }
            return ($this->db->trans_commit() ? 0 : -1);
        }
        return -4;
    }

    public function updatePerfilEscola() {
//        return $this->db->query("UPDATE utilizador u JOIN pessoa p ON p.cod=u.cod_pessoa SET p.nome=" . $this->db->escape(filterString($this->input->post('nome'))) . ",p.telefone=" . $this->db->escape(filterString($this->input->post('telefone'))) . ",u.tipo=" . intval($this->input->post('cargo')) . ", u.password=" . $this->db->escape(password_hash(filterString($this->input->post('passwordN')), PASSWORD_DEFAULT)) . " WHERE u.cod=" . codUtilizador()) ? 0 : 1;
        return $this->db->query("UPDATE utilizador u JOIN pessoa p ON p.cod=u.cod_pessoa SET p.nome=" . $this->db->escape(filterString($this->input->post('nome'))) . ",p.telefone=" . $this->db->escape(filterString($this->input->post('telefone'))) . ",u.tipo=" . intval($this->input->post('cargo')) . " WHERE u.cod=" . codUtilizador()) ? 0 : 1;
    }
}
