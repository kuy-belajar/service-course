<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ImageCourseController;
use App\Http\Controllers\MyCourseController;
use App\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get("mentors", [MentorController::class, "index"]);
Route::get("mentors/{id}", [MentorController::class, "show"]);
Route::post("mentors", [MentorController::class, "store"]);
Route::put("mentors/{id}", [MentorController::class, "update"]);
Route::delete("mentors/{id}", [MentorController::class, "destroy"]);

Route::get("courses", [CourseController::class, "index"]);
Route::get("courses/{id}", [CourseController::class, "show"]);
Route::post("courses", [CourseController::class, "store"]);
Route::put("courses/{id}", [CourseController::class, "update"]);
Route::delete("courses/{id}", [CourseController::class, "destroy"]);

Route::get("chapters", [ChapterController::class, "index"]);
Route::get("chapters/{id}", [ChapterController::class, "show"]);
Route::post("chapters", [ChapterController::class, "store"]);
Route::put("chapters/{id}", [ChapterController::class, "update"]);
Route::delete("chapters/{id}", [ChapterController::class, "destroy"]);

Route::get("lessons", [LessonController::class, "index"]);
Route::get("lessons/{id}", [LessonController::class, "show"]);
Route::post("lessons", [LessonController::class, "store"]);
Route::put("lessons/{id}", [LessonController::class, "update"]);
Route::delete("lessons/{id}", [LessonController::class, "destroy"]);

Route::post("image-courses", [ImageCourseController::class, "store"]);
Route::delete("image-courses/{id}", [ImageCourseController::class, "destroy"]);

Route::get("my-courses", [MyCourseController::class, "index"]);
Route::post("my-courses", [MyCourseController::class, "store"]);

Route::post("reviews", [ReviewController::class, "store"]);
Route::put("reviews", [ReviewController::class, "update"]);
Route::delete("reviews", [ReviewController::class, "destroy"]); 