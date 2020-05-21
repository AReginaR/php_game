ajaxPointsIncrease();
ajaxPopulationIncrease();


function ajaxPointsIncrease() {
    $('#pointsButton').css("display","none");
    $.ajax({
        type: "POST",
        url: "ajax/ajaxIncreasePoints.php",
        data: {id:1}
    }).done(function (result) {
        $("#points").text(result);
    })
}

function ajaxPopulationIncrease() {
    $.ajax({
        type: "POST",
        url: "ajax/ajaxIncreasePopulation.php",
        data: {id:1}
    }).done(function (result) {
        $("#population").text(result);
    })
}

function ajaxImproveWelfare() {
    $.ajax({
        type: "POST",
        url: "ajax/ajaxImproveWelfare.php",
        data: {id:1}
    }).done(function (result) {
        $("#welfare").text(result);
    })
}

function buyItem(title) {
    $.ajax({
        type: "POST",
        url: "ajax/ajaxBuyItem.php",
        data: {title: title, id: 1}
    }).done(function (result) {
        var data = jQuery.parseJSON(result);
        if (data != null) {
            $("#points").text(data[0]);
            if (data[1] != null) {
                if (data[1] === 1) {
                    $("#stock").append("<div><img src=../../images/lab42.png> <text id='" + title + "'>Количество " + data[1] + "</text></div>");
                } else if (data[1] != null) {
                    $('[id="' + title + '"]').text("Количество " + data[1]);
                }
            }
        }
    })
}

setInterval(function (){
    $('#pointsButton').css("display","");
} , 2000);
setInterval( ajaxPopulationIncrease, 5000);