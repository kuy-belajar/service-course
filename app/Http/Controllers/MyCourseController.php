<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\MyCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MyCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $myCourses = MyCourse::query()->with("course");

        $userId = $request->query("user_id");

        $myCourses->when($userId, function($query) use ($userId) {
            return $query->where("user_id", "=", $userId);
        });

        return response()->json([
                "status" => "success",
                "data" => $myCourses->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            "course_id" => "required|integer",
            "user_id" => "required|integer",
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()
            ], 400);
        }

        $courseId = $request->input("course_id");
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json([
                "status" => "error",
                "message" => "course not found"
            ], 404);
        }

        $userId = $request->input("user_id");
        $user = getUser($userId);

        if ($user["status"] === "error") {
            return response()->json([
                "status" => $user["status"],
                "message" => $user["message"]
            ]);
        }

        $isMyCourseExist = MyCourse::where("course_id", "=", $courseId)
                            ->where("user_id", "=", $userId)
                            ->exist();

        if ($isMyCourseExist){
            return response()->json([
                    "status" => "success",
                    "message" => "user already take the course"
            ], 409);
        }

        $myCourse = MyCourse::create($data);

        return response()->json([
                "status" => "success",
                "data" => $myCourse
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MyCourse  $myCourse
     * @return \Illuminate\Http\Response
     */
    public function show(MyCourse $myCourse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MyCourse  $myCourse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MyCourse $myCourse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MyCourse  $myCourse
     * @return \Illuminate\Http\Response
     */
    public function destroy(MyCourse $myCourse)
    {
        //
    }

    /**
     * Create premium access to user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createPremiumAccess(Request $request)
    {
        $data = $request->all();
        $myCourse = MyCourse::create($data);

        return response()->json([
            "status" => "success",
            "data" => $myCourse
        ]);
    }
}