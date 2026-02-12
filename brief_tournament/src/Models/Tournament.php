<?php
/**
 * Modello Tournament
 *
 * È l'entità principale dell'applicazione: descrive un intero torneo,
 * con i suoi metadati (nome, data, luogo, stato) e collega round,
 * partite e squadre. Senza questo modello non sarebbe possibile
 * raggruppare tutte le informazioni del bracket sotto un unico contesto.
 */

namespace App\Models;

use App\Traits\WithValidate;
use App\Database\DB;

/**
 * Modello Tournament
 *
 * Rappresenta un torneo composto da più round e partite.
 * Gestisce informazioni anagrafiche (nome, data, luogo), lo stato
 * di avanzamento e la squadra vincitrice, oltre alle relazioni con
 * squadre e round.
 */
class Tournament extends BaseModel
{

    use WithValidate;

    
    public ?string $name = null;           // Nome del torneo
    public ?string $date = null;           // Data (es. 2025-05-01)
    public ?string $place = null;          // Luogo dove si svolge il torneo
    public ?int $winner_team_id = null;    // FK verso la squadra vincitrice (teams.id)
    public ?string $status = null;         // Stato: pending, in_progress, completed


    /**
     * Nome della collection
     */
    protected static ?string $table = "tournaments";

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    protected static function validationRules(): array
    {
        return [
            "name" => ["required", "min:2", "max:100"],
            "date" => ["sometimes"],
            "place" => ["sometimes", "min:2", "max:100"],
            // winner_team_id viene impostato solo quando il torneo viene completato
            // e valida che sia un valore numerico valido.
            "winner_team_id" => ["sometimes", "numeric"],
            "status" => ["sometimes", function ($field, $value) {
                if ($value === null || $value === '') {
                    return null; // Permetti valore nullo o stringa vuota
                }
                $statuses = ['pending', 'in_progress', 'completed'];
                if (!in_array($value, $statuses, true)) {
                    return "Il campo $field deve essere uno dei seguenti valori: " . implode(', ', $statuses);
                }
                return null;    
            }],
        ];
    }

 

    /**
     * Relazioni
     * 
     * 
     */

        // Un torneo ha molti round (quarti, semifinali, finale, …).
        // Ogni round appartiene a un solo torneo.
        // Perché è fatta così:

        // Il campo id_tournament è sul round, quindi è il round che “punta” al torneo → belongsTo.
        // Dal punto di vista del torneo, leggere tutti i suoi round ($tournament->round()) è l’operazione naturale per mostrare la struttura del torneo.

    protected function round()
    {
        return $this->hasMany(Round::class, 'id_tournament');
    }


    protected function teams()
    {
        // many-to-many tramite tabella pivot "tournament_teams"
        // campi: id_tournament (FK torneo) e id_team (FK squadra)
        return $this->belongsToMany(Team::class, 'tournament_teams', 'id_tournament', 'id_team');
    }
 
    
  


}