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
<?php    
    
if(isset($_GET["n"])){
 
    function deleteDirectory($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir) || is_link($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!deleteDirectory($dir . "/" . $item)) {
                chmod($dir . "/" . $item, 0777);
                if (!deleteDirectory($dir . "/" . $item)) return false;
            };
        }
        return rmdir($dir);
    } 
    
$path = $_GET["n"]; 
deleteDirectory($path);   
   
  
    print "
    <fieldset id='setlng' style=\"border-color:red;background-color:#FF9D9D;color:black;text-decoration:blink;\">
    <legend>Alerta!</legend>
    Listo.
    </fieldset>
    ";    
    
}

if(isset($_GET["v"])){
    
    function deleteFiles($dir){
    $files = array();
    $directory = opendir($dir);
    while($item = readdir($directory)){
    // We filter the elements that we don't want to appear ".", ".." and ".svn"
         if(($item != ".") && ($item != "..") && (!is_dir($dir."/".$item))){
             unlink($dir."/".$item);
         }
    }
    closedir($directory);
    clearstatcache();
    return true;
}
    
$path = $_GET["v"]; 
deleteFiles($path);   
   
  
    print "
    <fieldset id='setlng' style=\"border-color:red;background-color:#FF9D9D;color:black;text-decoration:blink;\">
    <legend>Alerta!</legend>
    Listo.
    </fieldset>
    ";    
    
}

if(isset($_POST["folder"]) and !empty($_POST["folder"])){
 
 $_POST["folder"] = str_replace("/","",$_POST["folder"]);   
 $_POST["folder"] = str_replace("\\","",$_POST["folder"]); 
 $_POST["folder"] = str_replace(" ","_",$_POST["folder"]); 
    
$path = "Gallery/".$_POST["folder"]; 
@mkdir($path);   
  
    print "
    <fieldset id='setlng' style=\"border-color:red;background-color:#FF9D9D;color:black;text-decoration:blink;\">
    <legend>Alerta!</legend>
    Listo.
    </fieldset>
    ";    
    
}

if (isset($_POST["id"]) and $_POST["id"]!=''){ 
echo "<center><hr><h1><font id='setlng' face=tahoma color=#006699>Log del upload.</font></h1><hr><br></center>";  
     
           $filename=$_FILES["archivo"]["tmp_name"];
           $filename2=$_FILES["archivo"]["name"];
           $filename2=str_replace(" ","",$filename2);
           $filename2=str_replace("_","",$filename2);
           $filename2=str_replace("á","",$filename2);
           $filename2=str_replace("é","",$filename2); 
           $filename2=str_replace("í","",$filename2); 
           $filename2=str_replace("ó","",$filename2); 
           $filename2=str_replace("ú","",$filename2); 
           $filename3=$_FILES["archivo"]["size"]; 
           $filename4=$_FILES["archivo"]["type"];
           
           $path = $_POST["id"]."/"; 
           
           $ferror='';    
     if (is_uploaded_file($filename)){
    if($filename4 <> 'image/gif' and $filename4 <> 'image/pjpeg' and $filename4 <> 'image/x-png' and $filename4 <> 'image/jpeg' and $filename4 <> 'image/png') $ferror = "<b>La extencion no es valida '$filename4'.</b>";
    if($ferror != ''){
        
    print "
    <fieldset style=\"border-color:red;background-color:#FF9D9D;color:black;text-decoration:blink;\">
    <legend>Alerta!</legend>
    Error en: $filename2 - $ferror
    </fieldset>
    ";
                     }else{

    @mkdir($path);
    if(file_exists($path.$filename2))$filename2 = mt_rand(1,99999).$filename2;  
    @move_uploaded_file($filename,$path.$filename2)or die("<b>No se movio el archivo!</b>");
    @chmod($path.$filename2,0644)or die("<b>No se movieron los permisos del archivo!</b>");
    
    $file = $path.$filename2;
    
    $width = 230;
    if(isset($_POST["width"]) and $_POST["width"] != '')$width = $_POST["width"]; 
    
    if($width != '='){
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

    if($eext=="png") {
            imagealphablending($dst_img, false);
            $colorTransparent = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);
            imagefill($dst_img, 0, 0, $colorTransparent);
            imagesavealpha($dst_img, true);
        } elseif($eext=="gif") {
            $trnprt_indx = imagecolortransparent($img);
            if ($trnprt_indx >= 0) {
                //its transparent
                $trnprt_color = imagecolorsforindex($img, $trnprt_indx);
                $trnprt_indx = imagecolorallocate($dst_img, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
                imagefill($dst_img, 0, 0, $trnprt_indx);
                imagecolortransparent($dst_img, $trnprt_indx);
            }
        }
    
    imagecopyresampled($dst_img,$img,0,0,0,0,$new_w,$new_h,imagesx($img),imagesy($img));
    
        if($eext == 'png'){          
        imagepng($dst_img,$path.$filename2,0);} 
        if($eext == 'jpg' or $eext == 'jpeg'){ 
        imagejpeg($dst_img,$path.$filename2,100);} 
        if($eext == 'gif'){ 
        imagegif($dst_img,$path.$filename2);} 
    
    }else{
    unlink($file);
    $nogo = "true";
    print "
    <fieldset style=\"border-color:red;background-color:#FF9D9D;color:black;text-decoration:blink;\">
    <legend>Alerta!</legend>
    ERROR al procesar ".$filename2." intente de nuevo con otra imagen.
    </fieldset>
    ";
         }  
    }      
         
         
    //continuar con la carga de la informacion.
    if($nogo != "true"){
    echo "<fieldset id='setlng'><b>Listo... $filename2</b></fieldset>";  
    }
      
    }}else{
        
    print "
    <fieldset id='setlng' style=\"border-color:red;background-color:#FF9D9D;color:black;text-decoration:blink;\">
    <legend>Alerta!</legend>
    No se cargo ningun archivo.
    </fieldset>
    ";     

    }
    //el echo para regresar al editor.  
 }
 ?>
 <a href="fileupload.php">Atr&aacute;s</a>
 <script type="text/javascript">
<!--
setTimeout("location.href='fileupload.php';",5000);
-->
</script> 

