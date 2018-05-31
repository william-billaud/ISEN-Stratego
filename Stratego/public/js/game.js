var url;
var game_id;

function afficheTableau(data)
{
    $('#board').empty();
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
                    div.attr("title", "ennemi");
                    break;
                case 0:
                    div.addClass("drapeau");
                    div.attr("title", "drapeau");
                    break;
                case 1:
                    div.addClass("espion");
                    div.attr("title", "espion");
                    break;
                case 2:
                    div.addClass("soldat");
                    div.attr("title", "soldat");
                    break;
                case 3:
                    div.addClass("demineur");
                    div.attr("title", "demineur");
                    break;
                case 4:
                    div.addClass("sergent");
                    div.attr("title", "sergent");
                    break;
                case 5:
                    div.addClass("lieutenant");
                    div.attr("title", "lieutenant");
                    break;
                case 6:
                    div.addClass("capitaine");
                    div.attr("title", "capitaine");
                    break;
                case 7:
                    div.addClass("commandant");
                    div.attr("title", "commandant");
                    break;
                case 8:
                    div.addClass("colonel");
                    div.attr("title", "colonel");
                    break;
                case 9:
                    div.addClass("general");
                    div.attr("title", "general");
                    break;
                case 10:
                    div.addClass("marechal");
                    div.attr("title", "marechal");
                    break;
                case 11:
                    div.addClass("bombe");
                    div.attr("title", "bombe");
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

    var ajaxGame= function (){
        $.ajax({
            url: '/api/joue/'+game_id,
            data: {
                format: 'json'
            },
            error: function () {
                console.log("error");
            },
            dataType: 'json',
            success: function (data) {
                afficheTableau(data);
                $("#dernier-coup").empty().text("Dernière attaque :" + (data.derniereAttaque)+ "   " + ((data.peut_jouer) ? "C'est votre tour" : "Tour adverse"));
            },
            type: 'GET'
        });
    }
    ajaxGame();
    setInterval(ajaxGame,5000);
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
            afficheTableau(data);
            var _receiver = document.getElementById('ws-content-receiver');
            _receiver.className = "showUp";
            _receiver.innerHTML=data.error==null?"Coup Valide":data.error;
            setTimeout(function () {
                _receiver.className = _receiver.className.replace("showUp", "");
            }, 8000);
            $("#dernier-coup").empty().text("Dernière attaque :" + (data.derniereAttaque)+ "   " + ((data.peut_jouer) ? "C'est votre tour" : "Tour adverse"));



        },
        type: 'GET'
    });

}