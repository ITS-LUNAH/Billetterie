<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Participant;
use App\Models\Reservation;
use App\Models\Billet;

class ReservationController extends Controller
{
    /**
     * Affiche la liste des réservations du participant
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $participant = Participant::where('user_id', $user->id)->first();
            
            if (!$participant) {
                return Inertia::render('Participant/Reservations', [
                    'reservations' => [],
                    'message' => 'Aucun profil participant trouvé.',
                    'stats' => [
                        'total' => 0,
                        'confirmees' => 0,
                        'en_attente' => 0,
                        'annulees' => 0
                    ]
                ]);
            }

            // Récupérer toutes les réservations avec pagination
            $reservations = Reservation::where('idparticipant', $participant->idParticipant)
                ->with(['evenement'])
                ->orderBy('created_at', 'desc')
                ->paginate(10)
                ->through(function ($reservation) {
                    // Compter les billets pour chaque réservation
                    $billets = Billet::where('idreservation', $reservation->idreservation)->get();
                    
                    return [
                        'id' => $reservation->idreservation,
                        'numero' => 'RES-' . str_pad($reservation->idreservation, 6, '0', STR_PAD_LEFT),
                        'date_reservation' => $reservation->created_at->format('d/m/Y H:i'),
                        'etat' => $reservation->etat ?? 'en_attente',
                        'evenement' => [
                            'id' => $reservation->evenement?->idevenement,
                            'titre' => $reservation->evenement?->titre ?? 'Événement non trouvé',
                            'lieu' => $reservation->evenement?->lieu ?? 'Lieu non défini',
                            'date' => $reservation->evenement?->created_at?->format('d/m/Y') ?? 'Date non définie',
                        ],
                        'total_billets' => $billets->count(),
                        'prix_total' => $reservation->prix_total ?? '0.00',
                        'devise' => 'MAD'
                    ];
                });

            // Calculer les statistiques
            $allReservations = Reservation::where('idparticipant', $participant->idParticipant)->get();
            $stats = [
                'total' => $allReservations->count(),
                'confirmees' => $allReservations->where('etat', 'confirmee')->count(),
                'en_attente' => $allReservations->where('etat', 'en_attente')->count(),
                'annulees' => $allReservations->where('etat', 'annulee')->count(),
            ];

            return Inertia::render('Participant/Reservations', [
                'reservations' => $reservations,
                'message' => 'Toutes vos réservations',
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return Inertia::render('Participant/Reservations', [
                'reservations' => [],
                'message' => 'Erreur lors du chargement des réservations',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Affiche le détail d'une réservation
     */
    public function show($id)
    {
        $user = Auth::user();
        $participant = Participant::where('user_id', $user->id)->first();
        
        if (!$participant) {
            return redirect()->route('participant.reservations');
        }

        $reservation = Reservation::where('idreservation', $id)
            ->where('idparticipant', $participant->idParticipant)
            ->with(['evenement'])
            ->first();

        if (!$reservation) {
            return redirect()->route('participant.reservations')
                ->with('error', 'Réservation non trouvée');
        }

        // Récupérer les billets de cette réservation
        $billets = Billet::where('idreservation', $reservation->idreservation)->get();

        return Inertia::render('Participant/ReservationDetail', [
            'reservation' => [
                'id' => $reservation->idreservation,
                'numero' => 'RES-' . str_pad($reservation->idreservation, 6, '0', STR_PAD_LEFT),
                'date_reservation' => $reservation->created_at->format('d/m/Y H:i'),
                'etat' => $reservation->etat ?? 'en_attente',
                'evenement' => [
                    'id' => $reservation->evenement?->idevenement,
                    'titre' => $reservation->evenement?->titre ?? 'Événement non trouvé',
                    'lieu' => $reservation->evenement?->lieu ?? 'Lieu non défini',
                    'description' => $reservation->evenement?->description ?? '',
                ],
                'billets' => $billets->map(function ($billet) {
                    return [
                        'id' => $billet->idbillet,
                        'qr_code' => $billet->QRcode,
                        'est_scanne' => $billet->estscanne,
                        'date_validation' => $billet->datevalidation ? 
                            \Carbon\Carbon::parse($billet->datevalidation)->format('d/m/Y H:i') : null,
                    ];
                }),
                'prix_total' => $reservation->prix_total ?? '0.00',
                'devise' => 'MAD'
            ]
        ]);
    }
}
