@if(session('success'))
    <div class="container my-4">
        <div class="relative p-4 bg-green-100 text-green-800 rounded" id="success-alert">
            {{ session('success') }}
            <button type="button" onclick="document.getElementById('success-alert').remove()" class="absolute top-2 right-2 text-green-800 hover:text-green-900 cursor-pointer">
                &times;
            </button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="container my-4">
        <div class="relative p-4 bg-red-100 text-red-800 rounded" id="error-alert">
            {{ session('error') }}
            <button type="button" onclick="document.getElementById('error-alert').remove()" class="absolute top-2 right-2 text-red-800 hover:text-red-900 cursor-pointer">
                &times;
            </button>
        </div>
    </div>
@endif
