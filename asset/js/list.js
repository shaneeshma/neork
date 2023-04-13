$(document).ready(function () {
    list_data();
    $("#name").focus();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
}); //documenyt
function add_form() {
    $("#user_details")[0].reset();
    $("#user_id").val('');
    $(".error_cls").hide();
    
    $("#add_overlay").css("display", "block");
}
function close_view() {
    $("#user_details")[0].reset();
    $("#add_overlay").css("display", "none");
}

function list_data(page = "") {
    if (!page) page = 1;
    $.ajax({
        type: "GET",
        url: base_url + "/list_data",
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        success: function (result) {
            $("#detail_list tbody").html("");
            $("#detail_list tbody").html(result.table);
            // $(".asset_pagination").html(result.paginate);
        },
        error: function (result) {
            // alert("error");
            console.log(result);
            // alert(result);
        },
    });
}

function numonly(evt) {
    evt = evt ? evt : window.event;
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
}
function save_data() {
    $selected = "";
    $selected_names = "";
    $(".new_check").each(function (index, value) {
        if ($(this).is(":checked")) {
            id = $(this).attr("id");
            $selected += $(this).attr("id") + ",";
            $selected_names += $(this).attr("name") + ",";
        }
    });
    $("#hobbies").val($selected);
    $("#hobby_names").val($selected_names);
    var formData = new FormData(document.getElementById("user_details"));

    $.ajax({
        url: $("#user_details").attr("action"),
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        cache: false,
        async: false,
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),

        success: function (data) {
            if (data) {
                alert(data.status);
                close_view();
                list_data();
            }
        },
        error: function (response) {
            var response = JSON.parse(response.responseText);
            $.each(response.errors, function (key, value) {
                alert(key);
                $("#" + key + "_error").show();
                $("#" + key + "_error").text(value);
            });
            console.log(response);
        },
    });
}
//view page
function edit_User_detail(id) {
    $(".error_cls").hide();
    $("#add_overlay").css("display", "block");
    $.ajax({
        method: "get",
        url: base_url + "/data_view",
        data: {
            id: id,
        },
        success: function (data) {
            $("#user_id").val(data.details.id);
            $("#name").val(data.details.name);
            $("#contact_no").val(data.details.contact_no);
            if (data.hobbies) {
                hobby_ids = data.hobbies.toString().split(",");
                $(".new_check").each(function (index, value) {
                    // if ($(this).is(":checked")) {
                    id = $(this).attr("id");
                    if (hobby_ids.indexOf(id) != -1) {
                        $(this).prop("checked", true);
                    }

                    // }
                });
            }
            // $("#hobbies").val(data.details.hobbies);
            $("#category_id").val(data.details.category_id);
        },
        error: function (response) {},
    });
}

//view page
function delete_User_detail(id) {
    $conf = confirm("Are you sure to delete?(Y/N)");
    if ($conf)
        $.ajax({
            method: "get",
            url: base_url + "/delete_user",
            data: {
                id: id,
            },
            success: function (data) {
                if (data == "success") {
                    alert("Deleted");
                    list_data();
                }
            },
            error: function (response) {},
        });
}
