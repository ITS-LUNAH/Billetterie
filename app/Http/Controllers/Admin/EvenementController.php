<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;

class EvenementController extends Controller
{
    public function index() {
        $evenements = Evenement::with('organisateur')->latest()->get();
        return view('admin.evenements.index', compact('evenements'));
    }

    public function valider($id) {
        $e = Evenement::findOrFail($id);
        $e->estValide = true;
        $e->save();
        return back()->with('success', 'Événement validé.');
    }

    public function refuser($id) {
        $e = Evenement::findOrFail($id);
        $e->estValide = false;
        $e->save();
        return back()->with('success', 'Événement refusé.');
    }
}
