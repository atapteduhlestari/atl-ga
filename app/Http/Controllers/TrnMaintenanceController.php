<?php

namespace App\Http\Controllers;

use App\Models\SBU;
use App\Models\Asset;
use App\Models\Employee;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use App\Models\TrnMaintenance;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\TrnMaintenanceRequest;
use App\Exports\MaintenanceExportDetailView;
use App\Exports\MaintenanceExportSummaryView;
use App\Exports\MaintenanceExportPlanView;

class TrnMaintenanceController extends Controller
{

    public function index()
    {
        $data = request()->all();
        if (isSuperadmin())
            $trnMaintenances = TrnMaintenance::search($data)->orderBy('trn_date', 'asc')->paginate(10);
        else
            $trnMaintenances = TrnMaintenance::search($data)->where('sbu_id', userSBU())->orderBy('trn_date', 'asc')->paginate(10);

        $maintenances = Maintenance::get();
        $assets = Asset::with('sbu')->orderBy('asset_name', 'asc')->where('condition', '!=', 4)->get();
        $employees = Employee::orderBy('name', 'asc')->get();
        $SBUs = SBU::orderBy('sbu_name', 'asc')->get();

        return view('transaction.maintenance.index', compact(
            'trnMaintenances',
            'maintenances',
            'assets',
            'employees',
            'SBUs'
        ));
    }

    public function create(Request $request)
    {
        $asset = Asset::findOrFail($request->id);
        $this->authorize('update', $asset);

        if ($asset->condition == 4) {
            return redirect()->back()->with('fail', 'Asset is Disposed !');
        }

        $maintenances = Maintenance::orderBy('name', 'asc')->get();
        $employees = Employee::orderBy('name', 'asc')->get();
        $SBUs = SBU::orderBy('sbu_name', 'asc')->get();

        return view('transaction.maintenance.create', compact(
            'asset',
            'maintenances',
            'employees',
            'SBUs'
        ));
    }

    public function store(TrnMaintenanceRequest $request)
    {
        $data = $this->storeTrnData($request->all());

        if ($request->file('file')) {
            $file = $request->file('file');
            $extension = $file->extension();
            $fileUrl = $file->storeAs('uploads/files/transactions/maintenance', formatTimeDoc($data['trn_no'], $extension));
            $data['file'] = $fileUrl;
        }

        TrnMaintenance::create($data);
        return redirect()->back()->with('success', 'Successfully deleted!');
    }

    public function storeTrnData($data)
    {
        $date = createDate($data['trn_date']);
        $count = TrnMaintenance::whereMonth('trn_date', $date->month)
            ->whereYear('trn_date', $date->year)
            ->count();

        $data['user_id'] = auth()->user()->id;
        $data['trn_value_plan'] = removeDots($data['trn_value_plan']);
        $data['trn_value'] = removeDots($data['trn_value']);
        $data['trn_no'] = setNoTrn($data['trn_date'], $count ?? null, 'MAI');

        return $data;
    }

    public function show(TrnMaintenance $trnMaintenance)
    {
        $this->authorize('view', $trnMaintenance);
        return view('transaction.maintenance.show', compact('trnMaintenance'));
    }

    public function edit(TrnMaintenance $trnMaintenance)
    {
        $this->authorize('update', $trnMaintenance);
        $maintenances = Maintenance::get();
        $employees = Employee::orderBy('name', 'asc')->get();
        $SBUs = SBU::orderBy('sbu_name', 'asc')->get();

        return view('transaction.maintenance.edit', compact(
            'maintenances',
            'trnMaintenance',
            'employees',
            'SBUs'
        ));
    }

    public function update(TrnMaintenanceRequest $request, TrnMaintenance $trnMaintenance)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $data['trn_value_plan'] = removeDots($data['trn_value_plan']);
        $data['trn_value'] = removeDots($data['trn_value']);

        if ($request->file('file')) {
            Storage::delete($trnMaintenance->file);
            $file = $request->file('file');
            $extension = $file->extension();
            $fileUrl = $file->storeAs('uploads/files/transactions/maintenance',  formatTimeDoc($trnMaintenance->trn_no, $extension));
            $data['file'] = $fileUrl;
        } else {
            $data['file'] = $trnMaintenance->file;
        }

        $trnMaintenance->update($data);
        return redirect("/trn-maintenance/" . $trnMaintenance->id)->with('success', 'Successfully updated!');
    }

    public function destroy(TrnMaintenance $trnMaintenance)
    {
        if ($trnMaintenance->trn_status)
            return redirect()->back()->with('warning', 'Status is <b>CLOSED!</b>');

        Storage::delete($trnMaintenance->file);
        $trnMaintenance->delete();
        return redirect('/trn-maintenance')->with('success', 'Successfully deleted!');
    }

    public function search(Request $request)
    {
        $data = TrnMaintenance::search($request)->orderBy('trn_date', 'desc')->get();
        return $data;
    }

    public function updateStatus(TrnMaintenance $trnMaintenance)
    {
        if (!$trnMaintenance->file)
            return redirect()->back()->with('warning', 'Upload file proven!');

        $trnMaintenance->update([
            'trn_status' => 1
        ]);
        return redirect()->back()->with('success', 'Successfully deleted!');
    }

    public function updateStatusPlan(TrnMaintenance $trnMaintenance)
    {
        if (!$trnMaintenance->file)
            return redirect()->back()->with('warning', 'Upload file proven!');

        request()->validate([
            'trn_start_date' => 'required',
            'trn_date' => 'required',
            'trn_value_plan' => 'required'
        ]);

        $trnMaintenance->update([
            'trn_status' => 1
        ]);

        $data = $this->setPlanData(request()->all(), $trnMaintenance);
        TrnMaintenance::create($data);

        return redirect()->back()->with('success', 'Successfully deleted!');
    }

    public function setPlanData($request, $trn)
    {
        $date = createDate($request['trn_date']);
        $count = TrnMaintenance::whereMonth('trn_date', $date->month)
            ->whereYear('trn_date', $date->year)
            ->count();
        $no = setNoTrn($request['trn_date'], $count ?? null, 'MAI');

        $trn->trn_no = $no;
        $trn->trn_start_date = $request['trn_start_date'];
        $trn->trn_date  = $request['trn_date'];
        $trn->pemohon  = null;
        $trn->penyetuju  = null;
        $trn->trn_value_plan  = removeDots($request['trn_value_plan']);
        $trn->trn_value  = null;
        $trn->trn_desc = '<span class="text-info font-weight-bold">(PLAN)</span> ' . $trn->trn_desc;
        $trn->file  = null;
        $trn->trn_status = 0;

        return $trn->toArray();
    }

    public function download(TrnMaintenance $trnMaintenance)
    {
        $path = public_path() . $trnMaintenance->takeDoc;
        return response()->download($path);
    }

    public function detailView()
    {
        $SBUs = SBU::orderBy('sbu_name', 'asc')->get();
        return view('report.detail.maintenance', compact('SBUs'));
    }

    public function summaryView()
    {
        $SBUs = SBU::orderBy('sbu_name', 'asc')->get();
        return view('report.summary.maintenance', compact('SBUs'));
    }

    public function reportDetail()
    {
        // request()->validate([
        //     'start' => 'required',
        //     'end' => 'required'
        // ]);

        if (request('start') > request('end'))
            return redirect()->back()->with('warning', 'Start date must be lower than End date');

        $data['request'] = request()->all();

        if (isSuperadmin())
            $data['transactions'] =  TrnMaintenance::filter($data['request'])->orderBy('trn_start_date')->whereNotNull('trn_value')->get();
        else
            $data['transactions'] = TrnMaintenance::filter($data['request'])->where('sbu_id', userSBU())->orderBy('trn_start_date')->whereNotNull('trn_value')->get();

        if (count($data['transactions']) <= 0)
            return redirect()->back()->with('warning', 'No data available');

        $sbu = SBU::find(request('sbu_id'));
        $time = now()->format('dmY') . '-' . uniqid();
        $name = "ATL-GAN-MAI-DETAIL-{$time}.xlsx";

        $data['sbu'] = request('sbu_id') ? $sbu->sbu_name : 'All';
        $data['status'] = (request('status') == 1) ? 'Closed' : ((request('status') == null) ? 'All' : 'Open');
        $data['periode'] = getPeriodeExport(request());
        $data['total_cost'] =  $data['transactions']->sum('trn_value');
        $data['total_cost_plan'] =  $data['transactions']->sum('trn_value_plan');
        $data['total_data'] = $data['transactions']->count();

        // return Excel::download(new MaintenanceExport($data), $name);
        // $transactions = $data;
        // return view('export.maintenance', compact('transactions'));
        return Excel::download(new MaintenanceExportDetailView($data), $name);
    }

    public function reportSummary()
    {
        // request()->validate([
        //     'start' => 'required',
        //     'end' => 'required'
        // ]);

        if (request('start') > request('end'))
            return redirect()->back()->with('warning', 'Start date must be lower than End date');

        $data['request'] = request()->all();

        $data['transactions'] = TrnMaintenance::filter($data['request'])->whereNotNull('trn_value')->with(['sbu' => function ($q) {
            $q->select('id', 'sbu_name');
        }])->get()->groupBy('sbu.sbu_name');
        $data['countByMaintenance'] = TrnMaintenance::filter($data['request'])->whereNotNull('trn_value')->get()->groupBy('maintenance.name')->count();

        if (count($data['transactions']) <= 0)
            return redirect()->back()->with('warning', 'No data available');

        $time = now()->format('dmY') . '-' . uniqid();
        $name = "ATL-GAN-MAI-SUMMARY-{$time}.xlsx";
        $trn = TrnMaintenance::filter($data['request'])->whereNotNull('trn_value')->get();

        $data['periode'] = getPeriodeExport(request());
        $data['total_cost'] = $trn->sum('trn_value');
        $data['total_qty'] = $trn->count();
        // return view('export.summary.maintenance', compact('data'));
        return Excel::download(new MaintenanceExportSummaryView($data), $name);
    }

    public function reportPlan()
    {
        if (request('start') > request('end'))
            return redirect()->back()->with('warning', 'Start date must be lower than End date');

        $data['request'] = request()->all();

        if (isSuperadmin())
            $data['transactions'] = TrnMaintenance::filter($data['request'])->orderBy('trn_start_date')->whereNull('trn_value')->where('trn_type', 1)->get();
        else
            $data['transactions'] = TrnMaintenance::filter($data['request'])->where('sbu_id', userSBU())->orderBy('trn_start_date')->whereNull('trn_value')->where('trn_type', 1)->get();

        if (count($data['transactions']) <= 0)
            return redirect()->back()->with('warning', 'No data available');

        $sbu = SBU::find(request('sbu_id'));
        $time = now()->format('dmY') . '-' . uniqid();
        $name = "ATL-GAN-MAI-PLAN-{$time}.xlsx";

        $data['sbu'] = request('sbu_id') ? $sbu->sbu_name : '';
        $data['status'] = (request('status') == 1) ? 'Closed' : ((request('status') == null) ? '' : 'Open');
        $data['periode'] = getPeriodeExport(request());
        $data['total_cost_plan'] =  $data['transactions']->sum('trn_value_plan');
        $data['total_data'] = $data['transactions']->count();

        return Excel::download(new MaintenanceExportPlanView($data), $name);
    }
}
