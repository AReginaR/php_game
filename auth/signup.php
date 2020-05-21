<?php
require_once '../db.php';
//require_once 'function.php';
//require_once 'pages/login.php';

$data = $_POST;
if ( isset($data['submit']) ) {
    $nickname = $data['nickname'];
    $email = $data['email'];
    $pass = $data['password'];
    $pass2 = $data['password2'];
    $errors = false;
    if (trim($email) != '' && trim($nickname) != '' &&  $pass != '' ) {

        if ( $pass2 != $pass ) {
            $errors = true;
            $_SESSION['passErr'] = 'Повторный пароль введен не верно!';
        }
        if(!preg_match('#^([a-z0-9_.-]{1,20}+)@([a-z0-9_.-]+)\.([a-z\.]{2,10})$#', $email)){
            $errors = true;
            $_SESSION['emailErr'] = 'Укажите правильный email';
        }

        //проверка на существование одинакового логина
        $resultEmail = getUser($email);
        $resultNick = getUserByNickName($nickname);

        if ($resultEmail){
            $_SESSION['emailErr'] = 'Такой email уже зарегистрирован';
            $errors = true;
        }
        if ($resultNick){
            $_SESSION['nickErr'] = 'Такой ник уже зарегистрирован';
            $errors = true;
        }

        if (!$errors){
            $sql = 'INSERT INTO users (nickname, email, password, role, points, population, population_welfare) 
                    VALUES (:nickname, :email, :password, :role, :points, :population, :population_welfare)';
            $values = [
                'nickname' => $nickname,
                'email' => $email,
                'password' => password_hash($pass, PASSWORD_DEFAULT),
                'role' => "USER",
                'points' => "5",
                'population' => "5",
                'population_welfare' => "1"
            ];
//            $sql = 'INSERT INTO users (nickname, email, password, role)
//                    VALUES (:nickname, :email, :password, :role)';
//            $values = [
//                'nickname' => $nickname,
//                'email' => $email,
//                'password' => password_hash($pass, PASSWORD_DEFAULT),
//                'role' => "USER",
//            ];
            $db = GetConnection();
            $statement = $db->prepare($sql);
            $statement->execute($values);
            userStock($email);
            header("Location: http://localhost:63342/GameProject42/login.php");
            exit;
        }else {
            header("Location: http://localhost:63342/GameProject42/signup.php");
        }

    } else {
        $_SESSION['Err'] = 'Заполните поля!';
    }

}
