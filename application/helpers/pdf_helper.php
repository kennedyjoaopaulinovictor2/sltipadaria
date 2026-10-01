<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$anoA = anoLectivo();
defined('FAMILY') || define('FAMILY', 'Times');         //tipo de letra dos doc Courier , Times, Symbol, ZapfDingbats, Arial, 
defined('FAMILY2') || define('FAMILY2', 'Arial');
defined('BORDER') || define('BORDER', 1);              //border nas celulas sim
defined('NO_BORDER') || define('NO_BORDER', 0);           //border nas celulas sim
defined('PDF_TITULO') || define('PDF_TITULO', 'REPÚBLICA DE ANGOLA_MINISTÉRIO DA EDUCAÇÃO_INSTITUTO NACIONAL DE AVALIAÇÃO E DE_DESENVOLVIMENTO DA EDUCAÇÃO_PLATAFORMA DE GESTÃO DE DADOS DOS EXAMES NACIONAIS', 0); //pdf cabecalho
defined('PDF_TITULO2') || define('PDF_TITULO2', 'REPÚBLICA DE ANGOLA_MINISTÉRIO DA EDUCAÇÃO_INFQE - EXAME DE ACESSO AO ETP 2024/2025', 0); //pdf cabecalho
defined('LN') || define('LN', 1);
defined('NO_LN') || define('NO_LN', 0);
defined('H') || define('H', 5);                  //espacamento entra as celulas baixo cima
defined('COR') || define('COR', 1);
defined('NO_COR') || define('NO_COR', 0);
defined('CENTER_IMG_P') || define('CENTER_IMG_P', 97);
defined('CENTER_IMG_L') || define('CENTER_IMG_L', 142);
defined('IMG_WIDTH') || define('IMG_WIDTH', 15);

$_SESSION['FOOTER_PDF'] = TRUE;
$_SESSION['FOOTER_IMG'] = TRUE;
$_SESSION['FOOTER_PDFN'] = TRUE;

$_SESSION['TOKENXLSX'] = 'NAO';
$_SESSION['TOKENCLOS'] = 'NAO';

include_once 'fpdf/mc_table.php';

//include_once "excel/eiseXLSX.php";
//include_once "excel/SimpleXLSX.php";

function texto_externo($text) {
    return utf8_decode($text);
}

function texto_interno($text) {
    return utf8_decode($text);
}

function style() {
    return array('N' => '', 'B' => 'B', 'I' => 'I', 'U' => 'U', 'BU' => 'BU');
}

function size() {
    return array(8 => 8, 9 => 9, 10 => 10, 11 => 11, 12 => 12, 13 => 13, 14 => 14);
}

function rgb() {
    return array(183, 203, 227);
}

function rgb1() {
    return array(233, 233, 233);
}

function rgb2() {
    return array(215, 215, 215);
}

function rgb3() {
    return array(240, 240, 240);
}

function rgb4() {
    return array(255, 255, 0);
}

function align() {
    return array('C' => 'C', 'L' => 'L', 'R' => 'R', 'J' => 'J');
}

function setFillColorPdf(PDF_MC_Table $pdf, $rgb) {
    $pdf->SetFillColor($rgb[0], $rgb[1], $rgb[2]);
}

function addTitulo(FPDF $pdf, $titulo, $h = H, $ln = 0) {
    $titulos = explode('_', $titulo);
    $c = 1;
    $pdf->SetFont(FAMILY, style()[''], size()[11]);
    foreach ($titulos as $tit) {
        if ($c == 3)
            $pdf->SetFont(FAMILY, style()['B'], size()[11]);
        $pdf->Cell(0, $h, strtoupper(utf8_decode(acentoUpperApp($tit))), NO_BORDER, LN, align()['C']);
        if ($ln)
            $pdf->Ln($ln);
        $c++;
    }
}

function addTituloConvocatoria(FPDF $pdf) {
    $titulos = explode('_', $titulo);
    foreach ($titulos as $tit) {
        $pdf->Cell(0, $h, strtoupper(utf8_decode(acentoUpperApp($tit))), NO_BORDER, LN, align()['C']);
        if ($ln)
            $pdf->Ln($ln);
    }
}

function addSubTitulo(FPDF $pdf, $subTitulo) {
    if (is_array($subTitulo)) {
        for ($i = 0; $i < count($subTitulo); $i++) {
            if (!empty($subTitulo[$i])) {
                if ($i + 1 == count($subTitulo)) {
                    $pdf->SetFont(FAMILY, style()['BU'], size()[12]);
                    $pdf->Cell(0, 5, strtoupper(utf8_decode(acentoUpperApp($subTitulo[$i]))), NO_BORDER, LN, align()['C']);
                } else {
                    $pdf->SetFont(FAMILY, style()['B'], size()[12]);
                    $pdf->Cell(0, 5, strtoupper(utf8_decode(acentoUpperApp($subTitulo[$i]))), NO_BORDER, LN, align()['C']);
                }
            } else {
                $pdf->Ln(5);
            }
        }
    } else {
        $pdf->SetFont(FAMILY, style()['BU'], size()[12]);
        $pdf->Cell(0, 5, strtoupper(utf8_decode(acentoUpperApp($subTitulo))), NO_BORDER, LN, align()['C']);
    }
}

function criaPdf($orientation, $size, $subTitulo, $ln = 0) {
    $pdf = new PDF_MC_Table();
    $pdf->AliasNbPages();
    $pdf->AddPage($orientation, $size);
    $pdf->Image(IMG_LOGO_PDF, $orientation == 'P' ? CENTER_IMG_P : CENTER_IMG_L, NULL, IMG_WIDTH);
    addTitulo($pdf, PDF_TITULO);
    if ($ln) {
        $pdf->Ln(3);
    }
    addSubTitulo($pdf, ($subTitulo));
    $pdf->Ln(5);
    setFillColorPdf($pdf, rgb1());
    return $pdf;
}

function criaPdf21($orientation, $size, $subTitulo, $ln = 0) {
    $pdf = new PDF_MC_Table();
    $pdf->AliasNbPages();
    $pdf->AddPage($orientation, $size);
    $pdf->Ln(1);
    $pdf->SetFont(FAMILY, style()['B'], size()[12]);
    addTitulo($pdf, PDF_TITULO);
    if ($ln) {
        $pdf->Ln(3);
    }
    addSubTitulo($pdf, acentoUpperApp($subTitulo));
    $pdf->Ln(5);
    setFillColorPdf($pdf, rgb1());
    return $pdf;
}

function criaPdfT($orientation, $size, $subTitulo) {
    $pdf = new PDF_MC_Table();
    $pdf->AliasNbPages();
    $pdf->SetAuthor(utf8_decode('Sampaio André'));
    $pdf->AddPage($orientation, $size);
    $pdf->Image(IMG_LOGO_PDF, $orientation == 'P' ? CENTER_IMG_P : CENTER_IMG_L, NULL, IMG_WIDTH);
    $pdf->Ln(3);
    $pdf->SetFont(FAMILY, style()['N'], size()[12]);
    addTitulo($pdf, PDF_TITULO . '_' . $subTitulo);
    $pdf->Ln(5);
    setFillColorPdf($pdf, rgb1());
    return $pdf;
}

function criarPdfRes(FPDF $pdf, $orientation, $size, $subTitulo, $ln = 0) {
    $pdf->AliasNbPages();
    $pdf->SetAuthor(utf8_decode('Sampaio André'));
    $pdf->AddPage($orientation, $size);
    $pdf->AliasNbPages();
    $pdf->Image(APP_IMG_MIN, 10, 10, 20, 20);
    $pdf->Image(IMG_LOGO_PDF, ($orientation == 'P' ? 175 : 250), 10, 30, 20);
    $pdf->Ln(25);
    $pdf->SetFont(FAMILY, style()['N'], size()[10]);
    addTitulo($pdf, PDF_TITULO1);
    $pdf->SetFont(FAMILY, 'BU', 11);
    $pdf->Ln(2);
    addTitulo($pdf, $subTitulo);
    $pdf->Ln(5);
    setFillColorPdf($pdf, rgb1());
    return $pdf;
}

function definePdf(FPDF $pdf, $orientation, $size, $subTitulo, $ln = 0) {
    $pdf->AddPage($orientation, $size);
    $pdf->AliasNbPages();
    $pdf->Image(IMG_LOGO_PDF, $orientation == 'P' ? CENTER_IMG_P : CENTER_IMG_L, NULL, IMG_WIDTH);
    $pdf->Ln(1);
    $pdf->SetFont(FAMILY, style()['N'], size()[11]);
    addTitulo($pdf, PDF_TITULO);
    $pdf->SetFont(FAMILY, style()['B'], size()[11]);
    addTitulo($pdf, $subTitulo);
    $pdf->Ln(3);
    setFillColorPdf($pdf, rgb());
}

function definePdfT(FPDF $pdf, $orientation, $size, $subTitulo, $ln = 0) {
    $pdf->AddPage($orientation, $size);
    $pdf->Image(IMG_LOGO_PDF, $orientation == 'P' ? CENTER_IMG_P : CENTER_IMG_L, NULL, IMG_WIDTH);
    $pdf->Ln(3);
    $pdf->SetFont(FAMILY, style()['N'], size()[12]);
    addTitulo($pdf, PDF_TITULO);
    if ($ln) {
        $pdf->Ln(3);
    }
    addTitulo($pdf, $subTitulo);
    $pdf->Ln(5);
    setFillColorPdf($pdf, rgb1());
}

function thead(FPDF $pdf, $thead, $w, $a, $cor, $h = H, $textSize = 12) {
    $pdf->SetFont(FAMILY, style()['B'], size()[12]);
    for ($i = 0; $i < count($thead); $i++) {
        $pdf->Cell($w[$i], $h, utf8_decode($thead[$i]), BORDER, NO_LN, $a[$i], $cor);
    }
    $pdf->Ln();
}

function thead2(FPDF $pdf, $thead, $w, $align, $cor) {
    $pdf->SetFont(FAMILY, style()['B'], size()[12]);
    for ($i = 0; $i < count($thead); $i++) {
        $pdf->Cell($w[$i], H, utf8_decode($thead[$i]), BORDER, NO_LN, $align[$i], $cor);
    }
    $pdf->Ln();
}

function vistoTracos($tamanho) {
    $string = "";
    if ($tamanho > 1 && $tamanho < 100) {
        for ($i = 2; $i < $tamanho; $i++) {
            $string .= "_";
        }
    }
    return $string;
}

function isArray($var, $i) {
    $texto = isset($var[$i]) ? $var[$i] : $var;
    return @substr($texto, 0, 3) == 'TV$' ? vistoTracos(substr($texto, 3, 2)) : $texto;
}

function cellPdfFoto(FPDF $pdf, $textos, $w = array(40, 40, 110), $border = 1, $alings = array('R', 'L'), $style = array('B', ''), $size = 12, $ln = 1) {
    for ($i = 0; $i < count($textos); $i++) {
        $text = explode('_', $textos[$i]);
        $pdf->Cell($w[0], H, "", NO_BORDER, NO_LN, align()['R']);
        $pdf->SetFont(FAMILY, $style[0], $size);
        $pdf->Cell($w[1], H, texto_interno($text[0] . ":"), $border, NO_LN, $alings[0]);
        $pdf->SetFont(FAMILY, $style[1], $size);
        $pdf->Cell($w[2], H, texto_interno($text[1]), $border, LN, $alings[1]);
        $pdf->Ln($ln);
    }
}

function cellPdfNoFoto(FPDF $pdf, $textos, $w = array(40, 150), $border = 1, $alings = array('R', 'L'), $style = array('B', ''), $size = 12, $ln = 1) {
    for ($i = 0; $i < count($textos); $i++) {
        if (!empty($textos[$i])) {
            $text = explode('_', $textos[$i]);
            $pdf->SetFont(FAMILY, $style[0], $size);
            $pdf->Cell($w[0], H, texto_interno($text[0] . ":"), $border, NO_LN, $alings[0], 1);
            $pdf->SetFont(FAMILY, $style[1], $size);
            $pdf->Cell($w[1], H, texto_interno($text[1]), $border, LN, $alings[1]);
            $pdf->Ln($ln);
        }
    }
}

function totalPDF($t) {
    return empty($t) ? '0' : $t;
}

//array(183, 203, 227);
function coresTextCell(FPDF $pdf, $cor) {
    $cores = $cor == 'V' ? array(255, 0, 0) : ($cor == 'G' ? array(0, 255, 0) : ($cor == 'A' ? rgb1() : ($cor == 'Z' ? array(0, 0, 225) : array(0, 0, 0))));
    $pdf->SetTextColor($cores[0], $cores[1], $cores[2]);
}

function cellPdf(FPDF $pdf, $textos, $w, $border, $alings, $style, $cor, $ln = 0, $size = 12, $texColor = 'P', $h = H, $family = FAMILY) {
    foreach ($textos as $texto) {
        $text = explode('_', $texto);
        for ($i = 0; $i < count($text); $i++) {
            $pdf->SetFont($family, isArray($style, $i), $size);
            coresTextCell($pdf, isArray($texColor, $i));
            $pdf->Cell(isArray($w, $i), isArray($h, $i), texto_interno(isArray($text, $i)), isArray($border, $i), ($i + 1 == count($text) ? LN : NO_LN), isArray($alings, $i), isArray($cor, $i));
            $pdf->SetTextColor(0, 0, 0);
        }
        if ($i + 1 != count($textos) && $ln) {
            $pdf->Ln($ln);
        }
    }
}

function diaDaSemana($data) {
    $days = array('Domingo', 'Sábado', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira');
    $dia = date('w', strtotime($data));
    return !empty($days[$dia]) ? $days[$dia] : '';
}

function horaHminDoTime($time) {
    $ex = explode(':', $time);
    return $ex[0] . 'H' . (!empty($ex[1]) ? $ex[1] : '');
}

function dataActualPdf(FPDF $pdf) {
    cellPdf($pdf, array(('Criado Aos: ' . date('d/m/Y H:i:s'))), 0, 0, 'L', 'B', 0, 0, 10);
}

function fimPdf(FPDF $pdf, $nome, $i = 'I', $inici = (APP_SIGLA_PDF . '_')) {
    $pdf->SetAuthor(utf8_decode('Sampaio André'));
    $pdf->SetTitle($inici . strtoupper($nome) . '.pdf');
    $pdf->Output($nome . '.pdf', $i);
    $_SESSION['TOKENXLSX'] = 'SIM';
}

function falha($tipo) {
    switch (intval($tipo)) {
        case 1:
            return "FALHA 001";
        case 2:
            return "FALHA 002";
        case 3:
            return "FALHA 003";
        case 4:
            return "FALHA 004";
        default :
            return "FALHA";
    }
}

function meses($mes) {
    return $mes == '1' ? 'Janeiro' : ($mes == '2' ? 'Fevereiro' : ($mes == '3' ? 'Março' : ($mes == '4' ? 'Abril' : ($mes == '5' ? 'Maio' : ($mes == '6' ? 'Junho' : ($mes == '7' ? 'Julho' : ($mes == '8' ? 'Agosto' : ($mes == '9' ? 'Setembro' : ($mes == '10' ? 'Outubro' : ($mes == '11' ? 'Novembro' : ($mes == '12' ? 'Dezembro' : '')))))))))));
}

function dataExtenco($data) {
    if (empty($data)) {
        return $data;
    }
    $ano = (substr($data, 0, 4) != '0000' ? substr($data, 0, 4) : '');
    $mes = meses(substr($data, 5, 2) != '00' ? substr($data, 5, 2) : '');
    $dia = (substr($data, 8, 2) != '00' ? substr($data, 8, 2) : '');
    return "$dia de $mes de $ano";
}

function footerPage(FPDF $pdf, $page, $text = 1) {
    $pdf->SetAutoPageBreak(false);
    $pdf->SetY(-15);
    cellPdf($pdf, array((APP_EMP_NOME) . '_' . ($text == 1 ? 'Página ' : '') . ($page)), array(95, 95), 0, array('L', 'R'), '', 0, 0, 8);
    $pdf->SetAutoPageBreak(true);
}

function corTextNota($nota) {
    return ($nota >= 0 && $nota < 10) ? 1 : 0;
}

function corEstadoRf($estado, $t = '') {
    return $estado == '1' ? 0 : ($t == '' ? 0 : 1);
}

function numeroExtenco($numero) {
    $array = array(0 => 'Zero', 1 => 'Um', 2 => 'Dois', 3 => 'Três', 4 => 'Quatro', 5 => 'Cinco', 6 => 'Seis', 7 => 'Sete', 8 => 'Oito', 9 => 'Nove', 10 => 'Dez', 11 => 'Onze', 12 => 'Doze', 13 => 'Treze', 14 => 'Catorze', 15 => 'Quinze', 16 => 'Dezasseis', 17 => 'Dezassete', 18 => 'Dezoito', 19 => 'Dezanove', 20 => 'Vinte');
    foreach ($array as $key => $value) {
        if ($key == intval($numero)) {
            return $value;
        }
    }
    return $numero;
}

function fimDocXLSX(eiseXLSX $xlsx, $file) {
    $_SESSION['TOKENXLSX'] = 'SIM';
    $xlsx->Output($file, "F");
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    unlink($file);
    exit;
}

function atencaoExamePdf11(FPDF $pdf, $atencao, $aling = 'J') {
    $tets = $atencao;
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont(FAMILY, '', 12);
    $a = 0;
    foreach ($tets as $value) {
        $pdf->SetFont(FAMILY, ($a == 0 ? 'B' : ''), 12);
        if ($value) {
            $pdf->MultiCell(0, H, utf8_decode($value), 0, $aling);
            $pdf->Ln(1);
        }
        $a++;
    }
}

function hederExameAdd(FPDF $pdf, $codI, $y = 80, $cy = 66) {
    $pdf->SetY($pdf->GetY() + 3);
    setFillColorPdf($pdf, rgb1());
//    $pdf->Code39(90, $cy, $codV . APP_CODEXAME, 0.8, 8, 8, 3, 0, FALSE);
//    cellPdf($pdf, array($codI), 0, 0, 'R', 'B', 0);
    cellPdf($pdf, array('INSTRUÇÕES DE PREENCHIMENTO'), 0, 0, 'C', 'B', 0);
//    $pdf->Rect($pdf->GetX(), $pdf->GetY() - 5, 190, 0);
    $pdf->Rect($pdf->GetX() + 34.45, $pdf->GetY() + 0.90, 3, 3, 'FD');
    cellPdf($pdf, array('Pinta todo interior do     da sua resposta_Utilize esferográfica de tinta preta ou azul_Não é permitido o uso de corretor'), array(63.33, 63.33, 63.33), 0, 'C', '', 0, 1, 10);
    $pdf->Rect($pdf->GetX(), $pdf->GetY() - 1, 190, 0);
    $pdf->SetY($pdf->GetY() + 3); //tamanho padram para listar opcoes
}

function respPerExamePDFres(FPDF $pdf, $resposta, $chave, $t, $cont, $c, $j, $endRes = '', $codRes = '') {
    //OPCAO A,B,C,D
    $pdf->SetFont(FAMILY, 'B', 10);
//    $o = (($resposta['classificacao'] == '0' ? (str_pad($cont, 2, 0, STR_PAD_LEFT)) . ' ' : '')) . ($resposta['classificacao'] == '0' ? $resposta['opcao'] : ($resposta['classificacao'] == '1' ? '' : ($resposta['classificacao'] == '2' ? '' : ($resposta['classificacao'] == '3' ? ' ' .  '' : ($resposta['classificacao'] == '4' ? '' : '')))));
    $pdf->Cell(1, H, (($resposta['classificacao'] == '0' ? (str_pad($cont, 2, 0, STR_PAD_LEFT)) . ' ' : '')), 0, 0, 'R');
    //COD DE BARRA
    $pdf->Code39($pdf->GetX() + 8, $pdf->GetY(), str_pad($resposta['cod'], 3, 0, STR_PAD_LEFT), 0.6, 5, 8, 2.30, 4, TRUE, '');
//    $pdf->Code39($pdf->GetX() + 8, $pdf->GetY(), str_pad($resposta['cod'], 3, 0, STR_PAD_LEFT), 0.6, 5, 8, 2.30, 4, TRUE, (empty($chave[1]) && !empty($chave[0]) && $resposta['opcao'] == 'A' ? ' ' . $chave[0] . 'v' : ($chave[1] == $resposta['cod'] ? ' ' . $chave[0] . 'v' : ($resposta['classificacao'] == '1' ? '' : (!(empty($chave[1]) && !empty($chave[0])) && $resposta['classificacao'] == '2' ? round(($chave[0] * 0.5), 2) . 'v' : (!(empty($chave[1]) && !empty($chave[0])) && $resposta['classificacao'] == '3' ? round(($chave[0] * 0.7), 2) . 'v' : (!(empty($chave[1]) && !empty($chave[0])) && $resposta['classificacao'] == '4' ? $chave[0] . 'v' : '')))) )));
    //TEXTO CAIXA PINTAR
    setFillColorPdf($pdf, rgb4());
    $pdf->Cell(3, 3, '', 1, 0, 'C', (((!empty($chave[2]) && $chave[2] == $resposta['cod']) || $codRes == $resposta['cod']) ? 1 : 0));
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->Cell(30, 3, '', 0, (($c == 5 || $j == $t) ? 1 : 0), 'C');
    if ($j == $t) {
        $pdf->Ln(5);
    }
}

function respPerExamePDF(FPDF $pdf, $resposta, $chave, $t, $cont, $c, $j, $endRes = '', $codRes = '') {
    //OPCAO A,B,C,D
    $pdf->SetFont(FAMILY, 'B', 10);
    $o = (($resposta['opcao'] == 'A' ? (str_pad($cont, 2, 0, STR_PAD_LEFT)) . ' ' : '')) . ($resposta['classificacao'] == '0' ? $resposta['opcao'] : ($resposta['classificacao'] == '1' ? APP_CODMAU . ' 0%' : ($resposta['classificacao'] == '2' ? APP_CODSUF . ' 50%' : ($resposta['classificacao'] == '3' ? ' ' . APP_CODBOM . ' 70%' : ($resposta['classificacao'] == '4' ? APP_CODEXC . ' 100%' : '')))));
    $pdf->Cell(($resposta['opcao'] == 'A' ? 8.5 : 14), H, $o, 0, 0, 'R');
    //COD DE BARRA
//    $pdf->Code39($pdf->GetX() + 8, $pdf->GetY(), str_pad($resposta['cod'], 3, 0, STR_PAD_LEFT), 0.6, 5, 8, 2.31, 4, TRUE);
    $pdf->Code39($pdf->GetX() + 8, $pdf->GetY(), str_pad($resposta['cod'], 3, 0, STR_PAD_LEFT), 0.6, 5, 8, 2.31, 4, TRUE, (empty($chave[1]) && !empty($chave[0]) && $resposta['opcao'] == 'A' ? ' ' . $chave[0] . 'v' : ($chave[1] == $resposta['cod'] ? ' ' . $chave[0] . 'v' : ($resposta['classificacao'] == '1' ? '' : (!(empty($chave[1]) && !empty($chave[0])) && $resposta['classificacao'] == '2' ? round(($chave[0] * 0.5), 2) . 'v' : (!(empty($chave[1]) && !empty($chave[0])) && $resposta['classificacao'] == '3' ? round(($chave[0] * 0.7), 2) . 'v' : (!(empty($chave[1]) && !empty($chave[0])) && $resposta['classificacao'] == '4' ? $chave[0] . 'v' : '')))) )));
//    $pdf->Code39($pdf->GetX() + 6, $pdf->GetY(), str_pad($resposta['cod'], 3, 0, STR_PAD_LEFT), 0.6, 5, 8, 2.31, 4, TRUE, (empty($chave[1]) && !empty($chave[0]) && $resposta['opcao'] == 'A' ? ' ' . $chave[0] . 'v' : ($chave[1] == $resposta['cod'] ? ' ' . $chave[0] . 'v' : ($resposta['classificacao'] == '1' ? '' : (!(empty($chave[1]) && !empty($chave[0])) && $resposta['classificacao'] == '2' ? round(($chave[0] * 0.5), 2) . 'v' : (!(empty($chave[1]) && !empty($chave[0])) && $resposta['classificacao'] == '3' ? round(($chave[0] * 0.7), 2) . 'v' : (!(empty($chave[1]) && !empty($chave[0])) && $resposta['classificacao'] == '4' ? $chave[0] . 'v' : '')))) )));
    //TEXTO CAIXA PINTAR
    setFillColorPdf($pdf, array(0, 0, 0));
    $pdf->Cell(3, 3, '', 1, 0, 'C', (((!empty($chave[2]) && $chave[2] == $resposta['cod']) || $codRes == $resposta['cod']) ? 1 : 0));
//    $pdf->Rect($pdf->GetX() + 1, $pdf->GetY(), 1.5, 1.5, 0);
//    $pdf->Rect($pdf->GetX()+1, $pdf->GetY()+3.5, 1.5, 1.5, 0);
//    $pdf->SetX($pdf->GetX() + 1);
//    $pdf->Cell(1.5, 1.5, '', 1, 0, 'C');
//    $pdf->Cell(1.5, 1.5, '', 1, 0, 'C');
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->Cell(30, 3, '', 0, (($c == 5 || $j == $t) ? 1 : 0), 'C');
    if ($j == $t) {
        $pdf->Ln(5);
    }
}

function cabecalhoProva($pdf, $cand, $turma, $cor = 1) {
    setFillColorPdf($pdf, ($cor == 1 ? rgb1() : rgb()));
    $pdf->SetY(8);
    $z = $cand['genero'] == '2' ? 'a' : 'o';
    $pdf->Image(IMG_LOGO_PDF, 62, 8, 15, 15);
    $pdf->SetX(134);
    $pdf->SetFont(FAMILY, 'B', 11);
    $pdf->MultiCell(70, 3.5, texto_interno(acentoUpperApp(strtoupper($cand['nome']))), 0, 'C');
    cellPdf($pdf, array('_Nº BI_Nº FICHA'), array(129, 40, 30), array(0, 1, 1), 'C', 'B', array(0, 1, 1), 0, 11, 'P', 4);
    cellPdf($pdf, array('_' . str_replace('_', '', $cand['nbi']) . '_' . $cand['codigo']), array(129, 40, 30), array(0, 1, 1), 'C', '', 0, 0, 11, 'P', 3.90);
    $pdf->Ln(3);
    cellPdf($pdf, array('_TV$32      ', '_(Assinatura d' . $z . ' Candidat' . $z . ')      '), array(112, 84), 0, 'R', 'B', 0, 0, 11);
    $pdf->SetDash(4, 2);
    $pdf->Line(80, 0, 282, 101);
    $pdf->SetDash();
    setFillColorPdf($pdf, ($cor == 1 ? rgb1() : rgb()));
    $pdf->SetFont(FAMILY, style()['N'], size()[12]);
    $pdf->SetY(23);
    //l.nome loc, l.genero locG,
    cellPdf($pdf, explode('_', PDF_TITULO2), 115, 0, 'C', '', 0, 0, 11, 'P');
    $pdf->SetFont(FAMILY, 'B', 11);
    $pdf->MultiCell(115, H, texto_interno(acentoUpperApp('Província ' . $turma->genProv . ' ' . $turma->prov)), 0, 'C');
    $pdf->MultiCell(115, H, texto_interno(acentoUpperApp($turma->inst)), 0, 'C');
    $pdf->Code39(40, $pdf->GetY(), $cand['codigo'] . '1', 0.8, 10, 10, 4, 5.5, FALSE);
    $pdf->SetY(63);
    setFillColorPdf($pdf, rgb1());
    $pdf->MultiCell(190, H, $cand['codigo'], 0, 'R');
    $pdf->MultiCell(190, H, texto_interno($turma->local . ' Sala: ' . $turma->sala . '; Data do Exame: ' . date('d/m/y', strtotime($turma->data_exame)) . ' das ' . date('H:i', strtotime($turma->inicio)) . ' às ' . date('H:i', strtotime($turma->fim))), 1, 'J');
//    $pdf->MultiCell(190, H, texto_interno($turma->cod.') '.$turma->local . ' Sala: ' . $turma->sala . '; Data do Exame: ' . date('d/m/y', strtotime($turma->data_exame)) . ' das ' . date('H:i', strtotime($turma->inicio)) . ' às ' . date('H:i', strtotime($turma->fim))), 1, 'J');
}

function criarExameCand($pdf, $cand, $turma) {
    $pdf->AddPage('P', 'A4');
    $pdf->SetAutoPageBreak(false);
    cabecalhoProva($pdf, $cand, $turma);
    $diss = get_instance()->db->query("SELECT d.nome, ed.cod, ed.percentagem, COUNT(p.cod) per FROM pergunta p JOIN exame_disciplina ed ON p.cod_exame_disciplina=ed.cod JOIN disciplina d ON d.cod=ed.cod_disciplina JOIN variante v ON v.cod_exame=ed.cod_exame WHERE v.cod={$cand['cod_exame']} AND p.eliminado='0' GROUP BY ed.cod;")->result_array();
    $tD = 1;
    $strDis = "";
    $nomesD = array();
    foreach ($diss as $dis) {
        $strDis .= ($tD . ( ($tD + $dis['per'] - 1) != $tD ? (' a ' . ($tD + $dis['per'] - 1)) : '') . ') ' . $dis['nome'] . ' ' . $dis['percentagem'] . '% ; ');
        $tD = $tD + $dis['per'];
        $nomesD[$dis['cod']] = ($dis['nome']);
    }
    $pdf->SetFont(FAMILY, '', 11);
    $pdf->MultiCell(190, H, texto_externo(substr($strDis, 0, strlen($strDis) - 2)) . ' Variante: ' . $cand['serie'], 1, 'J');
    hederExameAdd($pdf, $cand['codigo']);
    $cont = 1;
    $cPag = 1;
    $ccc = 1;
    $chave = array('', '', '');
    $codEdp = '';
    $perguntas = get_instance()->db->query("SELECT p.cod,p.nome,p.ordem,p.cotacao,ed.cod codED FROM pergunta p JOIN exame_disciplina ed ON ed.cod=p.cod_exame_disciplina WHERE p.eliminado='0' AND ed.cod_exame IN(SELECT cod_exame FROM variante WHERE cod={$cand['cod_exame']}) ORDER BY p.ordem;")->result_array();
    foreach ($perguntas as $pergunta) {
        if (empty($codEdp) || $codEdp != $pergunta['codED']) {
            $pdf->ln(1);
            $pdf->SetFont(FAMILY, 'B', 12);
            $pdf->MultiCell(190, H, texto_externo(acentoUpperApp($nomesD[$pergunta['codED']])), 1, 'C');
            $pdf->ln(4);
            $codEdp = $pergunta['codED'];
        }
        $pdf->SetX($pdf->GetX() + 8);
        $respostas = get_instance()->db->query("SELECT * FROM resposta WHERE eliminado='0' AND tipo='1' AND cod_pergunta=" . $pergunta['cod'] . " ORDER BY opcao")->result_array();
        $c = 1;
        $j = 1;
        foreach ($respostas as $resposta) {
            $a = respPerExamePDF($pdf, $resposta, $chave, count($respostas), $cont, $c, $j, '', '');
            $c = $c < 5 ? $c + 1 : 0;
            $j++;
        }
        ++$cont;
        ++$cPag;
        ++$ccc;
        if ($cPag == 26 && $cont < count($perguntas)) {
            $pdf->AddPage('P', 'A4');
            setFillColorPdf($pdf, rgb1());
            $pdf->Code39(100, 41, $cand['codigo'] . '2', 0.8, 10, 10, 4, 5.5, TRUE);
            $cPag = 1;
        }
    }
}
