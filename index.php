<?php
//-Settings 設定
//1 = enable , 0 = disable
//変換後のファイルを削除(有効/無効) Delete the converted file(enable/disable)
$_convertedFileDelete = 1;
//元のファイルを保存(有効/無効) Save the original file(enable/disable)
//$_originalFileSave = 0;予定 plans
  

//Words ワード
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
$change[0] = "変更";
$change[1] = "Change";

$overview[0] = "<br><br><br><br>オンライン上でPNGを8ビットPNGに変換できるツール<br><br>[アップロード]をクリックすると、変換されてダウンロードされます。<br>ネットワークエラーでダウンロードできない場合は、もう一度押してください。";
$overview[1] = "<br><br><br><br>A tool that can convert PNG to 8-bit PNG online<br><br>Click \"Upload\" to convert and download.<br>If you cannot download due to network error, press again.";
?>
<?php
if (($_POST["lb1"] == "default")||($_POST["lb1"] == null)){
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
}
if ($_POST["lb1"] == "ja"){$lang = "0";}
if ($_POST["lb1"] == "en"){$lang = "1";}
if ($lang == '') {
  $lang = "1";
}
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="index_main.css">
    <title>PNG to 8Bit PNG Converter</title>
  </head>  
  <body>
    <h1>PNG to 8Bit PNG Converter</h1>
    <form method="post" action="">
      <select name="lb1" size="1">
        <option value="default" label="Default" selected></option>
        <option value="ja" label="日本語"></option>
        <option value="en" label="English"></option>
      </select>
      <input type="submit" value="<?php echo $change[$lang] ?>"/>
    </form>
    <form method="post" action="" enctype="multipart/form-data">
    <input type="file" name="upload" multiple/>
    <input type="submit" value="<?php echo $upload[$lang] ?>"/>
    </form>
<?php
    if(is_uploaded_file($_FILES['upload']['tmp_name'])){
        mkdir("./temp_image", 0777);
        $tempFileName = md5(uniqid(rand(), true));
        $tempFileName .= '.'.substr(strrchr($_FILES['upload']['name'], '.'), 1);
        if(move_uploaded_file($_FILES['upload']['tmp_name'],"./temp_image/".$tempFileName)){
          if ($_FILES['upload']['type'] == "image/png"){
            $UPLOAD_FILE = $_FILES['upload']['tmp_name'];
            echo $uploaded[$lang]."<br>".filesize($UPLOAD_FILE)."B"."<br>".$_FILES['upload']['name']."<br><br>";
            $imageName = 'temp_image/'.$tempFileName;
            //$im = imagecreatefrompng($_FILES['upload']['name']);            
            $cmd = 'convert '.$imageName.' -background white -flatten PNG8:'.$imageName;
            exec($cmd, $opt, $return_ver);
            if ($return_ver==0){
              download('./'.$imageName);


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

    header('Content-Disposition: attachment; filename="' . basename($_FILES['upload']['name']) . '"');

    header('Connection: close');

    while (ob_get_level()) { ob_end_clean(); }

    readfile($pPath);
    echo $downloaded;
    if ($_convertedFileDelete == 1){unlink('./'.$pPath);}
    exit;
}
?>
    
</body>
  
  <footer>
    <a href="https://twitter.com/KeeEeeE_pItaN"><?php echo $author[$lang] ?>:Keeeeeeeeeeee(soediVK,Pitan)</a>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="https://github.com/PTOM76/png-to-8bit-png-converter-php"><?php echo $source[$lang] ?>:https://github.com/PTOM76/png-to-8bit-png-converter-php</a>
  </footer>
  <?php echo $overview[$lang] ?>
</html>
