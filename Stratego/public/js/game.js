$(document).ready(function () {
    console.log("Ajax");

    $.ajax({
        url: 'http://localhost:8000/api/getTab/1',
        data: {
            format: 'json'
        },
        crossDomain: true,
        error: function () {
            console.log("error");
        },
        dataType: 'json',
        success: function (data) {
            jQuery.each(data.tab, function () {
                var row = $("<div class='ligne'></div>");
                jQuery.each(this, function () {
                    var bg = $("<div class=\"ss1\"></div>");
                    if (this.value === -3) {
                        bg.addClass("eau");
                    }
                    else {
                        bg.addClass("herbe");
                        bg.attr("ondrop", "drop(event)");
                        bg.attr("ondragover", "allowDrop(event)");
                    }
                    var div = $("<div class='personnage'></div>");
                    div.attr("draggable", true).attr("ondragstart", "drag(event)");
                    div.attr("id", this.x + "-" + this.y);
                    switch (this.value) {
                        case -1:
                            div.addClass("ennemi");
                            break;
                        case 0:
                            div.addClass("drapeau");
                            break;
                        case 1:
                            div.addClass("espion");
                            break;
                        case 2:
                            div.addClass("soldat");
                            break;
                        case 3:
                            div.addClass("demineur");
                            break;
                        case 4:
                            div.addClass("sergent");
                            break;
                        case 5:
                            div.addClass("lieutenant");
                            break;
                        case 6:
                            div.addClass("capitaine");
                            break;
                        case 7:
                            div.addClass("commandant");
                            break;
                        case 8:
                            div.addClass("colonel");
                            break;
                        case 9:
                            div.addClass("general");
                            break;
                        case 10:
                            div.addClass("marechal");
                            break;
                        case 11:
                            div.addClass("bombe");
                            break;
                    }
                    bg.append(div);
                    row.append(bg);
                });
                $('#board').append(row);
            });
        },
        type: 'GET'
    });
});

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("pion", ev.target.id);
    console.log(ev);
}

function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("pion");
    ev.target.appendChild(document.getElementById(data));
    console.log(ev.target);
}