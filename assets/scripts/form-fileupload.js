var FormFileUpload = function () {
    return {
        //main function to initiate the module
        init: function () {
            // Initialize the jQuery File Upload widget:
            $('#fileupload').fileupload({
                url: urlAjax('boletim'),
                dataType: 'json',
                autoUpload: false,
                replaceFileInput: true,
                acceptFileTypes: /(\.|\/)(xlsx)$/i,
                maxFileSize: 5000000,
                previewMaxWidth: 100,
                previewMaxHeight: 100,
                previewCrop: true
            });
            $('#fileupload').bind('fileuploadadd', function (e, data) {
                var currentfiles = [];
                $(this).fileupload('option').filesContainer.children().each(function () {
                    currentfiles.push($.trim($('.name', this).text()));
                });
                data.files = $.map(data.files, function (file, i) {
                    if ($.inArray(file.name, currentfiles) >= 0) {
                        alertSwal("Pauta " + file.name + "  já foi adicionada");
                        return null;
                    }
                    return file;
                });
                $('#divBtnGCB').css('display', 'block');
                $('#selecGCB').val('');
            });
        }

    };
}();