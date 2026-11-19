<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $query = Slider::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $sliders = $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create-edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'required|integer',
            'media_file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,webm,ogg,mov,qt|max:102400',
        ]);

        $file_type = 'image';
        $mediaPath = null;
        if ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $mime = $file->getMimeType();
            if (strpos($mime, 'video/') !== false) {
                $file_type = 'video';
            }
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/sliders'), $filename);
            $mediaPath = 'uploads/sliders/' . $filename;
        }

        $slider = Slider::create([
            'title' => $request->title,
            'description' => $request->description,
            'sort_order' => $request->sort_order,
            'file_path' => $mediaPath,
            'file_type' => $file_type,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Create Slider',
            'details' => 'Added slider media: "' . ($slider->title ?? 'Untitled') . '" (Type: ' . $slider->file_type . ')'
        ]);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.sliders.create-edit', compact('slider'));
    }

    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'required|integer',
            'media_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,webm,ogg,mov,qt|max:102400',
        ]);

        $mediaPath = $slider->file_path;
        $file_type = $slider->file_type;
        if ($request->hasFile('media_file')) {
            if ($slider->file_path && file_exists(public_path($slider->file_path))) {
                @unlink(public_path($slider->file_path));
            }
            $file = $request->file('media_file');
            $mime = $file->getMimeType();
            if (strpos($mime, 'video/') !== false) {
                $file_type = 'video';
            } else {
                $file_type = 'image';
            }
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/sliders'), $filename);
            $mediaPath = 'uploads/sliders/' . $filename;
        }

        $slider->update([
            'title' => $request->title,
            'description' => $request->description,
            'sort_order' => $request->sort_order,
            'file_path' => $mediaPath,
            'file_type' => $file_type,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Update Slider',
            'details' => 'Updated slider media: "' . ($slider->title ?? 'Untitled') . '"'
        ]);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        
        if ($slider->file_path && file_exists(public_path($slider->file_path))) {
            @unlink(public_path($slider->file_path));
        }

        $title = $slider->title ?? 'Untitled';
        $slider->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Delete Slider',
            'details' => 'Deleted slider media: "' . $title . '"'
        ]);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider berhasil dihapus.');
    }
}
