<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\CampingPackage;
use Illuminate\Console\Command;

class SyncBookableFacilities extends Command
{
    protected $signature = 'booking:sync-bookables';

    protected $description = 'Ensure activities and camping packages have matching facility entries for booking.';

    public function handle(): int
    {
        $this->info('Syncing activities to facilities...');
        Activity::query()->each(fn (Activity $activity) => $activity->syncFacility());

        $this->info('Syncing camping packages to facilities...');
        CampingPackage::query()->each(fn (CampingPackage $package) => $package->syncFacility());

        $this->info('Finished syncing bookable resources.');

        return self::SUCCESS;
    }
}
