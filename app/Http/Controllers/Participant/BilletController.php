<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Participant;
use App\Models\Billet;

class BilletController extends Controller
{
    /**
     * Affiche tous les billets du participant
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $participant = Participant::where('user_id', $user->id)->first();
            
            if (!$participant) {
                return Inertia::render('Participant/Billets', [
                    'billets' => [],
                    'message' => 'Aucun profil participant trouvé.',
                    'stats' => [
                        'total' => 0,
                        'scannes' => 0,
                        'non_scannes' => 0,
                        'valides' => 0
                    ]
                ]);
            }

            // Récupérer tous les billets avec pagination
            $billets = Billet::where('idparticipant', $participant->idParticipant)
                ->with(['evenement', 'reservation'])
                ->orderBy('created_at', 'desc')
                ->paginate(12)
                ->through(function ($billet) {
                    return [
                        'id' => $billet->idbillet,
                        'qr_code' => $billet->QRcode,
                        'est_scanne' => $billet->estscanne,
                        'date_validation' => $billet->datevalidation ? 
                            \Carbon\Carbon::parse($billet->datevalidation)->format('d/m/Y H:i') : null,
                        'evenement' => [
                            'id' => $billet->evenement?->idevenement,
                            'titre' => $billet->evenement?->titre ?? 'Événement non trouvé',
                            'lieu' => $billet->evenement?->lieu ?? 'Lieu non défini',
                        ],
                        'reservation' => [
                            'id' => $billet->reservation?->idreservation,
                            'numero' => 'RES-' . str_pad($billet->reservation?->idreservation ?? 0, 6, '0', STR_PAD_LEFT),
                        ]
                    ];
                });

            // Calculer les statistiques
            $allBillets = Billet::where('idparticipant', $participant->idParticipant)->get();
            $stats = [
                'total' => $allBillets->count(),
                'scannes' => $allBillets->where('estscanne', true)->count(),
                'non_scannes' => $allBillets->where('estscanne', false)->count(),
                'valides' => $allBillets->whereNotNull('datevalidation')->count(),
            ];

            return Inertia::render('Participant/Billets', [
                'billets' => $billets,
                'message' => 'Tous vos billets',
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return Inertia::render('Participant/Billets', [
                'billets' => [],
                'message' => 'Erreur lors du chargement des billets',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Affiche le détail d'un billet (QR code, etc.)
     */
    public function show($id)
    {
        $user = Auth::user();
        $participant = Participant::where('user_id', $user->id)->first();
        
        if (!$participant) {
            return redirect()->route('participant.billets');
        }

        $billet = Billet::where('idbillet', $id)
            ->where('idparticipant', $participant->idParticipant)
            ->with(['evenement', 'reservation'])
            ->first();

        if (!$billet) {
            return redirect()->route('participant.billets')
                ->with('error', 'Billet non trouvé');
        }

        return Inertia::render('Participant/BilletDetail', [
            'billet' => [
                'id' => $billet->idbillet,
                'qr_code' => $billet->QRcode,
                'est_scanne' => $billet->estscanne,
                'date_validation' => $billet->datevalidation ? 
                    \Carbon\Carbon::parse($billet->datevalidation)->format('d/m/Y H:i') : null,
                'evenement' => [
                    'id' => $billet->evenement?->idevenement,
                    'titre' => $billet->evenement?->titre ?? 'Événement non trouvé',
                    'lieu' => $billet->evenement?->lieu ?? 'Lieu non défini',
                    'description' => $billet->evenement?->description ?? '',
                ],
                'reservation' => [
                    'id' => $billet->reservation?->idreservation,
                    'numero' => 'RES-' . str_pad($billet->reservation?->idreservation ?? 0, 6, '0', STR_PAD_LEFT),
                ]
            ]
        ]);
    }
}
