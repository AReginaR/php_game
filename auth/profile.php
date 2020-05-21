<?php
require_once '../db.php';

$data = $_POST;
echo $id = $_SESSION['logged_user']['id'];
echo $nickname = $data['nickname'];
echo $email = $data['email'];

$errors = false;
if (trim($email) == ''){
    $_SESSION['nickErr'] = 'Ник не лоджен быть пустым';
    $errors = true;
} if(trim($nickname) == '') {
    $errors = true;
    $_SESSION['emailErr'] = 'email не лоджен быть пустым';
}

if(!preg_match('#^([a-z0-9_.-]{1,20}+)@([a-z0-9_.-]+)\.([a-z\.]{2,10})$#', $email)){
    $errors = true;
    $_SESSION['emailErr'] = 'Укажите правильный email';
}

//проверка на существование одинакового логина
$resultEmail = getUser($email);
$resultNick = getUserByNickName($nickname);


if ($resultEmail){
    if($resultEmail['id'] != $id) {
        $_SESSION['emailErr'] = 'Такой email уже зарегистрирован';
        $errors = true;
    }
}
if ($resultNick){
    if ($resultNick['id'] != $id){
        $_SESSION['nickErr'] = 'Такой ник уже зарегистрирован';
        $errors = true;
    }
}

if (!$errors){
    $set = 'nickname = :nickname, email = :email';
    $sql = "UPDATE users SET $set WHERE id = :id";
    $values = [
        'nickname' => $nickname,
        'email' => $email,
        'id' => $id
    ];
    $db = GetConnection();
    $statement = $db->prepare($sql);
    $statement->execute($values);

    $_SESSION['logged_user']['email'] = $email;
    $_SESSION['logged_user']['nickname'] = $nickname;

    $_SESSION['successName'] = 'Профиль успешно изменен';

    if (isset($_COOKIE['email'])) {

        setcookie('email', $email, time() + 3600);
        setcookie('name', $nickname, time() + 3600);
    }
}
header("Location: http://localhost:63342/GameProject42/profile.php");
exit();
