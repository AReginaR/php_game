<?php
//error_reporting(E_USER_ERROR);
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Колонизация планеты</title>
    <link rel="stylesheet" href="resources/css/map.css">
    <script src="resources/js/script.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body style="background: #dcd3dc">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
    ajaxPopulationIncrease();
    function ajaxPointsIncrease() {
        var id = "<?php echo $_SESSION['logged_user']['id']?>";
        $('#pointsButton').css("display","none");
        $.ajax({
            type: "POST",
            url: "ajax/ajaxIncreasePoints.php",
            data: {id:id}
        }).done(function (result) {
            $("#points").text(result);
        })
    }

    function ajaxPopulationIncrease() {
        var id = "<?php echo $_SESSION['logged_user']['id']?>";
        $.ajax({
            type: "POST",
            url: "ajax/ajaxIncreasePopulation.php",
            data: {id:id}
        }).done(function (result) {
            $("#population").text(result);
        })
    }

    function ajaxImproveWelfare() {
        var id = "<?php echo $_SESSION['logged_user']['id']?>";
        $.ajax({
            type: "POST",
            url: "ajax/ajaxImproveWelfare.php",
            data: {id:id}
        }).done(function (result) {
            var data = jQuery.parseJSON(result);
            if (data != null) {
                $("#points").text(data[1]);
                $("#welfare").text(data[0]);
            }
        })
    }

    function buyItem(title) {
        var id = "<?php echo $_SESSION['logged_user']['id']?>";
        $.ajax({
            type: "POST",
            url: "ajax/ajaxBuyItem.php",
            data: {title: title, id: id}
        }).done(function (result) {
            var data = jQuery.parseJSON(result);
            if (data != null) {
                $("#points").text(data[0]);
                if (data[1] != null) {
                        $('[id="' + title+" count" + '"]').text(data[1]);
                }
            }
        })
    }
    function useItem(title) {
        var disaster = title.replace("vs ", "");
        var id = "<?php echo $_SESSION['logged_user']['id']?>";
        $.ajax({
            type: "POST",
            url: "ajax/ajaxUseItem.php",
            data: {id: id, title: title}
        }).done(function (result) {
            var data = jQuery.parseJSON(result);
            $('[id="' + title +" count" + '"]').text(data[0]);
            $('[id="' + data[2] +" count" + '"]').text(data[1]);
          // $("'"+disaster+":first'").detach();
            document.getElementsByClassName(disaster)[0].remove();
        })
    }
    function addDisaster(title)
    {
        var id = "<?php echo $_SESSION['logged_user']['id']?>";
        $.ajax({
            type: "POST",
            url: "ajax/AddDisaster.php",
            data: {id: id, title: title}
        }).done(function (result) {
        })
    }

    function ajaxDisastersEffect() {
        var id = "<?php echo $_SESSION['logged_user']['id']?>";
        $.ajax({
            type: "POST",
            url: "ajax/ajaxDisasterEffect.php",
            data: {id:id}
        }).done(function (result) {
            $("#population").text(result);
        })
    }

    function ajaxGetItem(title) {
        var id = "<?php echo $_SESSION['logged_user']['id']?>";
        $.ajax({
            type: "POST",
            url: "ajax/ajaxGetItem.php",
            data: {id:id, title: title}
        }).done(function (result) {
            $('[id="' + title+" count" + '"]').text(result);
        })
    }
    setInterval( ajaxPopulationIncrease, 2000);
    setInterval( ajaxDisastersEffect, 3000);
</script>

<ul class="menu-main">
    <li><a href="profile.php">Профиль</a></li>
    <li><a href="map.php" class="current">Карта</a></li>
    <?php if ($_SESSION['logged_user']['role'] == "ADMIN") : ?>
        <li><a href="admin.php">Админ</a></li>
    <?php endif; ?>
</ul>
<header class="map">
<div class="row">
    <div class="col-lg-7 col-md-7 col-sm-12">
        <label>Население:
            <h3 style="/*margin-left: 50px*/" id="population"> <?php echo(GetUserPopulation($_SESSION['logged_user']['id'])); ?></h3>
        </label>
        <label>Очки:
            <h3 style="margin-left: 10px" id="points"><?php echo(GetUserPoints($_SESSION['logged_user']['id'])); ?></h3>
        </label>
       <label >Состояние населения:
           <h3 style="margin-left: 10px" id="welfare"> <?php echo(GetUserPopulationWelfare($_SESSION['logged_user']['id'])); ?></h3>
       </label>

        <!-- Отсюда -->
        <div class="row">
            <div class="col-lg-10" style="padding-right: 0">
                <div class="progress" style="height: 40px; /*margin-left: 5pc;*/ color: #10828C">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width:  <?php echo(GetUserPopulationWelfare($_SESSION['logged_user']['id']));?>%; max-width: 50pc;">Счастье населения</div>
                </div></div>
            <div class = 'col-lg-2' style="padding-left: 0">
                <div align="right">Улучшить: <button style="background-color:#10828C;max-width: 3pc; font-size:20px;" onclick="ajaxImproveWelfare()">+</button></div>
            </div>
        </div>
        <img src="resources/images/карта1.png" class="img-fluid mapImg">
    </div>
    <div class="col-lg-5 col-md-5 col-sm-12 desc">
        <div class="bottom-menu">
            <div class="row">
                <div class="col-lg-5 col-md-5">
                    <button type="button" data-toggle="modal" data-target="#exampleModalLong">
                        Лидеры
                    </button>
                </div>
                <div class="col-lg-5 col-md-5">
                    <button type="button" data-toggle="modal" data-target="#shop">
                        Магазин
                    </button>
                </div>
            </div>
        </div>
        <div class="intselect">
            <h2>Инвентарь</h2>
        </div>
        <div class="container">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Icon</th>
                    <th scope="col">Название</th>
                    <th scope="col">Против</th>
                    <th scope="col">Количество</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach (GetUserStock($_SESSION['logged_user']['id']) as $item): ?>
                    <tr>
                        <th scope="row"><img src = 'resources/potion/<?php echo $item[0]; ?>.png' onclick="useItem('<?php echo $item[0]; ?>')" style="width: 50px; height: 50px" /></th>
                        <td><?php echo $item[2]; ?></td>
                        <td><?php echo $item[3]; ?></td>
                        <td id="<?php echo $item[0]; ?> count"><?php echo $item[1]; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="intselect">
            <h2>Ваши бедствия</h2>
        </div>
        <div class="container">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Icon</th>
                    <th scope="col">Название</th>
                    <th scope="col">Количество</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach (GetUserDisasters($_SESSION['logged_user']['id']) as $item): ?>
                    <tr>
                        <th scope="row"><img src="resources/disaster/<?php echo $item[0]?>.png" style="width: 50px; height: 50px">Icon</img></th>
                        <td><?php echo $item[0]?></td>
                        <td id="<?php echo $item[0]; ?> count"><?php echo $item[1]; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

        <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Таблица лидеров</h5>
                    </div>
                    <div class="modal-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Место</th>
                                <th>Имя игрока</th>
                                <th>Население</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $bestUsers = getBestUsers(); ?>
                            <?php $i = 1; ?>
                            <?php foreach ($bestUsers as $user) : ?>
                                <tr>
                                    <th scope="row"><?php echo $i; $i++;?></th>
                                    <td><?php echo $user['nickname']; ?></td>
                                    <td><?php echo $user['population']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- > Shop modal -->
        <!-- Button trigger modal -->


        <!-- Shop -->
        <div class="modal fade" id="shop" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" style="text-align: center">Магазин</h5>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Название</th>
                                <th scope="col">Против</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row" ><img src = 'resources\potion\1.png' style="width: 100px; height: 100px" /></th>
                                <td>Navodneniym</td>
                                <td>Наводнения</td>
                                <td>25</td>
                                <td><a class="buybutton" href="#"  onclick="buyItem('vs flood')">Тык</a></td>
                            </tr>
                            <tr>
                                <th scope="row"><img src = "resources\potion\2.png" style="width: 100px; height: 100px"/></th>
                                <td>Tornadiym</td>
                                <td>Урагана</td>
                                <td>5</td>
                                <td><a class="buybutton"  href="#"  onclick="buyItem('vs hurricane')">Тык</a></td>
                            </tr>
                            <tr>
                                <th scope="row"><img src = "resources\potion\3.png" style="width: 100px; height: 100px"/></th>
                                <td>AntiFireiym</td>
                                <td>Пожара</td>
                                <td>10</td>
                                <td><a class="buybutton"  href="#"  onclick="buyItem('vs wildfire')">Тык</a></td>
                            </tr>
                            <tr>
                                <th scope="row" ><img src = "resources\potion\4.png" style="width: 100px; height: 100px"/></th>
                                <td>Volcanoneniym</td>
                                <td>Извержения вулкана</td>
                                <td>10</td>
                                <td><a class="buybutton"  href="#"  onclick="buyItem('vs eruption')">Тык</a></td>
                            </tr>
                            <tr>
                                <th scope="row" "><img src = "resources\potion\5.png" style="width: 100px; height: 100px"/></th>
                                <td>AntiWariym</td>
                                <td>Войны</td>
                                <td>1000</td>
                                <td><a class="buybutton"  href="#"  onclick="buyItem('vs war')">Тык</a></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!--    $('#exampleModalLong').on('shown.bs.modal', function () {-->
<!--        $('#exampleModalLongTitle').trigger('focus')-->
<!--    })-->
</body>
</html>
<?php
