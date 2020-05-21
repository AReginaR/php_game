<?php

require_once '../db.php';
//require_once 'function.php';
//require_once '../pages/login.php';

$data = $_POST;
    $email = $data['email'];
    $pass = $data['password'];

    $validate = true;

    if (!preg_match('#^([a-z0-9_.-]{1,20}+)@([a-z0-9_.-]+)\.([a-z\.]{2,10})$#', $email)) {
        $_SESSION['emailErr'] = 'email введен неверно';
        $validate = false;
    }
    if (trim($email) == ''){
        $_SESSION['emailErr'] = 'Введите email';
        $validate = false;
    }
    if (trim($pass) == ''){
        $_SESSION['passErr'] = 'Введите пароль';
        $validate = false;
    }

    if ($validate) {
        $user = getUser($email);
        $res_pass = password_verify($pass, $user['password']);
        if ($user && $res_pass) {//если пароль совпадает, то нужно авторизовать пользователя
            $_SESSION['logged_user'] = $user;
            if (isset($data['check'])) {// если нажата кнопка запомнить меня
                setcookie('email', $user['email'], time() + 3600);
                setcookie('nickname', $user['nickname'], time() + 3600);
                setcookie('id', $user['id'], time() + 3600);
                setcookie('role', $user['role'], time() + 3600);
            }
            header('Location: http://localhost:63342/GameProject42/profile.php');
            exit();
        } else {
            $_SESSION['emailErr'] = 'Неверно введен email или пароль!';
          //  header('Location: http://localhost:63342/GameProject42/login.php');
        }
    }
    header('Location: http://localhost:63342/GameProject42/login.php');
    exit();


?>