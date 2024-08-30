<?php

namespace App\Http\Controllers;

use App\Models\AssetChild;
use App\Models\SDB;
use Illuminate\Http\Request;
use App\Rules\SpecialCharacter;

class SDBController extends Controller
{
    public function index()
    {
        $SDBs = SDB::get();
        return view('asset.sdb.index', compact('SDBs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sdb_name' => ['required', new SpecialCharacter],
        ]);

        $data = $request->all();
        $data['pcs_value'] = removeDots($request->pcs_value);

        SDB::create($data);
        return redirect()->back()->with('success', 'Success!');
    }

    public function show(SDB $sdb)
    {
        return view('asset.sdb.show', compact('sdb'));
    }

    public function edit(SDB $sdb)
    {
        return view('asset.sdb.edit', compact('sdb'));
    }

    public function update(Request $request, SDB $sdb)
    {
        $request->validate([
            'sdb_name' => ['required', new SpecialCharacter],
        ]);

        $data = $request->all();
        $data['pcs_value'] = removeDots($request->pcs_value);

        $sdb->update($data);
        return redirect()->back()->with('success', 'Success!');
    }

    public function destroy(SDB $sdb)
    {
        if ($sdb->docs()->exists()) {
            return redirect('/sdb')->with('warning', 'SDB is not empty!');
        }

        $sdb->delete();
        return redirect()->back()->with('success', 'Success!');
    }

    public function removeDocs($id)
    {
        $document = AssetChild::find($id);
        $document->update([
            'sdb_id' => null
        ]);
        return redirect()->back()->with('success', 'Success!');
    }
}
