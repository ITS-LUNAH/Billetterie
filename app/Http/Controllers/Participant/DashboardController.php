<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Participant;
use App\Models\Reservation;
use App\Models\Billet;
use Illuminate\Support\Facades\Log;
use Milon\Barcode\DNS2D;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Recherche du participant
            $participant = Participant::where('user_id', $user->id)->first();
            
            if (!$participant) {
                return Inertia::render('Participant/Dashboard', [
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'stats' => [
                        'total_reservations' => 0,
                        'total_billets' => 0
                    ],
                    'recent_tickets' => []
                ]);
            }

            // Compter les réservations
            $totalReservations = Reservation::where('idparticipant', $participant->idParticipant)->count();
            $barcodeGenerator = new DNS2D();

            // Récupérer les derniers billets (5 plus récents)
            $recentTickets = Billet::where('idparticipant', $participant->idParticipant)
                ->with(['evenement', 'reservation'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($billet) use ($barcodeGenerator) { // Pass $barcodeGenerator to the closure
                    $qrCodeData = $billet->QRcode;
                    $qrCodeImageBase64 = '';

                    if (!empty($qrCodeData)) {
                        // Generate QR code in SVG format
                        $svg = $barcodeGenerator->getBarcodeSVG($qrCodeData, 'QRCODE');
                        // Encode SVG to base64 for embedding in HTML
                        $qrCodeImageBase64 = 'data:image/svg+xml;base64,' . base64_encode($svg);
                    } else {
                        Log::warning("QRcode data is empty for billet ID: {$billet->idbillet}. QR code image will be empty.");
                    }
                    return [
                        'id' => $billet->idbillet,
                        'qr_code_data' => $billet->QRcode,
                        'qr_code_image' => $qrCodeImageBase64, // The base64 image data for frontend
                        'est_scanne' => $billet->estscanne,
                        'evenement' => [
                            'titre' => $billet->evenement?->titre ?? 'Événement supprimé',
                            'lieu' => $billet->evenement?->lieu ?? 'Lieu non défini',
                        ],
                        'reservation' => [
                            'numero' => 'RES-' . str_pad($billet->reservation?->idreservation ?? 0, 6, '0', STR_PAD_LEFT),
                            'date' => $billet->reservation?->created_at?->format('d/m/Y') ?? 'Date inconnue',
                        ]
                    ];
                });

            $totalBillets = Billet::where('idparticipant', $participant->idParticipant)->count();

            return Inertia::render('Participant/Dashboard', [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'stats' => [
                    'total_reservations' => $totalReservations,
                    'total_billets' => $totalBillets
                ],
                'recent_tickets' => $recentTickets
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur dashboard participant', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return Inertia::render('Participant/Dashboard', [
                'user' => [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
                'stats' => [
                    'total_reservations' => 0,
                    'total_billets' => 0
                ],
                'recent_tickets' => [],
                'error' => 'Erreur lors du chargement des données'
            ]);
        }
    }
}
