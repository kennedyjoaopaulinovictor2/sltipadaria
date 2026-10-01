<?php

function paginaEmConstrucao($text = '') {
    return '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><strong>Aten&ccedil;&atilde;o!</strong> P&aacute;gina em constru&ccedil;&atilde;o. ' . (!empty($text) ? '<span style="color;#ff0000">' . $text . '</span>' : '') . '</div>';
}

function paginaFaltaPDF() {
    return '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Aten&ccedil;&atilde;o!</strong> Falta Adicionar Imprimir Relatorio PDF.</div>';
}

function divAtencaoAdd($text, $erro = 'Aten&ccedil;&atilde;o') {
    return '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><strong>' . $erro . '!</strong> ' . $text . '</div>';
}

function includeLinks($url, $links) {
    $st = "";
    foreach ($links as $link) {
        $st .= '<link href="' . $url . $link . '.css?v=' . time() . '" rel="stylesheet" type="text/css"/>';
    }
    return $st;
}

function includeScripts($url, $srcs) {
    $st = "";
    foreach ($srcs as $src) {
        if (!empty($src))
            $st .= '<script src="' . $url . $src . '.js?v=' . time() . '" type="text/javascript"></script>';
    }
    return $st;
}

function inArrayMenu($page, $menu) {
    foreach ($menu as $m) {
        if (!empty($m)) {
            if ($m[0] == $page && !empty($page)) {
                return TRUE;
            }
        }
    }
    return FALSE;
}

function menuTop($titulo, $icon, $menu, $page = '') {
    $string = '<li class="' . (inArrayMenu($page, $menu) ? 'active' : '') . '">';
    if (count($menu) == 1) {
        foreach ($menu as $key) {
            if (isset($key[0]) && isset($key[1])) {
                $string .= '<a ' . $key[1] . '><i class="' . $icon . '"></i> ' . $key[0] . '</a>';
            }
            break;
        }
    } else {
        $string .= '<a data-hover="dropdown" data-close-others="true" href="javascript:;"><i class="' . $icon . '"></i> ' . $titulo . '<i class="icon-angle-down"></i><span class="arrow"></span></a>';
        $string .= '<ul class="dropdown-menu">';
        foreach ($menu as $key) {
            if (isset($key[0]) && isset($key[1])) {
                $string .= '<li class="' . ((!empty($page) && $page == $key[0]) ? 'active' : '') . '"><a ' . $key[1] . '>' . (isset($key[5]) && !empty($key[5]) ? '<i class="' . $key[5] . '"></i> ' : '') . $key[0] . '</a></li>';
            }
        }
        $string .= '</ul>';
    }
    return $string . '</li>';
}

function menuLeft($titulo, $icon, $menu, $page = '') {
    $string = '<li class="' . (inArrayMenu($page, $menu) ? ' active' : '') . '">';
    if (count($menu) == 1) {
        foreach ($menu as $key) {
            if (isset($key[0]) && isset($key[1])) {
                $string .= '<a ' . $key[1] . '><i class="' . $icon . '"></i><span class="title">' . $key[0] . '</span><span class="selected"></span></a>';
            }
            break;
        }
    } else {
        $string .= '<a href="javascript:;"><i class="' . $icon . '"></i><span class="title">' . $titulo . '</span><span class="selected"></span><span class="arrow' . (inArrayMenu($page, $menu) ? ' open' : '') . '"></span></a>';
        $string .= '<ul class="sub-menu">';
        foreach ($menu as $key) {
            if (isset($key[0]) && isset($key[1])) {
                $string .= '<li class="' . ((!empty($page) && $page == $key[0]) ? 'active' : '') . '"><a ' . $key[1] . '>' . (isset($key[5]) && !empty($key[5]) ? '<i class="' . $key[5] . '"></i> ' : '') . $key[0] . '</a></li>';
            }
        }
        $string .= '</ul>';
    }
    return $string . '</li>';
}

function addMenuLeft($menus, $page, $not = array()) {
    $string = '';
    for ($i = 0; $i < count($menus); $i++) {
        if (!in_array($i, $not)) {
            $string .= menuLeft($menus[$i][0], $menus[$i][1], $menus[$i][2], $page);
        }
    }
    return $string;
}

function addMenuTop($menus, $page, $not = array()) {
    $string = '';
    for ($i = 0; $i < count($menus); $i++)
        $string .= (!in_array($i, $not) ? menuTop($menus[$i][0], $menus[$i][1], $menus[$i][2], $page) : $string);
    return $string;
}

function menuLeftAjuda($urlAjuda) {
    return '<li class=""><a href="javascript:;"><i class="icon-exclamation-sign"></i><span class="title">Menu Ajuda</span><span class="arrow "></span></a><ul class="sub-menu"><li><a href="' . (!empty($urlAjuda) && $urlAjuda != '#' ? $urlAjuda : '') . '"' . (!empty($urlAjuda) && $urlAjuda != '#' ? '' : ' onclick="alert(\'Atenção! Manual em Construção\');return false;"') . '>Manual de Ajuda</a></li><li><a href="#modalAcercaSistema" data-toggle="modal"><span>Sobre o ' . APP_SIGLA . '</span></a></li></ul></li>';
}

function menuVoltar($url) {
    return '<li class="last"><a href="' . $url . '"><i class="icon-arrow-left"></i><span class="title">Voltar</span><span class="selected"></span></a></li>';
}

function menuTopVoltar($area) {
    return '<li><a href="' . $area . '"><i class="icon-arrow-left"></i></a></li>';
}

function menuTopAjuda($urlAjuda) {
    return '<li><a data-toggle="dropdown" data-hover="dropdown" data-close-others="true" class="dropdown-toggle" href="javascript:;"><span class="selected"></span><i class="icon-exclamation-sign"></i><i class="icon-angle-down"></i></a><ul class="dropdown-menu"><li><a href="' . (!empty($urlAjuda) && $urlAjuda != '#' ? $urlAjuda : '') . '"' . (!empty($urlAjuda) && $urlAjuda != '#' ? '' : ' onclick="alert(\'Atenção! Manual em Construção\');return false;"') . '>Manual de Ajuda</a></li><li><a href="#modalAcercaSistema" data-toggle="modal"><span>Sobre do ' . APP_SIGLA . '</span></a></li></ul></li>';
}

function addMenuArea($localNot = array()) {
    $menuA = array();
    foreach (menuAreas() as $mods) {
        if (!in_array($mods->local, $localNot)) {
            $menuA[] = (in_array($mods->local, array('ESCOLA', 'PROVINCIA', 'MUNICIPIO')) ? array($mods->descricao, 'href="#modalAcesso' . ($mods->local == 'ESCOLA' ? 'Escola' : ($mods->local == 'MUNICIPIO' ? 'Municipio' : 'Provincia')) . '" data-toggle="modal"') : array($mods->descricao, 'href="' . base_url() . $mods->acesso . '"'));
        }
    }
    return $menuA;
}

function menuApp($tipo, $page) {
    $menu = ($tipo == 1 ? addMenuTop(menu(), $page) : addMenuLeft(menu(), $page));
    if (userLocal()) {
        switch (userLocal()) {
            case "ADMINISTRADOR":
                $not = array("ADMINISTRADOR");
                $menu .= ($tipo == 1 ? menuTop('Acessar &Aacute;rea', 'icon-windows', addMenuArea($not)) : menuLeft('Acessar &Aacute;rea', 'icon-windows', addMenuArea($not)));
                break;
//            case "MED":
//                $not = array("ADMINISTRADOR", "MED", "SUPERVISOR");
//                $menu .= ($tipo == 1 ? menuTop('Acessar &Aacute;rea', 'icon-windows', addMenuArea($not)) : menuLeft('Acessar &Aacute;rea', 'icon-windows', addMenuArea($not)));
//                break;
            case "INADE":
                $not = array("ADMINISTRADOR", "MED", "INADE", "MUNICIPIO", "SUPERVISOR");
                $menu .= ($tipo == 1 ? menuTop('Acessar &Aacute;rea', 'icon-windows', addMenuArea($not)) : menuLeft('Acessar &Aacute;rea', 'icon-windows', addMenuArea($not)));
                break;
//            case "PROVINCIA":
//                $not = array("ADMINISTRADOR", "MED", "INADE", "PROVINCIA", "SUPERVISOR", "CERTIFICACAO");
//                $menu .= ($tipo == 1 ? menuTop('Acessar &Aacute;rea', 'icon-windows', addMenuArea($not)) : menuLeft('Acessar &Aacute;rea', 'icon-windows', addMenuArea($not)));
//                break;
        }
        if (userLocal() != 'ADMINISTRADOR')
            $menu .= ($tipo == 1 ? (in_array(userModulo(), array("ADMINISTRADOR", "MED", "INADE", "PROVINCIA")) ? menuVoltar(base_url(userAcesso())) : '') : (in_array(userModulo(), array("ADMINISTRADOR", "MED", "INADE", "PROVINCIA")) ? menuVoltar(base_url() . userAcesso()) : ''));
    }
    return $menu;
}

function addMenuNotificacao() {
//    $str = '<li class="dropdown" id="header_notification_bar"><a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"><i class="icon-warning-sign"></i><span class="badge">';
//    $add = '</span></a><ul class="dropdown-menu extended notification"><li><p>Notificações</p></li><li>';
//    switch (userLocal()) {
//        case "ADMINISTRADOR":
//            $c = 0;
//            $str .= $c . $add;
////            $str .= $c . $add . '<li><a ' . get_instance()->menu()[0][2][0][1] . '><span class="label label-sm label-icon label-danger"><i class="icon-group"></i></span>' . ($c > 0 ? $c . ' funcionário(s) sem acesso' : ' Nenhum funcionários sem acesso') . '</a></li>';
//            break;
//        case "DRH":
//            $c = get_instance()->getSolicitacoes();
//            $str .= $c . $add;
//            $str .= '<li><a  title="Clique para gerir" ' . get_instance()->menu()[0][2][0][1] . '>' . ($c[0] ? 'Tens <strong>' . $c[0] . '</strong> Alteração de Funcionários' : 'Sem Alteração Solicitada') . '</a></li>';
//            break;
//        case "GESTOR":
//            $c = 0;
//            $str .= $c . $add;
//            break;
//        case "COLABORADOR":
//            $c = 0;
//            $str .= $c . $add;
//            break;
//    }
//    return $str . '</li></ul></li>';
}

function caminhoPerfilUtilizador($nome, $var) {
    return array($var, primieroUtilmoNome(($nome)) . "_Gerir Perfil");
}

function breadcrumb($caminho, $acesso) {
$swww = explode('|', $caminho);
    $str = '<div class="row" style="margin-top:-41px;"><div class="col-md-12"><ul class="page-breadcrumb breadcrumb">';
    $c = 0;
    foreach ($swww as $cc) {
        $str .= ($c == 0 ? '<li><i class="icon-home"></i><a href="' . $acesso . '">' . $cc . '</a><i class="icon-angle-right"></i></li>' . (count($swww) == 1 ? '<li class="active"><a>In&iacute;cio</a></li>' : '') : '<li' . ($c + 1 == count($swww) ? ' class="active"' : '') . '><a>' . $cc . '</a></li>' . ($c + 1 != count($swww) ? ' <i class="icon-angle-right"></i> ' : ''));
        $c++;
    }
    return $str . '</ol></div></div>';
}

function headerTitle($caminho, $cot = 2) {
    $str = '';
    if (!empty($caminho)) {
        if ($cot == 2) {
            $swww = explode('|', $caminho);
            $str .= (count($swww) == 1 ? (!empty(userLocal()) ? (ucfirst(localAcesso())) : '') : end($swww));
        } else {
            $str .= $caminho;
        }
    }
    return ((!empty($str) ? $str . ' | ' : '') . APP_SIGLA);
}

function countItemcomIconAtalho($menu) {
    $c = 0;
    foreach ($menu as $mun) {
        foreach ($mun[2] as $aa) {
            $c = (isset($aa[5]) && !empty($aa[5])) ? $c + 1 : $c;
        }
    }
    return $c;
}

function headerInicio($array, $menu = '', $accordion = 1, $accordionIn = 1, $logotipo = '') {
    $string = '<div class="row"><div class="col-md-12 text-center well note-info" style="border:0;width:65%;margin-left:auto;margin-right:auto;padding-bottom:5px;"><div class="animate__animated animate__zoomIn"><img style="display:block;margin-left:auto;margin-right:auto;" class="img-responsive" src="' . base_url(APP_IMG_INI) . '" alt="' . APP_EMP_SIGLA . '" /><h5 style="margin-top:10px;"><strong>Bem-vindo(a)</strong></h5>';
    for ($i = 0; $i < count($array); $i++) {
        if (preg_match("/#/", $array[$i])) {
            $string .= substr($array[$i], 1);
        } else {
            $string .= '<h3 class="page-title">' . $array[$i] . '</h3>';
        }
    }
    $strAdd = '';
    $cc = countItemcomIconAtalho($menu);
    if ((!empty($menu) && is_array($menu)) && $cc > 0) {
        $strAdd = '<div class="row"><div class="col-md-12 well note-info text-center" style="border:0;width:75%;margin-left:auto;margin-right:auto;padding:10px 0 10px 0;"><div class="animate__animated animate__zoomIn"><h5><strong>Atalhos do Sistema</strong></h5>' . ($accordion == 1 ? '<div class="row"><div class="col-md-12" style="border:0;width:75%;margin-left:auto;margin-right:auto;padding:10px 0 10px 0;"><div class="panel-group accordion" id="accordion1">' : '');
        $c = 1;
        foreach ($menu as $mun) {
            $strAdd .= ($accordion != 1 ? '' : '<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_' . ($c) . '">' . $mun[0] . '</a></h4></div><div id="collapse_' . ($c) . '" class="panel-collapse ' . ($accordionIn == $c ? 'in' : 'collapse') . '"><div class="panel-body">');
            foreach ($mun[2] as $aa) {
                if (!empty($aa[5])) {
                    $estt = (!empty($aa[6]) ? explode('-', $aa[6]) : '');
                    $strAdd .= '<a ' . $aa[1] . ' class="icon-btn blue' . (!empty($aa[8]) ? (' ' . $aa[8]) : '') . '" style="width:140px;"><i class="' . (!empty($aa[5]) ? $aa[5] : 'icon-link') . '"></i><div style="padding:5px">' . $aa[0] . '</div>' . ($estt ? '<span class="badge badge-' . $estt[1] . '">' . $estt[0] . '</span>' : '') . '</a>' . (!empty($aa[7]) ? $aa[7] : '');
                }
            }
            $strAdd .= ($accordion != 1 ? '' : '</div></div></div>');
            $c++;
        }
        $strAdd .= '</div></div></div>' . ($accordion != 1 ? '' : '</div></div>');
    }
    return $string . '</div></div></div>' . $strAdd;
}

function servicoInicio($titulo, $idbtnUpdate, $dados, $onclickPrint = '', $smsServico = '') {
    $string = '<div class="row"><div class="col-md-12"><div class="portlet box blue"><div class="portlet-title"><div class="caption">' . $titulo . '</div><div class="actions"><button id="' . $idbtnUpdate . '" class="btn btn-sm green easy-pie-chart-reload"><i class="icon-repeat"></i> Actualizar</button>' . ($onclickPrint ? ' <button onclick="' . $onclickPrint . '" class="btn btn-sm purple easy-pie-chart-reload"><i class="icon-file-text"></i> Imprimir</button>' : '') . '</div></div><div class="portlet-body">' . ($smsServico ? '<p class="text-center">' . $smsServico . '</p>' : '') . '<div class="row">';
    $t = count($dados);
    foreach ($dados as $key => $val) {
        $string .= '<div class="col-md-' . ($t == 4 ? '3' : '4') . '"><div class="top-news"><a href="javascript:;" class="btn ' . $val[1] . '"><strong>' . (!empty($val[2]) ? '<i class="' . $val[2] . '"></i> ' : '') . $val[0] . '<span id="' . $key . '" style="float:right"></span></strong></a></div></div>';
    }
    return $string . '</div></div></div></div></div>';
}

function menuInicio($array) {
    $strAdd = '<div class="row"><div class="col-md-12">';
    $d = 12 / count($array);
    foreach ($array as $aa) {
        $strAdd .= '<div class="col-md-' . $d . '"><div class="block block-drop-shadow"><div class="header"><h2><a ' . $aa[0] . ' class="widget-icon widget-icon-large ' . $aa[2] . '"></a> ' . $aa[1] . '</h2></div></div></div>';
    }
    return $strAdd . '</div></div>';
}

function elementsHidden(Array $ids) {
    $tring = '';
    for ($i = 0; $i < count($ids); $i++) {
        if (!empty($ids[$i])) {
            if (preg_match('/_/', $ids[$i])) {
                $aa = explode('_', $ids[$i]);
                $tring .= '<input id="' . $aa[0] . '" name="' . $aa[0] . '" value="' . $aa[1] . '" type="hidden" />';
            } else {
                $tring .= '<input id="' . $ids[$i] . '" name="' . $ids[$i] . '" type="hidden" />';
            }
        }
    }
    return $tring;
}

function elementsHiddenCaminho($idName, $comeco, Array $caminhos) {
    $strCaminho = $comeco;
    for ($i = 0; $i < count($caminhos); $i++) {
        $strCaminho .= "&" . $caminhos[$i] . '=$val' . ($i + 1);
    }
    return '<input id="' . $idName . '" name="' . $idName . '" value="' . $strCaminho . '" type="hidden" />';
}

function optionSelect($options, $select = '') {
    $string = "";
    foreach ($options as $opti) {
        if ($opti == '#') {
            $string .= '<option value="">Selecione</option>';
        } else {
            $option = explode('_', $opti);
            $string .= '<option ' . ($select == $option[0] ? 'selected="selected"' : '') . ' value="' . ($option[0] != '#' ? $option[0] : '') . '">' . $option[1] . '</option>';
        }
    }
    return $string;
}

function optionSelectEntidade($objs, $campos, $select = '') {
    $string = "";
    if (!empty($objs) && !empty($campos) && is_array($campos)) {
        foreach ($objs as $opti) {
            $cod = $campos[0];
            $dat = $campos[1];
            if ($opti->$cod && $opti->$dat)
                $string .= '<option' . ($select == $opti->$cod ? ' selected="selected"' : '') . ' value="' . $opti->$cod . '">' . $opti->$dat . '</option>';
        }
    }
    return $string;
}

function optionGroupSelectEntidade($dados, $campos, $select = '') {
    $z = count($dados);
    $selectI = '';
    $c = '';
    for ($i = 0; $i < $z; $i++) {
        $val = $campos[0];
        $nam = $campos[1];
        $opg = $campos[2];
        $aNo = (!empty($campos[3]) ? ($campos[3] . '') : '');
        if ($c != $dados[$i]->$opg) {
            $selectI .= '<optgroup label="' . $dados[$i]->$opg . '">';
            $c = $dados[$i]->$opg;
        }
        $selectI .= '<option' . ($select == $dados[$i]->$val ? ' selected="selected"' : '') . ' value="' . $dados[$i]->$val . '">' . ($aNo ? ($dados[$i]->$val . ') ' . $dados[$i]->$nam . ' - ' . ($dados[$i]->$aNo ? $dados[$i]->$aNo : 0) . ' cands') : $dados[$i]->$nam) . '</option>';
        if (($i + 1 < $z && $c != $dados[$i + 1]->$opg) || ($i + 1 == $z))
            $selectI .= '</optgroup>';
    }
    return $selectI;
}

function redElementForm($value) {
    return (empty($value) ? 'has-error' : '');
}

function elementoForm($nome, $id, $elemento, $idT = '', $idM = '', $styleDiv = '', $ln = '', $classes = '') {
    return '<div class="form-group' . (!empty($classes) ? ' ' . $classes : '') . '"' . (!empty($idT) ? ' id="' . $idT . '"' : '') . '' . (!empty($styleDiv) ? ' style="' . $styleDiv . '"' : '') . '><label class="control-label' . (empty($ln) ? ' col-md-3' : '') . '" for="' . $id . '">' . $nome . '</label>' . (!empty($elemento) ? (empty($ln) ? '<div class="col-md-9"' . (!empty($idM) ? ' id="' . $idM . '"' : '') . '>' : '') . '' . str_replace('$idname', 'id="' . $id . '" name="' . $id . '"', $elemento) . (empty($ln) ? '</div>' : '') : '') . '</div>';
}

function elementoForm1($nome, $id, $elemento, $idT = '', $idM = '', $styleDiv = '', $ln = '') {
    return '<div class="form-group"' . (!empty($idT) ? ' id="' . $idT . '"' : '') . '' . (!empty($styleDiv) ? ' style="' . $styleDiv . '"' : '') . '><label class="control-label' . (empty($ln) ? ' col-md-2' : '') . '" for="' . $id . '">' . $nome . '</label>' . (empty($ln) ? '<div class="col-md-10"' . (!empty($idM) ? ' id="' . $idM . '"' : '') . '>' : '') . '' . str_replace('$idname', 'id="' . $id . '" name="' . $id . '"', $elemento) . (empty($ln) ? '</div>' : '') . '</div>';
}

function elementAddon($nome, $id, $elemento, $idT = '', $styleDiv = '', $addInicio = '', $addEnd = '') {
    return $addInicio . '<div class="form-group"' . (!empty($idT) ? ' id="' . $idT . '"' : '') . '' . (!empty($styleDiv) ? ' style="' . $styleDiv . '"' : '') . '><div class="input-group" id="' . $id . 'D"><label class="input-group-addon control-label" for="' . $id . '">' . $nome . '</label>' . str_replace('$idname', 'id="' . $id . '" name="' . $id . '"', $elemento) . '</div></div>' . $addEnd;
}

function btnHederForm($onclick, $title, $id = '') {
    return '<div class="col-md-12 btn-group" style="margin-bottom: 10px"><button' . (!empty($id) ? ' id="' . $id . '"' : '') . ' class="btn blue pull-right"' . (!empty($onclick) ? ' onclick="' . $onclick . '"' : '') . '><i class="icon-plus-sign"></i> ' . $title . '</button></div>';
}

function btnFormActions($clickCancel, $clickSubmite = '', $idSubmit = '', $idCancel = '') {
    return '<div class="form-actions">
                <button type="submit"' . (!empty($idSubmit) ? ' id="' . $idSubmit . '"' : '') . '' . (!empty($clickSubmite) ? ' onclick="' . $clickSubmite . ';"' : '') . ' class="btn green m-icon"><i  class="icon-ok-sign"></i> Executar</button>
                <button type="reset"' . (!empty($idCancel) ? ' id="' . $idCancel . '"' : '') . '' . (!empty($clickCancel) ? ' onclick="' . $clickCancel . ';"' : '') . ' class="btn default m-icon"><i class="icon-remove-circle"></i> Cancelar</button>
            </div>';
}

function criaTabela($idTabela, $thead, $tbody = '', $idTboby = '', $dynamicTable = '') {
    $string = '';
    foreach ($thead as $th) {
        $string .= '<th' . (!empty($th[0]) ? (' ' . $th[0]) : (in_array($th[1], array('#', 'Opera&ccedil;&atilde;o')) ? ' style="text-align:center"' : '')) . ' >' . $th[1] . '</th>';
    }
    return '<div class="table-responsive"><table class="table table-bordered table-hover' . (!empty($dynamicTable) ? " dynamicTable" : "") . '" id="' . $idTabela . '"><thead><tr>' . $string . '</tr></thead><tbody>' . (empty($idTboby) ? $tbody : "") . '</tbody></table></div>';
}

function spanActionTable($tipo, $funOnclick, $id = '', $nome = '', $title = '', $corBtn = '', $iconBtn = '', $activeLink = '') {
    return '<span' . (!empty($title) ? ' title="' . $title . '"  ' : '') . (!empty($id) ? ' id="' . $id . '"  ' : '') . (!empty($funOnclick) ? ' onclick="' . $funOnclick . ';' . (empty($activeLink) ? 'clickActiveLink(this, \'' . (!empty($corBtn) ? $corBtn : ($tipo == 1 ? 'green' : ($tipo == 2 ? 'red' : 'blue'))) . '\',\'default\');' : '') . '"  ' : '') . ' class="btn btn-xs ' . (!empty($corBtn) ? $corBtn : ($tipo == 1 ? 'green' : ($tipo == 2 ? 'red' : 'blue'))) . '"><i class="' . (!empty($iconBtn) ? $iconBtn : ($tipo == 1 ? 'icon-edit' : ($tipo == 2 ? 'icon-remove' : 'icon-print'))) . '"></i> ' . (!empty($nome) ? ($nome == '#' ? '' : $nome) : ($tipo == 1 ? 'Edit' : ($tipo == 2 ? 'Remo' : 'Print')) ) . '</span>';
}

function botoesAcctionModal($nameS, $idSubmit = '', $onclickS = '', $butonAferSubmit = '', $idFechar = '', $notFechar = '') {
    return ' <div class="modal-footer">' . (empty($notFechar) ? '<button type="button"' . (!empty($idFechar) ? 'id="' . $idFechar . '"' : '') . ' data-dismiss="modal" class="btn default">Fechar</button>' : '') . '' . (!empty($butonAferSubmit) ? ' ' . $butonAferSubmit : '') . (!empty($nameS) ? '<button type="submit" ' . (!empty($idSubmit) ? 'id="' . $idSubmit . '"' : '') . ' ' . (!empty($onclickS) ? 'onclick="' . $onclickS . '"' : '') . ' class="btn blue">' . $nameS . '</button>' : '') . '</div>';
}

function myModalCriar($idModal, $titulo, $stringForm, $stringFooter, $idTitulo = '', $style = '', $addSubTitulo = '', $idForm = '', $idFechar = '') {
    return '<div class="modal fade" data-backdrop="static" data-keyboard="false" aria-hidden="true" id="' . $idModal . '"' . (!empty($style) ? ' ' . $style : '') . ' ><div class="modal-dialog"><div class="modal-content"><div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"' . (!empty($idFechar) ? ' id="' . $idFechar . '" ' : '') . '></button><h4 ' . (!empty($idTitulo) ? 'id="' . $idTitulo . '" ' : '') . 'style="margin-bottom: 0;" class="modal-title">' . $titulo . '</h4>' . (!empty($addSubTitulo) ? $addSubTitulo : '') . '</div>
            <div class="modal-body"><form method="post" onsubmit="return false;"' . (!empty($idForm) ? ' id="' . $idForm . '"' : '') . ' class="form-modal">' . $stringForm . '</div>' . $stringFooter . '</div></form></div></div>';
}

function addWizardHeadLi($tabs) {
    $ul = ' <ul class="nav nav-pills steps" style="margin:-0;margin-top:-6px;padding:0;">';
    for ($i = 0; $i < count($tabs); $i++) {
        $ul .= '<li><a href="#tab' . ($i + 1) . '" data-toggle="tab" class="step"><span class="desc"><i class="icon-ok"></i> ' . $tabs[$i] . '</span></a></li>';
    }
    return $ul . '</ul>';
}

function addTituloPageEscola() {
    return'<div class="text-center well note-info" style="border:0;width:65%;margin-left:auto;margin-right:auto;padding-bottom:5px;"><h5>' . APP_SIGLA . '</h5><p>Província: ' . (objEscola()->provincia . '; Município: ' . objEscola()->municipio) . '</p><h5>' . acentoUpperApp(objEscola()->nome) . '</h5></div></div>';
}

function scriptToastr($type, $texto) {
    return 'toastr.options = {"closeButton": true, "positionClass": "toast-bottom-right", "preventDuplicates": false, "onclick": null, "showDuration": "3000", "hideDuration": "10000", "timeOut": "50000", "extendedTimeOut": "10000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut"};toastr["' . $type . '"]("' . $texto . '");';
}

function showDivPdfPreview($idcanvas, $idinput, $nameBtn = 'Selecionar PDF', $btnColor = 'default', $filesCheck = '', $urlFileVer = '', $targModal = '') {
    return '<div><canvas class="pdf-preview-show" style="margin-bottom:3px" id="' . $idcanvas . '" width="150" height="150"></canvas><input style="display:none" type="file" id="' . $idinput . '" name="' . $idinput . '" accept="application/pdf" onchange="showSelectedFileHasChanged(\'' . $idinput . '\',\'' . $idcanvas . '\',\'' . $filesCheck . '\',\'' . $targModal . '\')" /><span class="btn ' . $btnColor . ' btn-file btn-sm" onclick="showSelectFileDialog(\'' . $idinput . '\')"><i class="icon-paper-clip"></i> ' . $nameBtn . '</span>' . ($urlFileVer ? '<br><span id="' . $idinput . 'Ver" class="btn btn-link" onclick="carregarPdf(\'' . base_url() . $urlFileVer . '\',1);clickActiveHref(this);">Visualizar</span>' : '') . '</div>';
}

function spanOpcao($opcao, $tipo = 1) {
    return ($tipo == 1 ? '<span class="label label-sm label-' . ($opcao == 1 ? 'success' : ($opcao == 2 ? 'warning' : ($opcao == 3 ? 'danger' : 'info'))) . '">' . $opcao . '&ordf; Opção</span>' : $opcao . 'º');
}
