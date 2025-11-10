<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\Specialization;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|super-admin']);
    }

    public function index()
    {
        $courses = Course::with(['instructor', 'specialization'])->paginate(20);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $specializations = Specialization::all();
        $instructors = User::role(['instructor', 'admin', 'super-admin'])->get();
        return view('admin.courses.create', compact('specializations', 'instructors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'specialization_id' => 'required|exists:specializations,id',
            'instructor_id' => 'required|exists:users,id',
            'duration_hours' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'level' => 'nullable|in:beginner,intermediate,advanced',
            'language' => 'nullable|string|max:50',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'specialization_id' => $request->specialization_id,
            'instructor_id' => $request->instructor_id,
            'duration_hours' => $request->duration_hours,
            'price' => $request->price ?? 0,
            'discounted_price' => $request->discounted_price,
            'level' => $request->level,
            'language' => $request->language,
            'is_published' => $request->has('is_published') ? true : false,
            'is_featured' => $request->has('is_featured') ? true : false,
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'تم إنشاء الكورس بنجاح');
    }

    public function show(Course $course)
    {
        $course->load(['instructor', 'specialization', 'lessons', 'enrolledStudents']);
        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $specializations = Specialization::all();
        $instructors = User::role(['instructor', 'admin', 'super-admin'])->get();
        return view('admin.courses.edit', compact('course', 'specializations', 'instructors'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'specialization_id' => 'required|exists:specializations,id',
            'instructor_id' => 'required|exists:users,id',
            'duration_hours' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'level' => 'nullable|in:beginner,intermediate,advanced',
            'language' => 'nullable|string|max:50',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $course->update([
            'title' => $request->title,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'specialization_id' => $request->specialization_id,
            'instructor_id' => $request->instructor_id,
            'duration_hours' => $request->duration_hours,
            'price' => $request->price ?? 0,
            'discounted_price' => $request->discounted_price,
            'level' => $request->level,
            'language' => $request->language,
            'is_published' => $request->has('is_published') ? true : false,
            'is_featured' => $request->has('is_featured') ? true : false,
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'تم تحديث الكورس بنجاح');
    }

    public function approve(Course $course)
    {
        $course->update(['is_published' => true]);
        return redirect()->back()->with('success', 'تم الموافقة على الكورس بنجاح');
    }

    public function reject(Course $course)
    {
        $course->update(['is_published' => false]);
        return redirect()->back()->with('success', 'تم رفض الكورس بنجاح');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'تم حذف الكورس بنجاح');
    }
}