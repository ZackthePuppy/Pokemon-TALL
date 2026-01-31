<?php

use Livewire\Component;

new class extends Component
{
    public $pokemons = [];
        
    public function getGeneration($value)
    {
        $response = Http::get("https://pokeapi.co/api/v2/generation/{$value}");
        
        // if ($response->successful()) {
        //     $this->pokemons = $response->json()['pokemon_species'] ?? [];

        //     $spriteresponse = Http::get("https://pokeapi.co/api/v2/pokemon/{$this->pokemons[0]["name"]}")->json();

        //     // dd($spriteresponse['sprites']['front_default']);
        // }
        if ($response->successful()) {
            $speciesList = $response->json()['pokemon_species'] ?? [];

            // Add sprite to each Pokémon
            $this->pokemons = collect($speciesList)->map(function ($pokemon) {
                $spriteResponse = Http::get("https://pokeapi.co/api/v2/pokemon/{$pokemon['name']}");

                $sprite = $spriteResponse->successful()
                    ? $spriteResponse->json()['sprites']['front_default']
                    : null;

                // Merge sprite into the existing $pokemon array
                return [...$pokemon, 'sprite' => $sprite];
            })->toArray();
        }
    }
};
?>

<div class="px-3 container mx-auto">

    <div class="flex items-center gap-4 mb-6 text-2xl">
        <label for="generation" class="font-semibold">Generation:</label>
        <select id="generation" wire:change="getGeneration($event.target.value)" class="border pl-3 pr-8 py-1 rounded">
            <option disabled selected>choose...</option>
            @for ($i = 1; $i <= 8; $i++) <option value="{{ $i }}">Gen {{ $i }}</option>
                @endfor
        </select>
    </div>

    <div wire:loading class="">
        <div class="pokemon"></div>
        <div class="text-gray-500 text-xl pt-3">Loading...</div>
    </div>


    <div wire:loading.remove class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @forelse($pokemons as $pokemon)
        <div class="p-4 bg-gray-100 rounded shadow text-center">
            <img src="{{ $pokemon['sprite'] }}" class="mx-auto" alt="">
            <p>
                {{ $pokemon['name'] }}
            </p>
        </div>
        @empty
        <div class="col-span-full text-gray-400 text-xl">No generation loaded yet.</div>
        @endforelse
    </div>
</div>