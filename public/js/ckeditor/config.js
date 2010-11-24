/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
   config.filebrowserBrowseUrl = '/public/kcfinder/browse.php?type=files';
   config.filebrowserImageBrowseUrl = '/public/kcfinder/browse.php?type=images';
   config.filebrowserFlashBrowseUrl = '/public/kcfinder/browse.php?type=flash';
   config.filebrowserUploadUrl = '/public/kcfinder/upload.php?type=files';
   config.filebrowserImageUploadUrl = '/public/kcfinder/upload.php?type=images';
   config.filebrowserFlashUploadUrl = '/public/kcfinder/upload.php?type=flash';

   config.bodyClass = "content";
   config.enterMode = CKEDITOR.ENTER_P;

   config.contentsCss = ['/css/style.css', '/css/ckeditor.css'];
   config.toolbar = 'MyToolbar';

   //   config.protectedSource.push( /<\?[\s\S]*?\?>/g );   // PHP Code

   // form buttons:
   //           ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],

   config.toolbar_CMS_Full =
   [
           ['Source'],
           ['Cut','Copy','Paste','PasteText','PasteFromWord','-','SpellChecker', 'Scayt'],
           ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
           '/',
           ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
           ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
           ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
           ['Link','Unlink','Anchor'],
           ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar'],
           '/',
           //           ['Styles','Format','Font','FontSize'],
           ['Font','FontSize'],
           ['TextColor','BGColor'],
           ['Maximize', 'ShowBlocks','-','About']
   ];

   config.toolbar_MyToolbar =
   [
       ['Source','Preview'],
       ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Scayt'],
       ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
       ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
       '/',
       ['TextColor','BGColor'],
       ['Styles','Format'],
       ['Bold','Italic','Strike'],
       ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
       ['Link','Unlink','Anchor'],
       ['Maximize','-','About']
   ];
};
