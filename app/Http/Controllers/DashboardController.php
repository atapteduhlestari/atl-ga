<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Asset;
use App\Models\AssetChild;
use App\Models\TrnRenewal;
use Illuminate\Http\Request;
use App\Models\TrnMaintenance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\Calendar as ModelsCalendar;

class DashboardController extends Controller
{
    public $calendar, $date, $now, $requestDay;

    public function __construct()
    {
        $this->requestDay = request()->days ?? 30;
        $this->now = now()->addDays($this->requestDay)->format('Y-m-d');
        $this->date = Carbon::createFromDate(request()->date);
        $this->calendar = new ModelsCalendar($this->date);
    }

    public function index()
    {
        $assetTotal = isSuperadmin() ? Asset::count() : Asset::where('sbu_id', userSBU())->count();
        $documentTotal = isSuperadmin() ? AssetChild::count() : AssetChild::where('sbu_id', userSBU())->count();
        $assetCondition = Asset::getCountByCondition(isSuperadmin());
        return view('index', compact('assetCondition', 'assetTotal', 'documentTotal'));
    }

    public function timeline()
    {
        if (isSuperadmin()) {
            $assets = Asset::getAllLastTransaction($this->now)->get();
            $docs = AssetChild::getAllLastTransaction($this->now)->get();
        } else {
            $assets = Asset::getLastTransaction($this->now)->get();
            $docs = AssetChild::getLastTransaction($this->now)->get();
        }

        $data = timelineReminders($assets, $docs);
        if (isSuperadmin()) {
            $trn_maintenance = TrnMaintenance::get();
            $trn_renewal = TrnRenewal::get();
        } else {
            $trn_maintenance = TrnMaintenance::where('sbu_id', userSBU())->get();
            $trn_renewal = TrnRenewal::where('sbu_id', userSBU())->get();
        }

        $calendar = $this->timelineCalendar($this->calendar, $trn_maintenance, $trn_renewal);
        return view('timeline', compact('calendar', 'data'));
    }

    public function timelineCalendar($calendar, $trn_maintenance, $trn_renewal)
    {
        foreach ($trn_maintenance as $maintain) {
            $calendar->add_event(
                $maintain->maintenance->name,
                $maintain->trn_start_date,
                1,
                "/trn-maintenance/{$maintain->id}",
                $maintain->trn_status ? "bg-primary" : '',
            );
        }

        foreach ($trn_renewal as $renew) {
            $calendar->add_event(
                $renew->renewal->name,
                $renew->trn_start_date,
                1,
                "/trn-renewal/{$renew->id}",
                $renew->trn_status ? "bg-primary" : '',
            );
        }
        return $calendar;
    }

    public function report()
    {
        return view('report.index');
    }

    public function group()
    {
        return view('asset.group.index');
    }

    public function formISO()
    {
        $path = public_path('uploads');
        $allFiles = File::allFiles($path);
        $files = collect();

        foreach ($allFiles as $path) {
            $files->push(pathinfo($path));
        }

        return view('asset.forms.index', compact('files'));
    }

    public function downloadFormISO($param)
    {
        $path =  public_path('uploads/forms/') . $param;
        return response()->download($path);
    }

    public function createForm(Request $request)
    {
        $request->validate([
            'url' => 'required',
        ]);

        $data = $request->all();
        $file = $request->file('url');

        $fileName = $file->getClientOriginalName();
        $fileUrl = $file->storeAs('forms', $fileName, 'uploads');
        $data['url'] = $fileUrl;

        DB::table('form')->insert([
            'url' => $data['url'],
        ]);
        return redirect()->back()->with('success', 'Success!');
    }

    public function deleteForm($param)
    {
        $path = public_path('uploads/forms/') . $param;

        if (File::exists($path)) {
            unlink($path);
        }
        return redirect()->back()->with('success', 'Success!');
    }

    public function changePassword()
    {
        return view('auth.user.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $user =  User::find(auth()->user()->id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with("success", "Password changed successfully!");
    }


    public function displayImage($fileName)
    {
        $path = Storage::path('uploads/images/assets/' . $fileName);

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
}
