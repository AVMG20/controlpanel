<?php

namespace App\Models\Pterodactyl;

use App\Classes\Pterodactyl\PterodactylClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nest extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $fillable = [
        'id',
        'name',
        'description',
        'disabled',
    ];

    public static function syncNests()
    {
        /** @var \App\Classes\Pterodactyl\PterodactylClient $client */
        $client = app(PterodactylClient::class);
        $nests = $client->getNests()->json()['data'];

        //map response
        $nests = array_map(function ($nest) {
            return array(
                'id' => $nest['attributes']['id'],
                'name' => $nest['attributes']['name'],
                'description' => $nest['attributes']['description'],
            );
        }, $nests);

        foreach ($nests as $nest) {
            self::query()->updateOrCreate([
                'id' => $nest['id']
            ], [
                'name' => $nest['name'],
                'description' => $nest['description'],
            ]);
        }

        self::removeDeletedNests($nests);
    }

    /**
     * @description remove nests that have been deleted on pterodactyl
     * @param array $nests
     */
    private static function removeDeletedNests(array $nests): void
    {
        $ids = array_map(function ($data) {
            return $data['id'];
        }, $nests);

        self::all()->each(function (Nest $nest) use ($ids) {
            if (!in_array($nest->id, $ids)) $nest->delete();
        });
    }

    public function eggs(): HasMany
    {
        return $this->hasMany(Egg::class);
    }
}
