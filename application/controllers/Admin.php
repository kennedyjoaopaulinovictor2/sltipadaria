<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Administrador
 *
 * @author Sampaio André
 */
class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function menu() {
        return array(
            array('Definições', 'icon-cogs',
                array(
                    array('Utilizadores', 'href="' . urlMenu() . 'utilizadores"', 'utilizadores', array(ajaxLocal('utilizador')), '', 'icon-group'),
                    array('Gerir Produtos', 'href="' . urlMenu() . 'produtos"', 'produtos', array(ajaxLocal('produto')), '', 'icon-stackexchange'),
                    array('Gerir Fechos', 'href="' . urlMenu() . 'fechos"', 'fecho', array(ajaxLocal('padaria'), ajaxLocal('fecho')), '', 'icon-sitemap'),
                    array('Gerir Mapas', 'href="' . urlMenu() . 'mapas"', 'mapas', array(ajaxLocal('padaria'), ajaxLocal('mapas')), '', 'icon-table'),
                )
            ),
        );
    }

    public function acesso() {
        return array('Admin', array('ADMINISTRADOR'));
    }

    public function index() {
        setLocal("ADMINISTRADOR");
        indexGeral(array());
    }

    public function perfil() {
        perfilUser();
    }

    public function utilizadores() {
        includeModals(array('utilizador'));
        paginaApp($this->menu()[0][0], $this->menu()[0][2][0], '', array('pessoas' => $this->utilizador_m->getPessoas('p.cod,p.nome'), 'modulos' => $this->utilizador_m->getModulos('cod,nome', '2')));
    }

    public function produtos() {
        includeModals(array('produto'));
        paginaApp($this->menu()[0][0], $this->menu()[0][2][1], '', array('categorias' => $this->produto_m->getCategorias()));
    }

    public function fechos() {
        paginaApp($this->menu()[0][0], $this->menu()[0][2][2], '', array());
    }

    public function mapas() {
        paginaApp($this->menu()[0][0], $this->menu()[0][2][3], '', array());
    }
}
