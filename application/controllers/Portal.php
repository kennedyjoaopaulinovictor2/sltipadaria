<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Portal extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function menu() {
        return array(
            array('Informa-se', 'icon-info',
                array(
                    array('Sobre SL-TI', hrefSemAcao(), '', array(), '', 'icon-sitemap'),
                    array('Entre em Contacto', hrefSemAcao(), '', array(), '', 'icon-envelope-alt'),
                )
            ),
            array('Documentos', 'icon-file-text',
                array(
                    array('MENU', hrefSemAcao(), '', array(), '', 'icon-file'),
//                    array('Di&aacute;rio da Republica', 'href="' . base_url(APP_DIR_FILE_ARQ . '17001643641.pdf') . '" target="_blank"', '', array(), '', 'icon-file'),
                    array('DOCUMENTOS', hrefSemAcao(), '', array(), '', 'icon-file-text'),
                )
            ),
            array('Entrar', 'icon-signin',
                array(
                    array('Entrar', 'href="' . APP_CLASS_INDEX . '/login" data-toggle="modal"', '', array(), '', 'icon-signin'),
                )
            ),
        );
    }

    public function acesso() {
        return array('Portal', array('PUBLICO'));
    }

    private function app_login($data_login = array()) {
        $this->session->unset_userdata(array(USER_COD, USER_LOCAL, USER_ACESSO));
        $this->session->sess_destroy();
        $this->load->view('header', array('body' => 'login', 'local' => 2, 'title' => headerTitle('Login', 1)));
        $this->load->view('login', $data_login);
        $this->load->view('footer', array('login' => TRUE, 'local' => 2, 'scripts' => array(ajaxLocal('login')), 'script' => ''));
    }

    public function login($logout = 'N') {
        $this->app_login(array('logout' => $logout));
    }

    public function logout() {
        redirect(base_url());
    }

    public function index() {
//        includeModals(array('portal', 'escola'));
        $this->login();
//        paginaSite('', '', 'index', array('menus' => $this->menu(), 'webSite' => $this->portal_m->getDataSite('titulo,subtitulo,descricao,termo_servico'), 'provinciasEscolas' => $this->escola_m->getProvinciasEscolas(), 'etapasAcesso' => $this->portal_m->getCalendarioEtapas()));
    }

    public function error_404() {
        redirect(base_url() . localAcesso());
    }

    public function token_pdf() {
        $r['r'] = 4;
        if (isset($_SESSION['TOKENXLSX'])) {
            $r['r'] = 0;
            $r['token'] = $_SESSION['TOKENXLSX'];
            $r['close'] = $_SESSION['TOKENCLOS'];
            retornAjax($r);
        } else
            retornAjax($r);
    }
}
