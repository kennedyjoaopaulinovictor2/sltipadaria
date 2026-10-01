<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if ($utilizadores) {
    //criar pdf
    $pdf = criaPdf('L', 'A4', 'LISTA DE UTILIZADORES DO SISTEMA');
    //cabecalho da tabela
    $a = array('C', 'L',  'L', 'L', 'L', 'L', 'C');
    $w = array(10, 90, 33, 42, 46, 18, 38);
    $thead = array('Nº', 'Nome', 'Telefone', 'Utilizador', 'Área de Trabalho', 'Estado', 'Data de Criação');
    thead($pdf, $thead, $w, $a, COR);

    //corpo da tabela 
    $pdf->SetFont(FAMILY, style()['N'], size()[11]);
    $pdf->SetWidths($w);
    $pdf->SetAligns($a);

    $c = 0;
    foreach ($utilizadores as $utilizador) {
        $pdf->SetCors(array(0, 0, 0, 0, 0, 0, ($utilizador->estado != '1' ? 1 : 0), 0));
        $pdf->Row(array(++$c, utf8_decode($utilizador->nomeU), $utilizador->telefone, $utilizador->username, $utilizador->nomeM, ($utilizador->estado != '1' ? 'Inactivo' : 'Activo'), $utilizador->data_criacao));
    }
    dataActualPdf($pdf);
    fimPdf($pdf, 'utilizadores');
}