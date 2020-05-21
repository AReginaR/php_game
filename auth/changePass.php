<?php
require_once '../db.php';

$data = $_POST;

$id = $_SESSION['logged_user']['id'];
$email = $_SESSION['logged_user']['email'];
$pass = $data['password'];
$newPass = $data['newPass'];
$newPassConfirm = $data['newPassConfirm'];

$errors = false;

if (trim($pass) != '' && trim($newPass) != '' && trim($newPassConfirm) != ''){

    $user = getUser($email);

    if(password_verify($pass, $user['password'])){
        if ($newPass !== $newPassConfirm) {

            $_SESSION['passErr'] = 'Пароли не совпадают';// Сообщение об ощибке
            header('Location: http://localhost:63342/GameProject42/profile.php');            //Редирект обратно
        }
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $values = [
            ':password' => password_hash($newPass, PASSWORD_DEFAULT),
            ':id' => $id
        ];
        $db = GetConnection();
        $statement = $db->prepare($sql);
        $statement->execute($values);

        $_SESSION['passSuccess'] = 'Пароль обновлён'; // Сообщение об успешном изменении пароля
        header("Location: http://localhost:63342/GameProject42/profile.php");
        exit();
    } else{
        $_SESSION['passErr'] = 'Пароль неверный'; // Сообщение об успешном изменении пароля
        header("Location: http://localhost:63342/GameProject42/profile.php");
        exit();
    }
} else {
    $_SESSION['passErr'] = 'Поля должны быть заполнены'; // Сообщение об успешном изменении пароля
    header("Location: http://localhost:63342/GameProject42/profile.php");
    exit();
}
