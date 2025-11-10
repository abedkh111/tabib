<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Specialization;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:instructor|admin|super-admin']);
    }

    public function index()
    {
        $courses = Course::where('instructor_id', auth()->id())
            ->with('specialization')
            ->paginate(20);
        return view('instructor.courses.index', compact('courses'));
    }

    public function create()
    {
        $specializations = Specialization::all();
        return view('instructor.courses.create', compact('specializations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'specialization_id' => 'required|exists:specializations,id',
            'duration_hours' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
        ]);

        Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'specialization_id' => $request->specialization_id,
            'instructor_id' => auth()->id(),
            'duration_hours' => $request->duration_hours,
            'price' => $request->price,
            'is_published' => false,
        ]);

        return redirect()->route('instructor.courses.index')->with('success', 'تم إنشاء الكورس بنجاح');
    }

    public function show(Course $course)
    {
        $this->authorize('view', $course);
        $course->load(['specialization', 'lessons', 'enrolledStudents']);
        return view('instructor.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        $specializations = Specialization::all();
        return view('instructor.courses.edit', compact('course', 'specializations'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'specialization_id' => 'required|exists:specializations,id',
            'duration_hours' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
        ]);

        $course->update($request->only([
            'title', 'description', 'specialization_id', 'duration_hours', 'price'
        ]));

        return redirect()->route('instructor.courses.index')->with('success', 'تم تحديث الكورس بنجاح');
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        $course->delete();
        return redirect()->route('instructor.courses.index')->with('success', 'تم حذف الكورس بنجاح');
    }

    public function publish(Course $course)
    {
        $this->authorize('update', $course);
        $course->update(['is_published' => true]);
        return redirect()->back()->with('success', 'تم نشر الكورس بنجاح');
    }

    public function unpublish(Course $course)
    {
        $this->authorize('update', $course);
        $course->update(['is_published' => false]);
        return redirect()->back()->with('success', 'تم إلغاء نشر الكورس بنجاح');
    }
}