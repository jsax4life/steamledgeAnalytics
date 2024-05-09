<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\TeacherController;
use App\Http\Controllers\PartnerSchoolController;
use App\Http\Controllers\QuizController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

    Route::group(['middleware' => 'cors','prefix' => 'analytics'], function () {

    Route::get('/trustleads', [TeacherController::class, 'index']);
    Route::get('quiz/trustleads', [QuizController::class, 'index']);
    Route::get('/quiz/users', [QuizController::class, 'getUserNames']);
    Route::get('/quiz/names', [QuizController::class, 'getQuizNames']);
    Route::get('quiz/thrustlead/{thrustleadName}/details', [QuizController::class, 'thrustLeadDetails']);
    Route::get('/quiz/scores-by-class', [QuizController::class, 'scoresByClass']);
    Route::get('/quiz/class-enrollment', [QuizController::class, 'getClassEnrollments']);
    Route::get('/quiz/thrustlead/{thrustLead}/quizname/{quiizName}', [QuizController::class, 'filterQuizName']);




    Route::get('/partnerschools', [PartnerSchoolController::class, 'index']);
    Route::get('/teacher/{teacherName}/details', [TeacherController::class, 'teacherDetails']);
    Route::get('/teacher/{teacherName}/contributions/{contribution}', [TeacherController::class, 'filterContributions']);
    Route::get('/teacher/{teacherName}/total-attendance', [TeacherController::class, 'totalAttendance']);
    Route::get('/teacher/{teacherName}/total-attendance-by-week', [TeacherController::class, 'totalAttendanceByWeek']);
    Route::get('/teacher/{teacherName}/attendees', [TeacherController::class, 'getAttendees']);
    Route::get('/duration-by-program', [PartnerSchoolController::class, 'durationByProgram']);
    // Route::get('/class-enrollment', [PartnerSchoolController::class, 'getClassEnrollments']);

    });


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
