$(document).ready(function() {
    $("#uploadForm").submit(function(event) {
        event.preventDefault();

        let file = $("#fileInput")[0].files[0];
        if (!file) {
            notification("error", "Vui lòng chọn file trước khi upload.");
            return;
        }

        const allowedTypes = [
            'image/jpeg', 'image/png', 'application/pdf',
            'application/msword', // .doc
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
            'text/plain'
        ];

        if (!allowedTypes.includes(file.type)) {
            notification("error", "Chỉ chấp nhận file jpg, jpeg, png, pdf, doc, docx, txt.");
            $("#fileInput").val(''); // Reset file input
            return;
        }

        let formData = new FormData();
        formData.append("file", file);

        $.ajax({
            url: "/api/v1/document",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                showLoadingSpinner();
            },
            success: function (response) {
                hideLoadingSpinner();
                closeSmallModal();
                if (typeof refreshDocumentTable === 'function') {
                    refreshDocumentTable();
                }
                notification("success", "Upload File successfully!");
                $("#fileInput").val(''); // Reset sau khi upload thành công
                $("#previewContainer").hide();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                hideLoadingSpinner();
                notification("error", "Upload thất bại, vui lòng thử lại.");
            }
        });
    });

    $("#fileInput").on("change", function (event) {
        let file = event.target.files[0];
        if (!file) return;

        const allowedTypes = [
            'image/jpeg', 'image/png', 'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain'
        ];

        if (!allowedTypes.includes(file.type)) {
            notification("error", "Chỉ chấp nhận file jpg, jpeg, png, pdf, doc, docx, txt.");
            $(this).val(''); // Reset input file
            $("#previewContainer").hide(); // Ẩn preview nếu file sai
            return;
        }

        let previewContainer = $("#previewContainer");
        let filePreview = $("#filePreview");
        let pdfPreview = $("#pdfPreview");
        let fileInfo = $("#fileInfo");

        previewContainer.show();
        filePreview.addClass("d-none");
        pdfPreview.addClass("d-none");
        fileInfo.text("");

        let fileType = file.type;
        let fileURL = URL.createObjectURL(file);

        if (fileType.startsWith("image/")) {
            filePreview.attr("src", fileURL).removeClass("d-none");
        } else if (fileType === "application/pdf") {
            pdfPreview.attr("src", fileURL).removeClass("d-none");
        } else if (fileType.includes("word")) {
            fileInfo.html(`<i class="fa fa-file-word text-primary fa-2x"></i> ${file.name}`);
        } else if (fileType === "text/plain") {
            fileInfo.html(`<i class="fa fa-file-alt text-secondary fa-2x"></i> ${file.name}`);
        } else {
            fileInfo.html(`<i class="fa fa-file text-muted fa-2x"></i> ${file.name}`);
        }
    });
});
