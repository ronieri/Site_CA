$("#rep_monografia").fileinput({
    allowedFileExtensions: ['pdf','odt','doc','docx'],
    showPreview: false,
    showUpload: false,
    showRemove: true,
    msgValidationError:'Arquivo não suportado...',
    language:'pt-BR'
});

$("#rep_video").fileinput({
    allowedFileExtensions: ['avi','mp4','wmv', 'mkv'],
    showPreview: false,
    showUpload: false,
    showRemove: true,
    msgValidationError:'Arquivo não suportado...',
    language:'pt-BR'
});

$("#rep_codigo_fonte").fileinput({
    allowedFileExtensions: ['rar','zip'],
    showPreview: false,
    showUpload: false,
    showRemove: true,
    msgValidationError:'Arquivo não suportado...',
    language:'pt-BR'
});

$(document).ready(function() {
        $('#sub_areas').multiselect({
            includeSelectAllOption: true,
            enableCollapsibleOptGroups: true,
            buttonWidth: '100%',
            enableFiltering: true,
            numberDisplayed: 2
        });
    });