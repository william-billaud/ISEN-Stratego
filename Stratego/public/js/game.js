$('#button').click(function (event) {
    event.preventDefault();
    console.log("Ajax");

    $.ajax({
        url: 'http://localhost:8000/api/getTab/1',
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
                    if (this.value === -3)
                    {
                        bg.addClass("eau");
                    }
                    else {
                        bg.addClass("herbe");
                    }
                    var div = $("<div class='personnage'></div>");
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