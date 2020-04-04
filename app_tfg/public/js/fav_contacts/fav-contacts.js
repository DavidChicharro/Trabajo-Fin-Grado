function contractIncident(){
    $(".expanded").hide(300, "linear"); //Oculta todas las rows
    $(".view-less").hide(300);
    $(".view-more").show(300);   //Muestro botón "ver más" del resto
    $(".expanded").closest("article").css("background-color", "white");
}

$(document).on("click",".view-less", function () {
    contractIncident();
});

$(".view-more").click(function() {
    contractIncident();
    $(this).hide(); //escondo boton "Ver más"
    $(this).next().show(300);

    $(this).closest("article").css("background-color", "var(--k-blue-op10)");

    let antecessor = $(this).closest("tbody");
    $(antecessor).find(".expanded").show(300);
});