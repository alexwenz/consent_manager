<?php

namespace FriendsOfRedaxo\ConsentManager\Api;

use FriendsOfRedaxo\ConsentManager\ConsentStats;
use rex;
use rex_api_function;
use rex_response;

class ConsentStatsApi extends rex_api_function
{
    protected $published = false; // Only for backend users

    public function execute(): void
    {
        rex_response::cleanOutputBuffers();

        // Nur wer die Log-Seite sehen darf, bekommt die Statistik daraus
        $user = rex::getUser();
        if (null === $user || !$user->hasPerm('consent_manager[]')) {
            rex_response::setStatus(rex_response::HTTP_FORBIDDEN);
            rex_response::sendJson(['error' => 'Keine Berechtigung']);
            exit;
        }

        $days = rex_request('days', 'int', 30);
        $stats = ConsentStats::getStats($days);

        rex_response::sendJson($stats);
        exit;
    }
}
