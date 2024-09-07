@include('layouts.header')
<div class=" bg-white w-full rounded-2xl shadow-lg">
    <div class=" flex justify-between p-3 rounded-t-2xl">
        <div class=" text-xl font-semibold">
            <h4>Animal List</h4>
        </div>


        <!-- Modal toggle -->
        <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
            Add Animal
        </button>
    </div>
    <div class=" py-4 overflow-x-auto">
        <table id="universalTable" class="display" style="width:100%">
            <thead class=" text-sm">
                <tr>
                    <th></th>
                    <th>Animal Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="universalTableBody" class=" text-sm">
                @foreach($animals as $animal)
                <tr>
                    <td><img src="{{asset($animal->animal_image)}}" class=" w-12" alt="Image"></td>
                    <td>{{ $animal->animal_name }}</td>
                    <td>
                        <button id="editAnimal" data-modal-toggle="crud-modal" data-animal-id="{{$animal->animal_id}}" data-animal-name="{{$animal->animal_name}}">
                            <img src="{{ asset('assets/icons/edit-icon.svg') }}" alt="btn">
                        </button>
                        <form action="/delete/animal/{{ $animal->animal_id }}" class=" inline-block" method="post">
                            @csrf
                            <button type="submit">
                                <img src="{{ asset('assets/icons/del-icon.svg') }}" alt="btn">
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Main modal -->
<div id="crud-modal" tabindex="-1" aria-hidden="true" class=" hidden overflow-y-auto overflow-x-hidden fixed top-2 right-0 left-0 z-50 justify-center items-center  md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4  max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Add New Animal
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form class="p-4 md:p-5" id="animalForm" method="POST" action="/addAnimal" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="animal_id" name="animal_id" value="">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2 sm:col-span-1">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Animal Name</label>
                        <input type="text" name="animalName" id="animalName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Animal Name" required="">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Animal Image</label>
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="animalImage" name="animalImage" type="file">
                    </div>
                </div>
                <div class="" align="right">
                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <div class=" text-center hidden spinner">
                            <svg aria-hidden="true" class="w-5 h-5 mx-auto text-center text-gray-200 animate-spin fill-[#EE81AF]" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor" />
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill" />
                            </svg>
                        </div>
                        <div class="text">
                            <svg class="me-1 -ms-1 w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span>
                                Save
                            </span>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('layouts.footer')
<script>
    $(document).ready(function() {
        // Click event for edit buttons
        $('#editAnimal').on('click', function() {
            // Get animal data from data attributes
            var animalId = $(this).data('animal-id');
            var animalName = $(this).data('animal-name');

            // Set the form fields with the fetched data
            $('#animal_id').val(animalId);
            $('#animalName').val(animalName);
        });

        function checkModalHidden() {
            if ($('#crud-modal').hasClass('hidden')) {
                $('#animalForm')[0].reset(); // Reset form fields
                $('#animal_id').val(''); // Clear hidden animal ID field
            }
        }

        setInterval(checkModalHidden, 100);

        $(window).on('beforeunload', function () { 
            clearInterval(checkModalHidden);
         })

    });
</script>