$(document).ready(function () {
    // Sakrij sve sekcije osim početne
    $("section").hide();
    $("#view_main").show();

    // Klik na navigacione linkove
    $(".nav-link").click(function (e) {
        e.preventDefault();
        let target = $(this).attr("href").substring(1);
        $("section").hide();
        $("#" + target).show();
    });

    // Funkcija za slanje forme iz treće sekcije
    $("#sendForm").click(function () {
        let name = $("#name").val();
        let title = $("#title").val();
        let desc = $("#desc").val();
        alert("Podaci poslani: " + name + ", " + title + ", " + desc);
    });
});