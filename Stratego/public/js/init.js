$(document).ready(function () {

    var url = window.location.pathname;
    var game_id = url.split("/")[3];
    console.log(game_id);

    $.ajax({
        url: '/api/init/' + game_id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            var perso = $("#personnages");

            var soldats = jQuery('<div/>', {
                id: 'soldats',
                class: 'row'
            });
            for (var i = 0; i < 8; i++) {
                soldats.append("<div class=\"soldat sl1\" id='soldat" + i + "' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
            }
            perso.append(soldats);

            var demineurs = jQuery('<div/>', {
                id: 'demineurs',
                class: 'row'
            });
            for (i = 0; i < 5; i++) {
                demineurs.append("<div class=\"demineur sl1\" id='demineur" + i + "' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
            }
            perso.append(demineurs);

            var sergents = jQuery('<div/>', {
                id: 'sergents',
                class: 'row'
            });
            for (i = 0; i < 4; i++) {
                sergents.append("<div class=\"sergent sl1\" id='sergent" + i + "' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
            }
            perso.append(sergents);

            var lieutenants = jQuery('<div/>', {
                id: 'lieutenants',
                class: 'row'
            });
            for (i = 0; i < 4; i++) {
                lieutenants.append("<div class=\"lieutenant sl1\" id='lieutenant" + i + "' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
            }
            perso.append(lieutenants);

            var capitaines = jQuery('<div/>', {
                id: 'capitaines',
                class: 'row'
            });
            for (i = 0; i < 4; i++) {
                capitaines.append("<div class=\"capitaine sl1\" id='capitaine" + i + "' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
            }
            perso.append(capitaines);

            var commandants = jQuery('<div/>', {
                id: 'commandants',
                class: 'row'
            });
            for (i = 0; i < 3; i++) {
                commandants.append("<div class=\"commandant sl1\" id='commandant" + i + "' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
            }
            perso.append(commandants);

            var colonels = jQuery('<div/>', {
                id: 'colonels',
                class: 'row'
            });
            for (i = 0; i < 2; i++) {
                colonels.append("<div class=\"colonel sl1\" id='colonel" + i + "' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
            }
            perso.append(colonels);

            //General
            var seuls = jQuery('<div/>', {
                class: 'row'
            });
            seuls.append("<div class=\"general sl1\" id='general' draggable=\"true\" ondragstart=\"drag(event)\"></div>");

            //Drapeau
            seuls.append("<div class=\"drapeau sl1\" id='drapeau' draggable=\"true\" ondragstart=\"drag(event)\"></div>");

            //Espion
            seuls.append("<div class=\"espion sl1\" id='espion' draggable=\"true\" ondragstart=\"drag(event)\"></div>");

            //Marechal
            seuls.append("<div class=\"marechal sl1\" id='marechal' draggable=\"true\" ondragstart=\"drag(event)\"></div>");

            perso.append(seuls);

            var bombes = jQuery('<div/>', {
                id: 'bombes',
                class: 'row'
            });
            for (i = 0; i < 6; i++) {
                bombes.append("<div class=\"bombe sl1\" id='soldat\" + i + \"draggable=\"true\" ondragstart=\"drag(event)\"></div>");
            }
            perso.append(bombes);

        },

        error: function () {
            console.log("error");
        }
    });

    console.log("Ajax");

    $.ajax({
        url: '/api/getTab/' + game_id,
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