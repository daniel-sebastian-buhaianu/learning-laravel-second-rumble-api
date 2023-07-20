<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;
use App\WebPages\Rumble\ChannelAboutPage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreChannelRequest;
use App\Helpers\ConversionHelper as Convert;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DB::table('channels')->paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChannelRequest $request, \DOMDocument $doc)
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
    public function show(string $id)
    {
        return Channel::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Channel::destroy($id);
    }
}
