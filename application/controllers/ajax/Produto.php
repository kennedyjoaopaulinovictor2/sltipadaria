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

    public function buscarOptionsPlanos() {
        if (intval($this->input->post('c'))) {
            $planos = $this->plano_m->getPlanos('p.cod,p.opcao nome', $this->input->post('c'));
            retornAjax(array('r' => !empty($planos) ? 0 : 1, 'select' => (!empty($planos) ? optionSelectEntidade($planos, array('cod', 'nome')) : '')));
        } else {
            retornAjax(array('r' => 4));
        }
    }

    public function listarCurricular() {
        if (!empty($this->input->post('c')) && !empty($this->input->post('p'))) {
            $curriculares = $this->plano_m->getCurriculares($this->input->post('p'), $this->input->post('c'));
            if ($curriculares) {
                $lista = array();
                $c = 0;
                foreach ($curriculares as $cur)
                    $lista[$c++] = array("<center>$c</center>", $cur['codigo'], $cur['nome'], '<span class="label label-sm label-' . ($cur['classificacao'] == '1' ? 'info">EN' : 'default">LN') . '</span>', '<span class="label label-sm label-' . ($cur['estado'] == '1' ? 'success">Activo' : 'danger">Inactivo') . '</span>', "<center>" . spanActionTable(1, 'btnUpdateCurriculo(' . $cur['cod'] . ',\'' . ($cur['codigo'] . ' ' . $cur['nome']) . '\')') . spanActionTable(2, 'deleteCurriculo(' . $cur['cod'] . ',' . ($c - 1) . ',\'' . ($cur['codigo'] . ' ' . $cur['nome']) . '\')') . "</center>");
                retornAjax(array('dados' => $lista, 'r' => (!empty($lista) ? 0 : 1)));
            } else
                retornAjax(array('r' => 4));
        } else
            retornAjax(array('r' => 2));
    }

    public function addCurriculo() {
        if (!empty($this->input->post('planoCur')) && !empty($this->input->post('classeCur')) && !empty($this->input->post('discCur')) && !empty($this->input->post('notaCur')) && !empty($this->input->post('codigoCur'))) {
            retornAjax(array('r' => $this->plano_m->insertCurriculo()));
        } else {
            retornAjax(array('r' => -2));
        }
    }

    public function buscaCurriculo() {
        if (intval($this->input->post('cod'))) {
            retornAjax($this->plano_m->getCurriculo($this->input->post('cod')));
        } else {
            retornAjax(4);
        }
    }

    public function editarCurriculo() {
        if (!empty(intval($this->input->post('codCur'))) && !empty($this->input->post('planoCur')) && !empty($this->input->post('classeCur')) && !empty($this->input->post('discCur')) && !empty($this->input->post('notaCur')) && !empty($this->input->post('codigoCur'))) {
            retornAjax(array('r' => $this->plano_m->updateCurriculo()));
        } else {
            retornAjax(array('r' => -2));
        }
    }

    public function apagarCurriculo() {
        if ($this->input->post('cod')) {
            retornAjax($this->plano_m->deleteCurriculo($this->input->post('cod')));
        } else {
            retornAjax(-4);
        }
    }
}
