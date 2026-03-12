<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Institution;
use Illuminate\Database\Seeder;

class ReferenceGeoSeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'Angola', 'code_iso' => 'AGO'],
            ['name' => 'Burundi', 'code_iso' => 'BDI'],
            ['name' => 'Cameroun', 'code_iso' => 'CMR'],
            ['name' => 'Republique centrafricaine', 'code_iso' => 'CAF'],
            ['name' => 'Tchad', 'code_iso' => 'TCD'],
            ['name' => 'Congo', 'code_iso' => 'COG'],
            ['name' => 'Republique democratique du Congo', 'code_iso' => 'COD'],
            ['name' => 'Guinee equatoriale', 'code_iso' => 'GNQ'],
            ['name' => 'Gabon', 'code_iso' => 'GAB'],
            ['name' => 'Rwanda', 'code_iso' => 'RWA'],
            ['name' => 'Sao Tome-et-Principe', 'code_iso' => 'STP'],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['code_iso' => $country['code_iso']],
                [
                    'name' => $country['name'],
                    'region' => 'Afrique Centrale',
                    'flag_url' => null,
                ]
            );
        }

        $ceeacCountry = Country::where('code_iso', 'GAB')->first();

        Institution::updateOrCreate(
            ['code' => 'CEEAC-COM'],
            [
                'name' => 'Commission de la CEEAC',
                'type_id' => 'ceeac',
                'country_id' => $ceeacCountry?->id,
            ]
        );

        foreach (Country::all() as $country) {
            Institution::updateOrCreate(
                ['code' => 'EM-' . $country->code_iso],
                [
                    'name' => 'Coordination nationale CEEAC - ' . $country->name,
                    'type_id' => 'etat_membre',
                    'country_id' => $country->id,
                ]
            );
        }

        $partners = [
            ['name' => 'Banque Africaine de Developpement', 'code' => 'PRT-BAD'],
            ['name' => 'Union Europeenne - Programme Regional', 'code' => 'PRT-UE'],
            ['name' => 'Nations Unies - Bureau Afrique Centrale', 'code' => 'PRT-ONU'],
        ];

        foreach ($partners as $partner) {
            Institution::updateOrCreate(
                ['code' => $partner['code']],
                [
                    'name' => $partner['name'],
                    'type_id' => 'partenaire',
                    'country_id' => null,
                ]
            );
        }
    }
}

