<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specialization;

class SpecializationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|super-admin']);
    }

    public function index()
    {
        $specializations = Specialization::paginate(20);
        return view('admin.specializations.index', compact('specializations'));
    }

    public function create()
    {
        return view('admin.specializations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string|max:255|unique:specializations',
            'name_ar' => 'required|string|max:255|unique:specializations',
            'description' => 'nullable|string',
        ]);

        Specialization::create($request->all());
        return redirect()->route('admin.specializations.index')->with('success', 'تم إنشاء التخصص بنجاح');
    }

    public function show(Specialization $specialization)
    {
        $specialization->load(['courses', 'questions']);
        return view('admin.specializations.show', compact('specialization'));
    }

    public function edit(Specialization $specialization)
    {
        return view('admin.specializations.edit', compact('specialization'));
    }

    public function update(Request $request, Specialization $specialization)
    {
        $request->validate([
            'name_en' => 'required|string|max:255|unique:specializations,name_en,' . $specialization->id,
            'name_ar' => 'required|string|max:255|unique:specializations,name_ar,' . $specialization->id,
            'description' => 'nullable|string',
        ]);

        $specialization->update($request->all());
        return redirect()->route('admin.specializations.index')->with('success', 'تم تحديث التخصص بنجاح');
    }

    public function destroy(Specialization $specialization)
    {
        // Check if specialization has courses or questions
        if ($specialization->courses()->count() > 0 || $specialization->questions()->count() > 0) {
            return redirect()->back()->with('error', 'لا يمكن حذف التخصص لأنه مرتبط بكورسات أو أسئلة');
        }

        $specialization->delete();
        return redirect()->route('admin.specializations.index')->with('success', 'تم حذف التخصص بنجاح');
    }
}