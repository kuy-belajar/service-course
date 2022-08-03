<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Mentor;
use App\Models\Review;
use App\Models\Chapter;
use App\Models\MyCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $courses = Course::query();

        $q = $request->query("q");
        $status = $request->query("status");

        $courses->when($q, function($query) use ($q) {
            return $query->whereRaw("name LIKE %".strtolower($q)."%");
        });

        $courses->when($status, function($query) use ($status) {
            return $query->where("status", "=", $status);
        });

        return response()->json([
                "status" => "success",
                "data" => $courses->paginate(15)
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
            "name" => "string",
            "description" => "string",
            "thumbnail" => "string|url",
            "type" => "in:free,premium",
            "status" => "in:draft,published",
            "price" => "integer",
            "level" => "in:all-level,beginner, intermediate, advance",
            "certificate" => "url",
            "mentor_id" => "integer"
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()
            ], 400);
        }

        $mentorId = $request->input("mentor_id");
        $mentor = Mentor::find($mentorId);

        if (!$mentor) {
            return response()->json([
                "status" => "error",
                "message" => "mentor not found"
            ], 404);
        }

        $course = Course::create($data);

        return response()->json([
                "status" => "success",
                "data" => $course
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Course::with("chapters.lessons")
                        ->with("mentor")
                        ->with("images")
                        ->find($id);

        if (!$course) {
            return response()->json([
                    "status" => "error",
                    "message" => "course not found"
            ], 404);
        }

        $reviews = Review::where("course_id", "=", $id)->get()->toArray();

        if (count($reviews) > 0) {
            $userIds = array_column($reviews, "user_id");
            $users = getUserByIds($userIds);

            if ($users["statues"] === "error") {
                $reviews = [];
            } else {
                foreach ($reviews as $key => $review) {
                   $userIndex = array_search($review["user_id"], array_column($users["data"], "id"));
                   $reviews[$key]["users"] = $users["data"][$userIndex];
                }
            }
        }

        $totalStudent = MyCourse::where("course_id", "=", $id)->count();
        $totalVideos = Chapter::where("course_id", "=", $id)->withCount("lessons")->get()->toArray();
        $totalVideosFinal = array_column($totalVideos, "lessons_count");

        $course["reviews"] = $reviews;
        $course["total_students"] = $totalStudent;
        $course["total_videos"] = $totalVideosFinal;

        return response()->json([
                "status" => "success",
                "data" => $course
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            "name" => "required|string",
            "description" => "string",
            "thumbnail" => "string|url",
            "type" => "required|in:free,premium",
            "status" => "required|in:draft,published",
            "price" => "integer",
            "level" => "required|in:all-level,beginner, intermediate, advance",
            "certificate" => "required|url",
            "mentor_id" => "required|integer"
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()
            ], 400);
        }

        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                "status" => "error",
                "message" => "course not found"
            ], 404);
        }

        $mentorId = $request->input("mentor_id");

        if ($mentorId) {
            $mentor = Mentor::find($mentorId);
            if (!$mentor) {
                return response()->json([
                    "status" => "error",
                    "message" => "mentor not found"
                ], 404);
            }
        }

        $course->fill($data);
        $course->save();

        return response()->json([
                "status" => "success",
                "data" => $course
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                "status" => "error",
                "message" => "course not found"
            ], 404);
        }

        $course->delete();

        return response()->json([
                "status" => "success",
                "message" => "course deleted"
        ]);
    }
}