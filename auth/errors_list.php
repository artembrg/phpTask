<?php
if ($res) {
    include_once 'regForm.html';
}
include_once 'errorsStart.html';
foreach ($errors as $key => $value) {
    if ($value !== false) {
        echo '<li>'.$value.'</li>';
    }
}
include_once 'errorsEnd.html';
