$(function () {
    // Ajax Login
    $("body").on("click", "#btn-login", function (event) {
        event.preventDefault();

        let form = $("#login-form"),
            url = form.attr("action");

        // Remove Error Message
        form.find(".text-danger").remove();
        form.find(".form-control").removeClass("is-invalid");

        $.ajax({
            url: url,
            method: "POST",
            data: form.serialize(),
            beforeSend: function () {
                $("#btn-login").hide();
                $("#btn-login-disabled").fadeIn();
            },
            success: function () {
                toastr.success(
                    "Sign In Success, You Will Redirected To Page Automatically",
                    "SUCCESS"
                );
                $("#btn-login-disabled").hide();
                $("#btn-login").fadeIn();
                $("#email").val(null);
                $("#password").val(null);
                window.location.href = "/";
            },
            error: function (xhr) {
                $("#btn-login-disabled").hide();
                $("#btn-login").fadeIn();
                $("#password").val(null);
                let error = xhr.responseJSON;
                toastr.error("Email / Password Is Invalid", "ERROR");
                if ($.isEmptyObject(error) == false) {
                    $.each(error.errors, function (key, value) {
                        $("#" + key)
                            .closest(".form-group")
                            .append(
                                '<strong><span class="text-danger">' +
                                    value +
                                    "</span></strong>"
                            )
                            .find(".form-control")
                            .addClass("is-invalid");
                    });
                }
            },
        });
    });

    // Modal Show
    $("body").on("click", "#btn-modal-show", function (event) {
        event.preventDefault();

        // Prevent Modal close with click / keyboard [ esc ]
        $(".modal").modal({
            backdrop: "static",
            keyboard: false,
        });

        let me = $(this),
            url = me.attr("href"),
            title = me.attr("title");

        $(".modal-title").text(title);

        $.ajax({
            url: url,
            dataType: "html",
            success: function (response) {
                $(".modal-body").html(response);
            },
        });

        $(".modal").modal("show");
        $("#btn-save").text(me.hasClass("edit") ? "Update" : "Save");
        $(".modal-body form").trigger("reset");
        $("#btn-save").show();
        $(".btn-destroy").hide();
    });

    // Btn Close Modal
    $("body").on("click", "#btn-close-modal", function (event) {
        event.preventDefault();

        Swal.fire({
            title: "Close Modal ? ",
            text: "If you have a unsaved changes, you will lose it.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, leave it!",
        }).then((result) => {
            if (result.value) {
                $(".modal").modal("hide");
            }
        });
    });

    // Show Some Detail
    $("body").on("click", "#btn-show", function (event) {
        event.preventDefault();

        let me = $(this),
            url = me.attr("href"),
            title = me.attr("title");

        $(".modal-title").html(title);

        $.ajax({
            url: url,
            dataType: "html",
            success: function (response) {
                $(".modal-body").html(response);
            },
        });

        $(".modal").modal("show");
        $(".modal-body form").trigger("reset");
        $("#btn-save").hide();
    });

    // Save Some Data
    $("body").on("click", "#btn-save", function (event) {
        event.preventDefault();

        let form = $(".modal-body form"),
            url = form.attr("action"),
            method =
                $("input type[name=_method]").val() == undefined
                    ? "POST"
                    : "PUT";

        // Remove Error Message
        form.find(".text-danger").remove();
        form.find(".form-control").removeClass("is-invalid");

        Swal.fire({
            title: "Are you sure?",
            text: "Choose Option Wisely",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, I Do!",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    method: method,
                    data: new FormData(form[0]),
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $("#btn-save").hide();
                        $("#btn-disabled").show();
                    },
                    success: function (data) {
                        $("#btn-save").show();
                        $("#btn-disabled").hide();
                        toastr.success(data.message, "SUCCESS");
                        $(".modal").modal("hide");
                        $("#datatable").DataTable().ajax.reload();
                        $(".modal-body form").trigger("reset");
                        // If Element Exists
                        if ($("#tell-reload-page")) {
                            $("#tell-reload-page").show();
                        }

                        // Load Data from PO in logbook
                        if (data.po_id) {
                            $("#project-list").load(
                                "./logbook/load?po_id=" +
                                    data.po_id +
                                    "&year=" +
                                    data.year
                            );
                        }

                        // Check For Calendar
                        if (data.start) {
                            $("#fetch").load("./events/load-calendar");
                        }
                    },
                    error: function (xhr) {
                        $("#btn-save").show();
                        $("#btn-disabled").hide();
                        let error = xhr.responseJSON;
                        console.log(xhr);
                        console.log(error);
                        toastr.error(error.message, "ERROR");
                        if ($.isEmptyObject(error) == false) {
                            $.each(error.errors, function (key, value) {
                                $("#" + key)
                                    .closest(".form-group")
                                    .append(
                                        '<strong><span class="text-danger">' +
                                            value +
                                            "</span></strong>"
                                    )
                                    .find(".form-control")
                                    .addClass("is-invalid");
                            });
                        }
                    },
                });
            }
        });
    });

    // Btn Done in project
    $("body").on("click", "#btn-done", function (event) {
        event.preventDefault();

        let me = $(this),
            url = me.attr("href"),
            csrf_token = $("meta[name=csrf-token]").attr("content");

        Swal.fire({
            title: "Update Status project?",
            text: "Choose Option Wisely",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, I Do!",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    method: "POST",
                    data: {
                        _token: csrf_token,
                        _method: "PUT",
                    },
                    success: function (data) {
                        toastr.success("project Updated", "SUCCESS");
                        $("#datatable").DataTable().ajax.reload();
                        if (data.po_id) {
                            $("#project-list").load(
                                "./logbook/load?po_id=" +
                                    data.po_id +
                                    "&year=" +
                                    data.year
                            );
                        }
                    },
                    error: function (xhr) {
                        let error = xhr.responseJSON;
                        toastr.error(error.message, "ERROR");
                    },
                });
            }
        });
    });

    $("body").on("click", "#btn-update-target-in-logbook", function (event) {
        event.preventDefault();

        let form = $("#update-target-logbook-form"),
            url = form.attr("action"),
            method =
                $("input type[name=_method]").val() == undefined
                    ? "POST"
                    : "PUT";

        // Remove Error Message
        form.find(".help-block").remove();
        form.find(".form-group").removeClass("has-error");

        Swal.fire({
            title: "Are you sure?",
            text: "Choose Option Wisely",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, I Do!",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    method: method,
                    data: form.serialize(),
                    beforeSend: function () {
                        $("#btn-save").hide();
                        $("#btn-disabled").show();
                    },
                    success: function (data) {
                        $("#btn-save").show();
                        $("#btn-disabled").hide();
                        toastr.success(data.message, "SUCCESS");
                        $(".modal").modal("hide");
                        $("#datatable").DataTable().ajax.reload();
                        $(".modal-body form").trigger("reset");
                        // If Element Exists
                        if ($("#tell-reload-page")) {
                            $("#tell-reload-page").show();
                        }
                        // Load Data from PO in logbook
                        if (data.po_id) {
                            $("#project-list").load(
                                "./logbook/load?po_id=" +
                                    data.po_id +
                                    "&year=" +
                                    data.year
                            );
                        }
                    },
                    error: function (xhr) {
                        $("#btn-save").show();
                        $("#btn-disabled").hide();
                        let error = xhr.responseJSON;
                        toastr.error(error.message, "ERROR");
                        if ($.isEmptyObject(error) == false) {
                            $.each(error.errors, function (key, value) {
                                $("#" + key)
                                    .closest(".form-group")
                                    .addClass("has-error")
                                    .append(
                                        '<strong><span class="help-block">' +
                                            value +
                                            "</span></strong>"
                                    );
                            });
                        }
                    },
                });
            }
        });
    });

    // Delete Data
    $("body").on("click", "#btn-destroy", function (event) {
        event.preventDefault();

        let me = $(this),
            url = me.attr("href"),
            csrf_token = $("meta[name=csrf-token]").attr("content");

        Swal.fire({
            title: "Are you sure want to delete?",
            text: "Choose Option Wisely",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Delete it!",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    method: "POST",
                    data: {
                        _token: csrf_token,
                        _method: "DELETE",
                    },
                    success: function (data) {
                        toastr.success(data.message, "SUCCESS");
                        $("#datatable").DataTable().ajax.reload();

                        if ($(".modal")) {
                            $(".modal").modal("hide");
                        }

                        if (data.start) {
                            $("#fetch").load("./events/load-calendar");
                        }
                    },
                    error: function (xhr) {
                        let error = xhr.responseJSON;
                        toastr.error(error.message, "ERROR");
                    },
                });
            }
        });
    });

    // Soft Deletes
    // Restore Button
    $("body").on("click", "#btn-restore", function (event) {
        event.preventDefault();

        let me = $(this),
            url = me.attr("href"),
            title = me.attr("title");

        Swal.fire({
            title: "Are you sure to Restore " + title + " ?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "blue",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, restore it!",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    method: "GET",
                    success: function () {
                        toastr.success(title + " Restored", "SUCCESS");
                        $("#datatable").DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        toastr.error("Failed Restoring " + title, "ERROR");
                    },
                });
            }
        });
    });

    // Delete Permanent Button
    $("body").on("click", "#btn-delete-permanent", function (event) {
        event.preventDefault();

        let me = $(this),
            url = me.attr("href"),
            title = me.attr("title"),
            csrf_token = $("meta[name=csrf-token]").attr("content");

        Swal.fire({
            title: "Are you sure want to delete " + title + " ?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: csrf_token,
                        _method: "DELETE",
                    },
                    success: function () {
                        toastr.success(
                            title + " Deleted Permanently",
                            "SUCCESS"
                        );
                        $("#datatable").DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        toastr.error(
                            title +
                                " Is being used / Still atached to other data",
                            "ERROR"
                        );
                    },
                });
            }
        });
    });
    // End Soft Deletes

    // Refresh Data
    $("body").on("click", "#btn-refresh", function (event) {
        event.preventDefault();

        let me = $(this),
            url = me.attr("href");

        $.ajax({
            url: url,
            type: "GET",
            success: function () {
                toastr.success("Data Refreshed", "SUCCESS");
                $("#datatable").DataTable().ajax.reload();
            },
            error: function (xhr) {
                toastr.error("Refresh Failed", "ERROR");
            },
        });
    });
});
