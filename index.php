<?php
// Main page

include_once 'pic_funcs.php';
// Page with the list of the pictures

// Verification of authorization
session_start();
if (!isset($_SESSION['login'])) header('Location: auth/');

$error = false;
// Processing pic-send POST request
if (isset($_POST['send'])) {
    // Checking the file extension
    $ext = getExt($_FILES['img']['name']);

    // Processing picture if the file extension is valid
    if ($ext === 'jpg' or $ext === 'gif' or $ext === 'png') {
        $pic_num = savePic($_FILES['img']['tmp_name'], $_SESSION['login'], $ext);
        $filename = 'static/imgs/'.$_SESSION['login'].'_'.$pic_num.'.'.$ext;
        resizePic($_SESSION['login'], $pic_num, $ext);
    }
    else $error = 'Можно загрузить только файлы форматов jpg/gif/png';
}

// Processing pic-del POST request
if (isset($_POST['del'])) {
    deleteImage($_SESSION['login'], $_POST['dn']);
}

// Displaying the content if the verification is passed
include_once 'template.html';
include_once 'content_upper.html';
if ($error !== false) echo $error;
include_once 'content_center.html';
$names = getImagesNames($_SESSION['login']);
for ($i = 0; $i < count($names['resized']); $i++) {
    echo '<li>
              <a href="javascript:PopUpPicShow('.$names["original"][$i].')">
                  <img src="'.$names["resized"][$i].'">
              </a>
              <form method="post" action="">
                  <input type="hidden" name="dn" value="'.$names["pic_num"][$i].'">
                  <button type="submit" name="del">Удалить</button>
              </form>
          </li>';
}
include_once 'content_lower.html';