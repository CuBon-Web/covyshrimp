<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Solution;

class SolutionController extends Controller
{
    public function detail($slug)
    {
        $data['solution'] = Solution::where('slug',$slug)->first();
        return view('solution',$data);
    }
}
