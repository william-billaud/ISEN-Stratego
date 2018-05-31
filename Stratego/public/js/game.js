var url;
var game_id;

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
                bg.addClass("herbe");
                bg.attr("ondrop", "drop(event)");
                bg.attr("ondragover", "allowDrop(event)");
                bg.attr("id_herbes", this.x + "-" + this.y);
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
}
$(document).ready(function () {
    url = window.location.pathname;
    game_id = url.split("/")[2];
    console.log(game_id);

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
            afficheTableau(data);

            $("#info").empty().append($("<p>Dernière attaque :" + (data.derniereAttaque) + "</p>"))
                .append($("<p>" + ((data.peut_jouer) ? "C'est votre tour" : "Tour adverse") + "</p>"));
        },
        type: 'GET'
    });
});

function allowDrop(ev) {
    ev.preventDefault();
}
var dragged;
function drag(ev) {
    ev.dataTransfer.setData("pion", ev.target.id);
    console.log(ev);
    dragged = ev.target;
}

function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("pion");
    ev.target.appendChild(document.getElementById(data));
    var start=dragged.getAttribute("id").split('-',2);
    var targetAttr=ev.target.getAttribute("id_herbes");
    if(targetAttr==null)
    {
        targetAttr= ev.target.getAttribute("id");
    }
        var arrive=targetAttr.split('-',2);
    $.ajax({
        url: '/api/joue/' + game_id + "?x_o=" + start[0] + "&y_o=" + start[1] + "&x_a=" + arrive[0] + "&y_a=" + arrive[1],
        data: {
            format: 'json'

        },
        error: function () {
            console.log("error");
        },
        dataType: 'json',
        success: function (data) {
            console.log(data.tab);
            $('#board').empty();
            afficheTableau(data);
            $("#info").empty().append($("<p>Dernière attaque :" + (data.derniereAttaque) + "</p>"))
                .append($("<p>" + ((data.peut_jouer) ? "C'est votre tour" : "Tour adverse") + "</p>"))
                .append($("<p>" + (data.error==null?"Coup Valide":data.error)+"</p>"));

        },
        type: 'GET'
    });

}