<?php

namespace App\Classes\Pterodactyl;

use App\Exceptions\PterodactylRequestException;
use App\Settings\PterodactylSettings;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * ControlPanel Pterodactyl Api wrapper
 */
class PterodactylClient
{
    /**
     * option to pull more than the default 50 from pterodactyl
     */
    public const PER_PAGE = 200;

    public PendingRequest $client;

    public function __construct(PterodactylSettings $settings)
    {
        $this->client = $this->createClient($settings);
    }

    /**
     * @param PterodactylSettings $settings
     * @return PendingRequest
     */
    public function createClient(PterodactylSettings $settings): PendingRequest
    {
        if (!str_ends_with($settings->url, '/')) {
            $settings->url .= '/';
        }

        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $settings->api_key,
            'Content-type' => 'application/json',
            'Accept' => 'Application/vnd.pterodactyl.v1+json',
        ])->baseUrl($settings->url . 'api' . '/');
    }

    /**
     * @throws PterodactylRequestException
     */
    public function getLocations(): PromiseInterface|Response
    {
        $response = $this->client->get('application/locations?per_page=' . self::PER_PAGE);
        return $this->handleResponse($response);
    }

    /**
     * @throws PterodactylRequestException
     */
    public function getEggs(int $nestId): PromiseInterface|Response
    {
        $response = $this->client->get("application/nests/$nestId/eggs?include=nest,variables&per_page=" . self::PER_PAGE);
        return $this->handleResponse($response);
    }

    /**
     * @throws PterodactylRequestException
     */
    public function getNests(): PromiseInterface|Response
    {
        $response = $this->client->get('application/nests?per_page=' . self::PER_PAGE);
        return $this->handleResponse($response);
    }

    /**
     * @throws PterodactylRequestException
     */
    public function getNodes(): PromiseInterface|Response
    {
        $response = $this->client->get('application/nodes?per_page=' . self::PER_PAGE);
        return $this->handleResponse($response);
    }

    /**
     * Get user from Pterodactyl using Pterodactyl id
     *
     * @param int $pterodactylId
     * @return PromiseInterface|Response
     * @throws PterodactylRequestException
     */
    public function getUser(int $pterodactylId): PromiseInterface|Response
    {
        $response = $this->client->get("application/users/$pterodactylId");
        return $this->handleResponse($response);
    }

    /**
     * Get user from Pterodactyl using email
     *
     * @param string $email
     * @return PromiseInterface|Response
     * @throws PterodactylRequestException
     */
    public function getUserByEmail(string $email): PromiseInterface|Response
    {
        $response = $this->client->get("application/users?filter[email]=$email");
        return $this->handleResponse($response);
    }

    /**
     * @param array $data Array containing the necessary params.
     * @throws PterodactylRequestException
     */
    public function createUser(array $data): PromiseInterface|Response
    {
        $response = $this->client->post("application/users", $data);
        return $this->handleResponse($response);
    }

    /**
     * @param PromiseInterface|Response $response
     * @return PromiseInterface|Response
     * @throws PterodactylRequestException
     */
    public function handleResponse(PromiseInterface|Response $response): Response|PromiseInterface
    {
        $message = __("Request failed with status code ':code' response :body", [
            'code' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->failed()) throw new PterodactylRequestException($message);

        return $response;
    }
}
