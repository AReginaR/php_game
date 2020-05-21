function createMessage() {

    var newIcon = document.createElement('img');
    const number = getRandomInt(1, 100);
    if (number === 99) {
        newIcon.src = 'resources/potion/vs wildfire.png';
        newIcon.className = 'vs wildfire';
        newIcon.onclick = function () {
            newIcon.parentNode.removeChild(newIcon);
            ajaxGetItem(newIcon.className);
        }
    } else if (number === 98) {
        newIcon.src = 'resources/potion/vs hurricane.png';
        newIcon.className = 'vs hurricane';
        newIcon.onclick = function () {
            newIcon.parentNode.removeChild(newIcon);
            ajaxGetItem(newIcon.className);
        }
    } else if (number === 97) {
        newIcon.src = 'resources/potion/vs war.png';
        newIcon.className = 'vs war';
        newIcon.onclick = function () {
            newIcon.parentNode.removeChild(newIcon);
            ajaxGetItem(newIcon.className);
        }
    } else if (number === 96) {
        newIcon.src = 'resources/potion/vs flood.png';
        newIcon.className = 'vs flood';
        newIcon.onclick = function () {
            newIcon.parentNode.removeChild(newIcon);
            ajaxGetItem(newIcon.className);
        }
    } else if (number === 95) {
        newIcon.src = 'resources/potion/vs eruption.png';
        newIcon.className = 'vs eruption';
        newIcon.onclick = function () {
            newIcon.parentNode.removeChild(newIcon);
            ajaxGetItem(newIcon.className);
        }
    } else if (number === 92) {
        newIcon.src = 'resources/disaster/Voyna.png';
        newIcon.className = 'war';
    } else if (number === 93 || number === 43) {
        newIcon.src = 'resources/disaster/Izverzhenievulkana.png';
        newIcon.className = 'eruption';
    } else if (number === 94 || number === 42) {
        newIcon.src = 'resources/disaster/Navodnenie.png';
        newIcon.className = 'flood';
    } else if (number === 91 || number === 41 || number === 20) {
        newIcon.src = 'resources/disaster/Tornado.png';
        newIcon.className = 'hurricane';
    } else if (number === 90 || number === 40) {
        newIcon.src = 'resources/disaster/Pozhar.png';
        newIcon.className = 'wildfire';
    } else {
        newIcon.src = 'resources/images/lab42.png';
        newIcon.className = 'points';
        newIcon.onclick = function () {
            newIcon.parentNode.removeChild(newIcon);
            ajaxPointsIncrease();
        }
    }
    newIcon.alt = 'lab42';
    newIcon.style.position = 'absolute';
    newIcon.style.maxWidth = '50px';

    return newIcon
}

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min)) + min;
}

function positionMessage(elem) {
    var map = document.getElementsByClassName("mapImg");
    const mapInfo = map[0].getBoundingClientRect();
    elem.style.position = 'absolute';
    elem.style.top = getRandomInt(mapInfo.y, mapInfo.y + mapInfo.height - 50) + 'px';
    elem.style.left = getRandomInt(mapInfo.x + 30, mapInfo.x + mapInfo.width - 30) + 'px'
}

function setupMessageButton() {
    var iconToShow = createMessage();
    positionMessage(iconToShow);
    document.body.appendChild(iconToShow);
    if (iconToShow.className !== "war"
        && iconToShow.className !== "wildfire"
        && iconToShow.className !== "hurricane"
        && iconToShow.className !== "flood"
        && iconToShow.className !== "eruption") {
        setTimeout(() => iconToShow.remove(), 5000); // время в миллисекундах, через сколько пропадет
    } else {
        addDisaster(iconToShow.className);
    }
}

setInterval(setupMessageButton, 10000); // время в миллисекундах, через сколько новая появляется
