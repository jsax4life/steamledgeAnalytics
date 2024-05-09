<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        // Retrieve the names of all TrustLead and TrustAssistant separately
        $trustLeads = Quiz::select('thrustlead')->distinct()->pluck('thrustlead');

        // Combine the names and remove duplicates
        // $trustLeads = $trustLeads->unique()->values()->all();

        return response()->json($trustLeads);
    }

    // get Teacher Details
public function thrustLeadDetails($thrustLeadName)
{
    // Retrieve the list of unique partner schools associated with the selected teacher
    $quizNames = Quiz::select('quiz_name')
        ->where('thrustlead', $thrustLeadName)
        ->distinct()
        ->pluck('quiz_name');

    // Retrieve the list of unique contributions associated with the selected teacher
    $users = Quiz::select('user')
        ->where('thrustlead', $thrustLeadName)
        ->distinct()
        ->pluck('user');

    return response()->json([
        'quizes' => $quizNames,
        'users' => $users
    ]);
}

    public function getUserNames()
    {
        // Retrieve the names from the 'user' column
        $userNames = Quiz::select('user')->distinct()->pluck('user');

        return response()->json($userNames);
    }

    public function getQuizNames()
    {
        // Retrieve the names from the 'Quiz_Name' column
        $quizNames = Quiz::select('Quiz_Name')->distinct()->pluck('Quiz_Name');

        return response()->json($quizNames);
    }


    // score by classes

    public function scoresByClass()
    {
        // Retrieve scores by classes
        $scoresByClass = Quiz::select('class', \DB::raw('avg(score) as average_score'))
                            ->groupBy('class')
                            ->get();

        return response()->json($scoresByClass);
    }

    public function getClassEnrollments()
    {
        try {
            // Query the Quiz table to get enrollment data
            $classEnrollments = Quiz::select('class', \DB::raw('COUNT(user) as enrollments'))
                ->groupBy('class')
                ->get();

            // Return the enrollment data as the response
            return response()->json(['success' => true, 'data' => $classEnrollments]);
        } catch (\Exception $e) {
            // Handle errors
            \Log::error('Error fetching class enrollment data: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Internal Server Error'], 500);
        }
    }


    public function filterQuizName($thrustName, $quizName)
    {


        // Retrieve the list of partner schools associated with the selected teacher and the specified contribution
    $user = Quiz::select('user')
        ->where(function ($query) use ($thrustName) {
            $query->where('thrustlead', $thrustName);
                })
        ->where('quiz_name', $quizName)
        ->distinct()
        ->pluck('user');

        return response()->json($user);

    }

}
