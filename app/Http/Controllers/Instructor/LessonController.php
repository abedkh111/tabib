<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Course;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:instructor|admin|super-admin']);
    }

    public function index(Course $course)
    {
        $this->authorize('view', $course);
        $lessons = $course->lessons()->orderBy('order')->get();
        return view('instructor.courses.lessons.index', compact('course', 'lessons'));
    }

    public function create(Course $course)
    {
        $this->authorize('update', $course);
        return view('instructor.courses.lessons.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'video_url' => 'nullable|url',
            'order' => 'nullable|integer|min:1',
        ]);

        $maxOrder = $course->lessons()->max('order') ?? 0;

        Lesson::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'content' => $request->content,
            'video_url' => $request->video_url,
            'order' => $request->order ?? $maxOrder + 1,
        ]);

        return redirect()->route('instructor.courses.lessons.index', $course)->with('success', 'تم إنشاء الدرس بنجاح');
    }

    public function show(Course $course, Lesson $lesson)
    {
        $this->authorize('view', $course);
        return view('instructor.courses.lessons.show', compact('course', 'lesson'));
    }

    public function edit(Course $course, Lesson $lesson)
    {
        $this->authorize('update', $course);
        return view('instructor.courses.lessons.edit', compact('course', 'lesson'));
    }

    public function update(Request $request, Course $course, Lesson $lesson)
    {
        $this->authorize('update', $course);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'video_url' => 'nullable|url',
            'order' => 'nullable|integer|min:1',
        ]);

        $lesson->update($request->only([
            'title', 'content', 'video_url', 'order'
        ]));

        return redirect()->route('instructor.courses.lessons.index', $course)->with('success', 'تم تحديث الدرس بنجاح');
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        $this->authorize('update', $course);
        $lesson->delete();
        return redirect()->route('instructor.courses.lessons.index', $course)->with('success', 'تم حذف الدرس بنجاح');
    }
}