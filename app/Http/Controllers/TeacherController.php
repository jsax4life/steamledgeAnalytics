<?php

namespace App\Http\Controllers;

use App\Models\SteamledgeClasses;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
{
    // Retrieve the names of all TrustLead and TrustAssistant separately
    $teachers = SteamledgeClasses::select('ThrustLead')->distinct()->pluck('ThrustLead');
    $assistants = SteamledgeClasses::select('ThrustAssistant')->distinct()->pluck('ThrustAssistant');

    // Combine the names and remove duplicates
    $allTeachers = $teachers->merge($assistants)->unique()->values()->all();

    return response()->json($allTeachers);
}


// get Teacher Details
public function teacherDetails($teacherName)
{
    // Retrieve the list of unique partner schools associated with the selected teacher
    $schools = SteamledgeClasses::select('PartnerSChool')
        ->where('ThrustLead', $teacherName)
        ->orWhere('ThrustAssistant', $teacherName)
        ->distinct()
        ->pluck('PartnerSChool');

    // Retrieve the list of unique contributions associated with the selected teacher
    $contributions = SteamledgeClasses::select('contribution')
        ->where('ThrustLead', $teacherName)
        ->orWhere('ThrustAssistant', $teacherName)
        ->distinct()
        ->pluck('contribution');

    return response()->json([
        'schools' => $schools,
        'contributions' => $contributions
    ]);
}


// filter schools for a selected teahcher and contribution
public function filterContributions($teacherName, $contribution)
{
    // Retrieve the list of partner schools associated with the selected teacher and the specified contribution
    $schools = SteamledgeClasses::select('PartnerSChool')
        ->where(function ($query) use ($teacherName) {
            $query->where('ThrustLead', $teacherName)
                  ->orWhere('ThrustAssistant', $teacherName);
        })
        ->where('contribution', $contribution)
        ->distinct()
        ->pluck('PartnerSChool');

    return response()->json($schools);
}


// Get sum of total attendance
public function totalAttendance($teacherName)
{
    // Calculate the sum of total attendance by the selected teacher
    $totalAttendance = SteamledgeClasses::where('ThrustLead', $teacherName)
        ->orWhere('ThrustAssistant', $teacherName)
        ->sum('totalAttendance');

    return response()->json(['totalAttendance' => $totalAttendance]);
}


// sum of total attendance by week
public function totalAttendanceByWeek($teacherName)
{
    // Retrieve the sum of total attendance by week for the selected teacher
    $attendanceByWeek = SteamledgeClasses::select('week', \DB::raw('SUM(totalAttendance) as totalAttendance'))
        ->where('ThrustLead', $teacherName)
        ->orWhere('ThrustAssistant', $teacherName)
        ->groupBy('week')
        ->get();

    return response()->json($attendanceByWeek);
}


public function getAttendees($teacherName)
{
    // Retrieve the attendantList for the selected teacher
    $attendeeList = SteamledgeClasses::where('ThrustLead', $teacherName)
        ->orWhere('ThrustAssistant', $teacherName)
        ->pluck('attendanceList')
        ->toArray();

    // Extract names from the attendantList and remove numbers, class identifiers, HTML tags, and additional characters
    $names = [];
    foreach ($attendeeList as $list) {
        // Remove HTML tags
        $list = strip_tags($list);

        // Remove additional characters (".\t")
        $list = str_replace(".\t", "", $list);

        // Split the attendee list by newline characters
        $attendees = explode("\n", $list);

        // Extract names and remove numbers and class identifiers
        foreach ($attendees as $attendee) {
            $name = trim(preg_replace('/[0-9]+/', '', $attendee));
            if (!empty($name)) {
                $names[] = $name;
            }
        }
    }

    // Return unique names
    $uniqueNames = array_unique($names);

    return response()->json($uniqueNames);
}



}
