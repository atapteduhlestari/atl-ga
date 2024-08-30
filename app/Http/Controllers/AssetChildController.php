<?php

namespace App\Http\Controllers;

use App\Models\SBU;
use App\Models\SDB;
use App\Models\Asset;
use App\Models\AssetChild;
use App\Models\DocumentGroup;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AssetChildRequest;

class AssetChildController extends Controller
{
    public function index()
    {
        $data = request()->all();
        $children = AssetChild::with([
            'parent' => fn($q) => $q->orderBy('asset_name')
        ]);

        $documentGroup = DocumentGroup::orderBy('document_group_name', 'asc')->get();
        $children = isSuperadmin() ? $children->search($data)->get() : $children->search($data)->where('sbu_id', userSBU())->get();
        $SDBs = SDB::orderBy('sdb_name', 'asc')->get();
        $SBUs = SBU::orderBy('sbu_name', 'desc')->get();
        $assets = Asset::orderBy('asset_name', 'asc')->get();

        return view('asset.child.index', compact(
            'documentGroup',
            'children',
            'assets',
            'SDBs',
            'SBUs'
        ));
    }

    public function store(AssetChildRequest $request)
    {
        $data = $request->validated();

        if (isUserSBU())
            $data['sbu_id'] = userSBU();

        if ($request->file('file')) {
            $file = $request->file('file');
            $extension = $file->extension();
            $fileUrl = $file->storeAs('uploads/files/docs',  formatTimeDoc($request->doc_name, $extension));
            $data['file'] = $fileUrl;
        }

        AssetChild::create($data);
        return redirect()->back()->with('success', 'Successfully deleted!');
    }

    public function edit(AssetChild $assetChild)
    {
        $this->authorize('update', $assetChild);

        $documentGroup = DocumentGroup::get();
        $child = $assetChild;
        $SDBs = SDB::orderBy('sdb_name', 'asc')->get();
        $SBUs = SBU::orderBy('sbu_name', 'asc')->get();

        return view('asset.child.edit', compact(
            'documentGroup',
            'child',
            'SDBs',
            'SBUs'
        ));
    }

    public function update(AssetChildRequest $request, AssetChild $assetChild)
    {
        $this->authorize('update', $assetChild);
        $data = $request->validated();

        if (isUserSBU())
            $data['sbu_id'] = userSBU();

        if ($request->file('file')) {
            Storage::delete($assetChild->file);
            $file = $request->file('file');
            $extension = $file->extension();
            $fileUrl = $file->storeAs('uploads/files/docs',  formatTimeDoc($request->doc_name, $extension));
            $data['file'] = $fileUrl;
        } else {
            $data['file'] = $assetChild->file;
        }

        $assetChild->update($data);
        return redirect()->back()->with('success', 'Successfully deleted!');
    }

    public function show(AssetChild $assetChild)
    {
        $children = $assetChild->load('trnRenewal');
        return view('asset.child.show', compact(
            'children',
        ));
    }

    public function download(AssetChild $assetChild)
    {
        $this->authorize('download', $assetChild);

        $path = public_path() . $assetChild->takeDoc;
        return response()->download($path);
    }

    public function destroy(AssetChild $assetChild)
    {
        $this->authorize('delete', $assetChild);

        if ($assetChild->trnRenewal()->exists()) {
            return redirect('/asset-child')->with('warning', 'Cannot delete document that has transactions!');
        }

        if ($assetChild->loan()->exists()) {
            return redirect('/asset-child')->with('warning', 'Cannot delete document that has been loaned!');
        }

        Storage::delete($assetChild->file);
        $assetChild->delete();

        return redirect('/asset-child')->with('success', 'Successfully deleted!');
    }
}
