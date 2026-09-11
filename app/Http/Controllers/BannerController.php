<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\S3Helper;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->paginate(10);
        return view('banners.index', compact('banners'));
    }

    public function create()
    {
        return view('banners.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $banner = Banner::create($data);
        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $filename = 'banner-' . $banner->id . '.' . $image->getClientOriginalExtension();

            $imagePath = S3Helper::storeAs(
                $image,
                'admin/banner/desktop',
                $filename
            );

            $banner->update([
                'image' => $imagePath
            ]);
        }

        if ($request->hasFile('mobile_image')) {

            $mobile = $request->file('mobile_image');

            $filename = 'banner-' . $banner->id . '-mobile.' . $mobile->getClientOriginalExtension();

            $mobilePath = S3Helper::storeAs(
                $mobile,
                'admin/banner/mobile',
                $filename
            );

            $banner->update([
                'mobile_image' => $mobilePath
            ]);
        }

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        return view('banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $this->validatedData($request, true);

        if ($request->hasFile('image')) {

            if ($banner->getRawOriginal('image')) {
                S3Helper::delete($banner->getRawOriginal('image'));
            }

            $file = $request->file('image');

            $filename = 'banner-' . $banner->id . '.' . $file->getClientOriginalExtension();

            $data['image'] = S3Helper::storeAs(
                $file,
                'admin/banner/desktop',
                $filename
            );
        }

        if ($request->hasFile('mobile_image')) {

            if ($banner->getRawOriginal('mobile_image')) {
                S3Helper::delete($banner->getRawOriginal('mobile_image'));
            }

            $file = $request->file('mobile_image');

            $filename = 'banner-' . $banner->id . '-mobile.' . $file->getClientOriginalExtension();

            $data['mobile_image'] = S3Helper::storeAs(
                $file,
                'admin/banner/mobile',
                $filename
            );
        }

        $banner->update($data);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        $this->deleteImage($banner->getRawOriginal('image'));
        $this->deleteImage($banner->getRawOriginal('mobile_image'));

        $banner->delete();

        return back()->with('success', 'Banner deleted successfully.');
    }

    private function validatedData(Request $request, bool $isUpdate = false): array
    {
        return $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => $isUpdate
                ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
                : 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'mobile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
            'page' => 'required|in:home,category,product',
            'position' => 'required|in:hero,mid,bottom',
            'layout' => 'required|in:right,left,center',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'text_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ]);
    }

    private function deleteImage(?string $path): void
    {
        if ($path) {
            S3Helper::delete($path);
        }
    }

    public function show(Banner $banner)
    {
        return view('banners.show', compact('banner'));
    }
}