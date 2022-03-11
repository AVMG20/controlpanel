<?php

namespace App\Http\Requests\NotificationTemplate\Server;

use App\Models\Configuration;
use App\Models\Pterodactyl\Egg;
use App\Models\Pterodactyl\Location;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;

class ServerStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:60'],
            'egg' => ['required', 'integer', 'exists:eggs,id'],
            'location' => ['required', 'integer', 'exists:locations,id'],
            'configuration' => ['required', 'integer', 'exists:configurations,id'],
        ];
    }

    /**
     * Validating the relations of the given attributes
     * To prevent ppl from using configurations not meant for a certain egg or location
     *
     *
     * @return void
     */
    protected function passedValidation()
    {
        $configuration = Configuration::query()
            ->whereHas('eggs', function (Builder $builder) {
                $builder->where('id', '=', $this->request->get('egg'));
            })
            ->whereHas('locations', function (Builder $builder) {
                $builder->where('id', '=', $this->request->get('location'));
            })
            ->where('id', '=', $this->request->get('configuration'))
            ->firstOrFail();

        $egg = Egg::find( $this->request->get('egg'));

        $location = Location::find( $this->request->get('location'));

        $this->request->add(compact('configuration', 'egg', 'location'));
    }
}
