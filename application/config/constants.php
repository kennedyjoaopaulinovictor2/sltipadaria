<?php
defined('BASEPATH') || exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') || define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  || define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') || define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   || define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  || define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           || define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     || define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       || define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  || define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   || define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              || define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            || define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       || define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

//constantes do sistema
defined('APP_ID_NOME')         || define('APP_ID_NOME', 'sltipadaria_');
defined('APP_CLASS_INDEX')     || define('APP_CLASS_INDEX', 'portal');

defined('APP_EMP_NOME')        || define('APP_EMP_NOME', 'SL-TI PADARIA');
defined('APP_EMP_SIGLA')       || define('APP_EMP_SIGLA', 'SL-TI');
defined('APP_NAME')            || define('APP_NAME', 'LU - DOCES E DECICIAS');
defined('APP_SIGLA')           || define('APP_SIGLA', 'SL-TI - PADARIA');
defined('APP_SIGLA_PDF')       || define('APP_SIGLA_PDF', 'SL-TI-PADARIA');
defined('APP_NAME_PDF')        || define('APP_NAME_PDF', 'Plataforma de Gestão de Vendas da Padaria & Pastelaria');
defined('APP_VERSION')         || define('APP_VERSION', 'v1.0');

defined('APP_IMG_TOP')         || define('APP_IMG_TOP', 'assets/img/logo_top.png');
defined('APP_IMG_INI')         || define('APP_IMG_INI', 'assets/img/logo_inicio.png');
defined('APP_IMG_LOG')         || define('APP_IMG_LOG', 'assets/img/logo_inicio.png');

defined('IMG_ICO')             || define('IMG_ICO', 'assets/img/ico.ico');
defined('IMG_LOGO')            || define('IMG_LOGO', 'assets/img/ico.png');
defined('IMG_NOT_IMG')         || define('IMG_NOT_IMG', 'assets/img/notImg.png');
defined('APP_IMG_MIN')         || define('APP_IMG_MIN', 'assets/img/ico.png');
defined('IMG_LOGO_PDF')        || define('IMG_LOGO_PDF', 'assets/img/logo-pdf.png');
defined('IMG_TRAZ_PDF')        || define('IMG_TRAZ_PDF', 'assets/img/traz.png');
defined('IMG_LOGO_PDFR')       || define('IMG_LOGO_PDFR', 'assets/img/rodape.png');
defined('IMG_LOGO_QRC')        || define('IMG_LOGO_QRC', 'assets/img/qrcod_img.png');
defined('IMG_LOGO_SMALL')      || define('IMG_LOGO_SMALL', 'assets/img/ico.png');
defined('IMG_USER_DEFAULT')    || define('IMG_USER_DEFAULT', 'assets/img/default_foto.png');

defined('APP_AJAX')            || define('APP_AJAX', 'assets/scripts/ajax/');
defined('APP_DIR_FILE')        || define('APP_DIR_FILE', 'assets/files/');
defined('APP_DIR_FILE_ARQ')    || define('APP_DIR_FILE_ARQ', 'assets/files/archive/');
defined('APP_DIR_FILE_PDF')    || define('APP_DIR_FILE_PDF', 'assets/files/pdf/');
defined('APP_DIR_FILE_XSL')    || define('APP_DIR_FILE_XSL', 'assets/files/excel/');
defined('APP_DIR_FILE_IMG')    || define('APP_DIR_FILE_IMG', 'assets/files/img/');
defined('APP_DIR_IMG_PESSOA')  || define('APP_DIR_IMG_PESSOA', 'assets/files/img/');
defined('APP_DIR_IMG_LOGO')    || define('APP_DIR_IMG_LOGO', 'assets/img/logotipos/');
defined('APP_DIR_FILE_LIST_T') OR define('APP_DIR_FILE_LIST_T', 'assets/files/temp/');
defined('APP_DIR_FILE_EXAME')  OR define('APP_DIR_FILE_EXAME', '../acessoetpshell/');

defined('SITE_CLASS_INDEX')    OR define('SITE_CLASS_INDEX', 'portal');
defined('APP_COPYRIGHT')       || define('APP_COPYRIGHT', '&copy; 2024 SL-TI - Padaria & Pastelaria');
defined('APP_COPYRIGHT2')       || define('APP_COPYRIGHT2', '&copy; SL-TI MED<br>Padaria & Pastelaria');

defined('APP_MENU')             || define('APP_MENU', '2'); //1->LEFT, 2->TOP
defined('APP_COR')              || define('APP_COR', '1'); //1->BLUE, 2->BLACK
defined('APP_SMS')              || define('APP_SMS', '2'); //1->INACTIVO, 2->ACTIVO
defined('APP_RUPE')              || define('APP_RUPE', '1'); //1->BUSCAR NA BD, 2->WEB SERVICE API

defined('SESSAO_ID')           || define('SESSAO_ID', 'sessaoId');
defined('USER_COD')            || define('USER_COD', 'codUser');
defined('USER_LOCAL')          || define('USER_LOCAL', 'local');
defined('USER_ACESSO')         || define('USER_ACESSO', 'acesso');

defined('APP_BD_HOST')         || define('APP_BD_HOST', 'localhost');
defined('APP_BD_USER')         || define('APP_BD_USER', 'root');
defined('APP_BD_PASS')         || define('APP_BD_PASS', '');
defined('APP_BD_NAME')      || define('APP_BD_NAME', 'padaria');
defined('APP_BD_DBCOLLAT')     || define('APP_BD_DBCOLLAT', 'utf8_general_ci');





