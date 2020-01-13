$( init );

var modified_rota = {};

function init() {
    $('.draggableDevelopers').draggable( {
        containment: 'document',
        cursor: 'move',
        revert: true,
        stack: '.card'
    } );

    $('.droppableRotaSlot').droppable( {
        accept: '.draggableDevelopers',
        hoverClass: 'hovered',
        drop: handleDeveloperRotaDrop
    } );
}

function handleDeveloperRotaDrop(event, ui) {
    if ($(this)[0].innerText === "** Dev Required **"){
        ui.draggable.position( { of: $(this), my: 'left top', at: 'left top' } );
        ui.draggable.draggable('option', 'revert', false);
        ui.draggable.draggable("disable");
        ui.draggable.find(".trashButton")[0].style.display = "inline";
        var name = ui.draggable.find("#devCardName")[0].innerText;
        modified_rota[name] = $(this).closest("#supportTeam").find("#startDate")[0].innerText;
        console.log(modified_rota);
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
    document.getElementById("originalDev" + index).innerHTML = "** Dev Required **";
    document.getElementById("rotaSlot" + index).style.backgroundColor = "red";

}