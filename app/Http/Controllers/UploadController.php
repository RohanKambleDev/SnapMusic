<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Show the upload form.
     *
     * @return \Illuminate\View\View
     */
    public function showUploadForm()
    {
        return view('upload');
    }

    /**
     * Handle the upload and processing of files.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleUpload(Request $request)
    {
        // Validate the request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240', // max 10MB
            'audio' => 'required|mimetypes:audio/mpeg,audio/wav,audio/mp3|max:50000', // max 50MB
        ]);

        // Store the uploaded files
        $imagePath = $request->file('image')->store('uploads/images');
        $audioPath = $request->file('audio')->store('uploads/audio');

        Log::debug('$imagePath', [$imagePath]);
        Log::debug('$audioPath', [$audioPath]);

        // Define the output path
        $outputPath = 'uploads/output/' . uniqid() . '.mp4';

        $imageAbsolutePath = storage_path('app/' . $imagePath);
        $audioAbsolutePath = storage_path('app/' . $audioPath);
        $outputAbsolutePath = storage_path('app/public/' . $outputPath);

        Log::debug($imageAbsolutePath);
        Log::debug($audioAbsolutePath);
        Log::debug($outputAbsolutePath);

        $command = "/opt/homebrew/bin/ffmpeg -loop 1 -i {$imageAbsolutePath} -i {$audioAbsolutePath} -vf 'scale=trunc(iw/2)*2:trunc(ih/2)*2' -c:v libx264 -c:a aac -b:a 192k -shortest -y {$outputAbsolutePath}";
        exec("$command 2>&1", $output);

        Log::debug('$command: ');
        Log::debug($command);
        Log::debug('$output: ');
        Log::debug(print_r($output, true));

        // Store the output file information in the database
        $upload = new Upload();
        $upload->image_path = $imagePath;
        $upload->audio_path = $audioPath;
        $upload->output_path = $outputPath;
        $upload->save();

        // Redirect with a success message and the link to download the file
        return redirect('/')->with('success', 'Files have been successfully combined!')->with('output', $outputPath);
    }


}
