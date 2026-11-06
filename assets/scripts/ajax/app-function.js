/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* global local, url, timeOut, timeSet, loginSp, acesso */

function getUrl() {
    return url;
}

function setTimeOut($time) {
    timeOut = $time;
}

function getTimeOut() {
    return timeOut;
}

function getTimeSet() {
    return timeSet;
}

function getLocal() {
    return local;
}

function getAcesso() {
    return acesso;
}

function getLoginExpirado() {
    return loginSp;
}

function smsCampoObrigatorio() {
    return 'Campo Obrigat&oacute;rio';
}

function ajaxApp(url, data, funSuccess, modal = true, asicr = true, funError = null) {
    $.ajax({
        type: 'POST',
        url: urlAjax(url),
        dataType: 'json',
        data: data,
        async: asicr,
        beforeSend: function () {
            if (modal == true)
                $('#modalCarregarAjax').modal('show');
        },
        complete: function () {
            if (modal == true)
                $('#modalCarregarAjax').modal('hide');
        },
        success: function (r) {
            funSuccess(r);
        },
        error: function (xhr, status, error) {
            if (typeof funError === 'function')
                funError(xhr, status, error);
        }
    });
}

function ajaxFormData(url, data, funSuccess, modal = 1) {
    $.ajax({
        type: 'POST',
        url: urlAjax(url),
        dataType: 'json',
        processData: false,
        contentType: false,
        data: data,
        beforeSend: function () {
            if (modal == 2)
                $('#carrInsAjx').css('display', 'block');
            else
                $('#modalCarregarAjax').modal('show');
        },
        complete: function () {
            if (modal == 2)
                $('#carrInsAjx').css('display', 'none');
            else
                $('#modalCarregarAjax').modal('hide');
        },
        success: function (r) {
            funSuccess(r);
        }
    });
}

function colocaFoco($idV, $idF) {
    if ($("#" + $idV).val()) {
        $("#" + $idF).focus();
    }
}

function efeitoFade($id) {
    $('#' + $id).fadeOut();
    $('#' + $id).fadeIn();
}

function removeErro($t) {
    $("#" + $t).removeClass('has-error');
    $("#" + $t).find('.help-block').remove();
}

function addErro($t, $msg) {
    removeErro($t);
    $("#" + $t).addClass('has-error');
    $("#" + $t).append('<span class="help-block">' + $msg + '</span>');
}

function addErroFoco($t, $b, $msg) {
    removeErro($t);
    $("#" + $t).addClass('has-error');
    $("#" + $t).append('<span class="help-block">' + $msg + '</span>');
    $("#" + $b).focus();
}

function mostraDivFormFocu($idDiv, $idFocus) {
    $('#' + $idDiv).show(1000);
    if ($idFocus) {
        $('#' + $idFocus).focus();
    }
}

function mostraDiv($idDiv) {
    $('#' + $idDiv).show(1000);
}

function ocultaDiv($idDiv) {
    $('#' + $idDiv).hide(1000);
}

function mostraDivForm($idDiv, $idMsg, $idFocus='', $idCod='', $idtr='') {
    if ($idMsg)
        $('#' + $idMsg).html("");
    if ($idFocus)
        $('#' + $idFocus).focus();
    if ($idCod)
        $('#' + $idCod).val('');
    if ($idtr)
        $('#' + $idtr).val('');
    $('#' + $idDiv).show(1000);
}

function ocultaDivForm($idDiv, $idMsg, $idCod='', $idtr='') {
    if ($idMsg)
        $('#' + $idMsg).html("");
    if ($idCod)
        $('#' + $idCod).val('');
    if ($idtr)
        $('#' + $idtr).val('');
    $('#' + $idDiv).hide(1000);
}

function visibiDivForm($idDiv, $idFocus) {
    $('#' + $idDiv).show(1000);
    if ($idFocus) {
        $('#' + $idFocus).focus();
    }
}

function collapseDivForm($idDiv, $idMsg, $idCod, $idTr) {
    $('#' + $idMsg).html("");
    $('#' + $idDiv).hide(1000);
    if ($idCod && $idTr) {
        $('#' + $idCod).val('');
        $('#' + $idTr).val('');
    }
}

function resetForm($idForm, $idCod, $idTr) {
    document.getElementById($idForm).reset();
    if ($idCod)
        $('#' + $idCod).val('');
    if ($idTr)
        $('#' + $idTr).val('');
}

function disableForm($idForm) {
    var allElements = document.getElementById($idForm).elements;
    for (var i = 0, l = allElements.length; i < l; ++i)
        allElements[i].disabled = true;
}

function enableForm($idForm) {
    var allElements = document.getElementById($idForm).elements;
    for (var i = 0, l = allElements.length; i < l; ++i)
        allElements[i].disabled = false;
}

function msgErroSpan($idDiv, $classErro, $erroN, $msg, $style) {
    document.getElementById($idDiv).innerHTML = "<div" + ($style != '' ? " style='" + $style + "'" : "") + " class='alert" + ($classErro != '' ? (" alert-" + $classErro) : "") + " alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button><strong>" + ($erroN ? ($erroN + '!') : $erroN) + "</strong> " + ($msg ? $msg : '') + "</div>";
    efeitoFade($idDiv);
}

function checkTockenExcel() {
    var token;
    var aaa;
    $('#modalCarregarAjax').modal('show');
    $("body").css('overflow', 'hidden');
    aaa = setInterval(function () {
        $.ajax({type: 'POST', url: (getUrl() + 'portal/token_pdf'), dataType: 'json', async: false,
            success: function (r) {
                if (r.r == '0') {
                    token = r.token;
                    if (r.token == 'SIM') {
                        $('#modalCarregarAjax').modal('hide');
                        $("body").css('overflow', 'auto');
                        if ($('#modalCarregarPdf').is(':visible')) {
                            if (r.close == 'SIM') {
                                $('#modalCarregarPdf').modal('hide');
                            }
                            clearTimeout(aaa);
                            return;
                        }
                    }
                } else {
                    alert('Erro 00' + r.r + '! Ao chamar o modal de controlo da página');
                    $('#modalCarregarAjax').modal('hide');
                }
            }
        });
    }, 1000);
    if (token == 'SIM') {
        clearTimeout(aaa);
    }
}

function carregarPdf($scr, $tipo) {
    if ($scr) {
        if ($(window).width() < 750) {
            window.open((!$tipo ? (getUrl() + 'pdf/' + $scr + '#page=1&zoom=100') : $scr), '_blank');
        } else {
            checkTockenExcel();
            document.getElementById('iframePdf').src = (!$tipo ? (getUrl() + 'pdf/' + $scr + '#page=1&zoom=100') : $scr + '#page=1&zoom=100');
            $('#modalCarregarPdf').modal('show');
        }
    }
}

function carregarPdf2($scr, $tipo) {
    if ($scr) {
        if ($(window).width() < 750) {
            window.open((!$tipo ? (getUrl() + $scr + '#page=1&zoom=100') : $scr), '_blank');
        } else {
//            checkTockenExcel();
            document.getElementById('iframePdf').src = (!$tipo ? (getUrl() + $scr + '#page=1&zoom=100') : $scr + '#page=1&zoom=100');
            $('#modalCarregarPdf').modal('show');
        }
    }
}

function carregarExcel($scr, $tipo) {
    if ($scr) {
        window.location.href = (!$tipo ? (getUrl() + 'pdf/' + $scr) : $scr);
    }
}

function inputSoNota($campo, $evento, $valor) {
    var tecla = (window.event) ? $evento.keyCode : $evento.which;
    $campo.value = $campo.value.replace(/,/gi, ".");
    if (tecla == 8 || tecla == 0 || tecla == 13) {
        return true;
    }
    $valor += "" + tecla - 48;
    if (tecla != 44 && tecla != 46 && tecla < 48 || tecla > 57 || $valor > 20)
        return false;
}

function isNumberKey(txt, evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 46) {
        //Check if the text already contains the . character
        if (txt.value.indexOf('.') === -1) {
            return true;
        } else {
            return false;
        }
    } else {
        if (charCode > 31 &&
                (charCode < 48 || charCode > 57))
            return false;
    }
    return true;
}

function inputSoNumero(e) {
    var tecla = (window.event) ? event.keyCode : e.which;
    if ((tecla > 47 && tecla < 58))
        return true;
    else {
        if (tecla == 8 || tecla == 0)
            return true;
        else
            return false;
    }
}

function clickActiveLink(e, $ant, $new) {
    $(e).removeClass($ant).addClass($new);
}

function clickActiveHref(e) {
    $(e).css('color', '#333');
}

function urlAjax($controller) {
    setTimeOut(getTimeSet());
    return getUrl() + 'ajax/' + $controller;
}

function dataActual($format) {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }
    return ($format == 'yyyy-mm-dd' ? (yyyy + '-' + mm + '-' + dd) : ($format == 'dd-mm-yyyy' ? (dd + '/' + mm + '/' + yyyy) : (mm + '/' + dd + '/' + yyyy)));
}

function dataFormat($data, $format) {
    return !$data ? '' : ($format == 1 ? ($data.substring(0, 4) + '/' + $data.substring(6, 2) + '/' + $data.substring(8, 2)) : ($data.substring(8, 2) + '/' + $data.substring(6, 2) + '/' + $data.substring(0, 4)));
}

function dataApp($data, $tipo = 1) {
    return !$data ? '' : ($tipo == 2 ? '' : ($data.split("-")[2] + '/' + $data.split("-")[1] + '/' + $data.split("-")[0]));
}

function verificaExtencao($filename, $tipo) {
    $extension = $filename.replace(/^.*\./, '');
    if ($extension === $filename) {
        return false;
    } else {
        $extension = $extension.toLowerCase();
        if ($tipo == '1') {
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                case 'png':
                    return true;
                default :
                    return false;
            }
        } else if ($tipo == '2') {
            switch ($extension) {
                case 'pdf':
                    return true;
                default :
                    return false;
            }
        } else if ($tipo == '4') {
            switch ($extension) {
                case 'xlsx':
                    return true;
                default :
                    return false;
            }
        } else if ($tipo == '3') {
            switch ($extension) {
                case 'pdf':
                case 'doc':
                case 'docx':
                    return true;
                default :
                    return false;
            }
        }
    }
}

function addSelect2($ids) {
    for (var i = 0; i < $ids.length; i++) {
        if ($("#" + $ids[i])) {
            $('#' + $ids[i]).select2();
        }
    }
}

function textoIniciasMaiuscula(id) {
    if ($(id).val()) {
        //palavras para ser ignoradas
        var wordsToIgnore = ["DOS", "DAS", "de", "do", "Dos", "Das"], minLength = 2;
        var getWords = function (str) {
            return str.match(/\S+\s*/g);
        };
        $(id).each(function () {
            var words = getWords(this.value);
            $.each(words, function (i, word) {
                // somente continua se a palavra nao estiver na lista de ignorados
                if (wordsToIgnore.indexOf($.trim(word)) == -1 && $.trim(word).length > minLength) {
                    words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1).toLowerCase();
                } else {
                    words[i] = words[i].toLowerCase();
                }
            });
            this.value = words.join("");
        });
    }
}

function logout($url) {
    swal({title: "Você realmente deseja sair da Plataforma?", icon: "warning", allowOutsideClick: false, showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'SIM', cancelButtonText: 'NÃO'}).then((result) => {
        if (result.value) {
            window.location.assign($url);
        }
    });
    return false;
}

function limpaElementHtml($elements) {
    $array = $elements.split('_');
    for ($i = 0; $i < $array.length; $i++) {
        if ($('#' + $array[$i]).size()) {
            $('#' + $array[$i]).html('');
        }
    }
}

function disableEnableElement($elements, $tipo = 1, $val = 2) {
    $array = $elements.split('_');
    for ($i = 0; $i < $array.length; $i++) {
        $vdeele = ($val == 2 ? $('#' + $array[$i]).val() : '');
        $('#' + $array[$i]).prop('disabled', ($tipo == 1 ? true : false)).val($vdeele);
}
}

function disableEnableSelect2($elements, $tipo = 1, $val = 2) {
    $array = $elements.split('_');
    for ($i = 0; $i < $array.length; $i++) {
        $vdes = ($val == 2 ? $('#' + $array[$i]).val() : '');
        $('#' + $array[$i]).select2($tipo == 1 ? "disable" : "enable");
        $('#' + $array[$i]).select2("val", $vdes);
}
}

function displayInlineOrNone($elements, $tipo = 1) {
    $array = $elements.split('_');
    for ($i = 0; $i < $array.length; $i++)
        $('#' + $array[$i]).css('display', ($tipo == 1 ? 'none' : 'inline'));
}

function removeAllTagInput($id) {
    if ($('#' + $id).val()) {
        var tags = $('#' + $id).val().split(',');
        for (var i = 0; i < tags.length; i++) {
            $('#' + $id).removeTag(tags[i]);
        }
    }
}

function removeAccents(string) {
    const accents = "ÀÁÂÃÄÅĄàáâãäåąßÒÓÔÕÕÖØÓòóôõöøóÈÉÊËĘèéêëęðÇĆçćÐÌÍÎÏìíîïÙÚÛÜùúûüÑŃñńŠŚšśŸÿýŽŻŹžżź";
    const accentsOut = "AAAAAAAaaaaaaaBOOOOOOOOoooooooEEEEEeeeeeeCCccDIIIIiiiiUUUUuuuuNNnnSSssYyyZZZzzz";
    return string.split("").map((letter, index) => {
        const accentIndex = accents.indexOf(letter);
        return accentIndex !== -1 ? accentsOut[accentIndex] : letter;
    }).join("");
}

function spanActionTable($tipo, $funOnclick, $id, $nome, $title, $corBtn, $btnIcon) {
    return '<span' + ($title ? ' title="' + $title + '"  ' : '') + ($id ? ' id="' + $id + '"  ' : '') + ($funOnclick ? ' onclick="' + $funOnclick + ';clickActiveLink(this,\'' + ($corBtn ? $corBtn : ($tipo == 1 ? 'btn-success' : ($tipo == 2 ? 'btn-danger' : 'btn-info'))) + '\',\'btn-default\');"  ' : '') + ' class="btn btn-xs ' + ($corBtn ? $corBtn : ($tipo == 1 ? 'btn-success' : ($tipo == 2 ? 'btn-danger' : 'btn-info'))) + '"><i class="' + ($btnIcon ? $btnIcon : ($tipo == 1 ? 'icon-edit' : ($tipo == 2 ? 'icon-remove' : 'icon-print'))) + '"></i> ' + ($nome ? $nome : ($tipo == 1 ? 'Editar' : ($tipo == 2 ? 'Apagar' : 'Print'))) + '</span>';
}

function configDynamicTable() {
    jQuery('.dataTables_filter input').addClass("form-control input-medium"); // modify table search input
    jQuery('.dataTables_length select').addClass("form-control input-xsmall"); // modify table per page dropdown
//    jQuery('.dataTables_length select').select2(); // initialize select2 dropdown
}

function criaUserName($nome) {
    $re = '';
    if ($nome) {
        $ss = $nome.split(' ');
        if ($ss.length > 1) {
            $re = removeAccents($ss[0] + '.' + $ss[$ss.length - 1]);
        } else
            $re = $ss;
    }
    return $re ? removeAccents($re.toLowerCase()) : '';
}

function periodoApp($perE) {
    return (($perE == '1') ? 'Diurno' : ($perE == '2' ? 'Nocturno' : 'Indefinido'));
}

function generoApp($perE) {
    return (($perE == '1') ? 'Masculino' : ($perE == '2' ? 'Feminino' : 'Indefinido'));
}

function turnoTurma($turno) {
    return ($turno == '1' ? 'Manhã' : ($turno == '2' ? 'Tarde' : ($turno == '3' ? 'Noite' : '')));
}

function idadeByDate(dateString) {
    var dates = dateString.split("/");
    var d = new Date();
    var age = d.getFullYear() - dates[2];
    if (((d.getMonth() + 1) < dates[1]) || (((d.getMonth() + 1) == dates[1]) && d.getDate() < dates[0]))
        age--;
    return age;
}

function  handleTitle(navigation, index) {
//    $('.step-title', $('#form_wizard_1')).text('Pág. ' + (index + 1) + ' de ' + (navigation.find('li').length));
    jQuery('li', $('#form_wizard_1')).removeClass("done");
    var li_list = navigation.find('li');
    for (var i = 0; i < index; i++) {
        jQuery(li_list[i]).addClass("done");
    }
    if (index >= (navigation.find('li').length)) {
        $('#form_wizard_1').find('.button-next').hide();
        $('#form_wizard_1').find('.button-previous').hide();
    } else {
        $('#form_wizard_1').find('.button-next').show();
        $('#form_wizard_1').find('.button-previous').hide();
    }
    $("#idAvn").html((index == (parseInt(navigation.find('li').length) - 2) ? 'Finalizar' : 'Avançar') + ' <i class="m-icon-swapright m-icon-white"></i>');
    $('#controloPassagem').val('0');
    $('html, body').animate({scrollTop: 0}, 'slow');
}

function formatMoney(amount, decimalCount = 2, decimal = ",", thousands = ".") {
    decimalCount = Math.abs(decimalCount);
    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;
    const negativeSign = amount < 0 ? "-" : "";
    let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
    let j = (i.length > 3) ? i.length % 3 : 0;
    return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
}

function mesApp($mes) {
    return $mes == '1' ? 'Janeiro' : ($mes == '2' ? 'Fevereiro' : ($mes == '3' ? 'Março' : ($mes == '4' ? 'Abril' : ($mes == '5' ? 'Maio' : ($mes == '6' ? 'Junho' : ($mes == '7' ? 'Julho' : ($mes == '8' ? 'Agosto' : ($mes == '9' ? 'Setembro' : ($mes == '10' ? 'Outubro' : ($mes == '11' ? 'Novembro' : ($mes == '12' ? 'Dezembro' : '')))))))))));
}

function checkNome($strpar) {
    var str = $strpar.split('');
    if (str.length > 1) {
        for (var i = 0; i < str.length; i++) {
            if (str[0] == str[1])
                return false;
            if (i + 2 < str.length)
                if (str[i] == str[i + 1] && str[i] == str[i + 2])
                    return false;
        }
    }
    return true;
}

function validarPessoa($val, $cod) {
    $yy = -2;
    $.ajax({type: 'POST', url: urlAjax('utilizador/validarPessoa'), dataType: 'json', async: false, data: "dado=" + $val + '&cod=' + $cod,
        success: function (k) {
            $yy = k;
        }});
    return $yy;
}

function getTimeDiff(start, end) {
    return (new Date('2022-04-24 ' + end).getTime() - new Date('2022-04-24 ' + start).getTime());
}

function mesApp($mes) {
    return $mes == '1' ? 'Janeiro' : ($mes == '2' ? 'Fevereiro' : ($mes == '3' ? 'Março' : ($mes == '4' ? 'Abril' : ($mes == '5' ? 'Maio' : ($mes == '6' ? 'Junho' : ($mes == '7' ? 'Julho' : ($mes == '8' ? 'Agosto' : ($mes == '9' ? 'Setembro' : ($mes == '10' ? 'Outubro' : ($mes == '11' ? 'Novembro' : ($mes == '12' ? 'Dezembro' : '')))))))))));
}

function alertSwal($sms, $modal = '') {
    if ($modal)
        return swal({title: $sms, icon: "warning", allowOutsideClick: false, target: document.getElementById($modal)});
    else
        return swal({title: $sms, icon: "warning", allowOutsideClick: false});
}

function sessaoExpirada() {
    window.location.reload();
}

function calculateBusinessDays(startDate, endDate) {
// Validate input
    if (endDate < startDate)
        return 0;

// Calculate days between dates
    var millisecondsPerDay = 86400 * 1000; // Day in milliseconds
    startDate.setHours(0, 0, 0, 1);  // Start just after midnight
    endDate.setHours(23, 59, 59, 999);  // End just before midnight
    var diff = endDate - startDate;  // Milliseconds between datetime objects    
    var days = Math.ceil(diff / millisecondsPerDay);

// Subtract two weekend days for every week in between
    var weeks = Math.floor(days / 7);
    days = days - (weeks * 2);

// Handle special cases
    var startDay = startDate.getDay();
    var endDay = endDate.getDay();

// Remove weekend not previously removed.   
    if (startDay - endDay > 1)
        days = days - 2;

// Remove start day if span starts on Sunday but ends before Saturday
    if (startDay == 0 && endDay != 6) {
        days = days - 1;
    }

// Remove end day if span ends on Saturday but starts after Sunday
    if (endDay == 6 && startDay != 0) {
        days = days - 1;
    }

    return days;
}

function addWorkDays(startDate, days) {
    if (isNaN(days))
        return
    if (!(startDate instanceof Date))
        return
    var dow = startDate.getDay();
    var daysToAdd = parseInt(days);
    if (dow == 0)
        daysToAdd++;
    if (dow + daysToAdd >= 6) {
        var remainingWorkDays = daysToAdd - (5 - dow);
        daysToAdd += 2;
        if (remainingWorkDays > 5) {
            daysToAdd += 2 * Math.floor(remainingWorkDays / 5);
            if (remainingWorkDays % 5 == 0)
                daysToAdd -= 2;
        }
    }
    startDate.setDate(startDate.getDate() + daysToAdd);
    return startDate;
}

function printPdf(url) {
    var iframe = this._printIframe;
    if (!this._printIframe) {
        iframe = this._printIframe = document.createElement('iframe');
        document.body.appendChild(iframe);

        iframe.style.display = 'none';
        iframe.onload = function () {
            setTimeout(function () {
                iframe.focus();
                iframe.contentWindow.print();
            }, 1);
        };
    }

    iframe.src = url;
}

function showPDF(pdf_url, $idPdfPreview, $modalTarge) {
    PDFJS.getDocument({url: pdf_url}).then(function (pdf_doc) {
        showPage(1, pdf_doc, $idPdfPreview);
        URL.revokeObjectURL(pdf_url);
    }).catch(function (error) {
        alertSwal(error.message, $modalTarge);
    });
}
function showPage(page_no, _PDF_DOC, $idPdfPreview) {
    _PDF_DOC.getPage(page_no).then(function (page) {
        var scale_required = document.getElementById($idPdfPreview).width / page.getViewport(1).width;
        var viewport = page.getViewport(scale_required);
        document.getElementById($idPdfPreview).height = 150;
        var renderContext = {
            canvasContext: document.getElementById($idPdfPreview).getContext('2d'),
            viewport: viewport
        };
        page.render(renderContext).then(function () {
            document.getElementById($idPdfPreview).style.display = 'block';
        });
    });
}
function showSelectFileDialog($idPdfFile) {
    document.getElementById($idPdfFile).click();
}
function showSelectedFileHasChanged($idPdfFile, $idPdfPreview, $filesCheck, $modalTarge) {
    var file = document.getElementById($idPdfFile).files[0];
    if (['application/pdf'].indexOf(file.type) == -1) {
        alertSwal('Error: Ficheiro deve ser PDF', $modalTarge);
        return;
    }
    if (file.size > 10 * 1024 * 1024) {
        alertSwal("Error: Tamanho do ficheiro deve ser menor que 5MB", $modalTarge);
        return;
    }
    if ($filesCheck.length > 0) {
        $files = $filesCheck.split("_");
        for (var i = 0; i < $files.length; i++) {
            if ($files[i] != $idPdfFile && document.getElementById($files[i]).value == document.getElementById($idPdfFile).value) {
                alertSwal('Error: Ficheiro já foi Adicionado', $modalTarge);
                return;
            }
        }
    }
    showPDF(URL.createObjectURL(file), $idPdfPreview, $modalTarge);
}

function getAge(birth) {
    var today = new Date();
    var curr_date = today.getDate();
    var curr_month = today.getMonth() + 1;
    var curr_year = today.getFullYear();

    var pieces = birth.split('/');
    var birth_date = pieces[0];
    var birth_month = pieces[1];
    var birth_year = pieces[2];

    if (curr_month == birth_month && curr_date >= birth_date)
        return parseInt(curr_year - birth_year);
    if (curr_month == birth_month && curr_date < birth_date)
        return parseInt(curr_year - birth_year - 1);
    if (curr_month > birth_month)
        return parseInt(curr_year - birth_year);
    if (curr_month < birth_month)
        return parseInt(curr_year - birth_year - 1);
}

function limparListar($idTableLimpar, $tipo = 1) {
    if ($tipo == 1) {
        $tabela = $("#" + $idTableLimpar[0]).dataTable();
        configDynamicTable();
        $tabela.fnClearTable();
        limpaElementHtml($idTableLimpar[1]);
    } else
        limpaElementHtml($idTableLimpar[0] + '_' + $idTableLimpar[1]);
}

function valPercentagem($total, $numPer) {
    return (parseInt($numPer) > 0 ? parseInt((parseInt($numPer) * 100) / parseInt($total)) : 0) + '%';
}

function selectCursoClasseEscola($idSel, $idDiv, $codClasse, $t = 1) {
    document.getElementById($idSel).innerHTML = '';
    document.getElementById($idSel).disabled = true;
    $('#' + $idDiv).css('display', 'none');
    ajaxApp(
            'escola/buscarOptionsCursoClasseEscola', "c=" + $codClasse,
            function (r) {
                if (r.r == 0) {
                    document.getElementById($idSel).innerHTML = ((r.c > 1 ? (($t == 2 ? '<option value="T">MOSTRA TODOS</option>' : ($t == 3 ? '<option value="">Selecione o Curso</option>' : ''))) : '') + r.select);
                    $('#' + $idDiv).css('display', 'block');
                    document.getElementById($idSel).disabled = false;
                }
            }, false, false);
}

function isNumeric(value) {
    return /^-?\d+$/.test(value);
}

function buscaOptionsMunicipios($idSele, $codP, $tipo = 1) {
    document.getElementById($idSele).innerHTML = ($tipo == 2 ? '<option value="">Selecione</option>' : ($tipo == 3 ? '<option value="T">Mostra Todos</option>' : ''));
    ajaxApp(
            'escola/buscarOptionsMunicipios', "p=" + $codP,
            function (r) {
                if (r.r !== 0) {
                    alertSwal('Erro 00' + r.r + '! Munic&iacute;pios Não Encontrados');
                } else {
                    document.getElementById($idSele).innerHTML += r.select;
                }
            }, false, false);
}
