<?php

namespace App\Services;

use App\Models\Referral;

class ReferralService
{
    public function attachClient(int $agentId, int $clientId, string $source)
    {
        if ($agentId === $clientId) {
            return false;
        }

        if (Referral::where('client_id', $clientId)->exists()) {
            return false;
        }

        return Referral::create([
            'agent_id' => $agentId,
            'client_id' => $clientId,
            'source' => $source
        ]);
    }
}
