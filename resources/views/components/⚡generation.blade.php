<?php

use Livewire\Component;
use App\Helpers\PokeApi;

new class extends Component
{
    public $pokemons = [];
    public $pokemonDetails = [];
    public $tip = false;
    public bool $pokemonDetailsModal = false;
    public $typeColors = [
        "normal" => 'bg-normal',
        "fire" => 'bg-fire',
        "water" => 'bg-water',
        "electric" => 'bg-electric',
        "grass" => 'bg-grass',
        "ice" => 'bg-ice',
        "fighting" => 'bg-fighting',
        "poison" => 'bg-poison',
        "ground" => 'bg-ground',
        "flying" => 'bg-flying',
        "psychic" => 'bg-psychic',
        "bug" => 'bg-bug',
        "rock" => 'bg-rock',
        "ghost" => 'bg-ghost',
        "dragon" => 'bg-dragon',
        "dark" => 'bg-dark',
        "steel" => 'bg-steel',
        "fairy" => 'bg-fairy',
    ];
        
    public function getGeneration($value)
    {
        $api = new PokeApi();
        $result = $api->gameGeneration($value);
        $this->pokemons = $result['pokemon_species'];
        $this->tip = true;
    }

    public function viewPokemonDetails($value){
        $api = new PokeApi();
        $result = $api->pokemon($value);
        $this->pokemonDetails = $result;
    }
};
?>

<div class="px-3 container mx-auto">

    <div class="flex items-center gap-4 mb-6 text-2xl">
        <label for="generation" class="font-semibold">Generation:</label>
        <select id="generation" wire:change="getGeneration($event.target.value)"
            class="select border pl-3 pr-8 py-1 rounded">
            <option disabled selected>choose...</option>
            @for ($i = 1; $i <= 8; $i++) <option value="{{ $i }}">Gen {{ $i }}</option>
                @endfor
        </select>
    </div>

    <div wire:target="getGeneration" wire:loading>
        <div>
            <div class="pokemon"></div>
            <div class="text-gray-500 text-xl pt-3">Loading...</div>
        </div>
    </div>


    <div wire:target="getGeneration" wire:loading.remove class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div wire:show="tip" wire:cloak class="col-span-full">
            <p class="text-lg">There are {{ count($pokemons) }} pokemon in this generation</p>
            <p>Click a pokemon to view more</p>
        </div>
        @forelse($pokemons as $key => $pokemon)
        <div wire:key="{{ $key }}" class="p-4 text-center btn" @click="$wire.pokemonDetailsModal = true"
            wire:click="viewPokemonDetails('{{ $pokemon['name'] }}')">
            <p>
                {{ $pokemon['name'] }}
            </p>
        </div>
        @empty
        <div class="col-span-full text-gray-400 text-xl">No generation loaded yet.</div>
        @endforelse
    </div>

    <x-modal wire:model="pokemonDetailsModal" class="">
        <div>
            <div wire:target="viewPokemonDetails" wire:loading>
                <div class="pokemon"></div>
                <div class="text-gray-500 text-xl pt-3">Loading...</div>
            </div>
            <div wire:target="viewPokemonDetails" wire:cloak wire:loading.remove class="uppercase">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>

                <h2 class="bg-red mt-5 p-2 text-xl text-white text-center font-semibold border-black border-y-2">
                    Info
                </h2>

                <div class="gap-5 md:grid grid-cols-2 md:my-5">
                    <div class="grid grid-cols-2 grid-rows-2 max-md:my-3">
                        <img src="{{ $pokemonDetails['sprites']['front_default'] ?? 'https://placehold.co/96x96.png' }}"
                            alt="sprite" class="mx-auto">
                        <img src="{{ $pokemonDetails['sprites']['back_default'] ?? 'https://placehold.co/96x96.png' }}"
                            alt="sprite" class="mx-auto">
                        <img src="{{ $pokemonDetails['sprites']['front_shiny'] ?? 'https://placehold.co/96x96.png' }}"
                            alt="sprite" class="mx-auto">
                        <img src="{{ $pokemonDetails['sprites']['back_shiny'] ?? 'https://placehold.co/96x96.png' }}"
                            alt="sprite" class="mx-auto">
                    </div>
                    <div class="flex flex-col justify-evenly gap-3 text-lg">
                        <div class="border rounded-lg border-red">
                            <div class="px-4 text-white rounded-t-lg bg-red flex justify-between">
                                <p>ID</p>
                                <p>{{ $pokemonDetails['id'] ?? '000'}}</p>
                            </div>
                            <p class="px-4 text-center">{{ $pokemonDetails['name'] ?? 'POKEMON NAME' }}</p>
                        </div>
                        <div class="flex justify-evenly text-center text-white gap-5">
                            @foreach ($pokemonDetails['types'] ?? [] as $type)
                            @php
                            $name = $type['type']['name'];
                            $color = $typeColors[$name] ?? 'bg-gray-400';
                            @endphp

                            <p class="rounded-full px-3 flex-1 {{ $color }}">
                                {{ strtoupper($name) }}
                            </p>
                            @endforeach
                        </div>
                        <div class="border divide-dashed divide-y-2 rounded-lg shadow-xl">
                            <div class="flex justify-evenly py-2">
                                <p>HEIGHT</p>
                                <p>{{ ($pokemonDetails['height'] ?? '00') / 10 }} m</p>
                            </div>
                            <div class="flex justify-evenly py-2">
                                <p>WEIGHT</p>
                                <p>{{ ($pokemonDetails['weight'] ?? '00') / 10 }} kg</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="max-md:mt-5">
                    <div class="flex rounded-xl border-y-2 shadow-xl border-red">
                        <div class="bg-red rounded-l-lg w-3">
                        </div>
                        <!-- Dynamic Pokémon cry with Alpine.js -->
                        <div class="m-3 flex flex-1" x-data="{ src: '{{ $pokemonDetails['cries']['latest'] ?? '' }}' }"
                            x-init="$watch('src', () => $refs.audio.load())" wire:ignore.self
                            wire:key="audio-{{ $pokemonDetails['id'] ?? 'none' }}">

                            <audio x-ref="audio" controls class="flex-1" :src="src">
                                <source :src="src" type="audio/ogg">
                                Your browser does not support the audio element.
                            </audio>

                            <template x-if="!src">
                                <p class="text-gray-400">No cry available</p>
                            </template>
                        </div>
                        <div class="bg-red rounded-r-lg w-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-modal>

</div>