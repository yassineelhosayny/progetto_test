<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\FaqProfile;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        // Creo i profili
        $private = FaqProfile::create(['name' => 'Privato']);
        $public = FaqProfile::create(['name' => 'Pubblico']);

        // Categoria 1: Ordini e Spedizioni
        $ordersCategory = FaqCategory::create([
            'name' => 'Ordini e Spedizioni',
            'description' => 'Informazioni su ordini, consegne e spedizioni.',
        ]);

        $faq1 = Faq::create([
            'question' => 'Quanto tempo impiega la spedizione?',
            'answer' => 'Le spedizioni standard impiegano in media 2–3 giorni lavorativi. Per ordini express i tempi si riducono a 24h.',
            'active' => true,
            'faq_category_id' => $ordersCategory->id,
        ]);

        $faq2 = Faq::create([
            'question' => 'Posso modificare un ordine già confermato?',
            'answer' => 'È possibile modificare l’ordine entro 2 ore dall’acquisto contattando l’assistenza clienti.',
            'active' => true,
            'faq_category_id' => $ordersCategory->id,
        ]);

        // Categoria 2: Pagamenti e Resi
        $paymentsCategory = FaqCategory::create([
            'name' => 'Pagamenti e Resi',
            'description' => 'Informazioni su pagamenti, rimborsi e resi.',
        ]);

        $faq3 = Faq::create([
            'question' => 'Quali metodi di pagamento accettate?',
            'answer' => 'Accettiamo carte di credito/debito, PayPal e bonifico bancario.',
            'active' => true,
            'faq_category_id' => $paymentsCategory->id,
        ]);

        $faq4 = Faq::create([
            'question' => 'Come posso richiedere un reso?',
            'answer' => 'Puoi richiedere il reso entro 14 giorni dal ricevimento compilando il modulo nella sezione “I miei ordini”.',
            'active' => true,
            'faq_category_id' => $paymentsCategory->id,
        ]);

        // Assegno i profili alle FAQ (in modo casuale, 1 o entrambi)
        foreach ([$faq1, $faq2, $faq3, $faq4] as $faq) {
            $profiles = [$private->id, $public->id];
            shuffle($profiles); // mischia
            $assign = array_slice($profiles, 0, rand(1, 2)); // prendi 1 o 2 profili
            $faq->profiles()->attach($assign);
        }
    }
}
