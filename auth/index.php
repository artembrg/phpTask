<?php
// Authorization and registration page

// Verification of authorization
session_start();
$is_authorized = isset($_SESSION['login']);

// Unsetting session login if user is authorized
if ($is_authorized) unset($_SESSION['login']);

$errors = array(
    'login' => false,
    'email' => false,
    'password' => false
);
$res = false;

// Processing the POST-request if exists
if (isset($_POST['auth'])) {
    include_once 'auth_funcs.php';

    // Validating login from request
    if (checkLoginExists($_POST['login']) !== false) {

        // Validating password from request
        if (validatingPassword($_POST['login'], $_POST['password']) !== false) {
            header('Location: ../');
            $_SESSION['login'] = $_POST['login'];
        }
        else {
            $errors['password'] = 'Неверно введен пароль';
        }
    }
    else {
        $errors['login'] = 'Неверно введен логин';
    }
}
elseif (isset($_POST['reg'])) {
    include_once 'auth_funcs.php';

    // Validating login from request
    if (checkLoginExists($_POST['login']) !== false) {
        $errors['login'] = 'Логин уже существует, попробуйте другой';
        $res = true;
    }

    // Validating email from request
    if (validateEmail($_POST['email']) !== 1) {
        $errors['email'] = 'Такой адрес электронной почты не может существовать';
        $res = true;
    }

    // Validating password from request
    if ($_POST['password'] !== $_POST['password_conf']) {
        $errors['password'] = 'Пароли не совпадают';
        $res = true;
    }

    // Registration of the user if there are no errors
    if ($res === false) {
        register($_POST['login'], $_POST['password']);
    }
}

include_once '../template.html';
include_once 'auth_content_upper.html';
include_once 'errors_list.php';
include_once 'auth_content_lower.html';
