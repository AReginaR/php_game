<?php
error_reporting(-1);
require_once 'db.php';
require_once 'pages/header.php';
?>
<body class="log">
<div class="container">
    <div class="row">
        <div class="col-md-offset-3 col-md-6">
            <div>
                <!-- Nav tabs -->
                <div class="form-reg">
                    <!-- Nav tabs -->
                    <ul class="nav nav-form" >
                        <li role="presentation"  style="text-align: center;"><a href="login.php" aria-controls="home" >Вход</a></li>
                        <li role="presentation" class="active" style="text-align: center;"><a href="signUp.php" aria-controls="home" >Регистрация</a></li>
                    </ul>
                </div>
                <!-- Tab panes -->
                    <div id="signUp">
                        <form class="form-horizontal" method="post" action="auth/signup.php">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email</label>
                                <input type="email" id="exampleInputEmail1" class="form-control" name="email" required>
                                <span style='color: red'><strong><?php
                                        if (isset($_SESSION['emailErr'])){
                                            echo $_SESSION['emailErr'];
                                            unset($_SESSION['emailErr']);
                                        }
                                        ?></strong></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Ник</label>
                                <input type="text" class="form-control" id="exampleInputNickName" name="nickname">
                                <span style='color: red'><strong><?php
                                        if (isset($_SESSION['nickErr'])){
                                            echo $_SESSION['nickErr'];
                                            unset($_SESSION['nickErr']);
                                        }
                                        ?></strong></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Пароль</label>
                                <input minlength="1" type="password" class="form-control" id="exampleInputPassword1" name="password">
                                <span style='color: red'><strong><?php
                                        if (isset($_SESSION['passErr'])){
                                            echo $_SESSION['passErr'];
                                            unset($_SESSION['passErr']);
                                        }
                                        ?></strong></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Повторите пароль</label>
                                <input minlength="8" type="password" class="form-control" id="exampleInputPasswordConfirm" name="password2">
                            </div>
                            <div class="form-group">
                                <button minlength="8" type="submit" class="btn btn-default" name="submit">Зарегистрироваться</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
</body>
</html>