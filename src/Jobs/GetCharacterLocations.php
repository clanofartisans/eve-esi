<?php

namespace Clanofartisans\EveEsi\Jobs;

use Carbon\Carbon;
use Clanofartisans\EveEsi\Auth\User;
use Clanofartisans\EveEsi\Facades\EveESI as ESI;
use Clanofartisans\EveEsi\Models\System;
use Clanofartisans\EveEsi\Routes\ESIRoute;
use Illuminate\Support\Facades\DB;

class GetCharacterLocations extends ESIJob
{
    /**
     * New
     *
     * @return void
     */
    public function handle(): void
    {
        $stillActive = Carbon::now()->subMinutes(15)->timestamp;

        $users = DB::table('sessions')
            ->select('user_id')
            ->where('sessions.last_activity', '>=', $stillActive)
            ->get();

        foreach($users as $user) {
            $user = User::findOrFail($user->user_id);

            $data = $this->resourceRoute($user->character_id)->get()->json();

            $system = System::findOrFail($data['solar_system_id']);

            $user->current_system = $system->name;

            $user->save();
        }
    }

    /**
     * New - Per Handler
     *
     * @param int $id
     * @return ESIRoute
     */
    protected function resourceRoute(int $id): ESIRoute
    {
        return ESI::characters()->character($id)->location()->auth($id);
    }
}
