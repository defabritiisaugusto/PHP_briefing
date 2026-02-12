<?php
/**
 * Modello Round
 *
 * Rappresenta una fase del torneo (quarti, semifinali, finale, ecc.).
 * Serve per suddividere il torneo in step logici e per collegare
 * ogni partita (Game) a una determinata fase, rendendo possibile
 * costruire e leggere la struttura del tabellone.
 */

namespace App\Models;

use App\Traits\WithValidate;
use App\Database\DB;

/**
 * Modello Round
 *
 * Rappresenta una fase del torneo (es. Quarti, Semifinali, Finale).
 * Ogni round appartiene a un singolo torneo e può contenere più partite (Game).
 */
class Round extends BaseModel {

    use WithValidate;

    
    public ?int $id_tournament = null;
    public ?string $name = null; // Quarti, Semifinali, Finale, Finale 3° posto
    public ?string $status = null; // pending, in_progress, completed
    
   
    /**
     * Nome della tabella
     */
    protected static ?string $table = "rounds";

    public function __construct(array $data = []) {
        parent::__construct($data);
    }

    protected static function validationRules(): array {
        return [
           
            "id_tournament" => ["required", "numeric", function ($field, $value, $data) {
                if ($value !== null && $value !== '') {
                    $tournament = Tournament::find((int)$value);
                    if ($tournament === null) {
                        return "Il torneo specificato non esiste";
                    }
                }
            }],
            "name" => ["required", "min:2", "max:100"],
            "status" => ["required", function ($field, $value, $data) {
                if (!in_array($value, ["pending", "in_progress", "completed"])) {
                    return "Lo stato deve essere uno tra: in_caricamento, in_progresso, completato";
                }
            }]
        ];
    }

     /**
     * Relazioni
     */

   

        // Un torneo ha molti round (quarti, semifinali, finale, …).
        // Ogni round appartiene a un solo torneo.
        // Perché è fatta così:

        // Il campo id_tournament è sul round, quindi è il round che “punta” al torneo → belongsTo.
        // Dal punto di vista del torneo, leggere tutti i suoi round ($tournament->round()) è l’operazione naturale per mostrare la struttura del torneo.

    protected function tournament()
    {
        return $this->belongsTo(Tournament::class, 'id_tournament');
    }

   

}
