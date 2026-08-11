<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Requests\SettingGeneralRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChurchDetail;
use App\Traits\Common;
use Exception;
use Log;

class GeneralController extends Controller
{
    use Common;

    public function create()
    {
        return view('admin.settings.generalsettings');
    }

    public function store(SettingGeneralRequest $request)
    {
        try {
            $churchId = Auth::user()->church_id;

            $this->saveField($churchId, 'church_full_name',  $request->church_full_name);
            $this->saveField($churchId, 'church_short_name', $request->church_short_name ?? '-');

            if ($request->hasFile('church_logo')) {
                $path = $this->uploadFile($churchId . '/church_logo', $request->file('church_logo'));
                $this->saveField($churchId, 'church_logo', $path);
            }

            if ($request->hasFile('favicon')) {
                $path = $this->uploadFile('uploads/settings', $request->file('favicon'));
                $this->saveField($churchId, 'favicon', $path);
            }

            return redirect()->back()->with('successmessage', 'General settings updated.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('errormessage', 'Something went wrong.');
        }
    }

    public function storeSiteIdentity(Request $request)
    {
        $request->validate([
            'sitetitle' => 'required|string|max:255',
            'sitename'  => 'required|string|max:255',
        ]);

        try {
            $churchId = Auth::user()->church_id;
            $this->saveField($churchId, 'sitetitle', $request->sitetitle);
            $this->saveField($churchId, 'sitename',  $request->sitename);
            return redirect()->back()->with('successmessage', 'Site identity updated.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('errormessage', 'Something went wrong.');
        }
    }

    private function saveField(int $churchId, string $key, string $value): void
    {
        ChurchDetail::updateOrCreate(
            ['church_id' => $churchId, 'meta_key' => $key],
            ['meta_value' => $value ?: '-']
        );
    }
}
