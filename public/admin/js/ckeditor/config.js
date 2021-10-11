/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
var roxyFileman = '/js/ckeditor/fileman/index.html';

CKEDITOR.editorConfig = function( config ) {
    config.filebrowserBrowseUrl = roxyFileman;
    config.filebrowserImageBrowseUrl = roxyFileman+'?type=image';
    config.removeDialogTabs = 'link:upload;image:upload';
    config.height = 350;
    // config.removePlugins = 'htmldataprocessor';
    config.allowedContent = true;
    config.enterMode = CKEDITOR.ENTER_BR;
    // config.extraPlugins = 'btgrid';
    // config.extraPlugins = 'uploadimage';

    config.toolbar = [
        { name: 'document',    items: [ 'Maximize', '-', 'Source', '-',   'Preview', ] },
        { name: 'clipboard',   items: [ 'PasteText', 'PasteFromWord', '-','Undo', 'Redo' ] },
        { name: 'insert',      items: [ 'CreatePlaceholder', 'Image', 'Table', 'Iframe', 'InsertPre' ] },
        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', ] },


        '/',
        { name: 'paragraph',   items: [ 'NumberedList', 'BulletedList', '-', '-', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock',] },
        { name: 'links',       items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'tools',       items: [ 'UIColor'] },

        { name: 'styles',      items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
        { name: 'colors',      items: [ 'TextColor', 'BGColor' ] },
    ];
};
