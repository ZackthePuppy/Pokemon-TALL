<?php

namespace App\Helpers;

class PokeApi
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://pokeapi.co/api/v2/';
    }

    /** BASIC USAGE
     * use app\Helper\PokeApi;

        $api = new PokeApi();

        // Fetch a single Pokémon
        $pikachu = $api->pokemon('pikachu');
        echo $pikachu['name']; // pikachu

        // Fetch Generation I
        $gen1 = $api->gameGeneration(1);
        echo $gen1['name']; // generation-i

        // List Pokémon with limit/offset
        $list = $api->resourceList('pokemon', ['limit' => 10, 'offset' => 20]);
        print_r($list);

        // Fetch a move
        $thunderbolt = $api->move('thunderbolt');
        print_r($thunderbolt['power']);
     */

    /**
     * Generic fetch method for any endpoint
     *
     * @param string $endpoint
     * @param mixed|null $lookUp
     * @param array $queryParams
     * @return array
     * @throws \Exception
     */
    public function fetch(string $endpoint, mixed $lookUp = null, array $queryParams = []): array
    {
        $url = $this->baseUrl . $endpoint;

        if ($lookUp !== null) {
            $url .= '/' . $lookUp;
        }

        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        return $this->sendRequest($url);
    }

    /**
     * Convenience methods for common endpoints
     */
    public function pokemon($lookUp): array
    {
        return $this->fetch('pokemon', $lookUp);
    }

    public function gameGeneration($lookUp): array
    {
        return $this->fetch('generation', $lookUp);
    }

    public function pokedex($lookUp): array
    {
        return $this->fetch('pokedex', $lookUp);
    }

    public function berry($lookUp): array
    {
        return $this->fetch('berry', $lookUp);
    }

    public function move($lookUp): array
    {
        return $this->fetch('move', $lookUp);
    }

    public function type($lookUp): array
    {
        return $this->fetch('type', $lookUp);
    }

    public function ability($lookUp): array
    {
        return $this->fetch('ability', $lookUp);
    }

    /**
     * List resources with optional limit/offset
     */
    public function resourceList(string $endpoint, array $queryParams = []): array
    {
        return $this->fetch($endpoint, null, $queryParams);
    }

    /**
     * Send HTTP request and decode JSON
     *
     * @param string $url
     * @return array
     * @throws \Exception
     */
    private function sendRequest(string $url): array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        $data = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($http_code !== 200) {
            throw new \Exception("PokéAPI request failed with HTTP code {$http_code}");
        }

        $decoded = json_decode($data, true);

        if ($decoded === null) {
            throw new \Exception("Failed to decode JSON from PokéAPI.");
        }

        return $decoded;
    }
}
