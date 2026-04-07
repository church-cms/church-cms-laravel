<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Models\MediaFile;
use App\Traits\Common;
use Exception;
use Log;

class ImageController extends Controller
{
    use LogActivity;
    use Common;

    public function create()
    {
        return view('admin/mediafiles/image/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'required|image|max:5120',
        ]);

        try {
            $folder = Auth::user()->church_id . '/uploads/images/media';
            $path   = $this->uploadFile($folder, $request->file('image'));

            $image              = new MediaFile;
            $image->church_id   = Auth::user()->church_id;
            $image->media_type  = 'image';
            $image->name        = $request->name;
            $image->description = $request->description;
            $image->type        = 'attach';
            $image->url         = $path;
            $image->created_by  = Auth::id();
            $image->updated_by  = Auth::id();
            $image->save();

            return response()->json(['success' => 'Image uploaded successfully.']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to upload image.'], 500);
        }
    }

    public function listImages()
    {
        $uploaded = MediaFile::where([
            ['church_id', Auth::user()->church_id],
            ['media_type', 'image'],
        ])->get()->map(function ($img) {
            return [
                'id'   => 'media_' . $img->id,
                'name' => $img->name,
                'url'  => $img->UrlPath,
                'path' => $img->url,
            ];
        });

        $defaults = collect([
            ['id' => 'default_1',  'name' => 'Church Building',        'file' => 'church1.jpg'],
            ['id' => 'default_2',  'name' => 'Worship Service',        'file' => 'church2.jpg'],
            ['id' => 'default_3',  'name' => 'Prayer Meeting',         'file' => 'church3.jpg'],
            ['id' => 'default_4',  'name' => 'Sunday Gathering',       'file' => 'church4.jpg'],
            ['id' => 'default_5',  'name' => 'Candles & Prayer',       'file' => 'church5.jpg'],
            ['id' => 'default_6',  'name' => 'Cross at Sunrise',       'file' => 'church6.jpg'],
            ['id' => 'default_7',  'name' => 'Choir Performance',      'file' => 'church7.jpg'],
            ['id' => 'default_8',  'name' => 'Bible Study',            'file' => 'church8.jpg'],
            ['id' => 'default_9',  'name' => 'Community Outreach',     'file' => 'church9.jpg'],
            ['id' => 'default_10', 'name' => 'Youth Ministry',         'file' => 'church10.jpg'],
            ['id' => 'default_11', 'name' => 'Baptism Service',        'file' => 'church11.jpg'],
            ['id' => 'default_12', 'name' => 'Christmas Celebration',  'file' => 'church12.jpg'],
            ['id' => 'default_13', 'name' => 'Easter Service',         'file' => 'church13.jpg'],
            ['id' => 'default_14', 'name' => 'Wedding Ceremony',       'file' => 'church14.jpg'],
            ['id' => 'default_15', 'name' => 'Funeral Service',        'file' => 'church15.jpg'],
            ['id' => 'default_16', 'name' => 'Outdoor Mission',        'file' => 'church16.jpg'],
            ['id' => 'default_17', 'name' => 'Music Worship',          'file' => 'church17.jpg'],
            ['id' => 'default_18', 'name' => 'Children Ministry',      'file' => 'church18.jpg'],
            ['id' => 'default_19', 'name' => 'Evening Cathedral',      'file' => 'church19.jpg'],
            ['id' => 'default_20', 'name' => 'Communion Service',      'file' => 'church20.jpg'],
        ])->map(function ($img) {
            $path = 'uploads/images/defaults/' . $img['file'];
            return [
                'id'   => $img['id'],
                'name' => $img['name'],
                'url'  => \Storage::disk('public')->exists($path)
                            ? \Storage::disk('public')->url($path)
                            : asset('uploads/images/defaults/' . $img['file']),
                'path' => $path,
            ];
        });

        return response()->json(['data' => $uploaded->merge($defaults)->values()]);
    }

    public function destroy($id)
    {
        try {
            $image = MediaFile::where([['id', $id], ['church_id', Auth::user()->church_id]])->firstOrFail();
            $image->delete();
            return response()->json(['success' => 'Image deleted successfully.']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to delete image.'], 500);
        }
    }
}
