
function CopiLink(idlist, sourceID){

    navigator.clipboard.writeText(document.location.origin+idlist);

    $('.greenButton').each(function (i, element) {
        element.classList.remove("greenButton");
        element.classList.add("normalButton");
    })

    document.getElementById(sourceID).classList.remove("normalButton");
    document.getElementById(sourceID).classList.add("greenButton");
}