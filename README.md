iFmup
=====

Repo for Dim Works

This is an image file uploader, for the WYSIWYG Tiny MCE. With this script you can upload images directly to your web host, and you can insert directly to the text editor. So easy to use, and totaly free. 

for install, only upload the top folder ("ibrowser") in the plugin folder and in the script to call the text editor name ibrowser. 

-This script need the GD library of PHP.- 

was inspired by iBrowser, but iFmup do not need phpthumbs, supports php 5, is to much easy to use, and is not to heavy like iBrowser. 

v0.1.1 example for the install: 

theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,ibrowser,cleanup,help,code",


FAQs for install plugins & examples:

-http://www.tinymce.com/forum/viewtopic.php?id=3349

-http://www.tinymce.com/wiki.php/Configuration:plugins



More details for INSTALL:

First unzip and upload in the sub folder of plugins of your tinymce folders.

when you are calling the script of the tinymce in your html, name the plug in.

ex: plugins : "ibrowser",

then in the theme buttons list insert the button:

ex: theme_advanced_buttons1 : "ibrowser",

and that is all.


///////////////////////////////////////////////////////////////////////////////////////
here a complete example:

<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
        // General options
        mode : "textareas",
        language : "es",
        theme : "advanced",
        convert_urls : false,
        relative_urls : false,
        content_css : "style.css",
        plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,ibrowser",

        // Theme options
        theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect,|,sub,sup",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,ibrowser,cleanup,help,code",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,spellchecker,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        spellchecker_languages : "English=en,+Spanish=es",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "lists/template_list.js",
        external_link_list_url : "lists/link_list.js",
        external_image_list_url : "lists/image_list.js",
        media_external_list_url : "lists/media_list.js",

    });
</script>
<textarea name="message" rows="20" cols="45" class="mceSimple" style="font-size: 1.1em;">
