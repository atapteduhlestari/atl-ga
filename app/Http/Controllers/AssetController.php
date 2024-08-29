<?php

namespace App\Http\Controllers;

use App\Models\SBU;
use App\Models\SDB;
use App\Models\Asset;
use App\Models\Employee;
use App\Models\AssetChild;
use App\Models\AssetGroup;
use App\Models\DocumentGroup;
use App\Exports\AssetExportView;
use Yajra\DataTables\DataTables;
use App\Http\Requests\AssetRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetExportSummaryView;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AssetChildRequest;


class AssetController extends Controller
{
    public function index()
    {
        $assetGroup = AssetGroup::get();
        $employees = Employee::orderBy('name', 'asc')->get();
        $SDBs = SDB::orderBy('sdb_name', 'asc')->get();
        $SBUs = SBU::orderBy('sbu_name', 'asc')->get();

        return view('asset.parent.index', compact(
            'assetGroup',
            'employees',
            'SDBs',
            'SBUs'
        ));
    }

    public function search($param)
    {
        $data = request()->all();

        if (isSuperadmin())
            $assets = $param == 'all' ? Asset::search($data)->orderBy('sbu_id', 'asc')->orderBy('pcs_date', 'desc')->get() : Asset::where('asset_group_id', $param)->orderBy('sbu_id', 'asc')->orderBy('pcs_date', 'desc')->search($data)->get();
        else
            $assets = $param == 'all' ? Asset::where('sbu_id', userSBU())->orderBy('sbu_id', 'asc')->orderBy('pcs_date', 'desc')->search($data)->get() : Asset::where('asset_group_id', $param)->where('sbu_id', userSBU())->orderBy('pcs_date', 'desc')->search($data)->get();

        $assetGroup = AssetGroup::get();
        $employees = Employee::orderBy('name', 'asc')->get();
        $SDBs = SDB::orderBy('sdb_name', 'asc')->get();
        $SBUs = SBU::orderBy('sbu_name', 'asc')->get();

        return view('asset.parent.search', compact(
            'assets',
            'assetGroup',
            'employees',
            'SDBs',
            'SBUs'
        ));
    }

    public function getData()
    {
        $asset = new Asset();
        $query = $asset->query();

        if (isUserSBU())
            $query = $asset->where('sbu_id', userSBU());

        $totalQueries = $query->count();
        $dt = DataTables::of($query);

        $dt->addIndexColumn()->editColumn('pcs_date', function ($row) {
            return createDate($row->pcs_date)->format('d F Y');
        })->editColumn('pcs_value', function ($row) {
            return rupiah($row->pcs_value);
        })->addColumn('asset_code', function (Asset $asset) {
            return $asset->asset_code;
        })->addColumn('sbu', function (Asset $asset) {
            return $asset->sbu ? $asset->sbu->sbu_name : '';
        })->addColumn('group', function (Asset $asset) {
            return $asset->group->asset_group_name;
        })->addColumn('employee', function (Asset $asset) {
            return $asset->employee ? $asset->employee->name : '-';
        })->addColumn('condition', function (Asset $asset) {
            $text = textCondition($asset->condition);
            $color = colorCondition($asset->condition);
            return "<span class='$color'> {$text}</span>";
        })->addColumn('action', function ($row) {
            return
                '<div class="d-flex justify-content-around">
                    <div>
                        <a title="Asset Detail" href="/asset-parent/docs/' . $row->id . '" class="btn btn-outline-dark btn-sm">Detail</a>
                    </div>
                    <div>
                        <a title="Edit Data" href="/asset-parent/' . $row->id . '/edit" class="btn btn-outline-dark btn-sm">Edit</a>
                    </div>
                    <div>
                        <form action="/asset-parent/' . $row->id . '" method="post" id="deleteForm">
                        ' . csrf_field() . '
                        ' . method_field("DELETE") . '
                            <button title="Delete Data" class="btn btn-outline-danger btn-sm" onclick="return false" id="deleteButton" data-id="' . $row->id . '"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </div>
                </div>';
        })->rawColumns(['action', 'condition']);

        return $dt->orderColumn('pcs_date', '-pcs_date $1')->setTotalRecords($totalQueries)->toJson();
        // return $dt->toJson();
    }

    public function store(AssetRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->user()->id;
        $data['pcs_value'] = removeDots($request->pcs_value);
        $data['nilai_buku'] = removeDots($request->nilai_buku);

        if (isUserSBU())
            $data['sbu_id'] = userSBU();

        if ($request->file('image')) {
            $image = $request->file('image');
            $extension = $image->extension();
            $imageUrl = $image->storeAs('uploads/images/assets', formatTimeDoc($request->asset_name, $extension));
            $data['image'] = $imageUrl;
        }

        Asset::create($data);
        return redirect()->back()->with('success', 'Success!');
    }

    public function show(Asset $asset)
    {
        // $asset = Asset::select('id', 'asset_code', 'sbu_id', "asset_name")->with('sbu')->where('aktiva', "")->get();
        // foreach ($asset as $s) {
        //     $s->update([
        //         'aktiva' => ''
        //     ]);
        // }
        // return $asset;

        return abort(404);
    }

    public function exportDetail(Asset $asset)
    {
        foreach ($asset->children as $doc) {
            $asset->sumRenewal += $doc->trnRenewal->sum('trn_value');
        }
        return view('export.asset_detail', compact('asset'));
    }

    public function export($param)
    {
        $data = request()->all();

        if (isSuperadmin())
            $data['assets'] = $param == 'all' ? Asset::with('sbu', 'employee')->filter($data)->orderBy('sbu_id', 'asc')->orderBy('pcs_date', 'desc')->get() : Asset::with('sbu', 'employee')->where('asset_group_id', $param)->orderBy('sbu_id', 'asc')->orderBy('pcs_date', 'desc')->filter($data)->get();
        else
            $data['assets'] = $param == 'all' ? Asset::with('sbu', 'employee')->where('sbu_id', userSBU())->filter($data)->orderBy('sbu_id', 'asc')->orderBy('pcs_date', 'desc')->get() : Asset::with('sbu', 'employee')->where('asset_group_id', $param)->where('sbu_id', userSBU())->orderBy('sbu_id', 'asc')->orderBy('pcs_date', 'desc')->filter($data)->get();

        $sbu = SBU::find(request('sbu_id'));
        $time = now()->format('dmY') . '-' . uniqid();
        $name = "ATL-GAN-ASSET-DETAIL-{$time}.xlsx";

        $data['sbu'] = request('sbu_id') ? $sbu->sbu_name : 'All';
        $data['status'] = (request('status') == 1) ? 'Closed' : ((request('status') == null) ? 'All' : 'Open');
        $data['start'] = createDate(request('start'))->format('F');
        $data['end'] = createDate(request('end'))->format('F');
        $data['year'] = createDate(request('end'))->format('Y');

        return view('export.asset', compact('data'));
        // return Excel::download(new AssetExportView($data), $name);
    }

    public function edit(Asset $asset)
    {
        $this->authorize('update', $asset);

        $assetGroup = AssetGroup::get();
        $assets = Asset::get();
        $employees = Employee::orderBy('name', 'asc')->get();
        $SDBs = SDB::orderBy('sdb_name', 'asc')->get();
        $SBUs = SBU::orderBy('sbu_name', 'asc')->get();

        return view('asset.parent.edit', compact(
            'asset',
            'assets',
            'assetGroup',
            'employees',
            'SDBs',
            'SBUs'
        ));
    }

    public function update(AssetRequest $request, Asset $asset)
    {
        $this->authorize('update', $asset);
        $data = $request->validated();

        $data['user_id'] = auth()->user()->id;
        $data['pcs_value'] = removeDots($request->pcs_value);
        $data['nilai_buku'] = removeDots($request->nilai_buku);

        if (isUserSBU())
            $data['sbu_id'] = userSBU();

        if ($request->file('image')) {
            Storage::delete($asset->image);
            $image = $request->file('image');
            $extension = $image->extension();
            $imageUrl = $image->storeAs('uploads/images/assets', formatTimeDoc($request->asset_name, $extension));
            $data['image'] = $imageUrl;
        } else {
            $data['image'] = $asset->image;
        }
        $asset->update($data);
        return redirect()->back()->with('success', 'Success!');
    }

    public function destroy(Asset $asset)
    {
        $this->authorize('delete', $asset);

        if ($asset->children()->exists()) {
            return redirect('/asset-parent')->with('warning', 'Cannot delete assets that have documents!');
        }

        if ($asset->trnMaintenance()->exists()) {
            return redirect('/asset')->with('warning', 'Cannot delete asset that have transactions!');
        }

        Storage::delete($asset->image);
        $asset->delete();
        return redirect('/asset-parent')->with('success', 'Success!');
    }

    public function documents(Asset $asset)
    {
        $this->authorize('update', $asset);
        $documentGroup = DocumentGroup::get();
        $SBUs = SBU::orderBy('sbu_name', 'asc')->get();
        $SDBs = SDB::orderBy('sdb_name', 'asc')->get();

        return view('asset.parent.docs.index', compact(
            'documentGroup',
            'asset',
            'SDBs',
            'SBUs'
        ));
    }

    public function addDocuments(AssetChildRequest $request, Asset $asset)
    {
        $this->authorize('update', $asset);
        $data = $request->validated();
        $data['asset_id'] = $asset->id;

        if ($request->file('file')) {
            $file = $request->file('file');
            $extension = $file->extension();
            $fileUrl = $file->storeAs('uploads/files/docs',  formatTimeDoc($request->doc_name, $extension));
            $data['file'] = $fileUrl;
        }

        $asset->children()->create($data);
        return redirect()->back()->with('success', 'Success!');
    }

    public function editDocuments(Asset $asset, $childId)
    {
        $this->authorize('update', $asset);
        $documentGroup = DocumentGroup::get();
        $child = AssetChild::find($childId);
        $SDBs = SDB::orderBy('sdb_name', 'asc')->get();
        $SBUs = SBU::orderBy('sbu_name', 'asc')->get();

        return view('asset.parent.docs.edit', compact(
            'documentGroup',
            'asset',
            'child',
            'SDBs',
            'SBUs'
        ));
    }

    // public function exportView()
    // {
    //     $assets = Asset::get();
    //     return view('export.asset', compact('assets'));
    //     return Excel::download(new AssetExportView, 'tes.xlsx');
    // }


    public function detailView()
    {
        $SBUs = SBU::orderBy('sbu_name', 'asc')->get();
        return view('report.detail.asset', compact('SBUs'));
    }

    public function summaryView()
    {
        $SBUs = SBU::orderBy('sbu_name', 'asc')->get();
        return view('report.summary.asset', compact('SBUs'));
    }

    public function reportDetail()
    {
        if (request('start') > request('end'))
            return redirect()->back()->with('warning', 'Start date must be lower than End date');

        $data = request()->all();

        if (isSuperadmin())
            $data['assets'] = Asset::with('sbu', 'employee')->filter($data)->orderBy('sbu_id', 'asc')->orderBy('pcs_date', 'desc')->get();
        else
            $data['assets'] = Asset::with('sbu', 'employee')->where('sbu_id', userSBU())->filter($data)->orderBy('pcs_date', 'desc')->get();

        if (count($data['assets']) <= 0)
            return redirect()->back()->with('warning', 'No data available');

        $sbu = SBU::find(request('sbu_id'));
        $sbuName = $sbu ? $sbu->sbu_name . '_' : '';
        $time =  $sbuName . now()->format('dmY') . uniqid();
        $name = "ATL-GAN-ASSET-DETAIL-{$time}.xlsx";

        $data['sbu'] = request('sbu_id') ? $sbu->sbu_name : 'All';
        $data['condition'] = (request('condition') == 1) ? 'Baik' : ((request('condition') == 3) ? 'Rusak' :  'All');
        $data['total_cost'] =  $data['assets']->sum('pcs_value');
        $data['total_data'] = $data['assets']->count();
        $data['periode'] = $this->getPeriodeExport(request());

        // return view('export.asset', compact('data'));
        return Excel::download(new AssetExportView($data), $name);
    }

    public function reportSummary()
    {
        $data['request'] = request()->all();

        if (isSuperadmin())
            $data['assets'] = Asset::filter($data['request'])->with(['sbu' => fn($q) => $q->select('id', 'sbu_name')])->get()->groupBy('sbu.sbu_name');
        else
            $data['assets'] = Asset::filter($data['request'])->with(['sbu' => fn($q) => $q->select('id', 'sbu_name')])->where('sbu_id', userSBU())->get()->groupBy('sbu.sbu_name');

        $time = now()->format('dmY') . '-' . uniqid();
        $name = "ATL-GAN-ASSET-SUMMARY-{$time}.xlsx";
        $asset = isSuperadmin() ? Asset::filter($data['request'])->get() : Asset::filter($data['request'])->where('sbu_id', userSBU())->get();

        $data['periode'] = $this->getPeriodeExport(request());
        $data['total_baik']  = $asset->where('condition', 1)->count();
        $data['total_cost_baik'] = $asset->where('condition', 1)->sum('pcs_value');
        $data['total_rusak']  = $asset->where('condition', 3)->count();
        $data['total_cost_rusak'] = $asset->where('condition', 3)->sum('pcs_value');
        $data['total_cost'] = $asset->sum('pcs_value');
        $data['total_data'] = $asset->count();

        // return view('export.summary.asset', compact('data'));
        return Excel::download(new AssetExportSummaryView($data), $name);
    }

    public function getPeriodeExport($data)
    {
        $start = null;
        $startYear = null;
        $end = null;
        $endYear = null;

        if ($data['start']) {
            $start = createDate($data['start'])->format('F');
            $startYear = createDate($data['start'])->format('Y');
        }

        if ($data['end']) {
            $end = createDate($data['end'])->format('F');
            $endYear = createDate($data['end'])->format('Y');
        }

        $sd = 'sd';

        if ($start ==  $end && $startYear == $endYear) {
            $end = null;
            $endYear = null;
            $sd = null;
        }

        if ($startYear == $endYear) {
            $startYear = null;
        }

        $text = "$start $startYear $sd $end $endYear";
        $periode = trim($text) == '' ? 'All' : $text;

        return $periode;
    }

    public static function generateButton($row)
    {
        '<div class="d-flex justify-content-around">
            <div>
                <a title="Asset Detail" href="/asset-parent/docs/' . $row->id . '" class="btn btn-outline-dark btn-sm">Detail</a>
            </div>
            <div>
                <a title="Edit Data" href="/asset-parent/' . $row->id . '/edit" class="btn btn-outline-dark btn-sm">Edit</a>
            </div>
            <div>
                <form action="/asset-parent/' . $row->id . '" method="post" id="deleteForm">
                ' . csrf_field() . '
                ' . method_field("DELETE") . '
                    <button title="Delete Data" class="btn btn-outline-danger btn-sm" onclick="return false" id="deleteButton" data-id="' . $row->id . '"><i class="fas fa-trash-alt"></i></button>
                </form>
            </div>
        </div>';
    }
}
