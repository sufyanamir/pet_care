<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" />
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/logo.svg')}}">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <title>login</title>
    <style>
  .gradient-text {
    background: linear-gradient(274.05deg, #FCBAE0 0.54%, #B37EEA 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-fill-color: transparent;
  }
</style>
</head>

<body>
    <section class=" min-h-screen">
        <!-- login container -->
        <div class="flex items-center justify-center">
            <div class="flex rounded-2xl  shadow-2xl  p-5 max-w-4xl items-center mt-24">
                <!-- form -->

                <div class="md:w-1/2 px-8  sm:px-16  ">
                    <div>
                        <img class="w-24" src="{{ asset('assets/images/logo.svg') }}" alt="logo">
                    </div>

                    <h2 class="font-bold text-2xl mt-16 gradient-text">Log In</h2>
                    <form action="/Login" id="login-form" method="post" class="flex flex-col gap-4">
                        @csrf
                        <div class="relative mt-16 border-b border-[#EE81AF]">
                            <label for="" class="gradient-text">Email</label>
                            <input class="p-2 pl-5 relative focus:outline-none focus:border-transparent outline-none border-none w-full" type="email" name="email" placeholder="Email">
                            <svg width="14" height="12" class="absolute top-[60%]" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 1.71635V1.33333C0 1.0858 0.0921872 0.848401 0.256282 0.673367C0.420376 0.498333 0.642936 0.4 0.875 0.4L13.125 0.4C13.3571 0.4 13.5796 0.498333 13.7437 0.673367C13.9078 0.848401 14 1.0858 14 1.33333V1.71635L7 6.38302L0 1.71635ZM7.23188 7.32918C7.16232 7.37549 7.08199 7.40003 7 7.40003C6.91801 7.40003 6.83768 7.37549 6.76813 7.32918L0 2.81698V10.6667C0 10.9142 0.0921872 11.1516 0.256282 11.3266C0.420376 11.5017 0.642936 11.6 0.875 11.6H13.125C13.3571 11.6 13.5796 11.5017 13.7437 11.3266C13.9078 11.1516 14 10.9142 14 10.6667V2.81698L7.23188 7.32918Z" fill="url(#paint0_linear_139_1375)" />
                                <defs>
                                    <linearGradient id="paint0_linear_139_1375" x1="7" y1="0.4" x2="7" y2="11.6" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#FCBAE0" />
                                        <stop offset="1" stop-color="#B37EEA" />
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                        <div class="relative border-b border-[#EE81AF]">
                            <label for="" class="gradient-text">Password</label>
                            <input class="p-2 pl-5  focus:outline-none focus:border-transparent outline-none border-none w-full" type="password" name="password" placeholder="Password">
                            <svg width="14" height="18" class="absolute  top-[60%]" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.375 6.375V4.625C11.375 2.175 9.45 0.25 7 0.25C4.55 0.25 2.625 2.175 2.625 4.625V6.375C1.1375 6.375 0 7.5125 0 9V15.125C0 16.6125 1.1375 17.75 2.625 17.75H11.375C12.8625 17.75 14 16.6125 14 15.125V9C14 7.5125 12.8625 6.375 11.375 6.375ZM4.375 4.625C4.375 3.1375 5.5125 2 7 2C8.4875 2 9.625 3.1375 9.625 4.625V6.375H4.375V4.625ZM7.875 13.375C7.875 13.9 7.525 14.25 7 14.25C6.475 14.25 6.125 13.9 6.125 13.375V10.75C6.125 10.225 6.475 9.875 7 9.875C7.525 9.875 7.875 10.225 7.875 10.75V13.375Z" fill="url(#paint0_linear_139_1390)" />
                                <defs>
                                    <linearGradient id="paint0_linear_139_1390" x1="7" y1="0.25" x2="7" y2="17.75" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#FCBAE0" />
                                        <stop offset="1" stop-color="#B37EEA" />
                                    </linearGradient>
                                </defs>
                            </svg>

                        </div>
                        <!-- <a href="/forgotPassword" class="text-[#FF0000] text-[12px]/[18px] hover:scale-105 duration-300 text-end m-0">forget your password?</a> -->
                        <button type="submit" id="loginBtn" style="background: linear-gradient(274.05deg, #FCBAE0 0.54%, #B37EEA 100%);" class=" rounded-full w-full text-white py-2 hover:scale-105 duration-300">
                            <div class=" text-center hidden" id="spinner">
                                <svg aria-hidden="true" class="w-5 h-5 mx-auto text-center text-gray-200 animate-spin fill-[#930027]" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor" />
                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill" />
                                </svg>
                            </div>
                            <div class="" id="text">
                                Login
                            </div>
                        </button>
                    </form>
                    <div class=" mt-3 text-center">
                        <a target="_blank" href="https://thewebconcept.com/" class="gradient-text hover:underline">
                            <span class=" text-sm sm:text-center my-auto dark:text-gray-400">Powered by : The Web Concept™.
                            </span>
                        </a>
                    </div>
                </div>

                <!-- image -->
                <div class="md:block hidden w-1/2">
                    <img class="rounded-2xl" src="{{ asset('assets/images/logo.svg') }}">
                </div>
            </div>
        </div>
    </section>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $("#login-form").submit(function(event) {
                // Prevent the default form submission
                event.preventDefault();

                // Serialize the form data into a JSON object
                var formData = $(this).serialize();

                // Send the AJAX request
                $.ajax({
                    type: "POST", // Use POST method
                    url: "/Login", // Replace with the actual URL to your login endpoint
                    data: formData, // Send the form data
                    dataType: "json", // Expect JSON response
                    beforeSend: function() {
                        $('#spinner').removeClass('hidden');
                        $('#text').addClass('hidden');
                        $('#loginBtn').attr('disabled', true);
                    },
                    success: function(response) {
                        // Handle the success response here
                        if (response.success == true) {
                            // Redirect to the dashboard or do something else
                            $('#text').removeClass('hidden');
                            $('#spinner').addClass('hidden');
                            window.location.href = "/dashboard";
                        } else if (response.success == false) {
                            Swal.fire(
                                'Warning!',
                                response.message,
                                'warning'
                            )
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Log the error response to the console
                        console.error("AJAX Error: " + textStatus, errorThrown);

                        // Log the response content for further investigation
                        console.log("Response:", jqXHR.responseText);
                        let response = JSON.parse(jqXHR.responseText);
                        Swal.fire(
                            'Warning!',
                            response.message,
                            'warning'
                        )
                        // Handle the error here
                        $('#text').removeClass('hidden');
                        $('#spinner').addClass('hidden');
                        $('#loginBtn').attr('disabled', false);
                    }
                });
            });
        });
    </script>
</body>

</html>