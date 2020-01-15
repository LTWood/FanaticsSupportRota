$( init );

var modified_rota = {};

function init() {
    $('.draggableDevelopers').draggable( {
        containment: 'document',
        cursor: 'move',
        revert: true,
        stack: '.col-xl-6',
    } );

    $('.droppableRotaSlot').droppable( {
        accept: '.draggableDevelopers',
        hoverClass: 'hovered',
        drop: handleDeveloperRotaDrop
    } );

    $(function() {
        var dateFormat = "dd-mm-yyyy",
            from = $("#from")
                .datepicker({
                    showButtonPanel: true,
                    changeMonth: true,
                    changeYear: true
                })
                .on( "change", function() {
                    to.datepicker( "option", "minDate", getDate( this ) );
                }),
            to = $( "#to" ).datepicker({
                showButtonPanel: true,
                changeMonth: true,
                changeYear: true
            })
                .on( "change", function() {
                    from.datepicker( "option", "maxDate", getDate( this ) );
                });

        function getDate( element ) {
            var date;
            try {
                date = $.datepicker.parseDate( dateFormat, element.value );
            } catch( error ) {
                date = null;
            }

            return date;
        }
    } );
}

function handleDeveloperRotaDrop(event, ui) {

    // Don't allow devs to be dropped on top of another dev
    if ($(this)[0].innerText === "** Developer Required **") {
        // Don't show dev in teams list
        ui.draggable.css("display", "none");
        // Sets rota slot to display devs name
        $(this)[0].children[0].children[0].innerText = ui.draggable.find("#devCardName")[0].innerText;
        // Shows trash button
        $(this)[0].children[0].children[1].style.display = "inline";
        // Change background colour to show it has been manually modified
        $(this)[0].style.backgroundColor = "CornflowerBlue";
    }
}

function removeDeveloperFromSupport(index)
{
    // Get name from current developer in slot
    var name = $("#rotaSlot" + index)[0].children[0].children[0].innerText.trim();
    // var name = $("#rotaSlot" + index)[0].children[0];
    // Find the dev card with the username and display it again
    if ($(".draggableDevelopers:contains(" + name + ")").length) {
        $(".draggableDevelopers:contains(" + name + ")")[0].style.display = "block";
    }
    // // Set the rota slot to red, don't show trash button, say developer required
    $("#rotaSlot" + index)[0].style.backgroundColor = "red";
    $("#rotaSlot" + index)[0].children[0].children[0].innerText = "** Developer Required **";
    $("#rotaSlot" + index)[0].children[0].children[1].style.display = "none";
    // console.log($("#rotaSlot" + index)[0]);
}

function updateRota() {
    var modified_rota = {};
    $("#supportTeamColumn").children("#supportTeams").children("#supportTeam").each(function(index) {
        var date_start = $(this).find("#startDate")[0].innerText;
        var dev1 = $(this).find(".card-body")[0].children[0].innerText;
        var dev2 = $(this).find(".card-body")[0].children[1].innerText;
        var dev_array = [];
        dev_array.push(date_start);
        dev_array.push(dev1);
        dev_array.push(dev2);
        modified_rota[index] = dev_array;
    });
    $.ajax({
        url: "updateRota.php",
        type: "post",
        data: {rota: modified_rota},
        success: function(){
            console.log("Success");
            location.reload();
        }
    });
}

function loaddevs(dates) {
    $("#developerTeamColumn").load("getDevelopmentTeamsUnavailable.php", { date: dates }, function() {
        init();
    });
}