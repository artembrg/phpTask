<?php

// Extraction of the file extension
function getExt($filename) {
    // Preparing variables
    $matches = array();
    $pattern = '@.*\.(.+)@';

    // Searching the file extension
    preg_match($pattern, $filename, $matches);

    // Return it if found
    if (isset($matches[1])) return $matches[1];
    else return false;
}

// Saving picture on the server storage
function savePic($tmp_name, $login, $ext) {
    // Connecting to db
    include_once 'db/db_connect.php';
    $db = connectToDB();

    // Searching the last picture number of the user
    $result = $db->prepare('SELECT pic_num FROM images WHERE username = :login ORDER BY pic_num DESC LIMIT 1');
    $result->bindParam(':login', $login, PDO::PARAM_STR);
    $result->execute();
    $result->setFetchMode(PDO::FETCH_ASSOC);
    $result = $result->fetch();
    if ($result !== false) $result = $result['pic_num'] + 1;
    else $result = 1;

    // Saving picture
    move_uploaded_file($tmp_name, 'static/imgs/'.$login.'_'.$result.'.'.$ext);

    // Saving info in db
    $query = $db->prepare('INSERT INTO images (username, pic_num, ext) VALUES (:login, :pic_num, :ext)');
    $query->bindParam(':login', $login, PDO::PARAM_STR);
    $query->bindParam(':pic_num', $result, PDO::PARAM_INT);
    $query->bindParam(':ext', $ext, PDO::PARAM_STR);
    $query->execute();

    return $result;
}

// Resizing picture and saving it
function resizePic($login, $pic_num, $ext) {
    // Get width and height of original image
    $file_info = getimagesize('static/imgs/'.$login.'_'.$pic_num.'.'.$ext);
    $width = $file_info[0];
    $height = $file_info[1];

    // Reserving variable for image
    $image = false;

    // Check the condition for resize
    if ($width > 100 or $height > 100) {

        // Create image from original
        switch ($ext) {
            case 'gif':
                $image = imagecreatefromgif('static/imgs/'.$login.'_'.$pic_num.'.'.$ext);
                break;
            case 'jpg':
                $image = imagecreatefromjpeg('static/imgs/'.$login.'_'.$pic_num.'.'.$ext);
                break;
            case 'png':
                $image = imagecreatefrompng('static/imgs/'.$login.'_'.$pic_num.'.'.$ext);
                break;
        }

        // Resizing image
        if ($width > $height) {
            $ratio = 100 / $width;
            $new_height = $height * $ratio;
            $new_image = imagecreatetruecolor(100, $new_height);
            imagecopyresampled($new_image, $image, 0, 0, 0, 0, 100, $new_height, $width,
                $height);
            saveResizedPic($new_image, $login, $pic_num, $ext);
        }
        else {
            $ratio = 100 / $height;
            $new_width = $width * $ratio;
            $new_image = imagecreatetruecolor($new_width, 100);
            imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, 100, $width,
                $height);
            saveResizedPic($new_image, $login, $pic_num, $ext);
        }
    }

    else move_uploaded_file('static/imgs/'.$_SESSION['login'].'_'.$pic_num.'.'.$ext,
        'static/imgs/'.$_SESSION['login'].'_'.$pic_num.'_r.'.$ext);
}

// Saving resized img
function saveResizedPic($new_image, $login, $pic_num, $ext) {
    switch ($ext) {
        case 'gif':
            imagegif($new_image, 'static/imgs/'.$login.'_'.$pic_num.'_r.'.$ext);
            break;
        case 'jpg':
            imagejpeg($new_image, 'static/imgs/'.$login.'_'.$pic_num.'_r.'.$ext);
            break;
        case 'png':
            imagepng($new_image, 'static/imgs/'.$login.'_'.$pic_num.'_r.'.$ext);
            break;
    }
}

// Getting all images for the user
function getImagesNames($login) {
    // Connecting to db
    include_once 'db/db_connect.php';
    $db = connectToDB();

    // Preparing and executing query
    $result = $db->prepare('SELECT pic_num, ext FROM images WHERE username = :login');
    $result->bindParam(':login', $login, PDO::PARAM_STR);
    $result->execute();
    $result->setFetchMode(PDO::FETCH_ASSOC);

    // Forming image's names
    $names = array(
        'resized' => array(),
        'original' => array(),
        'pic_num' => array()
    );
    $params = $result->fetch();
    $i = 0;
    while ($params !== false) {
        $names['resized'][$i] = "static/imgs/".$login.'_'.$params['pic_num'].'_r.'.$params['ext'];
        $names['original'][$i] = "'static/imgs/".$login.'_'.$params['pic_num'].'.'.$params['ext']."'";
        $names['pic_num'][$i] = $params['pic_num'];
        $params = $result->fetch();
        $i++;
    }

    return $names;
}

// Deleting picture
function deleteImage($login, $pic_num) {
    // Connect to db
    include_once 'db/db_connect.php';
    $db = connectToDB();

    // Getting extension
    $result = $db->prepare('SELECT ext FROM images WHERE username = :login AND pic_num = :pic_num');
    $result->bindParam(':login', $login, PDO::PARAM_STR);
    $result->bindParam('pic_num', $pic_num, PDO::PARAM_INT);
    $result->execute();
    $result->setFetchMode(PDO::FETCH_ASSOC);
    $ext = $result->fetch()['ext'];

    // Deleting data from db
    $result = $db->prepare('DELETE FROM images WHERE username = :login AND pic_num = :pic_num');
    $result->bindParam(':login', $login, PDO::PARAM_STR);
    $result->bindParam('pic_num', $pic_num, PDO::PARAM_INT);
    $result->execute();

    // Deleting files from server
    unlink('static/imgs/'.$login.'_'.$pic_num.'_r.'.$ext);
    unlink('static/imgs/'.$login.'_'.$pic_num.'.'.$ext);
}