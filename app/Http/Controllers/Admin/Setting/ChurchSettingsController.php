<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChurchDetail;

class ChurchSettingsController extends Controller
{
    private function getDetails(): array
    {
        return ChurchDetail::where('church_id', Auth::user()->church_id)
            ->pluck('meta_value', 'meta_key')
            ->map(fn($v) => $v === '-' ? null : $v)
            ->toArray();
    }

    private function saveFields(array $fields, Request $request): void
    {
        $churchId = Auth::user()->church_id;
        foreach ($fields as $key) {
            if ($request->has($key)) {
                ChurchDetail::updateOrCreate(
                    ['church_id' => $churchId, 'meta_key' => $key],
                    ['meta_value' => $request->input($key) ?? '-']
                );
            }
        }
    }

    public function socialMedia()
    {
        $churchdetail = $this->getDetails();
        return view('admin.settings.socialmedia', compact('churchdetail'));
    }

    public function storeSocialMedia(Request $request)
    {
        $this->saveFields(['facebook', 'twitter', 'instagram'], $request);
        return redirect()->back()->with('successmessage', 'Social media links updated.');
    }

    public function contact()
    {
        $churchdetail = $this->getDetails();
        return view('admin.settings.contact', compact('churchdetail'));
    }

    public function storeContact(Request $request)
    {
        $this->saveFields(['phone', 'email', 'website'], $request);
        return redirect()->back()->with('successmessage', 'Contact details updated.');
    }

    public function location()
    {
        $churchdetail = $this->getDetails();
        return view('admin.settings.location', compact('churchdetail'));
    }

    public function storeLocation(Request $request)
    {
        $this->saveFields(['address', 'latitude', 'longitude'], $request);
        return redirect()->back()->with('successmessage', 'Location updated.');
    }
}
