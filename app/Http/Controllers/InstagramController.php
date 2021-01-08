<?php

namespace App\Http\Controllers;

use FFMpeg\FFMpeg;
use Illuminate\Http\Request;
use FFMpeg\Format\Video\X264;
use FFMpeg\Coordinate\TimeCode;
use Ayesh\InstagramDownload\InstagramDownload as IG;


class InstagramController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getDownloadLink(Request $request)
    {
        $url = $request->input('url');
        $client = new IG($url);

        return response()->json([
            'download' => $client->getDownloadUrl()
        ]);
    }

    public function cut(Request $request)
    {
        $url = $request->input('url');
        $clip = $request->input('clip');

        $id = \explode('/', \parse_url($url)['path'])[2];
        $path = storage_path('original_videos/') . "$id.mp4";

        if (!file_exists($path)) {
            try {
                $client = new IG($url);
                $video_url = $client->getDownloadUrl(); // Returns the download URL.
            }
            catch (\InvalidArgumentException $exception) {
                /*
                * \InvalidArgumentException exceptions will be thrown if there is a validation 
                * error in the URL. You might want to break the code flow and report the error 
                * to your form handler at this point.
                */
                $error = $exception->getMessage();
            }
            catch (\RuntimeException $exception) {
                /*
                * \RuntimeException exceptions will be thrown if the URL could not be 
                * fetched, parsed, or a media could not be extracted from the URL. 
                */
                $error = $exception->getMessage();
            }
    
            $video = file_get_contents($video_url);
            file_put_contents($path, $video);
        }

        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($path);

        $format = new X264();
        $format->setAudioCodec("aac");

        $export_path = storage_path('videos/') . "$id";
        $clip1 = $video->clip(TimeCode::fromSeconds($clip * 15), TimeCode::fromSeconds(15));
        $clip1->save($format, "{$export_path}_{$clip}.mp4");

        if ($clip == 4) {
            unlink($path);
        }

        return response()->json([
            'clip' => route('download', ['id' => "{$id}_{$clip}.mp4"]),
        ]);
    }
}
