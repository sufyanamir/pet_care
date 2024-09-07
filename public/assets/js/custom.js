$(document).ready(function () {

    topbar.config({
        autoRun: false,
        barThickness: 3,
        barColors: {
            '0': '#EE81AF'
        },
        shadowBlur: 5,
        shadowColor: 'rgba(0, 0, 0, .5)',
        className: 'topbar',
    })
    topbar.show();
    (function step() {
        setTimeout(function () {
            if (topbar.progress('+.01') < 1) step()
        }, 16)
    })()
    $(window).on('load', function () {
        topbar.hide();
    });

    // Listen for form submissions
    $(document).on('submit', 'form', function (event) {
        // Prevent the default form submission
        event.preventDefault();

        // Get the form data using FormData for handling file uploads
        var formData = new FormData(this);
        var apiUrl = $(this).attr('action');

        // Make the AJAX request
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            processData: false, // Important: Don't process the data
            contentType: false, // Important: Don't set content type (jQuery will automatically set it based on FormData)
            beforeSend: function () {
                $('.text').addClass('hidden');
                $('.spinner').removeClass('hidden');
                topbar.config({
                    autoRun: false,
                    barThickness: 3,
                    barColors: {
                        '0': '#EE81AF'
                    },
                    shadowBlur: 5,
                    shadowColor: 'rgba(0, 0, 0, .5)',
                    className: 'topbar',
                })
                topbar.show();
                (function step() {
                    setTimeout(function () {
                        if (topbar.progress('+.01') < 1) step()
                    }, 16)
                })()
                // $('.save-btn').attr('disabled', true);
                // $('.save-btn').removeClass('bg-[#930027]');
                // $('.save-btn').addClass('bg-[#0000]');
            },
            success: function (response) {
                if (response.success == true) {
                    // Handle success, if needed
                    handleSuccess(response);
                        setInterval(
                            location.reload()
                            ,
                            5000
                        );
                } else if (response.success == false) {
                    // Handle failure, if needed
                    handleFailure(response);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Log the error response to the console
                console.error("AJAX Error: " + textStatus, errorThrown);

                // Log the response content for further investigation
                console.log("Response:", jqXHR.responseText);

                // Handle the error here
                handleFailure(JSON.parse(jqXHR.responseText));
            }
        });
    });

    new DataTable('.universalTable');

    $('#universalTable').DataTable({
        "order": [[0, "desc"]]
    });





    function handleSuccess(response) {
        // Redirect to the dashboard or do something else
        $('.text').removeClass('hidden');
        $('.spinner').addClass('hidden');
        Swal.fire(
          'Success!',
          response.message,
          'success'
        );
        topbar.hide();
      }
    
      function handleFailure(response) {
        Swal.fire(
          'Warning!',
          response.message,
          'warning'
        );
        topbar.hide();
        // Additional failure handling if needed
        $('.text').removeClass('hidden');
        $('.spinner').addClass('hidden');
        $('#loginBtn').attr('disabled', false);
      }


});
