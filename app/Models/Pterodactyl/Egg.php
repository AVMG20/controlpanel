<?php

namespace App\Models\Pterodactyl;

use App\Classes\PterodactylClient;
use App\Models\Configuration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use function now;

class Egg extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'nest_id',
        'name',
        'description',
        'docker_image',
        'startup',
        'environment',
        'updated_at',
    ];

    public static function syncEggs()
    {
        /** @var PterodactylClient $client */
        $client = app(PterodactylClient::class);

        Nest::all()->each(function (Nest $nest) use ($client) {
            $eggs = $client->getEggs($nest->id)->json()['data'];

            foreach ($eggs as $egg) {
                $array = [];
                $environment = [];

                $array['id'] = $egg['attributes']['id'];
                $array['nest_id'] = $egg['attributes']['nest'];
                $array['name'] = $egg['attributes']['name'];
                $array['description'] = $egg['attributes']['description'];
                $array['docker_image'] = $egg['attributes']['docker_image'];
                $array['startup'] = $egg['attributes']['startup'];
                $array['updated_at'] = now();

                //get environment variables
                foreach ($egg['attributes']['relationships']['variables']['data'] as $variable) {
                    $environment[$variable['attributes']['env_variable']] = $variable['attributes']['default_value'];
                }

                $array['environment'] = json_encode([$environment]);

                self::query()->updateOrCreate([
                    'id' => $array['id']
                ], array_diff_key($array, array_flip(["id"]))
                );
            }

            self::removeDeletedEggs($nest, $eggs);
        });
    }

    /**
     * @description remove eggs that have been deleted on pterodactyl
     * @param Nest $nest
     * @param array $eggs
     */
    private static function removeDeletedEggs(Nest $nest, array $eggs): void
    {
        $ids = array_map(function ($data) {
            return $data['attributes']['id'];
        }, $eggs);

        $nest->eggs()->each(function (Egg $egg) use ($ids) {
            if (!in_array($egg->id, $ids)) $egg->delete();
        });
    }

    /**
     * @return array
     */
    public function getEnvironmentVariables(): array
    {
        $array = [];

        foreach (json_decode($this->environment) as $variable) {
            foreach ($variable as $key => $value) {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * @return BelongsTo
     */
    public function nest(): BelongsTo
    {
        return $this->belongsTo(Nest::class);
    }

    public function configurations(): BelongsToMany
    {
        return $this->belongsToMany(Configuration::class);
    }
}
