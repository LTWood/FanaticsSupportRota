$( init );

var modified_rota = {};

function init() {
    $('.draggableDevelopers').draggable( {
        containment: 'document',
        cursor: 'move',
        revert: true,
        stack: '.card',
    } );

    $('.droppableRotaSlot').droppable( {
        accept: '.draggableDevelopers',
        hoverClass: 'hovered',
        drop: handleDeveloperRotaDrop
    } );
}

function handleDeveloperRotaDrop(event, ui) {
    // if ($(this)[0].innerText === "** Dev Required **"){
    //     // ui.draggable.position( { of: $(this), my: 'left top', at: 'left top' } );
    //     // ui.draggable.draggable('option', 'revert', false);
    //     // ui.draggable.draggable("disable");
    //     // ui.draggable.find(".trashButton")[0].style.display = "inline";
    //     var name = ui.draggable.find("#devCardName")[0].innerText;
    //     var originalName = $(t
    //     // modified_rota[name] = $(this).closest("#supportTeam").find("#startDate")[0].innerText;
    //     console.log(modified_rota);
    // }
//     var original = $(this)[0].innerText;
//     var newName = ui.draggable.find("#devCardName")[0].innerText;
//     console.log(original);
//     console.log(newName);

//     if ($(this)[0].innerText === "** Dev Required **") {
//         $(this)[0].innerText = ui.draggable.find("#devCardName")[0].innerText;
//         £(this)[0].innerHTML = ""
//     }
//     if ($(this)[0].innerText === "** Dev Required **") {
//         $(this)[0].children[0].innerText = ui.draggable.find("#devCardName")[0].innerText;
//
//         $(this)[0].style.backgroundColor = "CornflowerBlue";
//
//     }
    if ($(this)[0].innerText === "** Developer Required **") {
        console.log($(this)[0].children[0].children[0].innerText);
        console.log(ui.draggable.find("#devCardName")[0].innerText);
        ui.draggable.css("display", "none");
        $(this)[0].children[0].children[0].innerText = ui.draggable.find("#devCardName")[0].innerText;
        $(this)[0].children[0].children[1].style.display = "inline";
        $(this)[0].style.backgroundColor = "CornflowerBlue";
    }
}

function removeDeveloperFromSupport(index)
{
    document.getElementById("devCard" + index).style.left = 0;
    document.getElementById("devCard" + index).style.top = 0;
    document.getElementById("trash" + index).style.display = "none";
    $("#devCard" + index).draggable("option", "revert", true);
    $("#devCard" + index).draggable("enable");
}

function removeOriginalDevFromSupport(index)
{
    $("#rotaSlot" + index)[0].style.backgroundColor = "red";
    $("#rotaSlot" + index)[0].children[0].children[0].innerText = "** Developer Required **";
    $("#rotaSlot" + index)[0].children[0].children[1].style.display = "none";
}