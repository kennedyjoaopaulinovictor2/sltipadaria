<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Administrador
 *
 * @author Sampaio André
 */
class Atendimento extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function menu() {
        return array(
            array('Atendimento', 'icon-table',
                array(
                    array('Atendimento', 'href="' . urlMenu() . 'atendimento"', 'atendimento', array(ajaxLocal('padaria')), '', 'icon-edit'),
                )
            ),
        );
    }

    public function acesso() {
        return array('Admin', array('ADMINISTRADOR', 'ATENDIMENTO'));
    }

    public function atendimento() {
//        includeModals(array('escola', 'definicao', 'configuracao'));
        paginaApp($this->menu()[0][0], $this->menu()[0][2][0], '', array());
    }

    public function index() {
        setLocal("ATENDIMENTO");
        $this->atendimento();
    }

    public function perfil() {
        perfilUser();
    }
}
