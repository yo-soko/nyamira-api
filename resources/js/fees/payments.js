$(document).ready(function () {
    // Reset form on Add click
    $("#addPaymentBtn").click(function () {
        $("#addPaymentForm")[0].reset();
        $("input[name='payment_id']").val("");
        $("#savePaymentBtn").text("Save payment").attr("name", "savePaymentBtn").removeData("edit-mode");

        // Clear and reset selects
        $('#student_id').html('<option value="">-- Select Student --</option>');
        $('#outstanding_balance').val("0.00");
    });

    // Handle edit payment
    $(".editPaymentBtn").click(function () {
        var paymentId = $(this).data("id");

        $.ajax({
            url: "/ajax/payments",
            type: "POST",
            data: { payment_id: paymentId },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    var payment = response.data;
                    $("input[name='payment_id']").val(payment.payment_id);
                    $("select[name='class_id']").val(payment.class_id).trigger('change');
                    $("select[name='term_id']").val(payment.term_id);
                    $("input[name='amount_paid']").val(payment.amount_paid);
                    $("input[name='receipt_number']").val(payment.receipt_number);

                    setTimeout(function () {
                        $("select[name='student_id']").val(payment.student_id);
                    }, 500);

                    $("#savePaymentBtn").text("Update")
                        .attr("name", "updateBtn")
                        .data("edit-mode", true);
                } else {
                    console.error("Failed to fetch payment details.");
                }
            },
            error: function (xhr) {
                console.error("AJAX error:", xhr.responseText);
            },
        });
    });

    // Submit payment form
    $("#addPaymentForm").submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var isEditMode = $("#savePaymentBtn").data("edit-mode");
        var paymentId = $("input[name='payment_id']").val();

        if (isEditMode) {
            formData.append("updateBtn", "true");
        } else {
            formData.append("savePaymentBtn", "true");
        }

        $.ajax({
            url: "/ajax/payments",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    showNotify("Payment saved Successfully!", "success");
                    $("#paymentModal").modal("hide");
                    $("#addPaymentForm")[0].reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    console.error("Update failed:", response.error);
                    showNotify("Error: " + response.error, "danger");
                }
            },
            error: function (xhr) {
                console.error("AJAX Error:", xhr.responseText);
            },
        });
    });

    // Delete payment
    $(".deletePaymentBtn").click(function () {
        var paymentId = $(this).data("id");

        Swal.fire({
            title: "Are you sure?",
            text: "This action cannot be reverted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/ajax/payments",
                    type: "POST",
                    data: { deleteBtn: true, payment_id: paymentId },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire("Deleted!", "The payment has been removed.", "success").then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire("Error!", response.error, "error");
                        }
                    },
                    error: function (xhr) {
                        console.error("AJAX Error:", xhr.responseText);
                        Swal.fire("Oops!", "Something went wrong.", "error");
                    },
                });
            }
        });
    });

    // NEW: Load students based on selected class
    $('#class_id').on('change', function () {
        const classId = $(this).val();
        const studentSelect = $('#student_id');

        studentSelect.html('<option value="">Loading...</option>');
        $('#outstanding_balance').val("0.00");

        if (!classId) return;

        $.ajax({
            url: `/students/by-class/${classId}`,
            type: "GET",
            success: function (data) {
                studentSelect.html('<option value="">-- Select Student --</option>');
                data.forEach(student => {
                    studentSelect.append(
                        `<option value="${student.id}">${student.full_name}</option>`
                    );
                });
            },
            error: function () {
                studentSelect.html('<option value="">-- Error loading students --</option>');
            }
        });

    });

    $('#student_id, #term_id').on('change', function () {
        const studentId = $('#student_id').val();
        const termId = $('#term_id').val();
        const classId = $('#class_id').val(); // ADD THIS — it's required by your controller

        if (studentId && termId && classId) {
            $.ajax({
                url: `/students/balance`,
                type: "GET",
                data: {
                    student_id: studentId,
                    term_id: termId,
                    class_id: classId
                },
                success: function (data) {
                    $('#outstanding_balance').val(parseFloat(data.balance).toFixed(2));
                },
                error: function () {
                    $('#outstanding_balance').val("0.00");
                }
            });
        }
    });

});
