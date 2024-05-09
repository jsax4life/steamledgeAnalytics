<?php

namespace App\Http\Controllers;

use App\Models\SteamledgeClasses;
use Illuminate\Http\Request;

class PartnerSchoolController extends Controller
{
    public function index()
    {
         // Retrieve the names of all partner schools
         $schools = SteamledgeClasses::select('PartnerSChool')->distinct()->pluck('PartnerSChool');
         ;

         return response()->json($schools);

    }

    // duration by program
    public function durationByProgram()
    {
        // Retrieve duration by program
        $durationByProgram = SteamledgeClasses::select('Program', \DB::raw('sum(Duration) / 60 as total_duration'))
        ->groupBy('Program')
        ->get();

        return response()->json($durationByProgram);
    }


}
