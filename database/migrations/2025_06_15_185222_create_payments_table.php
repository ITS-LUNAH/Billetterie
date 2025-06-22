<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Colonne ID (clé primaire auto-incrémentée)

            // Cela lie chaque paiement à une réservation spécifique.
            $table->foreignId('idreservation')
                  ->constrained('reservations', 'idreservation') // Spécifie la table et la colonne de référence
                  ->onDelete('cascade');

            // L'ID de l'intention de paiement ou de la transaction Stripe.
            // Très important pour la traçabilité et l'intégration avec les webhooks Stripe.
            $table->string('stripe_payment_intent_id')->nullable()->unique();

            // Le montant du paiement (en devise principale, ex: 200.00 MAD)
            // DECIMAL(10, 2) permet de stocker des nombres avec 2 décimales.
            $table->decimal('amount', 10, 2);

            // La devise utilisée (ex: 'MAD', 'EUR', 'USD')
            $table->string('currency', 3);

            // Le statut du paiement (ex: 'pending', 'succeeded', 'failed', 'refunded', 'cancelled')
            $table->string('status');

            // --- Informations optionnelles
            $table->string('payment_method_type')->nullable(); // ex: 'card', 'bank_transfer'
            $table->string('card_brand')->nullable(); // ex: 'visa', 'mastercard'
            $table->string('last_four_digits', 4)->nullable(); // Derniers 4 chiffres de la carte
            $table->text('receipt_url')->nullable(); // URL du reçu Stripe

            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
