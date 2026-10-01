<?php

error_reporting(0);
ini_set("display_errors", 0);
include_once 'fpdf.php';

class PDF_MC_Table extends FPDF {

    var $wLine; // Maximum width of the line
    var $hLine; // Height of the line
    var $Text; // Text to display
    var $border;
    var $align; // Justification of the text
    var $fill;
    var $Padding;
    var $lPadding;
    var $tPadding;
    var $bPadding;
    var $rPadding;
    var $TagStyle; // Style for each tag
    var $Indent;
    var $Space; // Minimum space between words
    var $PileStyle;
    var $Line2Print; // Line to display
    var $NextLineBegin; // Buffer between lines 
    var $TagName;
    var $Delta; // Maximum width minus width
    var $StringLength;
    var $LineLength;
    var $wTextLine; // Width minus paddings
    var $nbSpace; // Number of spaces in the line
    var $Xini; // Initial position
    var $href; // Current URL
    var $TagHref; // URL for a cell
    //TABLE
    var $widths;
    var $aligns;
    var $borders;
    var $hs;
    var $cores;
    var $coreCells;
    var $stylesRow;
    //grafico
    var $legends;
    var $wLegend;
    var $sum;
    var $NbVal;

    function criaPdf($l, $mm, $size) {
        $this->FPDF($l, $mm, $size);
    }

    function SetWidths($w) {
        //Set the array of column widths
        $this->widths = $w;
    }

    function SetHs($h) {
        //Set the array of column widths
        $this->hs = $h;
    }

    function SetAligns($a) {
        //Set the array of column alignments
        $this->aligns = $a;
    }

    function SetBorders($b) {
        //Set the array of column alignments
        $this->borders = $b;
    }

    function SetStyles($s) {
        //Set the array of column alignments
        $this->stylesRow = $s;
    }

    function SetCors($cor) {
        //Set the array of column alignments
        $this->cores = $cor;
    }

    function SetCorCells($cor) {
        //Set the array of column alignments
        $this->coreCells = $cor;
    }

    function Row($data, $border = 0) {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //add style
            if (isset($this->stylesRow[0]) && isset($this->stylesRow[1][$i]) && isset($this->stylesRow[2])) {
                $this->SetFont($this->stylesRow[0], $this->stylesRow[1][$i], $this->stylesRow[2]);
            }
            $cor = isset($this->cores[$i]) ? $this->cores[$i] : 0;
            $corCell = isset($this->coreCells[$i]) ? $this->coreCells[$i] : 0;
            if ($cor == 1) {
                $this->SetTextColor(255, 0, 0);
            }
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->SetDrawColor(0, 0, 0);
            if (empty($this->borders))
                $this->Rect($x, $y, $w, $h, ($corCell == 1 ? 'FD' : ''));
            //Print the text
            $this->MultiCell($w, isset($this->hs) ? $this->hs : 5, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
            $this->SetTextColor(0, 0, 0);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h) {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt) {
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

     function Code39($xpos, $ypos, $code, $baseline = 0.5, $height = 5, $text = 10, $hehgT = 4, $xposT = 5.5, $textBoo = true, $texAdd ='') {

        $wide = $baseline;
        $narrow = $baseline / 3;
        $gap = $narrow;

        $barChar['0'] = 'nnnwwnwnn';
        $barChar['1'] = 'wnnwnnnnw';
        $barChar['2'] = 'nnwwnnnnw';
        $barChar['3'] = 'wnwwnnnnn';
        $barChar['4'] = 'nnnwwnnnw';
        $barChar['5'] = 'wnnwwnnnn';
        $barChar['6'] = 'nnwwwnnnn';
        $barChar['7'] = 'nnnwnnwnw';
        $barChar['8'] = 'wnnwnnwnn';
        $barChar['9'] = 'nnwwnnwnn';
        $barChar['A'] = 'wnnnnwnnw';
        $barChar['B'] = 'nnwnnwnnw';
        $barChar['C'] = 'wnwnnwnnn';
        $barChar['D'] = 'nnnnwwnnw';
        $barChar['E'] = 'wnnnwwnnn';
        $barChar['F'] = 'nnwnwwnnn';
        $barChar['G'] = 'nnnnnwwnw';
        $barChar['H'] = 'wnnnnwwnn';
        $barChar['I'] = 'nnwnnwwnn';
        $barChar['J'] = 'nnnnwwwnn';
        $barChar['K'] = 'wnnnnnnww';
        $barChar['L'] = 'nnwnnnnww';
        $barChar['M'] = 'wnwnnnnwn';
        $barChar['N'] = 'nnnnwnnww';
        $barChar['O'] = 'wnnnwnnwn';
        $barChar['P'] = 'nnwnwnnwn';
        $barChar['Q'] = 'nnnnnnwww';
        $barChar['R'] = 'wnnnnnwwn';
        $barChar['S'] = 'nnwnnnwwn';
        $barChar['T'] = 'nnnnwnwwn';
        $barChar['U'] = 'wwnnnnnnw';
        $barChar['V'] = 'nwwnnnnnw';
        $barChar['W'] = 'wwwnnnnnn';
        $barChar['X'] = 'nwnnwnnnw';
        $barChar['Y'] = 'wwnnwnnnn';
        $barChar['Z'] = 'nwwnwnnnn';
        $barChar['-'] = 'nwnnnnwnw';
        $barChar['.'] = 'wwnnnnwnn';
        $barChar[' '] = 'nwwnnnwnn';
        $barChar['*'] = 'nwnnwnwnn';
        $barChar['$'] = 'nwnwnwnnn';
        $barChar['/'] = 'nwnwnnnwn';
        $barChar['+'] = 'nwnnnwnwn';
        $barChar['%'] = 'nnnwnwnwn';

        $this->SetFont('Arial', '', $text);
        if ($textBoo)
            $this->Text($xpos + (!empty($texAdd) ? 0 : $xposT), $ypos + $height + $hehgT, $code.(!empty($texAdd) ? ' '.$texAdd : ''));
        $this->SetFillColor(0);

        $code = '*' . strtoupper($code) . '*';
        for ($i = 0; $i < strlen($code); $i++) {
            $char = $code[$i];
            if (!isset($barChar[$char])) {
                $this->Error('Invalid character in barcode: ' . $char);
            }
            $seq = $barChar[$char];
            for ($bar = 0; $bar < 9; $bar++) {
                if ($seq[$bar] == 'n') {
                    $lineWidth = $narrow;
                } else {
                    $lineWidth = $wide;
                }
                if ($bar % 2 == 0) {
                    $this->Rect($xpos, $ypos, $lineWidth, $height, 'F');
                }
                $xpos += $lineWidth;
            }
            $xpos += $gap;
        }
    }

    //HTML
    function WriteTag($w, $h, $txt, $border = 0, $align = "J", $fill = false, $padding = 0) {
        $this->wLine = $w;
        $this->hLine = $h;
        $this->Text = trim($txt);
        $this->Text = preg_replace("/\n|\r|\t/", "", $this->Text);
        $this->border = $border;
        $this->align = $align;
        $this->fill = $fill;
        $this->Padding = $padding;

        $this->Xini = $this->GetX();
        $this->href = "";
        $this->PileStyle = array();
        $this->TagHref = array();
        $this->LastLine = false;

        $this->SetSpace();
        $this->Padding();
        $this->LineLength();
        $this->BorderTop();

        while ($this->Text != "") {
            $this->MakeLine();
            $this->PrintLine();
        }
        $this->SetTextColor(0, 0, 0);
        $this->BorderBottom();
    }

    function SetStyle($tag, $family, $style, $size, $color, $indent = -1) {
        $tag = trim($tag);
        $this->TagStyle[$tag]['family'] = trim($family);
        $this->TagStyle[$tag]['style'] = trim($style);
        $this->TagStyle[$tag]['size'] = trim($size);
        $this->TagStyle[$tag]['color'] = trim($color);
        $this->TagStyle[$tag]['indent'] = $indent;
    }

    // Private Functions

    function SetSpace() { // Minimal space between words
        $tag = $this->Parser($this->Text);
        $this->FindStyle($tag[2], 0);
        $this->DoStyle(0);
        $this->Space = $this->GetStringWidth(" ");
    }

    function Padding() {
        if (preg_match("/^.+,/", $this->Padding)) {
            $tab = explode(",", $this->Padding);
            $this->lPadding = $tab[0];
            $this->tPadding = $tab[1];
            if (isset($tab[2]))
                $this->bPadding = $tab[2];
            else
                $this->bPadding = $this->tPadding;
            if (isset($tab[3]))
                $this->rPadding = $tab[3];
            else
                $this->rPadding = $this->lPadding;
        }
        else {
            $this->lPadding = $this->Padding;
            $this->tPadding = $this->Padding;
            $this->bPadding = $this->Padding;
            $this->rPadding = $this->Padding;
        }
        if ($this->tPadding < $this->LineWidth)
            $this->tPadding = $this->LineWidth;
    }

    function LineLength() {
        if ($this->wLine == 0)
            $this->wLine = $this->w - $this->Xini - $this->rMargin;

        $this->wTextLine = $this->wLine - $this->lPadding - $this->rPadding;
    }

    function BorderTop() {
        $border = 0;
        if ($this->border == 1)
            $border = "TLR";
        $this->Cell($this->wLine, $this->tPadding, "", $border, 0, 'C', $this->fill);
        $y = $this->GetY() + $this->tPadding;
        $this->SetXY($this->Xini, $y);
    }

    function BorderBottom() {
        $border = 0;
        if ($this->border == 1)
            $border = "BLR";
        $this->Cell($this->wLine, $this->bPadding, "", $border, 0, 'C', $this->fill);
    }

    function DoStyle($tag) { // Applies a style
        $tag = trim($tag);
        $this->SetFont($this->TagStyle[$tag]['family'], $this->TagStyle[$tag]['style'], $this->TagStyle[$tag]['size']);

        $tab = explode(",", $this->TagStyle[$tag]['color']);
        if (count($tab) == 1)
            $this->SetTextColor($tab[0]);
        else
            $this->SetTextColor($tab[0], $tab[1], $tab[2]);
    }

    function FindStyle($tag, $ind) { // Inheritance from parent elements
        $tag = trim($tag);

        // Family
        if ($this->TagStyle[$tag]['family'] != "")
            $family = $this->TagStyle[$tag]['family'];
        else {
            reset($this->PileStyle);
            while (list($k, $val) = each($this->PileStyle)) {
                $val = trim($val);
                if ($this->TagStyle[$val]['family'] != "") {
                    $family = $this->TagStyle[$val]['family'];
                    break;
                }
            }
        }

        // Style
        $style = "";
        $style1 = strtoupper($this->TagStyle[$tag]['style']);
        if ($style1 != "N") {
            $bold = false;
            $italic = false;
            $underline = false;
            reset($this->PileStyle);
            while (list($k, $val) = each($this->PileStyle)) {
                $val = trim($val);
                $style1 = strtoupper($this->TagStyle[$val]['style']);
                if ($style1 == "N")
                    break;
                else {
                    if (strpos($style1, "B") !== false)
                        $bold = true;
                    if (strpos($style1, "I") !== false)
                        $italic = true;
                    if (strpos($style1, "U") !== false)
                        $underline = true;
                }
            }
            if ($bold)
                $style .= "B";
            if ($italic)
                $style .= "I";
            if ($underline)
                $style .= "U";
        }

        // Size
        if ($this->TagStyle[$tag]['size'] != 0)
            $size = $this->TagStyle[$tag]['size'];
        else {
            reset($this->PileStyle);
            while (list($k, $val) = each($this->PileStyle)) {
                $val = trim($val);
                if ($this->TagStyle[$val]['size'] != 0) {
                    $size = $this->TagStyle[$val]['size'];
                    break;
                }
            }
        }

        // Color
        if ($this->TagStyle[$tag]['color'] != "")
            $color = $this->TagStyle[$tag]['color'];
        else {
            reset($this->PileStyle);
            while (list($k, $val) = each($this->PileStyle)) {
                $val = trim($val);
                if ($this->TagStyle[$val]['color'] != "") {
                    $color = $this->TagStyle[$val]['color'];
                    break;
                }
            }
        }

        // Result
        $this->TagStyle[$ind]['family'] = $family;
        $this->TagStyle[$ind]['style'] = $style;
        $this->TagStyle[$ind]['size'] = $size;
        $this->TagStyle[$ind]['color'] = $color;
        $this->TagStyle[$ind]['indent'] = $this->TagStyle[$tag]['indent'];
    }

    function Parser($text) {
        $tab = array();
        // Closing tag
        if (preg_match("|^(</([^>]+)>)|", $text, $regs)) {
            $tab[1] = "c";
            $tab[2] = trim($regs[2]);
        }
        // Opening tag
        else if (preg_match("|^(<([^>]+)>)|", $text, $regs)) {
            $regs[2] = preg_replace("/^a/", "a ", $regs[2]);
            $tab[1] = "o";
            $tab[2] = trim($regs[2]);

            // Presence of attributes
            if (preg_match("/(.+) (.+)='(.+)'/", $regs[2])) {
                $tab1 = preg_split("/ +/", $regs[2]);
                $tab[2] = trim($tab1[0]);
                while (list($i, $couple) = each($tab1)) {
                    if ($i > 0) {
                        $tab2 = explode("=", $couple);
                        $tab2[0] = trim($tab2[0]);
                        $tab2[1] = trim($tab2[1]);
                        $end = strlen($tab2[1]) - 2;
                        $tab[$tab2[0]] = substr($tab2[1], 1, $end);
                    }
                }
            }
        }
        // Space
        else if (preg_match("/^( )/", $text, $regs)) {
            $tab[1] = "s";
            $tab[2] = ' ';
        }
        // Text
        else if (preg_match("/^([^< ]+)/", $text, $regs)) {
            $tab[1] = "t";
            $tab[2] = trim($regs[1]);
        }

        @$begin = strlen($regs[1]);
        $end = strlen($text);
        $text = substr($text, $begin, $end);
        $tab[0] = $text;

        return $tab;
    }

    function MakeLine() {
        $this->Text .= " ";
        $this->LineLength = array();
        $this->TagHref = array();
        $Length = 0;
        $this->nbSpace = 0;

        $i = $this->BeginLine();
        $this->TagName = array();

        if ($i == 0) {
            $Length = $this->StringLength[0];
            $this->TagName[0] = 1;
            $this->TagHref[0] = $this->href;
        }

        while ($Length < $this->wTextLine) {
            $tab = $this->Parser($this->Text);
            $this->Text = $tab[0];
            if ($this->Text == "") {
                $this->LastLine = true;
                break;
            }

            if ($tab[1] == "o") {
                array_unshift($this->PileStyle, $tab[2]);
                $this->FindStyle($this->PileStyle[0], $i + 1);

                $this->DoStyle($i + 1);
                $this->TagName[$i + 1] = 1;
                if ($this->TagStyle[$tab[2]]['indent'] != -1) {
                    $Length += $this->TagStyle[$tab[2]]['indent'];
                    $this->Indent = $this->TagStyle[$tab[2]]['indent'];
                }
                if ($tab[2] == "a")
                    $this->href = $tab['href'];
            }

            if ($tab[1] == "c") {
                array_shift($this->PileStyle);
                if (isset($this->PileStyle[0])) {
                    $this->FindStyle($this->PileStyle[0], $i + 1);
                    $this->DoStyle($i + 1);
                }
                $this->TagName[$i + 1] = 1;
                if ($this->TagStyle[$tab[2]]['indent'] != -1) {
                    $this->LastLine = true;
                    $this->Text = trim($this->Text);
                    break;
                }
                if ($tab[2] == "a")
                    $this->href = "";
            }

            if ($tab[1] == "s") {
                $i++;
                $Length += $this->Space;
                $this->Line2Print[$i] = "";
                if ($this->href != "")
                    $this->TagHref[$i] = $this->href;
            }

            if ($tab[1] == "t") {
                $i++;
                $this->StringLength[$i] = $this->GetStringWidth($tab[2]);
                $Length += $this->StringLength[$i];
                $this->LineLength[$i] = $Length;
                $this->Line2Print[$i] = $tab[2];
                if ($this->href != "")
                    $this->TagHref[$i] = $this->href;
            }
        }

        trim($this->Text);
        if ($Length > $this->wTextLine || $this->LastLine == true)
            $this->EndLine();
    }

    function BeginLine() {
        $this->Line2Print = array();
        $this->StringLength = array();

        if (isset($this->PileStyle[0])) {
            $this->FindStyle($this->PileStyle[0], 0);
            $this->DoStyle(0);
        }

        if (count($this->NextLineBegin) > 0) {
            $this->Line2Print[0] = $this->NextLineBegin['text'];
            $this->StringLength[0] = $this->NextLineBegin['length'];
            $this->NextLineBegin = array();
            $i = 0;
        } else {
            preg_match("/^(( *(<([^>]+)>)* *)*)(.*)/", $this->Text, $regs);
            $regs[1] = str_replace(" ", "", $regs[1]);
            $this->Text = $regs[1] . $regs[5];
            $i = -1;
        }

        return $i;
    }

    function EndLine() {
        if (end($this->Line2Print) != "" && $this->LastLine == false) {
            $this->NextLineBegin['text'] = array_pop($this->Line2Print);
            $this->NextLineBegin['length'] = end($this->StringLength);
            array_pop($this->LineLength);
        }

        while (end($this->Line2Print) === "")
            array_pop($this->Line2Print);

        $this->Delta = $this->wTextLine - end($this->LineLength);

        $this->nbSpace = 0;
        for ($i = 0; $i < count($this->Line2Print); $i++) {
            if ($this->Line2Print[$i] == "")
                $this->nbSpace++;
        }
    }

    function PrintLine() {
        $border = 0;
        if ($this->border == 1)
            $border = "LR";
        $this->Cell($this->wLine, $this->hLine, "", $border, 0, 'C', $this->fill);
        $y = $this->GetY();
        $this->SetXY($this->Xini + $this->lPadding, $y);

        if ($this->Indent != -1) {
            if ($this->Indent != 0)
                $this->Cell($this->Indent, $this->hLine);
            $this->Indent = -1;
        }

        $space = $this->LineAlign();
        $this->DoStyle(0);
        for ($i = 0; $i < count($this->Line2Print); $i++) {
            if (isset($this->TagName[$i]))
                $this->DoStyle($i);
            if (isset($this->TagHref[$i]))
                $href = $this->TagHref[$i];
            else
                $href = '';
            if ($this->Line2Print[$i] == "")
                $this->Cell($space, $this->hLine, "         ", 0, 0, 'C', false, $href);
            else
                $this->Cell($this->StringLength[$i], $this->hLine, $this->Line2Print[$i], 0, 0, 'C', false, $href);
        }

        $this->LineBreak();
        if ($this->LastLine && $this->Text != "")
            $this->EndParagraph();
        $this->LastLine = false;
    }

    function LineAlign() {
        $space = $this->Space;
        if ($this->align == "J") {
            if ($this->nbSpace != 0)
                $space = $this->Space + ($this->Delta / $this->nbSpace);
            if ($this->LastLine)
                $space = $this->Space;
        }

        if ($this->align == "R")
            $this->Cell($this->Delta, $this->hLine);

        if ($this->align == "C")
            $this->Cell($this->Delta / 2, $this->hLine);

        return $space;
    }

    function LineBreak() {
        $x = $this->Xini;
        $y = $this->GetY() + $this->hLine;
        $this->SetXY($x, $y);
    }

    function EndParagraph() {
        $border = 0;
        if ($this->border == 1)
            $border = "LR";
        $this->Cell($this->wLine, $this->hLine / 2, "", $border, 0, 'C', $this->fill);
        $x = $this->Xini;
        $y = $this->GetY() + $this->hLine / 2;
        $this->SetXY($x, $y);
    }

    function VCell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false) {
        //Output a cell
        $k = $this->k;
        if ($this->y + $h > $this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak()) {
            //Automatic page break
            $x = $this->x;
            $ws = $this->ws;
            if ($ws > 0) {
                $this->ws = 0;
                $this->_out('0 Tw');
            }
            $this->AddPage($this->CurOrientation, $this->CurPageSize);
            $this->x = $x;
            if ($ws > 0) {
                $this->ws = $ws;
                $this->_out(sprintf('%.3F Tw', $ws * $k));
            }
        }
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $s = '';
        // begin change Cell function 
        if ($fill || $border > 0) {
            if ($fill)
                $op = ($border > 0) ? 'B' : 'f';
            else
                $op = 'S';
            if ($border > 1) {
                $s = sprintf('q %.2F w %.2F %.2F %.2F %.2F re %s Q ', $border, $this->x * $k, ($this->h - $this->y) * $k, $w * $k, -$h * $k, $op);
            } else
                $s = sprintf('%.2F %.2F %.2F %.2F re %s ', $this->x * $k, ($this->h - $this->y) * $k, $w * $k, -$h * $k, $op);
        }
        if (is_string($border)) {
            $x = $this->x;
            $y = $this->y;
            if (is_int(strpos($border, 'L')))
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - $y) * $k, $x * $k, ($this->h - ($y + $h)) * $k);
            else if (is_int(strpos($border, 'l')))
                $s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ($this->h - $y) * $k, $x * $k, ($this->h - ($y + $h)) * $k);

            if (is_int(strpos($border, 'T')))
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - $y) * $k);
            else if (is_int(strpos($border, 't')))
                $s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - $y) * $k);

            if (is_int(strpos($border, 'R')))
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', ($x + $w) * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
            else if (is_int(strpos($border, 'r')))
                $s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', ($x + $w) * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);

            if (is_int(strpos($border, 'B')))
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - ($y + $h)) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
            else if (is_int(strpos($border, 'b')))
                $s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ($this->h - ($y + $h)) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
        }
        if (trim($txt) != '') {
            $cr = substr_count($txt, "\n");
            if ($cr > 0) { // Multi line
                $txts = explode("\n", $txt);
                $lines = count($txts);
                for ($l = 0; $l < $lines; $l++) {
                    $txt = $txts[$l];
                    $w_txt = $this->GetStringWidth($txt);
                    if ($align == 'U')
                        $dy = $this->cMargin + $w_txt;
                    elseif ($align == 'D')
                        $dy = $h - $this->cMargin;
                    else
                        $dy = ($h + $w_txt) / 2;
                    $txt = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));
                    if ($this->ColorFlag)
                        $s .= 'q ' . $this->TextColor . ' ';
                    $s .= sprintf('BT 0 1 -1 0 %.2F %.2F Tm (%s) Tj ET ', ($this->x + .5 * $w + (.7 + $l - $lines / 2) * $this->FontSize) * $k, ($this->h - ($this->y + $dy)) * $k, $txt);
                    if ($this->ColorFlag)
                        $s .= ' Q ';
                }
            }
            else { // Single line
                $w_txt = $this->GetStringWidth($txt);
                $Tz = 100;
                if ($w_txt > $h - 2 * $this->cMargin) {
                    $Tz = ($h - 2 * $this->cMargin) / $w_txt * 100;
                    $w_txt = $h - 2 * $this->cMargin;
                }
                if ($align == 'U')
                    $dy = $this->cMargin + $w_txt;
                elseif ($align == 'D')
                    $dy = $h - $this->cMargin;
                else
                    $dy = ($h + $w_txt) / 2;
                $txt = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));
                if ($this->ColorFlag)
                    $s .= 'q ' . $this->TextColor . ' ';
                $s .= sprintf('q BT 0 1 -1 0 %.2F %.2F Tm %.2F Tz (%s) Tj ET Q ', ($this->x + .5 * $w + .3 * $this->FontSize) * $k, ($this->h - ($this->y + $dy)) * $k, $Tz, $txt);
                if ($this->ColorFlag)
                    $s .= ' Q ';
            }
        }
        // end change Cell function 
        if ($s)
            $this->_out($s);
        $this->lasth = $h;
        if ($ln > 0) {
            //Go to next line
            $this->y += $h;
            if ($ln == 1)
                $this->x = $this->lMargin;
        } else
            $this->x += $w;
    }

    function Cell1($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '') {
        //Output a cell
        $k = $this->k;
        if ($this->y + $h > $this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak()) {
            //Automatic page break
            $x = $this->x;
            $ws = $this->ws;
            if ($ws > 0) {
                $this->ws = 0;
                $this->_out('0 Tw');
            }
            $this->AddPage($this->CurOrientation, $this->CurPageSize);
            $this->x = $x;
            if ($ws > 0) {
                $this->ws = $ws;
                $this->_out(sprintf('%.3F Tw', $ws * $k));
            }
        }
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $s = '';
// begin change Cell function
        if ($fill || $border > 0) {
            if ($fill)
                $op = ($border > 0) ? 'B' : 'f';
            else
                $op = 'S';
            if ($border > 1) {
                $s = sprintf('q %.2F w %.2F %.2F %.2F %.2F re %s Q ', $border, $this->x * $k, ($this->h - $this->y) * $k, $w * $k, -$h * $k, $op);
            } else
                $s = sprintf('%.2F %.2F %.2F %.2F re %s ', $this->x * $k, ($this->h - $this->y) * $k, $w * $k, -$h * $k, $op);
        }
        if (is_string($border)) {
            $x = $this->x;
            $y = $this->y;
            if (is_int(strpos($border, 'L')))
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - $y) * $k, $x * $k, ($this->h - ($y + $h)) * $k);
            else if (is_int(strpos($border, 'l')))
                $s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ($this->h - $y) * $k, $x * $k, ($this->h - ($y + $h)) * $k);

            if (is_int(strpos($border, 'T')))
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - $y) * $k);
            else if (is_int(strpos($border, 't')))
                $s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - $y) * $k);

            if (is_int(strpos($border, 'R')))
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', ($x + $w) * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
            else if (is_int(strpos($border, 'r')))
                $s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', ($x + $w) * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);

            if (is_int(strpos($border, 'B')))
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - ($y + $h)) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
            else if (is_int(strpos($border, 'b')))
                $s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ($this->h - ($y + $h)) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
        }
        if (trim($txt) != '') {
            $cr = substr_count($txt, "\n");
            if ($cr > 0) { // Multi line
                $txts = explode("\n", $txt);
                $lines = count($txts);
                for ($l = 0; $l < $lines; $l++) {
                    $txt = $txts[$l];
                    $w_txt = $this->GetStringWidth($txt);
                    if ($align == 'R')
                        $dx = $w - $w_txt - $this->cMargin;
                    elseif ($align == 'C')
                        $dx = ($w - $w_txt) / 2;
                    else
                        $dx = $this->cMargin;

                    $txt = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));
                    if ($this->ColorFlag)
                        $s .= 'q ' . $this->TextColor . ' ';
                    $s .= sprintf('BT %.2F %.2F Td (%s) Tj ET ', ($this->x + $dx) * $k, ($this->h - ($this->y + .5 * $h + (.7 + $l - $lines / 2) * $this->FontSize)) * $k, $txt);
                    if ($this->underline)
                        $s .= ' ' . $this->_dounderline($this->x + $dx, $this->y + .5 * $h + .3 * $this->FontSize, $txt);
                    if ($this->ColorFlag)
                        $s .= ' Q ';
                    if ($link)
                        $this->Link($this->x + $dx, $this->y + .5 * $h - .5 * $this->FontSize, $w_txt, $this->FontSize, $link);
                }
            }
            else { // Single line
                $w_txt = $this->GetStringWidth($txt);
                $Tz = 100;
                if ($w_txt > $w - 2 * $this->cMargin) { // Need compression
                    $Tz = ($w - 2 * $this->cMargin) / $w_txt * 100;
                    $w_txt = $w - 2 * $this->cMargin;
                }
                if ($align == 'R')
                    $dx = $w - $w_txt - $this->cMargin;
                elseif ($align == 'C')
                    $dx = ($w - $w_txt) / 2;
                else
                    $dx = $this->cMargin;
                $txt = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));
                if ($this->ColorFlag)
                    $s .= 'q ' . $this->TextColor . ' ';
                $s .= sprintf('q BT %.2F %.2F Td %.2F Tz (%s) Tj ET Q ', ($this->x + $dx) * $k, ($this->h - ($this->y + .5 * $h + .3 * $this->FontSize)) * $k, $Tz, $txt);
                if ($this->underline)
                    $s .= ' ' . $this->_dounderline($this->x + $dx, $this->y + .5 * $h + .3 * $this->FontSize, $txt);
                if ($this->ColorFlag)
                    $s .= ' Q ';
                if ($link)
                    $this->Link($this->x + $dx, $this->y + .5 * $h - .5 * $this->FontSize, $w_txt, $this->FontSize, $link);
            }
        }
// end change Cell function
        if ($s)
            $this->_out($s);
        $this->lasth = $h;
        if ($ln > 0) {
            //Go to next line
            $this->y += $h;
            if ($ln == 1)
                $this->x = $this->lMargin;
        } else
            $this->x += $w;
    }

    function SetDash($black = null, $white = null) {
        if ($black !== null)
            $s = sprintf('[%.3F %.3F] 0 d', $black * $this->k, $white * $this->k);
        else
            $s = '[] 0 d';
        $this->_out($s);
    }

    function Sector($xc, $yc, $r, $a, $b, $style = 'FD', $cw = true, $o = 90) {
        $d0 = $a - $b;
        if ($cw) {
            $d = $b;
            $b = $o - $a;
            $a = $o - $d;
        } else {
            $b += $o;
            $a += $o;
        }
        while ($a < 0)
            $a += 360;
        while ($a > 360)
            $a -= 360;
        while ($b < 0)
            $b += 360;
        while ($b > 360)
            $b -= 360;
        if ($a > $b)
            $b += 360;
        $b = $b / 360 * 2 * M_PI;
        $a = $a / 360 * 2 * M_PI;
        $d = $b - $a;
        if ($d == 0 && $d0 != 0)
            $d = 2 * M_PI;
        $k = $this->k;
        $hp = $this->h;
        if (sin($d / 2))
            $MyArc = 4 / 3 * (1 - cos($d / 2)) / sin($d / 2) * $r;
        else
            $MyArc = 0;
        //first put the center
        $this->_out(sprintf('%.2F %.2F m', ($xc) * $k, ($hp - $yc) * $k));
        //put the first point
        $this->_out(sprintf('%.2F %.2F l', ($xc + $r * cos($a)) * $k, (($hp - ($yc - $r * sin($a))) * $k)));
        //draw the arc
        if ($d < M_PI / 2) {
            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a), $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a), $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2), $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2), $xc + $r * cos($b), $yc - $r * sin($b)
            );
        } else {
            $b = $a + $d / 4;
            $MyArc = 4 / 3 * (1 - cos($d / 8)) / sin($d / 8) * $r;
            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a), $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a), $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2), $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2), $xc + $r * cos($b), $yc - $r * sin($b)
            );
            $a = $b;
            $b = $a + $d / 4;
            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a), $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a), $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2), $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2), $xc + $r * cos($b), $yc - $r * sin($b)
            );
            $a = $b;
            $b = $a + $d / 4;
            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a), $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a), $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2), $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2), $xc + $r * cos($b), $yc - $r * sin($b)
            );
            $a = $b;
            $b = $a + $d / 4;
            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a), $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a), $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2), $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2), $xc + $r * cos($b), $yc - $r * sin($b)
            );
        }
        //terminate drawing
        if ($style == 'F')
            $op = 'f';
        elseif ($style == 'FD' || $style == 'DF')
            $op = 'b';
        else
            $op = 's';
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', $x1 * $this->k, ($h - $y1) * $this->k, $x2 * $this->k, ($h - $y2) * $this->k, $x3 * $this->k, ($h - $y3) * $this->k));
    }

    function PieChart($w, $h, $data, $format, $colors = null) {
        $this->SetFont('Courier', '', 10);
        $this->SetLegends($data, $format);

        $XPage = $this->GetX();
        $YPage = $this->GetY();
        $margin = 2;
        $hLegend = 5;
        $radius = min($w - $margin * 4 - $hLegend - $this->wLegend, $h - $margin * 2);
        $radius = floor($radius / 2);
        $XDiag = $XPage + $margin + $radius;
        $YDiag = $YPage + $margin + $radius;
        if ($colors == null) {
            for ($i = 0; $i < $this->NbVal; $i++) {
                $gray = $i * intval(255 / $this->NbVal);
                $colors[$i] = array($gray, $gray, $gray);
            }
        }

        //Sectors
        $this->SetLineWidth(0.2);
        $angleStart = 0;
        $angleEnd = 0;
        $i = 0;
        foreach ($data as $val) {
            $angle = ($val * 360) / doubleval($this->sum);
            if ($angle != 0) {
                $angleEnd = $angleStart + $angle;
                $this->SetFillColor($colors[$i][0], $colors[$i][1], $colors[$i][2]);
                $this->Sector($XDiag, $YDiag, $radius, $angleStart, $angleEnd);
                $angleStart += $angle;
            }
            $i++;
        }

        //Legends
        $this->SetFont('Courier', '', 10);
        $x1 = $XPage + 2 * $radius + 4 * $margin;
        $x2 = $x1 + $hLegend + $margin;
        $y1 = $YDiag - $radius + (2 * $radius - $this->NbVal * ($hLegend + $margin)) / 2;
        for ($i = 0; $i < $this->NbVal; $i++) {
            $this->SetFillColor($colors[$i][0], $colors[$i][1], $colors[$i][2]);
            $this->Rect($x1, $y1, $hLegend, $hLegend, 'DF');
            $this->SetXY($x2, $y1);
            $this->Cell(0, $hLegend, $this->legends[$i]);
            $y1 += $hLegend + $margin;
        }
    }

    function BarDiagram($w, $h, $data, $format, $color = null, $maxVal = 0, $nbDiv = 4) {
        $this->SetFont('Courier', '', 10);
        $this->SetLegends($data, $format);

        $XPage = $this->GetX();
        $YPage = $this->GetY();
        $margin = 2;
        $YDiag = $YPage + $margin;
        $hDiag = floor($h - $margin * 2);
        $XDiag = $XPage + $margin * 2 + $this->wLegend;
        $lDiag = floor($w - $margin * 3 - $this->wLegend);
        if ($color == null)
            $color = array(155, 155, 155);
        if ($maxVal == 0) {
            $maxVal = max($data);
        }
        $valIndRepere = ceil($maxVal / $nbDiv);
        $maxVal = $valIndRepere * $nbDiv;
        $lRepere = floor($lDiag / $nbDiv);
        $lDiag = $lRepere * $nbDiv;
        $unit = $lDiag / $maxVal;
        $hBar = floor($hDiag / ($this->NbVal + 1));
        $hDiag = $hBar * ($this->NbVal + 1);
        $eBaton = floor($hBar * 80 / 100);

        $this->SetLineWidth(0.2);
        $this->Rect($XDiag, $YDiag, $lDiag, $hDiag);

        $this->SetFont('Courier', '', 10);
        $this->SetFillColor($color[0], $color[1], $color[2]);
        $i = 0;
        foreach ($data as $val) {
            //Bar
            $xval = $XDiag;
            $lval = (int) ($val * $unit);
            $yval = $YDiag + ($i + 1) * $hBar - $eBaton / 2;
            $hval = $eBaton;
            $this->Rect($xval, $yval, $lval, $hval, 'DF');
            //Legend
            $this->SetXY(0, $yval);
            $this->Cell($xval - $margin, $hval, $this->legends[$i], 0, 0, 'R');
            $i++;
        }

        //Scales
        for ($i = 0; $i <= $nbDiv; $i++) {
            $xpos = $XDiag + $lRepere * $i;
            $this->Line($xpos, $YDiag, $xpos, $YDiag + $hDiag);
            $val = $i * $valIndRepere;
            $xpos = $XDiag + $lRepere * $i - $this->GetStringWidth($val) / 2;
            $ypos = $YDiag + $hDiag - $margin;
            $this->Text($xpos, $ypos, $val);
        }
    }

    function SetLegends($data, $format) {
        $this->legends = array();
        $this->wLegend = 0;
        $this->sum = array_sum($data);
        $this->NbVal = count($data);
        foreach ($data as $l => $val) {
            $p = sprintf('%.2f', $val / $this->sum * 100) . '%';
            $legend = str_replace(array('%l', '%v', '%p'), array($l, $val, $p), $format);
            $this->legends[] = $legend;
            $this->wLegend = max($this->GetStringWidth($legend), $this->wLegend);
        }
    }

}
