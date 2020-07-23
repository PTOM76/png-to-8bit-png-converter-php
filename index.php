<?php
//言語 Language
$upload[0] = "アップロード";
$upload[1] = "Upload";
$error_saving[0] = "保存中にエラーが発生しました。";
$error_saving[1] = "Error while saving.";
$uploaded[0] = "正常にアップロードされました。";
$uploaded[1] = "Uploaded successfully.";
$notpngfile[0] = "アップロードされたファイルはPNGファイルではありません。";
$notpngfile[1] = "Uploaded file is not a PNG file.";
$cmd_error[0] = "変換中にエラーが発生しました。コード:";
$cmd_error[1] = "Error while converting.Code:";
$downloaded[0] = "正常にダウンロードされました。";
$downloaded[1] = "Download successfully.";
$author[0] = "作者";
$author[1] = "Author";
$source[0] = "ソースコード";
$source[1] = "Source code";

?>
<?php
$languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
$languages = array_reverse($languages);
 
$lang = '';
 
foreach ($languages as $language) {
  if (preg_match('/^en/i', $language)) {
    $lang = "1";
  } elseif (preg_match('/^ja/i', $language)) {
    $lang = "0";
  } 
}
if ($lang == '') {
  $lang = "1";
}
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="top.css">
    <title>PNG to 8Bit PNG Converter</title>
  </head>  
  <body>
    <h1>PNG to 8Bit PNG Converter</h1>
    <form method="post" action="" enctype="multipart/form-data">
    <input type="file" name="upload" multiple/>
    <input type="submit" value="<?php echo $upload[$lang] ?>"/>
    </form>
  </body>
  <footer>
    <a href="https://twitter.com/KeeEeeE_pItaN"><?php echo $author[$lang] ?>:Keeeeeeeeeeee(soediVK,Pitan)</a>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="https://github.com/PTOM76/png-to-8bit-png-converter-php"><?php echo $source[$lang] ?>:https://github.com/PTOM76/png-to-8bit-png-converter-php</a>
  </footer>
</html>
<?php
    if(is_uploaded_file($_FILES['upload']['tmp_name'])){
        mkdir("./temp_image", 0777);
        if(move_uploaded_file($_FILES['upload']['tmp_name'],"./temp_image/".$_FILES['upload']['name'])){
          if ($_FILES['upload']['type'] == "image/png"){
            $UPLOAD_FILE = $_FILES['upload']['tmp_name'];
            echo $uploaded[$lang]."<br>".filesize($UPLOAD_FILE)."B"."<br>".$_FILES['upload']['name']."<br><br>";
            $imageName = 'temp_image/'.$_FILES['upload']['name'];
            $im = imagecreatefrompng($_FILES['upload']['name']);            
            $cmd = 'convert '.$imageName.' -background white -flatten PNG8:'.$imageName;
            exec($cmd, $opt, $return_ver);
            if ($return_ver==0){
              download('./'.$imageName);
              echo $downloaded;
              //exec('rm -f '.$imageName);
              unlink('./'.$imageName);
            }else{
              echo $cmd_error[$lang].$return_ver;
            }
          } else {
            echo $notpngfile[$lang];
          }


        }else{
            echo $error_saving[$lang];
        }
    }
function download($pPath, $pMimeType = null)
{
    if (!is_readable($pPath)) { die($pPath); }

    $mimeType = (isset($pMimeType)) ? $pMimeType
                                    : (new finfo(FILEINFO_MIME_TYPE))->file($pPath);

    if (!preg_match('/\A\S+?\/\S+/', $mimeType)) {
        $mimeType = 'application/octet-stream';
    }

    header('Content-Type: ' . $mimeType);

    header('X-Content-Type-Options: nosniff');

    header('Content-Length: ' . filesize($pPath));

    header('Content-Disposition: attachment; filename="' . basename($pPath) . '"');

    header('Connection: close');

    while (ob_get_level()) { ob_end_clean(); }

    readfile($pPath);

    exit;
}