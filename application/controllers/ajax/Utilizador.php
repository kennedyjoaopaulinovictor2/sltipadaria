<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Utilizador extends CI_Controller {

    public function __construct() {
        parent::__construct();
        includeModals(array('utilizador', 'configuracao'));
    }

    private function autorizadoGestaoUtilizadores() {
        return !empty(codUtilizador())
                && userSession() === (APP_ID_NOME . codUtilizador())
                && userModulo() === 'ADMINISTRADOR';
    }

    public function efetuarLogin() {
        if (!empty($this->input->post('username')) && !empty($this->input->post('password'))) {
            $u = $this->utilizador_m->login($this->input->post('username'), $this->input->post('password'));
            if (!empty($u)) {
                if ($u->estado === '1') {
                    if (encryptor($this->input->post('password')) == $u->password) {
                        $this->session->unset_userdata(array(SESSAO_ID, USER_COD, USER_LOCAL, USER_ACESSO));
                        $arrayUserdata = array(SESSAO_ID => (APP_ID_NOME . $u->cod), USER_COD => $u->cod, USER_LOCAL => $u->local, USER_ACESSO => $u->acesso);
                        $this->session->set_userdata($arrayUserdata);
                        $this->utilizador_m->insertLogin($u->cod, $u->cod_modulo);
                        retornAjax(array('r' => 0, 'local' => (base_url() . $u->acesso)));
                    } else {
                        retornAjax(array('r' => -6));
                    }
                } else {
                    retornAjax(array('r' => -5));
                }
            }
            retornAjax(array('r' => -4));
        } else {
            retornAjax(array('r' => -2));
        }
    }

    public function editarSenha() {
        $r['r'] = 4;
        if (!empty($this->input->post('passwordA')) && !empty($this->input->post('passwordN')) && !empty($this->input->post('passwordC'))) {
            if ($this->input->post('passwordN') == $this->input->post('passwordC'))
                $r['r'] = $this->utilizador_m->setSenha(codUtilizador(), $this->input->post('passwordA'), $this->input->post('passwordN'));
            else
                $r['r'] = 5;
        } else
            $r['r'] = 3;
        retornAjax($r);
    }

    public function validaTelefone() {
        if (!$this->autorizadoGestaoUtilizadores())
            retornAjax(-21);
        if (!empty($this->input->post('dado'))) {
            retornAjax($this->utilizador_m->checkInTelefone($this->input->post('dado'), $this->input->post('cod')));
        } else {
            retornAjax(2);
        }
    }

    public function validaAtualSenha() {
        if (!empty($this->input->post('dado')) && !empty(codUtilizador())) {
            retornAjax($this->utilizador_m->checkSenhaAtual($this->input->post('dado')));
        } else {
            retornAjax(empty(codUtilizador()) ? -21 : 2);
        }
    }

    public function buscarPessoa() {
        if (!$this->autorizadoGestaoUtilizadores())
            retornAjax(4);
        if ($this->input->post('cod')) {
            echo retornAjax($this->utilizador_m->getPessoa('nome,telefone,email', $this->input->post('cod')));
        } else {
            echo retornAjax(4);
        }
    }

    public function listarUtilizador() {
        if (!$this->autorizadoGestaoUtilizadores())
            retornAjax(array('r' => -21));
        $modulo = $this->input->post('m', TRUE);
        $estado = $this->input->post('e', TRUE);
        if (is_numeric($modulo) && in_array($estado, array('all', '1', '2'), TRUE)) {
            $utilizadores = $this->utilizador_m->getUtilizadores($modulo, $estado);
            if ($utilizadores) {
                $lista = array();
                $c = 0;
                $optionsSenha = optionSelect(array('#', '1_Repor Senha Padrão', '2_Repor Senha e Enviar'));
                foreach ($utilizadores as $utilizador) {
                    $aler = '<span id="e' . $c . '" onclick="updateEstadoUtilizador(\'' . $utilizador['cod'] . '\',\'e' . $c . '\',\'' . $utilizador['estado'] . '\',\'' . $utilizador['nome'] . '\');" style="cursor: pointer;"';
                    $estadoHtml = ($utilizador['cod'] == '1'
                            ? '<span class="label label-success">Activo</span>'
                            : ($utilizador['estado'] != '1' ? $aler . ' title="Clique para Activar" class="label label-danger">Inativo</span>' : $aler . ' title="Clique para Desactivar" class="label label-success">Activo</span>'));
                    $operacoes = ($utilizador['cod'] != '1'
                            ? spanActionTable(1, 'btnUpdateUtilizador(\'' . $utilizador['cod'] . '\',\'' . $utilizador['nome'] . '\')')
                                    . spanActionTable(2, 'deleteUtilizador(\'' . $utilizador['cod'] . '\',\'' . $c . '\',\'' . $utilizador['nome'] . '\')')
                            : '');
                    $senhaHtml = ($utilizador['estado'] == '1' && $utilizador['cod'] != '1'
                            ? '<select class="form-control input-sm" id="su' . ($c + 1) . '" onchange="reporSenhaUtilizador(\'' . ($c + 1) . '\',\'' . $utilizador['cod'] . '\');">' . $optionsSenha . '</select>'
                            : '');
                    $lista[$c++] = array("<center>" . ($c) . "</center>", '<span title="Utilizador: ' . $utilizador['username'] . '">' . $utilizador['nome'] . '</span>', $utilizador['telefone'], $utilizador['modulo'], $estadoHtml, $senhaHtml, "<center>" . $operacoes . "</center>");
                }
                retornAjax(array('dados' => $lista, 'r' => (!empty($lista) ? 0 : 4)));
            } else
                retornAjax(array('r' => 4));
        } else
            retornAjax(array('r' => 2));
    }

    public function buscaOptionMunicipios() {
        if (!$this->autorizadoGestaoUtilizadores())
            retornAjax(array('r' => -21));
        if ($this->input->post('p')) {
            $munin = $this->utilizador_m->getMunicipiosUtilizador($this->input->post('p'));
            retornAjax(array('r' => (!empty($munin) ? 0 : -1), 'select' => (!empty($munin) ? optionSelectEntidade($munin, array('cod', 'nome')) : '')));
        } else {
            retornAjax(array('r' => 4));
        }
    }

    public function addUtilizador() {
        if (!$this->autorizadoGestaoUtilizadores())
            retornAjax(array('r' => -21));
        if (($this->input->post('pessoa') == '' || isNumeric($this->input->post('pessoa')))
                && strlen(trim((string) $this->input->post('nome'))) >= 5
                && (empty($this->input->post('telefone')) || preg_match('/^[92][0-9]{8}$/', $this->input->post('telefone')))
                && ($this->input->post('smsUser') != '2' || !empty($this->input->post('telefone')))
                && isNumeric($this->input->post('modulo'))
                && in_array($this->input->post('smsUser'), array('1', '2'), TRUE)) {
            $r = $this->utilizador_m->insertUtilizador();
            retornAjax(is_array($r) ? array('r' => 0, 'p' => $r[1], 'u' => $r[0]) : array('r' => $r));
        } else {
            retornAjax(array('r' => -2));
        }
    }

    public function buscarUtilizador() {
        if (!$this->autorizadoGestaoUtilizadores())
            retornAjax(4);
        if (intval($this->input->post('cod'))) {
            $r = $this->utilizador_m->getUtilizador('p.cod as codPessoa,p.nome,p.telefone,p.email,u.cod_modulo,u.username,u.tipo,u.cod_provincia,u.cod_municipio', $this->input->post('cod'));
            retornAjax($r);
        } else {
            retornAjax(4);
        }
    }

    public function editarUtilizador() {
        if (!$this->autorizadoGestaoUtilizadores())
            retornAjax(array('r' => -21));
        if (isNumeric($this->input->post('codUtilizador')) && $this->input->post('codUtilizador') != '1'
                && isNumeric($this->input->post('codPEdit'))
                && strlen(trim((string) $this->input->post('nome'))) >= 5
                && (empty($this->input->post('telefone')) || preg_match('/^[92][0-9]{8}$/', $this->input->post('telefone')))
                && isNumeric($this->input->post('modulo'))) {
            retornAjax(array('r' => $this->utilizador_m->updateUtilizador()));
        } else {
            retornAjax(array('r' => -21));
        }
    }

    public function apagarUtilizador() {
        if (!$this->autorizadoGestaoUtilizadores())
            retornAjax(-21);
        if ($this->input->post('cod')) {
            retornAjax($this->utilizador_m->deleteUtilizador($this->input->post('cod')));
        } else
            retornAjax(-4);
    }

    public function editarEstadoUtilizador() {
        if (!$this->autorizadoGestaoUtilizadores())
            retornAjax(-21);
        if ($this->input->post('cod') && $this->input->post('estado')) {
            retornAjax($this->utilizador_m->updateEstadoUtilizador($this->input->post('cod'), $this->input->post('estado')));
        } else {
            retornAjax(-4);
        }
    }

    public function reporSenhaUtilizador() {
        if (!$this->autorizadoGestaoUtilizadores())
            retornAjax(-21);
        if (!empty($this->input->post('cod')) && !empty($this->input->post('sen'))) {
            if (isNumeric($this->input->post('cod')) && in_array($this->input->post('sen'), array('1', '2'))) {
                retornAjax($this->utilizador_m->updateSenhaUtilizador($this->input->post('cod'), $this->input->post('sen')));
            } else {
                retornAjax(-3);
            }
        } else {
            retornAjax(-2);
        }
    }

    public function editarPerfilEscola() {
        $r['r'] = 4;
//        if (!empty($this->input->post('nome')) && !empty($this->input->post('telefone')) && !empty($this->input->post('cargo')) && !empty($this->input->post('passwordA')) && !empty($this->input->post('passwordN')) && !empty($this->input->post('passwordC'))) {
        if (!empty($this->input->post('nome')) && !empty($this->input->post('telefone')) && !empty($this->input->post('cargo'))) {
            if (preg_match("/^[A-Za-záàâãéèêëíïîìóòôõöúçñÁÀÂÃÉÈËÍÏÎÓÒÔÕÖÚÇÑ\-'\s]+$/", $this->input->post('nome')) && preg_match("/[0-9]{9}/", $this->input->post('telefone')) && in_array($this->input->post('cargo'), array(1, 2, 3))) {
//                if ($this->input->post('passwordN') == $this->input->post('passwordC'))
                $r['r'] = $this->utilizador_m->updatePerfilEscola();
//                else
//                    $r['r'] = 5;
            } else
                $r['r'] = 3;
        } else
            $r['r'] = 2;
        retornAjax($r);
    }

    public function checkAcessoArea() {
        if (!empty($this->input->post('t')) && in_array($this->input->post('t'), array('1', '2', '3'))) {
            if ($this->input->post('t') == 1 && (!empty($this->input->post('p')) && isNumeric($this->input->post('p')))) {
                if ($this->configuracao_m->checkAppProvincia($this->input->post('p'))) {
                    $this->session->set_userdata(array(PROVINCIA_COD => $this->input->post('p')));
                    retornAjax(array('r' => 0, 'local' => (base_url() . 'provincia')));
                }
            }
            if ($this->input->post('t') == 2 && (!empty($this->input->post('p')) && isNumeric($this->input->post('p'))) && (!empty($this->input->post('m')) && isNumeric($this->input->post('m')))) {
                if ($this->configuracao_m->checkAppMunicipio($this->input->post('p'), $this->input->post('m'))) {
                    $this->session->set_userdata(array(MUNICIPIO_COD => $this->input->post('m')));
                    retornAjax(array('r' => 0, 'local' => (base_url() . 'municipio')));
                }
            }
            if ($this->input->post('t') == 3 && (!empty($this->input->post('p')) && isNumeric($this->input->post('p'))) && (!empty($this->input->post('m')) && isNumeric($this->input->post('m'))) && (!empty($this->input->post('n')) && isNumeric($this->input->post('n'))) && (!empty($this->input->post('e')) && isNumeric($this->input->post('e')))) {
                includeModals(array('escola'));
                if ($this->escola_m->checkEscola($this->input->post('p'), $this->input->post('m'), $this->input->post('n'), $this->input->post('e'))) {
                    $this->session->set_userdata(array(ESCOLA_COD => $this->input->post('e')));
                    retornAjax(array('r' => 0, 'local' => (base_url() . 'escola')));
                }
            }
            retornAjax(array('r' => '4 ' . $this->input->post('p') . ' ' . $this->input->post('m')));
        } else {
            retornAjax(array('r' => 2));
        }
    }
}
