<?php
	// ================================================
	// iFmup - File Upload WYSIWYG
	// ================================================
	// iFmup
	// ================================================
	// Developed: dimworks.org
	// Copyright: dimworks.org
	// (c)2013 All rights reserved.
	// ================================================
	// Revision: 0.1.1                 Date: 10/05/2013
	// ================================================

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>iFmup by Dim Wroks (dimworks.org)</title>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=es/MX">
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
<style type="text/css">
<!--
    @import url("css/style.css");
-->
</style>

</head>
<body>
<table width="100%" border="0">
<tr><td align="center"><a href="fileupload.php"><img src="images/img_po.gif" alt='Subir Imagenes' border="0"><br>Subir Imagenes</a></td>
<td align="center"><a href="ibrowser.php" ><img src="images/img_in.gif" alt='Insertar Imagenes' border="0"><br>Insertar Imagen</a></td>
<td align="center"><a href="about.php" ><img src="images/ib.gif" alt='Acerca de...' border="0"><br>Acerca de...</a></td></tr>
</table><hr><br>
<table cellpadding="0" cellspacing="0" border="0" width="600">
<tr>
<td><div style="width:250px; height:430px; overflow:scroll;">
<?php

$dir = (isset($_GET['dir']))?$_GET['dir']:"Gallery";
if(stristr($dir,".."))$dir = "Gallery";

list_dir($dir);
?>
</div></td><td>
<center>
<div style="width:350px; height:430px;" align="center">
<br>
<img width="250" height="186" src='images/noPop.gif' id="thumbimg" border="1"><br><br>
<table border="0">
<tr>
<td align="right">Path:</td><td align="left"><input type="text" disabled="disabled" id="surce" value=""></td>
</tr><tr>
<td align="right">Width:</td><td align="left"><input type="text" disabled="disabled" id="width" value=""></td>
</tr><tr>
<td align="right">Height:</td><td align="left"><input type="text" disabled="disabled" id="height" value=""></td>
</tr><tr>
<td align="right">alt:</td><td align="left"><input type="text" id="alt" value=""></td>
</tr><tr>
<td align="right">Border:</td><td align="left"><input type="text" disabled="disabled" id="border" value=""></td>
</tr>
<tr>
<td colspan="2" align="center"><button onclick="insertImage('y')">Insertar Imagen</button></td>
</tr>
</table>
</div>
</center>
</td>
</tr>
</table>

<script type="text/javascript">
function insertImage(){
    var surc = document.getElementById('surce').value;
    var wid = document.getElementById('width').value;
    var hei = document.getElementById('height').value;
    var alt = document.getElementById('alt').value;
    var bor = document.getElementById('border').value;
    if (surc != '') {
        var imagen = "<img src='<?php echo url(); ?>/"+surc+"' width='"+wid+"' height='"+hei+"' border='"+bor+"' alt='"+alt+"'/>";
        window.opener.tinyMCE.execCommand('mceInsertContent', false, imagen);
    }
    window.close();    
    self.close();
} 

function showimg(srce){
var img = new Image();
img.src = srce;
var hie = img.height; 
var wid = img.width; 

document.getElementById('surce').value=srce;
document.getElementById('width').value=wid;
document.getElementById('height').value=hie;
document.getElementById('border').value="0";
    
    var aspect_ratio = hie / wid;   //ok todo
    
    if (wid < hie){   
    hie = 250;
    wid = Math.abs(hie / aspect_ratio);
    }else{
    wid = 250; 
    hie = Math.abs(wid * aspect_ratio);
    }

document.getElementById('thumbimg').src=srce;
document.getElementById('thumbimg').height=hie;
document.getElementById('thumbimg').width=wid;

}
</script>

</body>
</html>
<?php
    
function OutputThumb($image_file, $size = 80) { 
$size1 = GetImageSize($image_file); 
$hie = $size1[1]; 
$wid = $size1[0]; 
if($wid>$size) { 
$multiplier=$size/$wid; 
$wid = round($wid*$multiplier); 
$hie = round($hie*$multiplier); }
 return "<img onclick=\"showimg('$image_file');\" src=\"$image_file\" width=\"$wid\" height=\"$hie\"  border='1' style='cursor: pointer;' >"; 
}

/* Rendering */
function list_dir($path)
{
   $items = get_sorted_entries($path);

    if (!$items)
        return;
   
    echo "<b>Directorio actual:</b><br>$path<br><br>"; 
    echo "<b>Archivos:</b><br>"; 

    foreach($items as $item)
    {
        if ($item->type=='dir')
        {
            echo '<a href="?dir='.$path.'/'.$item->entry.'">'.$item->entry.'</a><br>';
            //list_dir($item->full_path);
        }
        else
        {
            echo '<br>'.OutputThumb($item->full_path).'<br>';
        }
    }

    echo "</ul>";

}

/* Finding */
function get_sorted_entries($path)
{
    $dir_handle = @opendir($path) ;
    $items = array();

    while (false !== ($item = readdir($dir_handle))) 
    {
        $dir =$path.'/'.$item;
        if ( $item == '.' or ($item == '..' and $path == 'Gallery' )  )
            continue;

        if(is_dir($dir))
        {
            $items[] = (object) array('type'=>'dir','entry'=>$item, 'full_path'=>$dir);
        }
        else
        {
            $items[] = (object) array('type'=>'file','entry'=>$item, 'full_path'=>$dir);
        }
    }
    closedir($dir_handle);

    usort($items,'_sort_entries');

    return $items;
}

/* Sorting */
function _sort_entries($a, $b)
{
    return strcmp($a->entry,$b->entry);
}

function url(){
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["PHP_SELF"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"];
 }
 return str_ireplace('/ibrowser.php','',$pageURL);
}
    
function urlno(){
    if(empty($php_self)){$php_self = $_SERVER['PHP_SELF'];}
$filename = explode("/", $php_self); // THIS WILL BREAK DOWN THE PATH INTO AN ARRAY
for( $i = 0; $i < (count($filename) - 1); ++$i ) {
$filename2 .= '/'.$filename[$i];
}
$pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"];
 }


return str_ireplace('//','/',$pageURL.$filename2);
}
    
/*function thumbs($fileT, $widthT = 75){
        
    $file = $fileT;
    
    $width = $widthT;
    
    $eext = pathinfo($file);
    $eext = strtolower($eext['extension']);
        if($eext == 'png'){
        $okeyc = "12345";  
        $img = @imagecreatefrompng($file)or $okeyc = "";} 
        if($eext == 'jpg' or $eext == 'jpeg'){
        $okeyc = "12345";  
        $img = @imagecreatefromjpeg($file)or $okeyc = "";} 
        if($eext == 'gif'){
        $okeyc = "12345";  
        $img = @imagecreatefromgif($file)or $okeyc = "";} 
    
    if($okeyc == "12345"){
        
    $picsize = $width; 
    $new_w = imagesx($img); 
    $new_h = imagesy($img); 
    
    $aspect_ratio = $new_h / $new_w;   //ok todo
    
    if ($new_w < $new_h){   
    $new_h = $picsize;
    $new_w = abs($new_h / $aspect_ratio);
    }else{
    $new_w = $picsize; 
    $new_h = abs($new_w * $aspect_ratio);
    }
    $dst_img = ImageCreateTrueColor($new_w,$new_h); 
    
     if(($eext == 'gif') OR ($eext=='png'))
    {
        imagealphablending($dst_img, false);
        imagesavealpha($dst_img,true);
        $transparent = imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
        imagefilledrectangle($dst_img, 0, 0, $new_w, $new_h, $transparent);
    }
    
    imagecopyresampled($dst_img,$img,0,0,0,0,$new_w,$new_h,imagesx($img),imagesy($img));
    
        if($eext == 'png'){          
        imagepng($dst_img,NULL,65);} 
        if($eext == 'jpg' or $eext == 'jpeg'){ 
        imagejpeg($dst_img,NULL,65);} 
        if($eext == 'gif'){ 
        imagegif($dst_img,NULL);} 
    
    imagedestroy($dst_img);
    }
        
    }*/
    
?>