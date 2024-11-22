<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image and Audio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-lg rounded-lg p-8 max-w-lg w-full">
        <h1 class="text-3xl font-bold text-center text-indigo-700 mb-6">Upload Image and Audio</h1>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <p>{{ session('success') }}</p>
            <a href="{{ Storage::url(session('output')) }}" target="_blank"
                class="text-white bg-green-500 hover:bg-green-600 font-bold py-2 px-4 rounded inline-block mt-2">
                Download Combined File
            </a>
        </div>
        @endif

        <form action="/upload" method="post" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="image" class="block text-lg font-medium text-indigo-700 mb-1">Choose Image:</label>
                <input type="file" name="image" id="image" required
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>

            <div>
                <label for="audio" class="block text-lg font-medium text-indigo-700 mb-1">Choose Audio:</label>
                <input type="file" name="audio" id="audio" required
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all transform hover:scale-105">
                Upload
            </button>
        </form>
    </div>
</body>

</html>
