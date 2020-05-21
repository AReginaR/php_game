<?php
$dbh = null;
session_start();

function GetConnection()
{
    global $dbh;
    try {
        if($dbh == null) {
            $dbh = new PDO(
                'pgsql:host=localhost;dbname=php_game',
                'postgres',
                '12345678',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
//          $dbh = new PDO(
//                'pgsql:host=localhost;dbname=php_game',
//                'postgres',
//                '15171517',
//                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        }
        return $dbh;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br />";
    }
}

function getUser($email){
    $db = GetConnection();
    $sql_get = 'SELECT * FROM users WHERE email = :email'; //Формирую запрос к БД
    $stmt_get = $db->prepare($sql_get);      //защита от sql-инъекций
    $stmt_get->execute([':email' => $email]); //связываем переменные
    return  $stmt_get->fetch();
}

function getUserByNickName($nick){
    $db = GetConnection();
    $sql_get = 'SELECT * FROM users WHERE nickname = :nickname'; //Формирую запрос к БД
    $stmt_get = $db->prepare($sql_get);      //защита от sql-инъекций
    $stmt_get->execute([':nickname' => $nick]); //связываем переменные
    return  $stmt_get->fetch();
}

function getAllUsers(){
    $db = GetConnection();
    $sql_get = 'SELECT * FROM users';
    $stmt_get = $db->prepare($sql_get);
    $stmt_get->execute();
    return  $stmt_get->fetchAll();
}

function getBestUsers(){
    $db = GetConnection();
    $sql_get = 'SELECT * FROM users ORDER BY population DESC LIMIT 10';
    $stmt_get = $db->prepare($sql_get);
    $stmt_get->execute();
    return  $stmt_get->fetchAll();
}


function GetUserPoints($id)
{
    $db = GetConnection();
    $query = "select points from users where (users.id = $id)";
    $res = $db->query($query);
    try {
        $users = $res->fetch();
        return $users['points'];
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function GetUserPopulation($id)
{
    $db = GetConnection();
    $query = "select population from users where (users.id = $id)";
    $res = $db->query($query);
    try {
        while ($users = $res->fetch())
            return $users['population'];
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function GetUserPopulationWelfare($id)
{
    $db = GetConnection();
    $query = "select population_welfare from users where (users.id = $id)";
    $res = $db->query($query);
    try {
        while ($users = $res->fetch())
            return $users['population_welfare'];
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
function GetUserStock($id)
{
    $db = GetConnection();
    $query = "select count, title, name, title2 from user_stock, items where(user_id = $id and item_id = items.id)";
    $stock = array();
    $res = $db->query($query);
    try {
        while ($items = $res->fetch())
        {
            array_push($stock, array($items["title"], $items["count"], $items["name"], $items['title2']));
        }
        return $stock;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function GetUserDisasters($id)
{
    $db = GetConnection();
    $query = "select count, name from user_disaster, disasters where(user_id = $id and disaster_id = disasters.id)";
    $dis = array();
    $res = $db->query($query);
    try {
        while ($items = $res->fetch())
        {
            array_push($dis, array($items["name"], $items["count"]));
        }
        return $dis;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function GetIncreasePoints($id)
{
    $db = GetConnection();
    $point = GetUserPoints($id);
    $population = GetUserPopulation($id);
    $query = "select points_per_minute from points where(population_from <= $population 
    and population_to >= $population)";
    $res = $db->query($query);
    try {
        while ($points = $res->fetch()) {
            $points = $points['points_per_minute'] + $point;
            $query = "update users set points = $points where(id = $id)";
            $db->exec($query);
            return $points;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function GetIncreasePopulation($id)
{
    $db = GetConnection();
    $welfare = GetUserPopulationWelfare($id);
    $before_population = GetUserPopulation($id);
    $query = "select growth_per_minute from population where(welfare_from <= $welfare and welfare_to>=$welfare)";
    $res = $db->query($query);
    try {
        while($population = $res->fetch()) {
            $population = $population['growth_per_minute'] + $before_population;
            $query = "update users set population = $population where(id = $id)";
            $db->exec($query);
            return $population;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

//function GetImprovedWelfare($id)
//{
//    $db = GetConnection();
//    $before_welfare = GetUserPopulationWelfare($id);
//    $points = GetUserPoints($id);
//    $query = "select improvement_price from population where (welfare_from <=
//    $before_welfare and welfare_to >= $before_welfare)";
//    $res = $db->query($query);
//    try {
//        $price = $res->fetch();
//        $price = $price["improvement_price"];
//        if ($price <= $points && $before_welfare <= 100) {
//            $welfare = $before_welfare + 1;
//            $query = "update users set population_welfare = $welfare where(id = $id)";
//            $db->exec($query);
//            $points = $points - $price;
//            $query = "update users set points = $points where(id = $id)";
//            $db->exec($query);
//            return $welfare;
//        }
//        return $before_welfare;
//    } catch (PDOException $e) {
//        echo $e->getMessage();
//    }
//}

function GetImprovedWelfare($id)
{
    $db = GetConnection();
    $before_welfare = GetUserPopulationWelfare($id);
    $points = GetUserPoints($id);
    $query = "select improvement_price from population where (welfare_from <= 
    $before_welfare and welfare_to >= $before_welfare)";
    $res = $db->query($query);
    try {
        $price = $res->fetch();
        $price = $price["improvement_price"];
        if ($price <= $points && $before_welfare <= 100) {
            $welfare = $before_welfare + 1;
            $query = "update users set population_welfare = $welfare where(id = $id)";
            $db->exec($query);
            $points = $points - $price;
            $query = "update users set points = $points where(id = $id)";
            $db->exec($query);
            return array($welfare, $points);
        }
        return array($before_welfare, $points);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function BuyItem($title, $id)
{
    $db = GetConnection();
    $points = GetUserPoints($id);
    $query = "select price, id from items where(title = '$title')";
    $res = $db->query($query);
    try {
        $item = $res->fetch();
        $price = $item["price"];
        $item_id = $item["id"];
        if ($price <= $points) {
            $countQuery = "select exists (select count from user_stock where(user_id = $id and item_id = $item_id))";
            $res = $db->query($countQuery);
            $count = $res->fetch();
            if(!$count["exists"]) {
                $query = "insert into user_stock values ($id, 1, $item_id)";
                $count = 1;
            }
            else{
                $countQuery = "select count from user_stock where(user_id = $id and item_id = $item_id)";
                $res = $db->query($countQuery);
                $count = $res->fetch();
                $count = $count["count"] +1;
                $query = "update user_stock set count = $count where(user_id= $id and item_id = $item_id)";
            }
            $db->exec($query);
            return array(GetReducePoints($price, $id, $points), $count);
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function GetReducePoints($price, $id, $points)
{
    $db = GetConnection();
    $update_points = $points - $price;
    $query = "update users set points = $update_points where(id = $id)";
    $db->exec($query);
    return $update_points;
}

//function UseItem($id, $title)
//{
//    $db = GetConnection();
//    $query = "select id from items where (title ='".$title."')";
//    $res = $db->query($query);
//    $item = $res->fetch();
//    $itemId = $item["id"];
//    $query = "select id from disasters where(item_id = $itemId)";
//    $res = $db->query($query);
//    $disaster = $res->fetch();
//    $disasterId = $disaster["id"];
//    $query = "select exists (select id from user_disaster where(disaster_id = $disasterId and user_id = $id))";
//    $res = $db->query($query);
//    $userDisaster = $res->fetch();
//    $countQuery = "select count from user_stock where(user_id = $id and item_id = $itemId)";
//    $res = $db->query($countQuery);
//    $count = $res->fetch();
////    $countQuery = "select count from user_disaster where(user_id = $id and disaster_id = $disasterId)";
////    $disasterRes = $db->query($countQuery);
////    $disasterCount = $disasterRes->fetch();
//    $countQuery = "select count,  from user_disaster, disasters where(user_id = $id and disaster_id = $disasterId and disasters.id = $disasterId)";
//    $disasterRes = $db->query($countQuery);
//    $disasterCount = $disasterRes->fetch();
//    if($userDisaster["exists"] && $disasterCount["count"] != 0 && $count["count"] != 0)
//    {
//        $count = $count["count"] - 1;
//        $query = "update user_stock set count = $count where(user_id= $id and item_id = $itemId)";
//        $db->exec($query);
//        $disasterCount = $disasterCount["count"] - 1;
//        $query = "update user_disaster set count = $disasterCount where(user_id= $id and disaster_id = $disasterId)";
//        $db->exec($query);
//        $disasterName = $disasterCount['name'];
//        return [$count, $disasterCount, $disasterName];
//    }
//    return [$count["count"], $disasterCount['count'], $disasterCount['name']];
//}
function UseItem($id, $title)
{
    $db = GetConnection();
    $query = "select id from items where (title ='".$title."')";
    $res = $db->query($query);
    $item = $res->fetch();
    $itemId = $item["id"];
    $query = "select id from disasters where(item_id = $itemId)";
    $res = $db->query($query);
    $disaster = $res->fetch();
    $disasterId = $disaster["id"];
    $query = "select exists (select id from user_disaster where(disaster_id = $disasterId and user_id = $id))";
    $res = $db->query($query);
    $userDisaster = $res->fetch();
    $countQuery = "select count from user_stock where(user_id = $id and item_id = $itemId)";
    $res = $db->query($countQuery);
    $count = $res->fetch();
//    $countQuery = "select count from user_disaster where(user_id = $id and disaster_id = $disasterId)";
//    $disasterRes = $db->query($countQuery);
//    $disasterCount = $disasterRes->fetch();
    $countQuery = "select count, name from user_disaster, disasters where(user_id = $id and disaster_id = $disasterId and disasters.id = $disasterId)";
    $disasterRes = $db->query($countQuery);
    $disasterCount = $disasterRes->fetch();
    if($userDisaster["exists"] && $disasterCount["count"] != 0 && $count["count"] != 0)
    {
        $count = $count["count"] - 1;
        $query = "update user_stock set count = $count where(user_id= $id and item_id = $itemId)";
        $db->exec($query);
        $disasterCount = $disasterCount["count"] - 1;
        $query = "update user_disaster set count = $disasterCount where(user_id= $id and disaster_id = $disasterId)";
        $db->exec($query);
        $disasterName = $disasterCount['name'];
        return [$count, $disasterCount, $disasterName];
    }
    return [$count["count"], $disasterCount['count'], $disasterCount['name']];
}
function AddDisaster($id, $title)
{
    $db = GetConnection();
    $query = "select id from disasters where(title = '$title')";
    $res = $db->query($query);
    $disaster = $res->fetch();
    $disasterId = $disaster["id"];
    $countQuery = "select exists (select count from user_disaster where(user_id = $id and disaster_id = $disasterId))";
    $res = $db->query($countQuery);
    $count = $res->fetch();
    if(!$count["exists"]) {
        $query = "insert into user_disaster values ($id,$disasterId, 1)";
    }
    else{
        $countQuery = "select count from user_disaster where(user_id = $id and disaster_id = $disasterId)";
        $res = $db->query($countQuery);
        $disaster = $res->fetch();
        $count = $disaster["count"] +1;
        $query = "update user_disaster set count = $count where(user_id= $id and disaster_id = $disasterId)";
    }
    $db->exec($query);
    return $count;
}

function GetDisasterEffect($id)
{
    $db = GetConnection();
    $population = GetUserPopulation($id);
    $query = "select sum(population_decline * count) as decline from user_disaster, disasters 
    where(user_id = $id and count != 0 and user_disaster.disaster_id = disasters.id)";
    $res = $db->query($query);
    $decline = $res->fetch();
    if (is_int($decline["decline"])) {
        if($decline["decline"] > $population*0.5)
        {
            $decline["decline"]= round($population*0.5);
        }
        $population = $population - $decline["decline"];
        if ($population < 0) {
            $population = 0;
        }
        $query = "update users set population = $population where (id = $id)";
        $db->exec($query);
    }
    return $population;
}

function GetItem($title, $id)
{
    $db = GetConnection();
    $points = GetUserPoints($id);
    $query = "select id from items where(title = '$title')";
    $res = $db->query($query);
    try {
        $item = $res->fetch();
        $item_id = $item["id"];
        $countQuery = "select exists (select count from user_stock where(user_id = $id and item_id = $item_id))";
        $res = $db->query($countQuery);
        $count = $res->fetch();
        if (!$count["exists"]) {
            $query = "insert into user_stock values ($id, 1, $item_id)";
            $count = 1;
        } else {
            $countQuery = "select count from user_stock where(user_id = $id and item_id = $item_id)";
            $res = $db->query($countQuery);
            $count = $res->fetch();
            $count = $count["count"] + 1;
            $query = "update user_stock set count = $count where(user_id= $id and item_id = $item_id)";
        }
        $db->exec($query);
        return $count;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function Restart($id)
{
    $db = GetConnection();
    $query = "update users set points = 0, population = 0, population_welfare = 0 where(user_id = $id)";
    $db->exec($query);
    $query = "update user_stock set count = 0 where(user_id = $id)";
    $db->exec($query);
    $query = "update user_disaster set count = 0 where(user_id = $id)";
    $db->exec($query);
}

function userStock($email){
    $db = GetConnection();
    $user = getUser($email);
    $arr = [3, 5, 7, 9, 19];
    foreach ($arr as $value){
        $sql = 'INSERT INTO user_stock (user_id, count, item_id) 
                    VALUES (:user_id, :count, :item_id)';
        $values = [
            'user_id' => $user['id'],
            'count' => '0',
            'item_id' => $value
        ];
        $db = GetConnection();
        $statement = $db->prepare($sql);
        $statement->execute($values);
    }
    $arr2 = [26, 28, 30, 32, 42];
    foreach ($arr2 as $value){
        $sql = 'INSERT INTO user_disaster (user_id, count, disaster_id) 
                    VALUES (:user_id, :count, :disaster_id)';
        $values = [
            'user_id' => $user['id'],
            'count' => '0',
            'disaster_id' => $value
        ];
        $db = GetConnection();
        $statement = $db->prepare($sql);
        $statement->execute($values);
    }
}
