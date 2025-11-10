<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResearchAndNews;

class ResearchAndNewsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|super-admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ResearchAndNews::with('creator');
        
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title_ar', 'like', '%' . $request->search . '%')
                  ->orWhere('title_en', 'like', '%' . $request->search . '%')
                  ->orWhere('content_ar', 'like', '%' . $request->search . '%');
            });
        }
        
        $items = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.research-and-news.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.research-and-news.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:medical_research,scientific_news,medical_news',
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'content_ar' => 'required|string',
            'content_en' => 'nullable|string',
            'summary_ar' => 'nullable|string',
            'summary_en' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'source' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'published_date' => 'nullable|date',
            'tags' => 'nullable|string',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->id();
        
        if ($request->has('tags') && is_string($request->tags)) {
            $data['tags'] = array_filter(array_map('trim', explode(',', $request->tags)));
        }
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('research-news', 'public');
            $data['image'] = $imagePath;
        }
        
        ResearchAndNews::create($data);
        
        return redirect()->route('admin.research-and-news.index')->with('success', 'تم إنشاء المقال بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(ResearchAndNews $researchAndNews)
    {
        $researchAndNews->load('creator');
        $researchAndNews->increment('views_count');
        return view('admin.research-and-news.show', compact('researchAndNews'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ResearchAndNews $researchAndNews)
    {
        return view('admin.research-and-news.edit', compact('researchAndNews'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResearchAndNews $researchAndNews)
    {
        $request->validate([
            'type' => 'required|in:medical_research,scientific_news,medical_news',
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'content_ar' => 'required|string',
            'content_en' => 'nullable|string',
            'summary_ar' => 'nullable|string',
            'summary_en' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'source' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'published_date' => 'nullable|date',
            'tags' => 'nullable|string',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data = $request->all();
        
        if ($request->has('tags') && is_string($request->tags)) {
            $data['tags'] = array_filter(array_map('trim', explode(',', $request->tags)));
        }
        
        if ($request->hasFile('image')) {
            if ($researchAndNews->image) {
                \Storage::disk('public')->delete($researchAndNews->image);
            }
            $imagePath = $request->file('image')->store('research-news', 'public');
            $data['image'] = $imagePath;
        }
        
        $researchAndNews->update($data);
        
        return redirect()->route('admin.research-and-news.index')->with('success', 'تم تحديث المقال بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResearchAndNews $researchAndNews)
    {
        if ($researchAndNews->image) {
            \Storage::disk('public')->delete($researchAndNews->image);
        }
        $researchAndNews->delete();
        return redirect()->route('admin.research-and-news.index')->with('success', 'تم حذف المقال بنجاح');
    }
}
