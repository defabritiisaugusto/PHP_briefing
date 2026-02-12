# Tournament Bracket API

API REST in PHP per gestire tornei, squadre, round e partite con generazione automatica del tabellone.

## Panoramica

- Gestione CRUD di tornei, squadre, round e partite
- Iscrizione squadre ai tornei e generazione bracket (quarti, semifinali, finale)

## Installazione

### Tramite Composer create-project

```bash
composer create-project codingspook/simple-rest-api nome-progetto
```

### Setup iniziale

1. **Configura il web server** per puntare alla directory `public/` (se non è già configurato)

2. **Configura la connessione al database** in `config/database.php`

3. **Configura il CORS** in `config/cors.php`

4. **Configura le route** in `routes/index.php`


## Comandi Utili

```bash
# Installa dipendenze
composer install

# Aggiorna autoload dopo aggiunta classi
composer dump-autoload

# Avvia server di sviluppo (PHP built-in)
php -S localhost:8000 -t public
```


## Struttura del Progetto

```
brief_tournament/
├── config/
│   ├── database.php          # Configurazione database
│   └── cors.php              # Configurazione CORS
├── public/
│   └── index.php             # Entry point (front controller)
├── routes/
│   ├── index.php             # Bootstrap delle route /api
│   ├── tournaments.php       # Endpoint tornei
│   ├── teams.php             # Endpoint squadre
│   ├── rounds.php            # Endpoint round
│   ├── games.php             # Endpoint partite
│   └── tournamentteams.php   # Endpoint iscrizioni torneo-squadre
├── src/
│   ├── bootstrap.php         # Bootstrap applicazione
│   ├── Database/
│   │   ├── DB.php            # Driver DB principale
│   │   └── JSONDB.php        # Driver DB JSON (se usato)
│   ├── Models/
│   │   ├── BaseModel.php
│   │   ├── Game.php
│   │   ├── Round.php
│   │   ├── Team.php
│   │   ├── Tournament.php
│   │   └── TournamentTeam.php
│   ├── Services/
│   │   └── BracketService.php # Logica generazione tabellone
│   ├── Traits/
│   │   ├── HasRelations.php
│   │   └── WithValidate.php
│   └── Utils/
│       ├── Request.php       # Gestione richiesta HTTP
│       └── Response.php      # Gestione risposte JSON
├── vendor/                   # Dipendenze Composer (SimpleRouter, ecc.)
├── composer.json             # Dipendenze e autoload
└── README.md                 # Documentazione
```

## Endpoint API principali

Base URL: `http://localhost:8000/api`

- Tornei
    - GET /tournaments
    - POST /tournaments
    - PUT/PATCH /tournaments/{id}
    - DELETE /tournaments/{id}
    - POST /tournaments/{id}/generate-bracket
    - GET /tournaments/{id}/bracket
    - POST /tournaments/{id}/generate-quarts
    - POST /tournaments/{id}/generate-semis
    - POST /tournaments/{id}/generate-final
    - GET /tournaments/status/{status}

- Squadre
    - GET /teams
    - POST /teams
    - PUT/PATCH /teams/{id}
    - DELETE /teams/{id}

- Round
    - GET /rounds? id_tournament={id_tournament}
    - POST /rounds
    - PUT/PATCH /rounds/{id}
    - DELETE /rounds/{id}

- Partite
    - GET /games? id_round={id_round}
    - POST /games
    - PUT/PATCH /games/{id}
    - DELETE /games/{id}

- Iscrizioni torneo-squadre
    - GET /tournament-teams/{id_team}/tournaments
    - GET /tournament-teams/{id_tournament}/teams
    - POST /tournament-teams/{id_team}/tournaments/{id_tournament}
    - PUT/PATCH /tournament-teams/{id_team}/tournaments/{id_tournament}
    - DELETE /tournament-teams/{id_team}/tournaments/{id_tournament}



## Licenza

MIT

## Supporto

Per domande o problemi, consulta la documentazione o apri una issue sul repository.

---

**Buon coding! 🚀**
