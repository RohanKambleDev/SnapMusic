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
            <div class="space-y-2">
                <label class="block text-lg font-medium text-indigo-700 mb-1">Audio Source:</label>
                <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="audio_source" value="file" checked
                            class="form-radio text-indigo-600" onchange="toggleAudioSource()">
                        <span class="ml-2">Upload Audio File</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="audio_source" value="youtube"
                            class="form-radio text-indigo-600" onchange="toggleAudioSource()">
                        <span class="ml-2">YouTube URL</span>
                    </label>
                </div>
            </div>

            <div id="audio-file-input">
                <label for="audio" class="block text-lg font-medium text-indigo-700 mb-1">Choose Audio:</label>
                <input type="file" name="audio" id="audio"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>

            <div id="youtube-url-input" class="hidden">
                <label for="youtube_url" class="block text-lg font-medium text-indigo-700 mb-1">YouTube URL:</label>
                <input type="url" name="youtube_url" id="youtube_url"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                    placeholder="https://www.youtube.com/watch?v=...">
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all transform hover:scale-105">
                Upload
            </button>
        </form>
    </div>

    <script>
    function toggleAudioSource() {
        const audioFileInput = document.getElementById('audio-file-input');
        const youtubeUrlInput = document.getElementById('youtube-url-input');
        const audioFile = document.getElementById('audio');
        const youtubeUrl = document.getElementById('youtube_url');

        if (document.querySelector('input[name="audio_source"]:checked').value === 'file') {
            audioFileInput.classList.remove('hidden');
            youtubeUrlInput.classList.add('hidden');
            audioFile.required = true;
            youtubeUrl.required = false;
        } else {
            audioFileInput.classList.add('hidden');
            youtubeUrlInput.classList.remove('hidden');
            audioFile.required = false;
            youtubeUrl.required = true;
        }
    }
    </script>
</body>

</html>
