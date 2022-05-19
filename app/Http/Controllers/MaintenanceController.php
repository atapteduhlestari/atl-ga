<?php

namespace App\Http\Controllers;

use App\Models\Cycle;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use App\Rules\ISOFormatRule;
use App\Rules\DocumentFormat;

class MaintenanceController extends Controller
{
    public function index()
    {
        $cycles = Cycle::get();
        $maintenances = Maintenance::get();

        $lastNoDoc = $maintenances->last();
        $no_doc = "ATL-HOJ-SOP-GAN-0#-##";

        return view('asset.maintenance.index', compact(
            'maintenances',
            'cycles',
            'no_doc'
        ));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cycle_id' => 'required',
            'no_doc' =>  ['nullable', new DocumentFormat],
        ]);

        $data = $request->all();
        Maintenance::create($data);

        return redirect()->back()->with('success', 'Success!');
    }

    public function show(Maintenance $maintenance)
    {
        //
    }

    public function edit(Maintenance $maintenance)
    {
        $cycles = Cycle::get();
        $maintenances = Maintenance::get();
        return view('asset.maintenance.edit', compact('maintenance', 'maintenances', 'cycles'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'name' => 'required',
            'cycle_id' => 'required',
            'no_doc' =>  [
                'nullable',
                new DocumentFormat,
                new ISOFormatRule
            ],
        ]);

        $data = $request->all();
        $maintenance->update($data);
        return redirect()->back()->with('success', 'Success!');
    }

    public function destroy(Maintenance $maintenance)
    {
        if ($maintenance->transactions()->exists()) {
            return redirect('/maintenance')->with('warning', 'Cannot delete maintenance that have transactions!');
        }

        $maintenance->delete();
        return redirect('/maintenance')->with('success', 'Success!');
    }
}
