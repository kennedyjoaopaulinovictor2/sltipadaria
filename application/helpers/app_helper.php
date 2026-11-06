<?php

function ajaxLocal($src) {
    return APP_AJAX . $src;
}

function includeModals($models) {
    foreach ($models as $model) {
        get_instance()->load->model($model . '_m');
    }
}

function includeHelpers($helpers) {
    foreach ($helpers as $helper) {
        get_instance()->load->helper($helper);
    }
}

function includeAllModels($pdf = 1) {
    if ($pdf == 2)
        get_instance()->load->model('pdf_m');
    get_instance()->load->model('padaria_m');
    get_instance()->load->model('produto_m');
}

function userSession() {
    return get_instance()->session->has_userdata(SESSAO_ID) ? get_instance()->session->userdata(SESSAO_ID) : '';
}

function userLocal() {
    return (!empty(get_instance()->session->has_userdata(USER_LOCAL)) ? get_instance()->session->userdata(USER_LOCAL) : '');
}

function setLocal($local) {
    get_instance()->session->unset_userdata(array(USER_LOCAL));
    get_instance()->session->set_userdata(array(USER_LOCAL => $local));
}

function userAcesso() {
    return get_instance()->session->has_userdata(USER_ACESSO) ? get_instance()->session->userdata(USER_ACESSO) : '';
}

function acessoLocal($local) {
    get_instance()->load->model('utilizador_m');
    return get_instance()->utilizador_m->getAcessoModulo($local);
}

function localAcesso($local = 2) {
    return ($local == 2 ? acessoLocal(userLocal()) : '');
}

function userModulo() {
    get_instance()->load->model('utilizador_m');
    return get_instance()->utilizador_m->getModuloUser(codUtilizador());
}

function userSenha() {
    get_instance()->load->model('utilizador_m');
    return get_instance()->utilizador_m->getSenhaUser(codUtilizador());
}

function senhaIsDefault() {
    return password_verify('12345678', userSenha()) ? true : false;
}

function urlIsPerfil() {
    $url = explode('/', $_SERVER['REQUEST_URI']);
    return end($url) == 'perfil' ? true : false;
}

function codUtilizador() {
    return get_instance()->session->has_userdata(USER_COD) ? get_instance()->session->userdata(USER_COD) : '';
}

function nomeUtilizador() {
    get_instance()->load->model('utilizador_m');
    return (get_instance()->utilizador_m->getNomeUser(codUtilizador()));
}

function urlMenu($add = '/', $localAcesso = '') {
    return base_url() . (empty($localAcesso) ? localAcesso() : $localAcesso) . $add;
}

function menu() {
    return get_instance()->menu();
}

function menuAreas() {
    return get_instance()->utilizador_m->getModulos('descricao,acesso,local');
}

function dataHoraBD() {
    return get_instance()->configuracao_m->getDataHoraSql();
}

function senhaRandBD() {
    return get_instance()->configuracao_m->getRandSenhaSql();
}

function buscaSmsTipo($tipo) {
    return get_instance()->configuracao_m->getTextSmsByTipo($tipo);
}

function controlAcesso() {
    return get_instance()->acesso();
}

function menuKey($menu, $key, $empty = '') {
    return !empty($menu) && is_array($menu) && isset($menu[$key]) ? $menu[$key] : $empty;
}

function paginaApp($men, $menu, $pagina = '', $data_page = array(), $scripsp = array(), $scriptp = '') {
    $homeAcesso = controlAcesso();
    if (!empty(codUtilizador()) && (userSession() == (APP_ID_NOME . codUtilizador())) && !empty(userLocal()) && !empty(userAcesso()) && in_array(userModulo(), $homeAcesso[1])) {
        $menu_0 = menuKey($menu, 0);
        $caminho = ((!empty($homeAcesso[2]) ? $homeAcesso[2] : $homeAcesso[0]) . ($menu_0 ? ('|' . $men . '|' . $menu_0) : ''));
        get_instance()->load->view('header', array('title' => headerTitle($caminho), 'links' => menuKey($menu, 7), 'local' => 2, 'body' => 'page-header-fixed page-full-width'));
        get_instance()->load->view('menu', array('page' => $menu_0, 'caminho' => $caminho, 'local' => 2));
        get_instance()->load->view((empty($pagina) ? (localAcesso() . '/' . menuKey($menu, 2, 'index')) : $pagina), $data_page);
        get_instance()->load->view('modals', array());
        get_instance()->load->view('footer', array('scripts' => (!empty($scripsp) ? $scripsp : menuKey($menu, 3)), 'script' => (!empty($scriptp) ? $scriptp : menuKey($menu, 4)), 'local' => 2));
    } else {
        redirect(base_url() . APP_CLASS_INDEX . '/login');
    }
}

function paginaSite($men, $menu, $pagina = '', $data_page = array()) {
    get_instance()->session->unset_userdata(array(USER_COD, USER_LOCAL, USER_ACESSO));
    get_instance()->session->sess_destroy();
    $menu_0 = menuKey($menu, 0);
    $caminho = controlAcesso()[0] . ($menu_0 ? ('|' . $men . '|' . $menu_0) : '');
    get_instance()->load->model('Configuracao_m');
    get_instance()->load->view('header', array('title' => headerTitle($caminho), 'links' => '', 'ico' => IMG_ICO, 'local' => '1', 'body' => 'page-header-fixed page-boxed page-full-width'));
    get_instance()->load->view('menu', array('page' => $menu_0, 'caminho' => $caminho, 'local' => '1'));
    get_instance()->load->view('portal/' . $pagina, $data_page);
    get_instance()->load->view('modals', array('local' => '1'));
    get_instance()->load->view('footer', array('scripts' => ($pagina == 'index' ? ajaxLocal('portal') : ''), 'script' => '', 'local' => '1'));
}

function indexGeral($data_page, $page = '', $scrips = array(), $script = '') {
    if (checkSession()) {
        paginaApp('', '', $page, $data_page, $scrips, $script);
    } else {
        redirect(base_url());
    }
}

function perfilUser() {
    get_instance()->load->model('utilizador_m');
    paginaApp(primieroUtilmoNome(nomeUtilizador()), array('Gerir Perfil', '', '', array(ajaxLocal('utilizador')), ''), 'perfil', array('senha' => senhaIsDefault(), 'utilizador' => get_instance()->utilizador_m->getDadoUtilizador(codUtilizador(), 'p.nome,u.username,p.telefone,u.estado,p.email,m.nome as m_nome')));
}

function countTabela($tabela, $where = '', $fun = "COUNT(*)", $alis = '') {
    get_instance()->load->model('configuracao_m');
    return get_instance()->configuracao_m->getCountTabela($tabela, $where, $fun, $alis);
}

function selectRowTabela($campo, $tabela, $where = '', $alis = '') {
    return countTabela($tabela, $where, $campo, $alis);
}

function pageErroControl() {
    if (!empty(codUtilizador()) && !empty(userLocal()) && !empty(userAcesso()))
        redirect(base_url() . APP_CLASS_INDEX . '/login');
    else
        redirect(localAcesso() . 'index');
}

function strReplace($text, $strs) {
    $texto = $text;
    foreach ($strs as $str) {
        $data = explode('_', $str);
        $texto = str_replace($data[0], $data[1], $texto);
    }
    return $texto;
}

function paginaInicial() {
    redirect(base_url() . localAcesso());
}

function paginaLogin() {
    redirect(base_url() . APP_CLASS_INDEX . '/login');
}

function checkSession() {
    if (!empty(codUtilizador()) && !empty(userLocal()) && !empty(userAcesso())) {
        return true;
    } else {
        paginaLogin();
    }
    return false;
}

function filterString($str) {
    return htmlspecialchars(trim($str));
}

function getFirstElementEntidade($objs) {
    foreach ($objs as $obj)
        return $obj;
    return NULL;
}

function nomeSemAcentos($nome) {
    return preg_replace('/[`^~\'"]/', null, iconv('UTF-8', 'ASCII//TRANSLIT', $nome));
}

function duasletrasPrimeiroNome($nome) {
    return strtolower(substr(trim(strtok($nome, ' ')), 0, 2));
}

function abreviaString($string) {
    $s = nomeSemAcentos($string);
    return strtoupper(preg_match('/\s/', $s) ? preg_replace('/\b(\w)\w*\W*/', '\1', $s) : substr($s, 0, (strlen($s) >= 3 ? 3 : strlen($s))));
}

function countPalavras($array) {
    $d = 0;
    foreach ($array as $value) {
        if (strlen($value) > 2) {
            $d++;
        }
    }
    return $d;
}

function abreviaMais3Pha($string) {
    $aaa = explode(' ', $string);
    if (countPalavras($aaa) > 3) {
        $new = $aaa[0] . ' ';
        for ($i = 1; $i < count($aaa) - 1; $i++) {
            $new .= strlen($aaa[$i]) > 2 ? substr($aaa[$i], 0, 1) . '. ' : $aaa[$i] . ' ';
        }
        return $new . end($aaa);
    } else {
        return $string;
    }
}

function moeda_akz($valor) {
    return (!empty($valor) ? number_format($valor, 2, ',', '.') : '');
}

function str_replace_first($search, $replace, $subject) {
    return implode($replace, explode($search, $subject, 2));
}

function moedaBd($valor) {
    return str_replace(',', '.', str_replace('.', '', $valor));
}

function somaMoedaAkz($valor1, $valor2) {
    return moeda_akz(floatval(str_replace('.', '', substr($valor1, 0, - 3)) . (substr($valor1, - 2) != '00' ? ('.' . substr($valor1, - 2)) : '')) + floatval(str_replace('.', '', substr("$valor2", 0, - 3)) . (substr($valor2, - 2) != '00' ? ('.' . substr($valor2, - 2)) : '')));
}

function dataBd($data) {
    return ($data ? date('Y-m-d', strtotime(str_replace('/', '-', $data))) : $data);
}

function dataApp($data, $tipo = 1) {
    return date('d/m/Y' . ($tipo == 2 ? ' H:i:s' : ''), strtotime(str_replace('/', '-', $data)));
}

function retornAjax($r) {
    echo json_encode($r);
    exit();
}

function primieroUtilmoNome($nome) {
    $primeiro = strtok($nome, ' ');
    $ultimo = substr($nome, strrpos($nome, ' '), strlen($nome));
    return ($primeiro . ($primeiro != $ultimo ? (' ' . substr($nome, strrpos($nome, ' '), strlen($nome))) : ''));
}

function tempoDemorado($horaI, $horaF) {
    if ($horaI && $horaF) {
        $first = new DateTime($horaI);
        $second = new DateTime($horaF);
        $diff = $first->diff($second);
        return $diff->format('%H:%I:%S');
    }
    return '';
}

function betweenDates($cmpDate, $startDate, $endDate) {
    return (date($cmpDate) >= date($startDate)) && (date($cmpDate) <= date($endDate));
}

function isNumeric($valor) {
    return ((!empty($valor) && preg_match('/^\d+$/', $valor)) ? true : false);
}

function diferencaDiasDatas($dataMenor, $dataMaior) {
    if ($dataMenor < $dataMaior) {
        return (int) floor((strtotime($dataMaior) - strtotime($dataMenor)) / (60 * 60 * 24));
    }
    return -1;
}

function diferencaHoraTimes($timeMenor, $timeMaior) {
    if ($timeMenor < $timeMaior) {
        $array1 = explode(':', $timeMenor);
        $array2 = explode(':', $timeMaior);
        $minutes1 = ($array1[0] * 60.0 + $array1[1]);
        $minutes2 = ($array2[0] * 60.0 + $array2[1]);
        return ($minutes1 - $minutes2 . ' Minutes');
    }
    return -1;
}

function reduzirStr($str, $tamanho = 36) {
    return (strlen($str) > $tamanho ? (mb_substr($str, 0, $tamanho) . '...') : $str);
}

function numeroStrPad($num, $pad = 4) {
    return str_pad($num, $pad, 0, STR_PAD_LEFT);
}

function generoApp($gen, $t = 1) {
    return (($gen == '1') ? ($t == 1 ? 'Masculino' : 'M') : ($gen == '2' ? ($t == 1 ? 'Feminino' : 'F') : ($t == 1 ? '' : 'I')));
}

function checkUrlFile($urlF) {
    return (!empty($urlF) && file_exists($urlF) ? TRUE : FALSE);
}

function data($date, $tipo = 1) {
    return $date ? ($tipo == 1 ? date('d/m/Y', strtotime($date)) : date('d/m/Y H:i', strtotime($date))) : '';
}

function numeroStrPag($num, $pag = 4) {
    return str_pad($num, $pag, 0, STR_PAD_LEFT);
}

function formaPagamento($forma, $referente) {
    return (($forma == '1' ? 'Dep.' : ($forma == '2' ? 'T.P.A' : ($forma == '3' ? 'Tran.' : ($forma == '4' ? 'Ref.' : '')))) . ' (' . $referente . ')');
}

function geraReferenciaPag($cod) {
    return (strlen($cod) < 8 ? ($cod . date('y') . substr(time(), -(8 - strlen($cod)))) : (strlen($cod) == 8 ? $cod . date('y') : $cod));
}

function dadoNull($data) {
    return ($data ? $data : NULL);
}

function percentagem($total, $per) {
    return round(($per * 100) / $total, 0);
}

function execSQLGetDataArrayApp($sq) {
    get_instance()->load->model('configuracao_m');
    return get_instance()->configuracao_m->execQueryGetDataArray($sq);
}

function ifNullZero($val) {
    return empty($val) ? 0 : $val;
}

function acentoUpperApp($word, $tipo = 1) {
    $acentos = array('à_À', 'á_Á', 'â_Â', 'ã_Ã', 'ä_Ä', 'å_Å', 'ç_Ç', 'è_È', 'é_É', 'ê_Ê', 'ë_Ë', 'ì_Ì', 'í_Í', 'î_Î', 'ï_Ï', 'ñ_Ñ', 'ò_Ò', 'ó_Ó', 'ô_Ô', 'õ_Õ', 'ö_Ö', 'ù_Ù', 'ú_Ú', 'û_Û', 'ü_Ü', 'ý_Ý', 'ÿ_Ÿ');
    foreach ($acentos as $acento)
        $word = $tipo == 1 ? str_replace((substr($acento, 0, 2)), (substr($acento, 3, 2)), $word) : str_replace(utf8_encode(substr($acento, 0, 2)), utf8_encode(substr($acento, 3, 2)), $word);

    return strtoupper($word);
}

function strtoupperApp($str) {
    return acentoUpperApp(strtoupper($str));
}

function setAlertItenMenu($t) {
    return !empty($t) ? $t . '-important' : '';
}

function hrefBrevemente() {
    return 'href="" onclick="swal({title:\'Brevemente Disponível\',icon:\'warning\'});return false;"';
}

function hrefSemAcao() {
    return 'href="" onclick="return false;"';
}

function estadoSolicitacao($e) {
    return ($e == '1' ? 'Solicitada' : ($e == '2' ? 'Aprovada' : ($e == '3' ? 'N/Aprovada' : 'Indefinida')));
}

function moveUploadedFileIns($file, $nome) {
    if ((isset($_FILES[$file]) && !empty($_FILES[$file]) && !($_FILES[$file]["error"] > 0))) {
        if (move_uploaded_file($_FILES[$file]["tmp_name"], $nome))
            return $nome;
    }
    return NULL;
}

function checkFileExists($urlFile) {
    return !empty($urlFile) && file_exists($urlFile) ? true : false;
}

function returnFileExists($urlFile, $not = '') {
    return !empty($urlFile) && file_exists($urlFile) ? $urlFile : $not;
}

function getWorkingDays($startDate, $endDate) {
    $begin = strtotime($startDate);
    $end = strtotime($endDate);
    if ($begin > $end) {
        return 0;
    } else {
        $no_days = 0;
        $weekends = 0;
        while ($begin <= $end) {
            $no_days++;
            $what_day = date("N", $begin);
            if ($what_day > 5)
                $weekends++;
            $begin += 86400;
        }
        $working_days = $no_days - $weekends;
        return (date("l") == "Saturday" ? $working_days : (date("l") == "Sunday" ? $working_days : $working_days));
    }
}

function rand_string($length) {
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    return substr(str_shuffle($chars), 0, $length);
}

function rand_number($length) {
    $chars = "0123456789";
    return substr(str_shuffle($chars), 0, $length);
}

function enviarSMS($fone, $sms, $tipoSms, $codE, $anoL = '') {
    $instance = get_instance();
    if (!function_exists('curl_init')) {
        $code = '600-cURL indisponível';
        $logged = $instance->configuracao_m->insertSmsEnviada($sms, $fone, $tipoSms, $code, $codE, $anoL);
        return array('success' => false, 'code' => $code, 'logged' => $logged);
    }

    $payload = json_encode(array(
        'message' => array(
            'api_key_app' => 'prd9d250f71d22f882d4ae7009171',
            'phone_number' => (string) $fone,
            'message_body' => (string) $sms
        )
    ));
    if ($payload === false) {
        $code = '600-JSON inválido';
        $logged = $instance->configuracao_m->insertSmsEnviada($sms, $fone, $tipoSms, $code, $codE, $anoL);
        return array('success' => false, 'code' => $code, 'logged' => $logged);
    }

    $curl = curl_init();
    if ($curl === false) {
        $code = '600-cURL indisponível';
        $logged = $instance->configuracao_m->insertSmsEnviada($sms, $fone, $tipoSms, $code, $codE, $anoL);
        return array('success' => false, 'code' => $code, 'logged' => $logged);
    }
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://telcosms.co.ao/send_message',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => array('Content-Type: application/json')
    ));
    $response = curl_exec($curl);
    $curlError = curl_error($curl);
    $httpStatus = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $ret = (is_string($response) ? json_decode($response, true) : NULL);
    if ($response === false) {
        $code = '600-' . ($curlError !== '' ? $curlError : 'Falha de comunicação');
        $success = false;
    } elseif ($httpStatus < 200 || $httpStatus >= 300) {
        $code = '600-HTTP ' . $httpStatus;
        $success = false;
    } elseif (!is_array($ret) || !isset($ret['error_code'])) {
        $code = '600-Resposta inválida da TelcoSMS';
        $success = false;
    } else {
        $providerCode = (string) $ret['error_code'];
        $providerMessage = (isset($ret['error_message']) ? (string) $ret['error_message'] : '');
        $code = $providerCode . ($providerMessage !== '' ? '-' . $providerMessage : '');
        $success = ($providerCode === '200');
    }
    $logged = $instance->configuracao_m->insertSmsEnviada($sms, $fone, $tipoSms, $code, $codE, $anoL);
    if (!$logged)
        log_message('error', 'Não foi possível registar o resultado do envio de SMS.');
    return array('success' => $success, 'code' => $code, 'logged' => $logged);
}

function curlPost($url, $file) {
    $ch = curl_init();
    if (!is_resource($ch))
        return false;
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '@' . $file);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function strReplaceTextSMS($texto, $vars) {
    $novoTexto = '';
    foreach ($vars as $var) {
        $dado = explode('_', $var);
        if ($dado[0] && $dado[1]) {
            $novoTexto = empty($novoTexto) ? str_replace($dado[0], $dado[1], $texto) : str_replace($dado[0], $dado[1], $novoTexto);
        }
    }
    return $novoTexto;
}

function encryptor($string, $action = 1) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'Sampaio Andre';
    $secret_iv = 'andre@12345678';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    //$action 1 ->encrypt, 2->decrypt
    if ($action == 1) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 2)
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

    return $output;
}
