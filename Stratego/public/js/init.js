let url;
let game_id;


$(document).ready(function () {

    let url = window.location.pathname;
    game_id = url.split("/")[3];
    console.log(game_id);
    $.ajax({
        url: '/api/init/' + game_id,
        data: {
            format: 'json'
        },
        error: function () {
            console.log("error");
        },
        dataType: 'json',
        success: function (data) {
            $('#board').empty();
            afficheTableau(data);
            personnagesRestants(data);
        },
        type: 'GET'
    });
});

function allowDrop(ev) {
    ev.preventDefault();
}

let dragged;

function drag(ev) {
    ev.dataTransfer.setData("pion", ev.target.id);
    console.log(ev);
    dragged = ev.target;
}

function drop(ev) {
    ev.preventDefault();
    let data = ev.dataTransfer.getData("pion");
    ev.target.appendChild(document.getElementById(data));
    console.log(ev.target);
    let valPion = dragged.getAttribute("data-value");
    var targetAttr=ev.target.getAttribute("id_herbes");
    if(targetAttr==null)
    {
        targetAttr=ev.target.getAttribute("id");
    }
    let coor = targetAttr.split('-', 2);
    $.ajax({
        url: '/api/init/' + game_id +"?x="+coor[0]+"&y="+coor[1]+"&value="+valPion,
        data: {
            format: 'json'

        },
        error: function () {
            console.log("error");
        },
        dataType: 'json',
        success: function (data) {
            console.log(data.tab);
            console.log("here"+data);
            $('#board').empty();
            afficheTableau(data);
            personnagesRestants(data);

        },
        type: 'GET'
    });
}

function personnagesRestants(data) {
    console.log(data.restante);
    var perso= $("#personnages").empty();

    var restante = data.restante;

    var soldats = jQuery('<div/>', {
        id: 'soldats',
        class: 'row'
    });
    for (var i = 0; i < restante[2]; i++) {
        soldats.append("<div data-value=\"2\" class=\"soldat sl1\" id='soldat" + i + "' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }
    perso.append(soldats);

    var demineurs = jQuery('<div/>', {
        id: 'demineurs',
        class: 'row'
    });
    for (i = 0; i < restante[3]; i++) {
        demineurs.append("<div data-value=\"3\" class=\"demineur sl1\" id='demineur" + i + "' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }
    perso.append(demineurs);

    var sergents = jQuery('<div/>', {
        id: 'sergents',
        class: 'row'
    });
    for (i = 0; i < restante[4]; i++) {
        sergents.append("<div data-value=\"4\" class=\"sergent sl1\" id='sergent" + i + "' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }
    perso.append(sergents);

    var lieutenants = jQuery('<div/>', {
        id: 'lieutenants',
        class: 'row'
    });
    for (i = 0; i < restante[5]; i++) {
        lieutenants.append("<div data-value=\"5\" class=\"lieutenant sl1\" id='lieutenant" + i + "' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }
    perso.append(lieutenants);

    var capitaines = jQuery('<div/>', {
        id: 'capitaines',
        class: 'row'
    });
    for (i = 0; i < restante[6]; i++) {
        capitaines.append("<div data-value=\"6\" class=\"capitaine sl1\" id='capitaine" + i + "' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }
    perso.append(capitaines);

    var commandants = jQuery('<div/>', {
        id: 'commandants',
        class: 'row'
    });
    for (i = 0; i < restante[7]; i++) {
        commandants.append("<div data-value=\"7\" class=\"commandant sl1\" id='commandant" + i + "' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }
    perso.append(commandants);

    var colonels = jQuery('<div/>', {
        id: 'colonels',
        class: 'row'
    });
    for (i = 0; i < restante[8]; i++) {
        colonels.append("<div data-value=\"8\" class=\"colonel sl1\" id='colonel" + i + "' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }
    perso.append(colonels);

    //General
    var seuls = jQuery('<div/>', {
        class: 'row'
    });
    if (restante[9] !== 0) {
        seuls.append("<div data-value=\"9\" class=\"general sl1\" id='general' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }

    if (restante[0] !== 0) {
        //Drapeau
        seuls.append("<div data-value=\"0\" class=\"drapeau sl1\" id='drapeau' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }

    if (restante[1] !== 0) {
        //Espion
        seuls.append("<div data-value=\"1\" class=\"espion sl1\" id='espion' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }

    if (restante[10] !== 0) {
        //Marechal
        seuls.append("<div data-value=\"10\" class=\"marechal sl1\" id='marechal' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }
    perso.append(seuls);

    var bombes = jQuery('<div/>', {
        id: 'bombes',
        class: 'row'
    });
    for (i = 0; i < restante[11]; i++) {
        console.log("here");
        bombes.append("<div data-value=\"11\" class=\"bombe sl1\" id='bombe" + i +"' draggable=\"true\" ondragstart=\"drag(event)\"></div>");
    }
    perso.append(bombes);

}


function afficheTableau(data)
{
    jQuery.each(data.tab, function () {
        var row = $("<div class='ligne'></div>");
        jQuery.each(this, function () {
            var bg = $("<div class=\"ss1\"></div>");
            if (this.value === -3) {
                bg.addClass("eau");
            }
            else {
                if(this.y>=0 && this.y<4 && data.side===1)
                {
                    bg.addClass("herbe-border");
                    bg.attr("ondrop", "drop(event)");
                    bg.attr("ondragover", "allowDrop(event)");
                }else if(this.y>=6 && this.y<10 && data.side===-1){
                    bg.addClass("herbe-border");
                    bg.attr("ondrop", "drop(event)");
                    bg.attr("ondragover", "allowDrop(event)");
                }else
                {
                    bg.addClass("herbe");
                }

                bg.attr("id_herbes", this.x + "-" + this.y);
            }
            var div = $("<div class='personnage'></div>");
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
}