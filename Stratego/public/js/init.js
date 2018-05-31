$(document).ready(function () {

    var url = window.location.pathname;
    var game_id = url.split("/")[3];
    console.log(game_id);

    var perso = $("#personnages");
    for (var i = 0; i < 8; i++) {
        perso.append("<div class=\"soldat\" draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }

    for (i = 0; i < 5; i++) {
        perso.append("<div class=\"demineur\" draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }

    for (i = 0; i < 4; i++) {
        perso.append("<div class=\"sergent\" draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }

    for (i = 0; i < 4; i++) {
        perso.append("<div class=\"lieutenant\" draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }


    for (i = 0; i < 4; i++) {
        perso.append("<div class=\"capitaine\" draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }

    for (i = 0; i < 3; i++) {
        perso.append("<div class=\"commandant\" draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }

    for (i = 0; i < 2; i++) {
        perso.append("<div class=\"colonel\" draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }

    //General
    perso.append("<div class=\"general\" draggable=\"true\" ondragstart=\"drag(event)\"></div>");

    //Drapeau
    perso.append("<div class=\"drapeau\" draggable=\"true\" ondragstart=\"drag(event)\"></div>");

    //Espion
    perso.append("<div class=\"espion\" draggable=\"true\" ondragstart=\"drag(event)\"></div>");

    //Marechal
    perso.append("<div class=\"marechal\" draggable=\"true\" ondragstart=\"drag(event)\"></div>");

    for (i = 0; i < 6; i++) {
        perso.append("<div class=\"bombe\" draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }


    console.log("Ajax");

    $.ajax({
        url: '/api/getTab/'+game_id,
        data: {
            format: 'json'
        },
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