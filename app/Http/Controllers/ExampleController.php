<?php

namespace App\Http\Controllers;

use FFMpeg\FFMpeg;
use Illuminate\Http\Request;
use FFMpeg\Format\Video\X264;
use FFMpeg\Coordinate\TimeCode;
use Ayesh\InstagramDownload\InstagramDownload as IG;


class ExampleController extends Controller
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

    public function cut(Request $request)
    {
        $url = $request->input('url');

        try {
            $client = new IG($url);
            $video_url = $client->getDownloadUrl(); // Returns the download URL.
            $id = \explode('/', \parse_url($url)['path'])[2];
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

        $path = storage_path('original_videos/') . "$id.mp4";
        $video = file_get_contents($video_url);
        file_put_contents($path, $video);

        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($path);

        $format = new X264();
        $format->setAudioCodec("aac");

        $export_path = storage_path('videos/') . "$id";
        $clip1 = $video->clip(TimeCode::fromSeconds(0), TimeCode::fromSeconds(15));
        $clip1->save($format, "{$export_path}_1.mp4");
        $clip2 = $video->clip(TimeCode::fromSeconds(15), TimeCode::fromSeconds(15));
        $clip2->save($format, "{$export_path}_2.mp4");
        $clip3 = $video->clip(TimeCode::fromSeconds(30), TimeCode::fromSeconds(15));
        $clip3->save($format, "{$export_path}_3.mp4");
        $clip4 = $video->clip(TimeCode::fromSeconds(45), TimeCode::fromSeconds(15));
        $clip4->save($format, "{$export_path}_4.mp4");

        unlink($path);

        return response()->json([
            'clips' => [
                route('download', ['id' => "{$id}_1.mp4"]),
                route('download', ['id' => "{$id}_2.mp4"]),
                route('download', ['id' => "{$id}_3.mp4"]),
                route('download', ['id' => "{$id}_4.mp4"]),
            ]
        ]);
    }
}
