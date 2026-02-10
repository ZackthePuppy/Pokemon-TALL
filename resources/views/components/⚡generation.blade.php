<?php

use Livewire\Component;

new class extends Component
{
    public $pokemons = [];
    public $pokemonDetails = [];
    public $tip = false;
        
    public function getGeneration($value)
    {
        $response = Http::get("https://pokeapi.co/api/v2/generation/{$value}");
        if ($response->successful()) {
            $this->pokemons = $response->json()['pokemon_species'] ?? [];
            $this->tip = true;
        }
    }

    public function viewPokemonDetails($value){
        $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$value}");
        if ($response->successful()) {
            $this->pokemonDetails = $response->json();
            dd($this->pokemonDetails['id']);
        }
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

    <div wire:target="getGeneration" wire:loading class="">
        <div class="pokemon"></div>
        <div class="text-gray-500 text-xl pt-3">Loading...</div>
    </div>


    <div wire:target="getGeneration" wire:loading.remove class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div wire:show="tip" wire:cloak class="col-span-full">
            <p class="text-lg">There are {{ count($pokemons) }} pokemon in this generation</p>
            <p>Click a pokemon to view more</p>
        </div>
        @forelse($pokemons as $pokemon)
        <div class="p-4 text-center btn" wire:click="viewPokemonDetails('{{ $pokemon['name'] }}')"
            onclick="details_modal.showModal()">
            <p>
                {{ $pokemon['name'] }}
            </p>
        </div>
        @empty
        <div class="col-span-full text-gray-400 text-xl">No generation loaded yet.</div>
        @endforelse
    </div>


    {{-- <div class="p-4 text-center btn" onclick="test.showModal()">
        awwww
    </div>

    <dialog id="test" class="modal" wire:ignore>
        <div class="modal-box">
            <div>
                <div wire:loading wire:target="viewPokemonDetails" class="">
                    <div class="pokemon"></div>
                    <div class="text-gray-500 text-xl pt-3">Loading...</div>
                </div>
                <div class="uppercase" wire:loading.remove wire:target="viewPokemonDetails">
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    </form>
                    <h2 class="bg-red mt-5 p-2 text-xl text-white text-center font-semibold border-black border-y-2">Info
                    </h2>

                    <div class="gap-5 md:grid grid-cols-2 my-5">
                        <img src="https://placehold.co/256x256.png" alt="">
                        <div class="flex flex-col justify-evenly text-lg">
                            <div class="border rounded-lg border-red">
                                <div class="px-4 text-white rounded-t-lg bg-red flex justify-between">
                                    <p>ID</p>
                                    <p>000</p>
                                </div>
                                <p class="px-4 text-center">TITLE</p>
                            </div>
                            <div class="flex justify-evenly text-center text-white gap-5">
                                <p class="rounded-full bg-red px-3 flex-1">FIRE</p>
                                <p class="rounded-full bg-blue px-3 flex-1">WATER</p>
                            </div>
                            <div class="border divide-dashed divide-y-2 rounded-lg shadow-xl">
                                <div class="flex justify-evenly py-2">
                                    <p>HEIGHT</p>
                                    <p>7</p>
                                </div>
                                <div class="flex justify-evenly py-2">
                                    <p>WEIGHT</p>
                                    <p>7</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex rounded-xl border-y-2 shadow-xl border-red">
                            <div class="bg-red rounded-l-lg w-3">
                            </div>
                            <audio controls class="flex-1 m-3">
                                <source
                                    src="https://raw.githubusercontent.com/PokeAPI/cries/main/cries/pokemon/latest/1.ogg"
                                    type="audio/ogg" />
                                Your browser does not support the audio element.
                            </audio>
                            <div class="bg-red rounded-r-lg w-3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog> --}}



    <dialog id="details_modal" class="modal" wire:ignore>
        <div class="modal-box">
            <div>
                <div wire:loading wire:target="viewPokemonDetails" class="">
                    <div class="pokemon"></div>
                    <div class="text-gray-500 text-xl pt-3">Loading...</div>
                </div>
                <div class="uppercase" wire:loading.remove wire:target="viewPokemonDetails">
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    </form>
                    <h2 class="bg-red mt-5 p-2 text-xl text-white text-center font-semibold border-black border-y-2">Info
                    </h2>

                    <div class="gap-5 md:grid grid-cols-2 my-5">
                        <img src="https://placehold.co/256x256.png" alt="">
                        <div class="flex flex-col justify-evenly text-lg">
                            <div class="border rounded-lg border-red">
                                <div class="px-4 text-white rounded-t-lg bg-red flex justify-between">
                                    <p>ID</p>
                                    <p>{{ $pokemonDetails['id'] ?? '000' }}</p>
                                </div>
                                <p class="px-4 text-center">TITLE</p>
                            </div>
                            <div class="flex justify-evenly text-center text-white gap-5">
                                <p class="rounded-full bg-red px-3 flex-1">FIRE</p>
                                <p class="rounded-full bg-blue px-3 flex-1">WATER</p>
                            </div>
                            <div class="border divide-dashed divide-y-2 rounded-lg shadow-xl">
                                <div class="flex justify-evenly py-2">
                                    <p>HEIGHT</p>
                                    <p>7</p>
                                </div>
                                <div class="flex justify-evenly py-2">
                                    <p>WEIGHT</p>
                                    <p>7</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex rounded-xl border-y-2 shadow-xl border-red">
                            <div class="bg-red rounded-l-lg w-3">
                            </div>
                            <audio controls class="flex-1 m-3">
                                <source
                                    src="https://raw.githubusercontent.com/PokeAPI/cries/main/cries/pokemon/latest/1.ogg"
                                    type="audio/ogg" />
                                Your browser does not support the audio element.
                            </audio>
                            <div class="bg-red rounded-r-lg w-3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</div>