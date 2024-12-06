<?php

namespace App\Http\Controllers;

use App\Services\YoutubeService;
use Illuminate\Http\Request;

class YoutubeController extends Controller
{
    private $youtubeService;

    public function __construct(YoutubeService $youtubeService)
    {
        $this->youtubeService = $youtubeService;
    }

    public function process(Request $request)
    {
        $request->validate([
            'youtube_url' => 'required|url',
            'duration' => 'nullable|integer|min:1|max:300'
        ]);

        $duration = $request->input('duration', 30);
        $result = $this->youtubeService->downloadAndConvertToAudio($request->youtube_url);

        if (!$result['success']) {
            return response()->json(['error' => 'Processing failed'], 422);
        }

        return response()->json([
            'message' => 'Processing completed successfully',
            'data' => $result
        ]);
    }
}
