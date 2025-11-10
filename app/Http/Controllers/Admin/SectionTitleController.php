<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionTitle;

class SectionTitleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|super-admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titles = SectionTitle::orderBy('sort_order')->paginate(20);
        return view('admin.section-titles.index', compact('titles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.section-titles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|max:255|unique:section_titles',
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        SectionTitle::create($request->all());
        
        return redirect()->route('admin.section-titles.index')->with('success', 'تم إنشاء العنوان بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(SectionTitle $sectionTitle)
    {
        return view('admin.section-titles.show', compact('sectionTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SectionTitle $sectionTitle)
    {
        return view('admin.section-titles.edit', compact('sectionTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SectionTitle $sectionTitle)
    {
        $request->validate([
            'key' => 'required|string|max:255|unique:section_titles,key,' . $sectionTitle->id,
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $sectionTitle->update($request->all());
        
        return redirect()->route('admin.section-titles.index')->with('success', 'تم تحديث العنوان بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SectionTitle $sectionTitle)
    {
        $sectionTitle->delete();
        return redirect()->route('admin.section-titles.index')->with('success', 'تم حذف العنوان بنجاح');
    }
}
