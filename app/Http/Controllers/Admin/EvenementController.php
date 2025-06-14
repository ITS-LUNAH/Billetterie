<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EvenementController extends Controller
{
    public function index() {
        $evenements = Event::with('organisateur')->latest()->get();
        return view('admin.evenements.index', compact('evenements'));
    }

    public function valider($id) {
        $e = Event::findOrFail($id);
        $e->estValide = true;
        $e->save();
        return back()->with('success', 'Événement validé.');
    }

    public function refuser($id) {
        $e = Event::findOrFail($id);
        $e->estValide = false;
        $e->save();
        return back()->with('success', 'Événement refusé.');
    }
}
