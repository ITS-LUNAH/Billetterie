<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Event;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index()
    {
        $commissions = Commission::with('evenement')->get();
        return view('admin.commissions.index', compact('commissions'));
    }

    public function create()
    {
        $evenements = Event::all();
        return view('admin.commissions.create', compact('evenements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idevenement' => 'required|exists:evenements,idevenement',
            'taux' => 'required|numeric|min:0|max:100'
        ]);

        Commission::create($request->all());
        return redirect()->route('admin.commissions.index')->with('success', 'Commission ajoutée.');
    }

    public function destroy($id)
    {
        $commission = Commission::findOrFail($id);
        $commission->delete();
        return back()->with('success', 'Commission supprimée.');
    }
}
