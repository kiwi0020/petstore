<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\json;

class PetController extends Controller
{

    // Funkcja dodania Peta

    public function addPet(Request $request){

        // Pobranie pól z formnularzy do zmiennych

        $name = $request->input('name');
        $addId = $request->input('addId');
        $status = $request->input('status');
        $categoryId = $request->input('categoryId');
        $categoryName = $request->input('categoryName');
        $tagId = $request->input('tagId');
        $tagName = $request->input('tagName');

        // Ustanowienie łączności z API oraz przesłanie danych z formularzy

        $response = Http::post(
            'https://petstore.swagger.io/v2/pet',
            [   
                'id' => $addId,
                
                'name' => $name,
                'category' => [
                    'id' => $categoryId,
                    'name' => $categoryName,
                ],
                'tags' => [
                    [
                        'id' => $tagId,
                        'name' => $tagName,
                    ]
                ],
                'status' => $status
                
            ]);

            // Walidacja operacji i wyświetlenie wyniku w widoku pets.pets.

            if($response->successful()){

                $petData = [
                    'id' => $addId ?? 'not set',
                    'name' => $name ?? 'not set',
                    'status' => $status ?? 'not set',
                    'category' => [
                        'id' => $categoryId ?? 'not set',
                        'name' => $categoryName ?? 'not set',
                    ],
                    'tags' => [
                        [
                            'id' => $tagId ?? 'not set',
                            'name' => $tagName ?? 'not set',
                        ]
                    ]
                ];

                return redirect()->route('pets.index')
                    ->with('success', 'Pet added successfully')
                    ->with('petData', $petData);

                    
            } else {
                return redirect()->route('pets.index')->with('error', 'Something went wrong');
            }

    }

    // Funkcja znalezienia Peta

    public function findPet(Request $request){

        // Pobranie pól z formnularzy do zmiennych

        $petId = $request->input('petId');

        // Ustanowienie łączności z API oraz pobranie Peta 

        $response = Http::withHeaders(['Accept' => 'application/json'])->get("https://petstore.swagger.io/v2/pet/{$petId}");


        // Walidacja oraz przesłanie wyniku do widoku

        if($response->successful()){
            return redirect()->route('pets.index')->with('success', 'Pet found successfuly')
            ->with('petData', $response->json());
        } else {
            return redirect()->route('pets.index')->with('error', "We have no pet with id: {$petId}");
        }

    }

    // Funkcja edycji Peta

    public function editPet(Request $request)

        // Pobranie pól z formnularzy do zmiennych
    {
        $editId = $request->input('editId');

        // Połączenie z Api i odczytanie danych Peta o wybranym ID. Jeśli Pet nie został znaleziony, lub podane zostało tylko ID wyświetli komunikat "Pet not found"

        $getResponse = Http::withHeaders(['Accept' => 'application/json'])
                            ->get("https://petstore.swagger.io/v2/pet/{$editId}");

        if (!$getResponse->successful()) {
            return redirect()->route('pets.index')->with('error', 'Pet not found');
        }

        // Wczytanie do zmiennej struktury Peta o ID przesłanym w formularzu przed edycją 

        $beforeEditData = $getResponse->json();

        // Wczytanie do tablicy edytowanej struktury danych Peta. Jeśłi jakieś pole formularza zostało puste, nie zmieni się ono.

        $updatedData = [
            'id' => $editId,
            'name' => $request->filled('editName') ? $request->input('editName') : ($beforeEditData['name'] ?? 'not set'),
            'status' => $request->filled('editStatus') ? $request->input('editStatus') : ($beforeEditData['status'] ?? 'not set'),
            'category' => [
                'id' => $request->filled('editCategoryId') ? $request->input('editCategoryId') : ($beforeEditData['category']['id'] ?? null),
                'name' => $request->filled('editCategoryName') ? $request->input('editCategoryName') : ($beforeEditData['category']['name'] ?? null),
            ],
            'tags' => [
                [
                    'id' => $request->filled('editTagId') ? $request->input('editTagId') : ($beforeEditData['tags'][0]['id'] ?? null),
                    'name' => $request->filled('editTagName') ? $request->input('editTagName') : ($beforeEditData['tags'][0]['name'] ?? null),
                ]
            ],
            'photoUrls' => $beforeEditData['photoUrls'] ?? ["string"],
        ];

        // Nawiązanie połączenia z Api i przesłanie edytowanych danych Peta

        $response = Http::withHeaders(['Accept' => 'application/json'])
                        ->put('https://petstore.swagger.io/v2/pet', $updatedData);


        // Walidacja oraz przesłanie wyniku do widoku

        if ($response->successful()) {

            $afterEditData = $response->json();

            return redirect()->route('pets.index')
                ->with('success', 'Pet edited successfully')
                ->with('beforeEdit', $beforeEditData)
                ->with('afterEdit', $afterEditData);
        } else {
            return redirect()->route('pets.index')->with('error', 'Something went wrong');
        }
    }

    // Funkcja usunięcia Peta

    public function deletePet(Request $request){

        // Pobranie pól z formnularzy do zmiennych

        $delId = $request->input('delId');
        
        // Ustanowienie łączności z API oraz usunięcie peta o 'id' pobranym z formularza

        $response = Http::withHeaders(['Accept' => 'application/json'])->delete("https://petstore.swagger.io/v2/pet/{$delId}");

        // Walidacja oraz przesłanie wyniku do widoku

        if($response->successful()){
            return redirect()->route('pets.index')->with('success', 'Pet deleted successfully')->with('delId', $delId);
        } else {
            return redirect()->route('pets.index')->with('error', 'Choose ID to delete the pet');
        }
        
    }
}
