<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class YoutubeService
{
    // public function downloadAndConvertToAudio(string $youtubeUrl, int $duration = 30): string
    // {
    //     $tempPath = 'uploads/temp/' . Str::random(40);
    //     $audioPath = 'uploads/audio/' . Str::random(40) . '.mp3';

    //     $youtubeUrl = addslashes($youtubeUrl);

    //     // Download video using yt-dlp (you'll need to install this)
    //     $downloadCommand = "yt-dlp -f 'bestaudio[ext=m4a]' --extract-audio --audio-format mp3 --audio-quality 0 --download-sections '*00:00-00:30' -o " . storage_path("app/{$tempPath}") . " {$youtubeUrl}";
    //     exec($downloadCommand, $output, $status);

    //     Log::debug('Download command:', [$downloadCommand]);
    //     Log::debug('Youtube audio:', [$output]);
    //     Log::debug('Youtube status:', [$status]);

    //     // Trim to exact duration if needed
    //     $trimCommand = "ffmpeg -i " . storage_path("app/{$tempPath}") . " -t {$duration} -acodec copy " . storage_path("app/{$audioPath}");
    //     exec($trimCommand);

    //     // Clean up temp file
    //     Storage::delete($tempPath);

    //     return $audioPath;
    // }

    // public function downloadAndConvertToAudio(string $youtubeUrl, int $duration = 30): string
    // {
    //     $tempDir = storage_path('app/uploads/temp/');
    //     $audioDir = storage_path('app/uploads/audio/');

    //     // Ensure directories exist
    //     Storage::makeDirectory('uploads/temp');
    //     Storage::makeDirectory('uploads/audio');

    //     // Temporary and final file paths
    //     $tempFile = $tempDir . Str::random(40) . '.mp3';
    //     $audioFile = $audioDir . Str::random(40) . '.mp3';

    //     // Sanitize YouTube URL
    //     $youtubeUrl = escapeshellarg($youtubeUrl);

    //     // Step 1: Download audio with yt-dlp
    //     $downloadCommand = "yt-dlp -f 'bestaudio[ext=m4a]' --extract-audio --audio-format mp3 --audio-quality 0 --download-sections '*00:00-00:{$duration}' -o {$tempFile} {$youtubeUrl}";
    //     exec($downloadCommand, $downloadOutput, $downloadStatus);

    //     Log::debug('Download command:', [$downloadCommand]);
    //     Log::debug('Download output:', $downloadOutput);
    //     Log::debug('Download status:', [$downloadStatus]);

    //     // Check if download was successful
    //     if ($downloadStatus !== 0 || !file_exists($tempFile)) {
    //         throw new \RuntimeException('Audio download failed. Check logs for details.');
    //     }

    // // Step 2: Move or rename the file to the final audio path
    // if (!Storage::move("uploads/temp/" . basename($tempFile), "uploads/audio/" . basename($audioFile))) {
    //     throw new \RuntimeException('Failed to move audio file to final destination.');
    // }

    // // Clean up temporary files
    // Storage::delete("uploads/temp/" . basename($tempFile));

    // return "uploads/audio/" . basename($audioFile);
    // }


    public function downloadAndConvertToAudio(string $youtubeUrl, int $duration = 30): string
    {
        $tempFile = storage_path('app/uploads/temp/') . Str::random(40) . '.mp3';
        $audioFile = storage_path('app/uploads/audio/') . Str::random(40) . '.mp3';

        // Ensure directories exist
        Storage::makeDirectory('uploads/temp');
        Storage::makeDirectory('uploads/audio');

        // Escape URL to avoid issues with special characters
        $youtubeUrl = escapeshellarg($youtubeUrl);

        Log::info('youtubeUrl');
        Log::info($youtubeUrl);

        // Build yt-dlp command
        $downloadCommand = "/opt/homebrew/bin/yt-dlp -f 'bestaudio[ext=m4a]' --extract-audio --audio-format mp3 --audio-quality 0 --download-sections '*00:00-00:{$duration}' -o {$tempFile} {$youtubeUrl}";
        // exec($downloadCommand, $output, $status);

        // Log::debug('Download command:', [$downloadCommand]);
        // Log::debug('yt-dlp output:', $output);
        // Log::debug('yt-dlp status:', [$status]);

        // // Check if the download was successful
        // if ($status !== 0 || !file_exists($tempFile)) {
        //     Log::error('yt-dlp failed to download audio.', ['status' => $status, 'output' => $output]);
        //     throw new \RuntimeException('Audio download failed. Check logs for details.');
        // }

        exec($downloadCommand . " 2>&1", $output, $status);

        // Log::debug('Download command:', [$downloadCommand]);
        // Log::debug('yt-dlp output:', $output);
        // Log::debug('yt-dlp status:', [$status]);

        if ($status !== 0) {
            Log::error('yt-dlp execution failed', [
                'command' => $downloadCommand,
                'output' => $output,
                'status' => $status,
            ]);
            throw new \RuntimeException('yt-dlp command failed');
        }

        if (!file_exists($tempFile)) {
            Log::error('Output file not found', ['path' => $tempFile]);
            throw new \RuntimeException('Output file not found.');
        }

        // Step 2: Move or rename the file to the final audio path
        if (!Storage::move("uploads/temp/" . basename($tempFile), "uploads/audio/" . basename($audioFile))) {
            throw new \RuntimeException('Failed to move audio file to final destination.');
        }

        // Clean up temporary files
        Storage::delete("uploads/temp/" . basename($tempFile));

        // Return path relative to storage
        return "uploads/audio/" . basename($audioFile);
    }

}