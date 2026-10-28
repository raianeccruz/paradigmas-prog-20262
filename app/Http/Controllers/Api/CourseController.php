<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Http\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(private CourseService $courseService)
    {}

    public function index(Request $request)
    {
        return CourseResource::collection(
            $this->courseService->getWithFilters($request->all())
        );
    }

    public function store(StoreCourseRequest $request)
    {
        $course = $this->courseService->store($request->validated());

        return (new CourseResource($course))->response()->setStatusCode(201);
    }

    public function show(string $id)
    {
        return new CourseResource($this->courseService->show($id));
    }

    public function update(UpdateCourseRequest $request, string $id)
    {
        return new CourseResource(
            $this->courseService->update($request->validated(), $id)
        );
    }

    public function destroy(string $id)
    {
        $this->courseService->destroy($id);

        return response()->noContent();
    }
}
