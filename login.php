<?php
error_reporting(-1);
require_once 'db.php';
require_once 'pages/header.php';
?>
<body class="log">
<div class="container">
    <div class="row">
        <div class="col-md-offset-3 col-md-6">

            <div class="form-reg">
                <!-- Nav tabs -->
                <ul class="nav nav-form" >
                    <li role="presentation" class="active" style="text-align: center;"><a href="login.php" aria-controls="home" >Вход</a></li>
                    <li role="presentation"  style="text-align: center;"><a href="signUp.php" aria-controls="home" >Регистрация</a></li>
                </ul>
            </div>
                <div id="login">
                    <form class="form-horizontal" method="post" action="auth/login.php">

                        <div class="form-group">
                            <label for="exampleInputEmail1">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php if (isset($_SESSION['email'])){
                                        echo $_SESSION['email'];
                                    }?>" autofocus>
                            <span style='color: red'><strong><?php
                                    if (isset($_SESSION['emailErr'])){
                                        echo $_SESSION['emailErr'];
                                        unset($_SESSION['emailErr']);
                                    }
                                    ?></strong></span>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">Пароль</label>
                            <input minlength="8" type="password" class="form-control" name="password" value="<?php if (isset($_SESSION['email'])){
                                echo $_SESSION['email'];
                            }?>">
                            <span style='color: red'><strong><?php
                                    if (isset($_SESSION['passErr'])){
                                        echo $_SESSION['passErr'];
                                        unset($_SESSION['passErr']);
                                    }
                                    ?></strong></span>
                        </div>

                        <div class="form-group">
                            <div class="main-checkbox">
                                <input value="None" id="checkbox1" name="check" type="checkbox">
                                <label for="checkbox1"></label>
                            </div>
                            <span class="text">Запомнить меня</span>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-default" name="submit">Войти</button>
                        </div>

<!--                            <div class="form-group forgot-pass">-->
<!--                                <button type="submit" class="btn btn-default">Забыли пароль?</button>-->
<!--                            </div>-->
                    </form>
                </div>

        </div>
    </div>

</div>
</body>
</html>