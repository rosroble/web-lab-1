let x, y, r;
let errorMessage = "";
const maxLength = 15;

function isNumber(input) {
    return !isNaN(parseFloat(input)) && isFinite(input);
}

function addToErrorMessage(errorDesc) {
    errorMessage += (errorDesc + "\n");
}

function hasProperLength(input) {
    return input.length <= maxLength;
}

function validateX() {
    x = document.querySelector("input[id=xCoordinate]").value.replace(",", ".");
    if (x === undefined) {
        addToErrorMessage("Поле X не заполнено");
        return false;
    }
    if (!isNumber(x)) {
        addToErrorMessage("X должен быть числом от -3 до 5!");
        return false;
    }
    if (!hasProperLength(x)) {
        addToErrorMessage(`Длина числа должна быть не более ${maxLength} символов`);
        return false;
    }
    if (!((x > -3) && (x < 5))) {
        addToErrorMessage("Нарушена область допустимых значений X (-3; 5)");
        return false;
    }
    return true;
}

function validateY() {
    const selector = document.getElementById("YSelect");
    const selectedValue = selector.value;
    if (selectedValue === "") {
        addToErrorMessage("Нужно выбрать Y");
        return false;
    }
    y = selectedValue;
    return true;
}

function validateR() {
    let RButtons = document.querySelectorAll("input[name=r]");

    RButtons.forEach(function (button) {
        console.log(button.value);
        if (button.checked) {
            r = button.value;
            console.log("success");
        }
    });

    if (r === undefined) {
        addToErrorMessage("Выберите R.");
        console.log("check r");
        return false;
    }
    return true;
}

function submit() {
    if (validateX() & validateY() & validateR()) {
        $.get("../php/main.php", { // assemble GET-RQ via jQuery
            'x': x,
            'y': y,
            'r' : r,
            'timezone': new Date().getTimezoneOffset()
        }).done(function(PHP_RESPONSE) { // do when success callback is received
            let result = JSON.parse(PHP_RESPONSE); // take array with results
                if (!result.isValid) {
                    addToErrorMessage("Request is not valid. Try refreshing the page");
                    return;
                }
                let newRow = result.isBlueAreaHit ? '<tr class="hit-yes">' : '<tr class="hit-no">';
                newRow += '<td>' + result.x + '</td>';
                newRow += '<td>' + result.y + '</td>';
                newRow += '<td>' + result.r + '</td>';
                newRow += '<td>' + result.userTime + '</td>';
                newRow += '<td>' + result.execTime + '</td>';
                newRow += '<td>' + (result.isBlueAreaHit ? '<img src="../img/tick.png" alt="Да" class="yes-no-marker">' : '<img src="../img/cross.png" alt="Нет" class="yes-no-marker">') + '</td>';
                $('#result-table tr:first').after(newRow);
                document.getElementById("result-table").style.backgroundColor = `rgba(250, 235, 215, ${Math.random() * 0.6 + 0.2})`;
        }).fail(function (error) {
            addToErrorMessage(error);
        });
    }

    if (!(errorMessage === "")) {
        alert(errorMessage);
        errorMessage = "";
    }
}