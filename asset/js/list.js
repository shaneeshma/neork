$(document).ready(function () {
    list_data();
    $("#name").focus();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("#user_details").validate({
        rules: {
            name: {
                required: true,
                maxlength: 255,
            },
            contact_no: {
                required: true,
                maxlength: 15,
                minlength: 10,
            },
        },
        messages: {
            name: {
                required: "Please enter name",
                maxlength: "Name must be no more than 255 characters",
            },
            contact_no: {
                required: "Please enter contact number",
                maxlength: "Contact number must be no more than 15 digits",
                minlength: "Contact number must be atleast 10 digits",
            },
        },
        submitHandler: function (form) {
            if ($(form).valid()) {
                $(".error_cls").hide();
            }
            return false;
        },

    });
});

$(document).keypress(function (e) {
    var id = $(e.currentTarget.activeElement).attr("id");
    $("#" + id).removeClass("error");
    $("#" + id).next('label').css('display','none');

});
function add_form() {
    $("#user_details")[0].reset();
    $("#user_id").val("");
    $(".error_cls").hide();
    $('label.error').hide();
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
            $table = "";

            if (result.countData > 0) {
                $.each(result.tableData, function (key, $objDet) {
                    $profile_img =
                        base_url +
                        "/public/uploads/images/" +
                        $objDet.profile_img;
                    $table += "<tr style='text-align:center;' scope='row'>";
                    $table += "<td width='15%'>" + $objDet.name + "</td>";
                    $table += "<td width='10%'>" + $objDet.contact_no + "</td>";
                    $hobbies = "";
                    $.each($objDet.hobbies, function (key, $hobby) {
                        $hobbies += $hobby.hobby + ",";
                    });
                    $table += "<td width='20%'>" + $hobbies + "</td>";
                    $table +=
                        "<td width='10%'>" +
                        $objDet.category.category +
                        "</td>";
                    $table +=
                        "<td width='10%'><img src ='" +
                        $profile_img +
                        "' width='100' height='100'></td>";
                    $table +=
                        '<td width="10%"><a onclick="edit_User_detail(' +
                        $objDet.id +
                        ')" style="padding: 0 0.5rem;color:#12AF41; cursor: pointer;" >Edit</a>/' +
                        '<a onclick="delete_User_detail(' +
                        $objDet.id +
                        ')" style="padding: 0 0.5rem;color:#E91F1F; cursor: pointer;" >Delete</a></td></tr>';
                });
            } else {
                $table =
                    "<tr><td colspan=6 style='text-align:center'>No Results Found</td></tr>";
            }
            $("#detail_list tbody").html($table);
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
    if (!$("#user_details").valid()) return false;
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
            if (data.status == "success") {
                alert(data.message);
                close_view();
                list_data();
            } else {
                alert("Failed");
                console.log(data.message);
            }
        },
        error: function (response) {
            var response = JSON.parse(response.responseText);
            $.each(response.errors, function (key, value) {
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
                if (data.status == "success") {
                    alert(data.message);
                    list_data();
                } else {
                    alert("Failed");
                    console.log(data.message);
                }
            },
            error: function (response) {},
        });
}
