<?php
require_once 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Колонизация планеты</title>
    <link href="resources/css/profile.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body style="background: #dcd3dc">
<ul class="menu-main">
    <li><a href="profile.php">Профиль</a></li>
    <li><a href="map.php">Карта</a></li>
    <?php if ($_SESSION['logged_user']['role'] == "ADMIN") : ?>
    <li><a href="admin.php" class="current">Админ</a></li>
    <?php endif; ?>
</ul>
<header class="profile">
    <div class="row">
        <table>
            <thead>
            <tr>
                <th>Имя</th>
                <th>Email</th>
            </tr>
            </thead>
            <tbody>
                <?php $users = getAllUsers()?>
                <?php foreach ($users as $user) : ?>
                <tr>
                    <td><?php echo $user['nickname'] ?></td>
                    <td><?php echo $user['email'] ?></td>
                    <td>
                        <form action="auth/admin.php" method="post">
                            <button onclick="return confirm('Вы уверены?')" type="submit" name="delete" value="<?php echo $user['id']?>">Удалить</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</header>
<?php
require_once "pages/footer.php"
?>
