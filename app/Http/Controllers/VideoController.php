<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Video;
use App\Models\Channel;
use Illuminate\Http\Request;
use App\WebPages\Rumble\VideoPage;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Helpers\ConversionHelper as Convert;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Video::paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVideoRequest $request)
    {
        $video = new VideoPage($request->input('url'));

        $channel = Channel::where('name', $video->channelName())->first();

        if ($channel === null) {
            return response([
                'message' => 'This video belongs to a channel which doesn\'t exist.'
            ], 422);
        }

        try {
            $tagIds = array_map(function ($tagName) {
                $tag = Tag::where('name', $tagName)->first();

                return ($tag === null) ? Tag::create(['name' => $tagName])->id : $tag->id;
            }, $video->tags());

            $video = Video::create([
                'id' => $video->id(),
                'channel_id' => $channel->id,
                'url' => $video->url(),
                'src' => $video->src(),
                'name' => $video->name(),
                'thumbnail' => $video->thumbnail(),
                'description' => $video->description(),
                'likes_count' => Convert::countStringToInt($video->likes()),
                'dislikes_count' => Convert::countStringToInt($video->dislikes()),
                'comments_count' => Convert::countStringToInt($video->commentsCount()),
                'views_count' => $video->views(),
                'uploaded_at' => Convert::dateStringToMySQLDate(Convert::ISO8601ToDateString($video->uploadDate()))
            ]);

            if (!empty($tagIds)) {
                $video->tags()->attach($tagIds);
            }

            return $video;

        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $video = Video::find($id);

        $this->authorize('view', $video);

        return $video;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVideoRequest $request, string $id)
    {
        $video = Video::find($id);

        $this->authorize('update', $video);

        try {
            $video->update([
                'src' => $request->input('src', $video->src),
                'name' => $request->input('name', $video->name),
                'thumbnail' => $request->input('thumbnail', $video->thumbnail),
                'description' => $request->input('description', $video->description),
                'likes_count' => $request->input('likes_count', $video->likes_count),
                'dislikes_count' => $request->input('dislikes_count', $video->dislikes_count),
                'comments_count' => $request->input('comments_count', $video->comments_count),
                'views_count' => $request->input('views_count', $video->views_count)
            ]);
        } catch(\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], 500);
        }

        return $video;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $video = Video::find($id);

        $this->authorize('delete', $video);

        return Video::destroy($id);
    }
}
