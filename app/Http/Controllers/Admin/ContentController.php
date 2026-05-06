<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Traits\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ContentController extends Controller
{
    use ImageHelper;
    public function index()
    {
        $contents = Content::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.content.index', compact('contents'));
    }

    public function create()
    {
        return view('admin.content.create');
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'image'       => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'upload_date' => 'required|date',
            ];

            $messages = [
                'image.required'       => __('validation.required_image'),
                'image.image'          => __('validation.image'),
                'image.max'            => __('validation.max'),
                'upload_date.required' => __('validation.required_upload_date'),
                'upload_date.date'     => __('validation.date_upload_date'),
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $this->compressAndStoreImage($request->file('image'), 'content_images');
            }

            Content::create([
                'user_id'     => auth()->id(),
                'image'       => $imagePath,
                'upload_date' => $request->upload_date,
            ]);

            return redirect()->route('admin.contents.index')
                ->with('success', 'Content added successfully');
        } catch (\Exception $e) {
            Log::error('ContentController@store Error: ' . $e->getMessage());
            return redirect()->route('admin.contents.index')
                ->with('error', 'Content not added');
        }
    }

    public function edit(Request $request, Content $content)
    {
        abort_if($content->user_id !== auth()->id(), 403);
        $page = $request->page;
        return view('admin.content.edit', compact('content', 'page'));
    }

    public function update(Request $request, Content $content)
    {
        abort_if($content->user_id !== auth()->id(), 403);
        try {
            $rules = [
                'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'upload_date' => 'nullable|date',
            ];

            $messages = [
                'image.image'      => __('validation.image'),
                'image.max'        => __('validation.max'),
                'upload_date.date' => __('validation.date_upload_date'),
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = [
                'upload_date' => $request->upload_date,
            ];

            if ($request->hasFile('image')) {
                $this->deleteStorageImage($content->image);
                $data['image'] = $this->compressAndStoreImage($request->file('image'), 'content_images');
            }

            $content->update($data);

            return redirect()->route('admin.contents.index', ['page' => $request->page])
                ->with('success', __('portal.content_updated'));
        } catch (\Throwable $th) {
            Log::error('ContentController@update Error: ' . $th->getMessage());
            return redirect()->route('admin.contents.index')
                ->with('error', $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $contentId = $request->input('content_id');

            // Scope destroy to authenticated user's content
            $content = Content::where('id', $contentId)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $this->deleteStorageImage($content->image);

            $content->delete();

            return redirect()->route('admin.contents.index')
                ->with('success', __('portal.content_deleted'));
        } catch (\Throwable $th) {
            Log::error('ContentController@destroy Error: ' . $th->getMessage());
            return redirect()->route('admin.contents.index')
                ->with('error', $th->getMessage());
        }
    }
}
