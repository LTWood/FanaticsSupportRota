$(init);

var modified_rota = {};

function init() {
    $('.draggableDevelopers').draggable({
        containment: 'document',
        cursor: 'move',
        revert: true,
        stack: '.col-xl-6',
    });

    $('.droppableRotaSlot').droppable({
        accept: '.draggableDevelopers',
        hoverClass: 'hovered',
        drop: handleDeveloperRotaDrop
    });

    $("#generateFrom").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        showWeek: true,
        firstDay: 1,
        editable: true
    });

    $("#showFrom").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        showWeek: true,
        firstDay: 1,
        editable: true
    });

    $("#showTo").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        showWeek: true,
        firstDay: 1
    });
}

function handleDeveloperRotaDrop(event, ui) {
    if ($(this)[0].innerText === "** Developer Required **") {
        if (ui.draggable.data("date") == $(this).closest(".card").find("#startDate")[0].innerText) {
            // Don't show dev in teams list
            ui.draggable.css("display", "none");
            // Sets rota slot to display devs name
            $(this)[0].children[0].children[0].innerText = ui.draggable.find("#devCardName")[0].innerText;

            // Shows trash button
            $(this)[0].children[0].children[1].style.display = "inline";
            // Change background colour to show it has been manually modified
            $(this)[0].style.backgroundColor = "CornflowerBlue";
        }
        else {
            alert("Wrong date!");
        }
    }
    else {
        alert("Slot already filled!");
    }
}

function removeDeveloperFromSupport(index) {
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
    $("#supportTeamColumn").children("#supportTeams").children("#supportTeam").each(function (index) {
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
        success: function () {
            console.log("Success");
            location.reload();
        }
    });
}

function loaddevs(dates) {
    $("#developerTeamColumn").load("getDevelopmentTeamsUnavailable.php", {date: dates}, function () {
        init();
    });
}

function updateSupportTeamList() {
    var startDate = $("#showFrom")[0].value;
    var endDate = $("#showTo")[0].value;
    $("#supportTeams").load("getSupportTeamsInDateRange.php", {start: startDate, end: endDate}, function() {
        init();
        console.log("Success");
    })
}