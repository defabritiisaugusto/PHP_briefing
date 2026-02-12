<?php
/**
 * Modello Team
 *
 * Serve a rappresentare le squadre partecipanti ai tornei,
 * con le loro informazioni principali (nome, logo). È la base
 * su cui vengono costruite le iscrizioni ai tornei e le partite.
 */

namespace App\Models;

use App\Traits\WithValidate;
use App\Database\DB;

/**
 * Modello Team
 *
 * Rappresenta una squadra che partecipa ai tornei.
 * Contiene il nome e un'eventuale immagine/logo, e gestisce
 * le relazioni con i tornei tramite la tabella pivot TournamentTeam.
 */
class Team extends BaseModel {

    use WithValidate;

    
    public ?string $name = null;
    public ?string $img = null;

    /**
     * Nome della tabella nel database
     */
    protected static ?string $table = "teams";

    public function __construct(array $data = []) {
        parent::__construct($data);
    }

    protected static function validationRules(): array {
        return [
            "name" => ["required", "min:2", "max:100"],
            "img" =>  ["sometimes", function ($field, $value, $data) {
                if ($value !== null && $value !== '') {
                    if (!filter_var($value, FILTER_VALIDATE_URL) && !preg_match('/^\/[^\/]/', $value)) {
                        return "Il campo $field deve essere un URL valido o un path valido";
                    }
                }
            }]
        ];
    }

    /**
     * Relazioni
     */
    protected function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'tournament_teams', 'id_team', 'id_tournament');
    }
}