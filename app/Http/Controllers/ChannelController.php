<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\WebPages\Rumble\ChannelAboutPage;
use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Helpers\ConversionHelper as Convert;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Channel::paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChannelRequest $request)
    {
        $channel = new ChannelAboutPage(request('url'));

        try {
            return Channel::create([
                'id' => $channel->id(),
                'url' => request('url'),
                'name' => $channel->name(),
                'description' => $channel->description(),
                'banner' => $channel->banner(),
                'avatar' => $channel->avatar(),
                'followers_count' => Convert::countStringToInt($channel->followersCount()),
                'videos_count' => Convert::countStringToInt($channel->videosCount()),
                'joined_at' => Convert::dateStringToMySQLDate($channel->joiningDate())
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Channel $channel)
    {
        return $channel;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChannelRequest $request, Channel $channel)
    {
        $request->validate([
            'name' => [Rule::unique('channels')->ignore($channel)]
        ]);

        try {
            $channel->update([
                'name' => $request->input('name', $channel->name),
                'description' => $request->input('description', $channel->description),
                'banner' => $request->input('banner', $channel->banner),
                'avatar' => $request->input('avatar', $channel->avatar),
                'followers_count' => $request->input('followers_count', $channel->followers_count),
                'videos_count' => $request->input('videos_count', $channel->videos_count)
            ]);
        } catch(\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], 500);
        }

        return $channel;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Channel $channel)
    {
        return $channel->delete();
    }
}
