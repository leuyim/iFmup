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

HERE A COMPLETE EXAMPLE:

<p>&lt;script type=&quot;text/javascript&quot; src=&quot;tiny_mce/tiny_mce.js&quot;&gt;&lt;/script&gt;<br />
  &lt;script type=&quot;text/javascript&quot;&gt;<br />
  tinyMCE.init({<br />
  // General options<br />
  mode : &quot;textareas&quot;,<br />
  language : &quot;es&quot;, <br />
  theme : &quot;advanced&quot;,<br />
  convert_urls : false,<br />
  relative_urls : false,<br />
  content_css : &quot;style.css&quot;,<br />
  plugins :   &quot;safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,ibrowser&quot;,</p>
<p>        // Theme options<br />
  theme_advanced_buttons1 :   &quot;newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect,|,sub,sup&quot;,<br />
  theme_advanced_buttons2 :   &quot;cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,ibrowser,cleanup,help,code&quot;,<br />
  theme_advanced_buttons3 :   &quot;tablecontrols,|,hr,removeformat,visualaid,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen&quot;,<br />
  theme_advanced_buttons4 :   &quot;insertlayer,moveforward,movebackward,absolute,|,styleprops,|,spellchecker,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,|,insertdate,inserttime,preview,|,forecolor,backcolor&quot;,<br />
  theme_advanced_toolbar_location : &quot;top&quot;,<br />
  theme_advanced_toolbar_align : &quot;left&quot;,<br />
  theme_advanced_statusbar_location : &quot;bottom&quot;,<br />
  theme_advanced_resizing : true,<br />
  spellchecker_languages : &quot;English=en,+Spanish=es&quot;,</p>
<p>        // Drop lists for link/image/media/template dialogs<br />
  template_external_list_url : &quot;lists/template_list.js&quot;,<br />
  external_link_list_url : &quot;lists/link_list.js&quot;,<br />
  external_image_list_url : &quot;lists/image_list.js&quot;,<br />
  media_external_list_url : &quot;lists/media_list.js&quot;,</p>
<p>    });<br />
  &lt;/script&gt;<br />
  &lt;textarea name=&quot;message&quot; rows=&quot;20&quot; cols=&quot;45&quot; class=&quot;mceSimple&quot; style=&quot;font-size: 1.1em;&quot;&gt;</p>
