<!DOCTYPE html>
<html>
<head>
    <title>Vos Billets - Réservation #{{ $reservation->idreservation }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; margin: 20px; }
        .ticket-container { border: 1px solid #ccc; padding: 20px; margin-bottom: 20px; border-radius: 8px; page-break-inside: avoid; }
        .ticket-header { background-color: #f0f0f0; padding: 10px; margin-bottom: 15px; text-align: center; border-radius: 5px; }
        .ticket-header h1 { margin: 0; color: #333; font-size: 24px; }
        .ticket-details p { margin-bottom: 5px; font-size: 14px; }
        .qr-code { text-align: center; margin-top: 15px; }
        .qr-code img { max-width: 150px; height: auto; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 30px;">
        <h1>Confirmation de Réservation</h1>
        <p>Merci d'avoir réservé avec nous !</p>
    </div>

    @foreach($billets as $billet)
    <div class="ticket-container">
        <div class="ticket-header">
            <h1>Billet #{{ $billet->idbillet }}</h1>
        </div>
        <div class="ticket-details">
            <p><strong>Événement :</strong> {{ $billet->reservation->evenement->titre }}</p>
            <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($billet->dateEvenement->datedebut)->translatedFormat('l d F Y à H:i') }}</p>
            <p><strong>Lieu :</strong> {{ $billet->reservation->evenement->lieu }}</p>
            <p><strong>Zone :</strong> {{ $billet->zone->nomzone }}</p>
            <p><strong>Prix :</strong> {{ number_format($billet->zone->tarif, 2) }} MAD</p>
            <p><strong>Réservation ID :</strong> {{ $reservation->idreservation }}</p>
        </div>

        <div class="qr-code">
            <img src="{{ $billet->qrCodeImage }}" alt="QR Code du billet" style="width:150px; height:150px;">
            <p style="font-size: 12px; margin-top: 5px;">Scannez ce code pour l'entrée.</p>
            <p style="font-size: 10px; color: #888;">(Code du billet : {{ $billet->QRcode }})</p>
        </div>
    </div>
    @endforeach

    <div class="footer">
        <p>Ceci est un document généré automatiquement. Date: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
    </div>
</body>
</html>
