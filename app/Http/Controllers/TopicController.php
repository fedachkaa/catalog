<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class TopicController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function getTopics()
    {
        $topicsData = [];

        return view('userProfile.common.topics.topics', compact('topicsData'));
    }
}
