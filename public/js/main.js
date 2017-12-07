$(document).ready(function() {

    $("#todo_date, #u_todo_date").datepicker({
        dateFormat:"yy-mm-dd",
        minDate: new Date(),
        changeMonth: true,
        changeYear: true
    });

    $('[data-toggle="tooltip"]').tooltip();

    $('.dropdown-toggle').dropdown()

    var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

    function setTooltip(date) {
        var date_arr = date.split("-");

        var tooltip = "";

        if(typeof date_arr[0] !== "undefined" && typeof date_arr[1] !== "undefined" && typeof date_arr[2] !== "undefined")
        {
            tooltip = months[(parseInt(date_arr[1])-1)] + " " + date_arr[2] + ", " + date_arr[0];
        }

        return tooltip;
    }

    $(document).on("click", ".completed", function() {
        var row = $(this).parent().parent();
        var todo_id = row.attr("id").replace("todo", "");
        var completed = 0;

        if($(this).is(":checked")) completed = 1;
        else completed = 0;

        var filter = $('.filter-menu').attr("id");

        $.post("/completed/"+todo_id, { completed:completed, _token:$("#token").val(), _method:"PUT" }, function(success) {
            if(success.success) {
                if(completed)
                {
                    var today = new Date();
                    var dd = today.getDate();
                    var mm = today.getMonth()+1;
                    var yyyy = today.getFullYear();

                    var todo_completed_date = yyyy+"-"+mm+"-"+dd;

                    row.find(".todo-completed-date").html(todo_completed_date);

                    var dt = setTooltip(todo_completed_date);
                    $("#"+row.attr("id")).find(".todo-completed-date").attr("title", dt);
                    $('[data-toggle="tooltip"]').tooltip();

                } else row.find(".todo-completed-date").html("");

                if (!completed && filter == "completed") {
                    row.fadeOut();
                }
                if (completed && filter == "uncompleted") {
                    row.fadeOut();
                }
            }
        }, "json");
        row.toggleClass("completed-todo");
        row.find(".edit-todo").toggleClass("display");
    });

    $(document).on("click", ".edit-todo", function() {
        var row = $(this).parent().parent().parent();

        $("#u_todo").val(row.find(".todo-text").html());
        $("#u_todo_date").val(row.find(".todo-date").html());
        $("#update_todo_id").val(row.attr("id"));

        $("#editTodoModal").modal({
            backdrop: "static",
            keyboard: false
        });
    });

    $("#editTodoModal").on("hidden.bs.modal", function () {
        $("#updateTodoForm")[0].reset();
    });

    $("#update_todo_btn").click(function() {

        var todo = $('#u_todo').val();
        var todo_date = $('#u_todo_date').val();
        var token = $("#token").val();
        var todo_id = $('#update_todo_id').val();

        $('.modal-msg').html('<div class="alert alert-info">Updating... please wait.</div>');

        $.ajax({
            url     : $('#updateTodoForm').attr("action")+"/"+todo_id.replace("todo",""),
            type    : "post",
            data    : { todo:todo, todo_date:todo_date, _token:token, _method:"PUT"},
            dataType: "json",
            success : function (json)
            {
                $("#"+todo_id).find(".todo-text").html(todo);
                $("#"+todo_id).find(".todo-date").html(todo_date);
                var dt = setTooltip(todo_date);
                $("#"+todo_id).find(".todo-date").attr("title", dt);
                $('[data-toggle="tooltip"]').tooltip();
                $(".modal-msg").html('<div class="alert alert-success">Todo was updated successfully. You may now close the modal dialog.</div>');
            },
            error   : function ( jqXhr, json, errorThrown )
            {
                var response = jqXhr.responseJSON;
                var errorsHtml= '';
                errorsHtml = '<div class="alert alert-danger"><ul>';
                    $.each(response.errors , function(key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                errorsHtml += '</ul></div>';
                $('.modal-msg').html(errorsHtml);
            }
        });

    });

    $(document).on("click", ".delete-todo", function() {
        if(confirm("Are you sure you want to delete this todo?"))
        {
            var token = $("#token").val();
            var row = $(this).parent().parent().parent();
            var todo_id = row.attr("id").replace("todo", "");

            $.post("/delete/"+todo_id, { _token:token, _method:"DELETE" }, function(success) {
               if(success.success) row.fadeOut();
            }, "json");
        }
    });

    $("#delete-all").click(function() {
        if(confirm("Are you sure you want to delete ALL todos? This action is irreversible."))
        {
            $.post("/delete/all", { _token:$("#token").val(), _method:"DELETE" }, function(success) {
               if(success.success) window.location.reload();
            }, "json");
        }
    });

    $(".check-all").click(function() {
       if($(this).is(":checked"))
       {
            $(".completed").prop("checked", true);
            $("#todos-table tbody tr").addClass("completed-todo");
            $(".edit-todo").addClass("display");
       }
       else
       {
           $(".completed").prop("checked", false);
           $("#todos-table tbody tr").removeClass("completed-todo");
           $(".edit-todo").removeClass("display");
       }
    });

    $("#pagination").change(function() {
       $("#updatePaginationForm").submit();
    });

});