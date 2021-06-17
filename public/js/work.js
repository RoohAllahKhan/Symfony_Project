var empTbl;
$(document).ready(function () {

    $(".editDesignation").prepend("<option value = '' >-- Select Designation --</option>")
    $(".editBoss").prepend("<option value = '' >-- Select Boss --</option>")
    if(empTbl == undefined){
        empTbl = $('#empTable').DataTable();
    }
    $('#currAttTable').DataTable();
    $('#monthyReportTable').DataTable();


})


$('.custom-file-input').on('change', function(event) {
    let inputFile = event.currentTarget;
    $(inputFile).parent().find('.custom-file-label').html(inputFile.files[0].name);
});

$(document).on('click', ".timein_btn", function (event) {
    event.preventDefault();
    var d = new Date();
    document.getElementById("attendance_timeIn").value = d.getHours() + ":" + d.getMinutes();

});

$(document).on('click', ".timeout_btn", function (event) {
    event.preventDefault();
    var d = new Date();
    document.getElementById("attendance_timeOut").value = d.getHours() + ":" + d.getMinutes();

});

$(document).on('click', ".saveBtn", function (event) {

    var time_in = $("#attendance_timeIn").val();
    var time_out = $("#attendance_timeOut").val();
    // if(time_in !== "")
    // {
    //     $(".timein_btn").hide();
    // }
    if(time_in === "" && time_out === "")
    {
        alert("Kindly Mark your attendance in order to save");
        document.getElementById("attendance_timeIn").value = "";
        $("#attendance_timeIn").attr("placeholder", "HH:MM");
        document.getElementById("attendance_timeOut").value = "";
        $("#attendance_timeOut").attr("placeholder", "HH:MM");

    }
    if(time_in === "" && time_out !== "")
    {
        alert("First mark your time In");
        document.getElementById("attendance_timeOut").value = "";
        $("#attendance_timeOut").attr("placeholder", "HH:MM");
    }
});


