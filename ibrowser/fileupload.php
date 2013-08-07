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
<center><h2>Cargar una imagen</h2></center>
<?php

$directory = "Gallery"; 
$files = glob($directory, GLOB_ONLYDIR );
?>
Selecciona el directorio destino:<br> 
<form action='upload.php' name='n' method=post>
Nueva carpeta: <input type="text" value="" name="folder"><input type="submit" value="Crear">
</form> <hr> 
<form action='upload.php' name='f' method=post  enctype='multipart/form-data'> 
<?php 
foreach($files as $file)
{
  echo "<input type='radio' name='id' value='{$file}' checked='checked'> ".$file." ";
    echo "(".countFiles($file)." files) <a href='upload.php?v=$file'>Vaciar</a><br>";
}


$directory = "Gallery/";
$files = glob($directory . "*", GLOB_ONLYDIR ); 
foreach($files as $file)
{
  echo "<input type='radio' name='id' value='{$file}'> ".$file." ";
  echo "(".countFiles($file)." files) <a href='upload.php?n=$file'>Eliminar</a> | <a href='upload.php?v=$file'>Vaciar</a><br>";
 
}
?>
<hr>
<fieldset>
<legend>Subir Foto.</legend>

<table>
<tr><td>Archivo:</td><td>
<input name="archivo" type="file" /></td><td style="font-size:x-small">Solo se aceptan archivos de tipo: JPG, GIF o PNG.</td></tr>
<tr bgcolor="#CCCCCC"><td>Avanzado:</td><td>
 width o height MAX: <input name="width" type="text" style="width: 44px; height: 16px;" />Px </td><td style="font-size:x-small">
    Solo si sabe para que funciona use este campo. <br>Predeterminado: 230px <br>Usar signo de <b>=</b> para no hacer redimension a la imagen. </td></tr>
    <tr><td></td><td align='right'>
    
    <input name='Submit1' type='submit' value="Cargar..." /></td><td></td></tr>
</table>
</fieldset> 
</form>
</body>
</html>
<?php
function countFiles($dir){
    $files = array();
    $directory = opendir($dir);
    while($item = readdir($directory)){
    // We filter the elements that we don't want to appear ".", ".." and ".svn"
         if(($item != ".") && ($item != "..") && (!is_dir($dir."/".$item))){
             $files[] = $item;
         }
    }
    $numFiles = count($files);
    closedir($directory);
    clearstatcache();
    return $numFiles;
} 
 ?>
