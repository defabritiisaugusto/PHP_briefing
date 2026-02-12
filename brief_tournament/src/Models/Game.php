<?php
/**
 * Modello Game
 *
 * Serve a rappresentare una singola partita del torneo, con le
 * relative squadre, il risultato e il collegamento alla partita
 * successiva nel bracket. È necessario per poter salvare e
 * interrogare le partite nel database e popolare il tabellone.
 */

namespace App\Models;

use App\Traits\WithValidate;
use App\Database\DB;

/**
 * Modello Game
 *
 * Rappresenta una singola partita all'interno di un round di un torneo.
 * Gestisce le squadre coinvolte, il risultato, il vincitore e l'eventuale
 * collegamento alla partita successiva nel tabellone (next_game_id).
 */
class Game extends BaseModel
{

    use WithValidate;

    public ?int $id_round = null;
    public ?int $id_team1 = null;
    public ?int $id_team2 = null;
    public ?int $goals_team1 = null;
    public ?int $goals_team2 = null;
    public ?int $winner_team_id = null;
    public ?int $next_game_id = null;

    /**
     * Nome della tabella nel database
     */
    protected static ?string $table = "games";

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    protected static function validationRules(): array
    {
        return [
            "id_round" => ["required", "numeric", function ($field, $value, $data) {
                if ($value !== null && $value !== '') {
                    $round = Round::find((int)$value);
                    if ($round === null) {
                        return "Il round specificato non esiste";
                    }
                }
            }],
            "id_team1" => ["sometimes", "numeric", function ($field, $value, $data) {
                if ($value !== null && $value !== '') {
                    $team = Team::find((int)$value);
                    if ($team === null) {
                        return "La squadra 1 specificata non esiste";
                    }
                }
            }],
            "id_team2" => ["sometimes", "numeric", function ($field, $value, $data) {
                if ($value !== null && $value !== '') {
                    $team = Team::find((int)$value);
                    if ($team === null) {
                        return "La squadra 2 specificata non esiste";
                    }
                }
            }],
            "goals_team1" => ["sometimes", "numeric"],
            "goals_team2" => ["sometimes", "numeric"],
            "winner_team_id" => ["sometimes", "numeric", function ($field, $value, $data) {
                if ($value !== null && $value !== '') {
                    if ($value !== $data['team1_id'] && $value !== $data['team2_id']) {
                        return "Il vincitore deve essere una delle due squadre";
                    }
                }
            }],
            "next_game_id" => ['numeric', 'sometimes', function ($field, $value, $data) {
                if ($value !== null && $value !== '') {
                    // Verifica che la partita esista davvero nel DB
                    $game = Game::find((int)$value);
                    if ($game === null) {
                        return "Devi selezionare una partita esistente";
                    }
                }
            }],

        ];
    }

    /**
     * Relazioni
     */
    protected function team1()
    {
        return $this->belongsTo(Team::class, 'id_team1');
    }

    protected function team2()
    {
        return $this->belongsTo(Team::class, 'id_team2');
    }

    protected function round()
    {
        return $this->belongsTo(Round::class, 'id_round');
    }

    protected function winnerTeam()
    {
        return $this->belongsTo(Team::class, 'winner_team_id');
    }
 

    // indica che molte istanze di un'entità A sono associate a una singola istanza di un'entità B, mentre ogni istanza di B può essere legata a una o più di A


}
