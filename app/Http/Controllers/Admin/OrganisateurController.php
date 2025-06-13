<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisateur;

class OrganisateurController extends Controller
{
    public function index() {
        $organisateurs = Organisateur::all();
        return view('admin.organisateurs.index', compact('organisateurs'));
    }

    public function valider($id) {
        $org = Organisateur::findOrFail($id);
        $org->estValide = true;
        $org->save();
        return back()->with('success', 'Organisateur validé');
    }

    public function suspendre($id) {
        $org = Organisateur::findOrFail($id);
        $org->estActif = false;
        $org->save();
        return back()->with('success', 'Organisateur suspendu');
    }
    
    //+
    public function reactiver($id)
    {
    $org = Organisateur::findOrFail($id);
    $org->estActif = true;
    $org->save();

    return redirect()->back()->with('success', 'Organisateur réactivé.');
    }
}
