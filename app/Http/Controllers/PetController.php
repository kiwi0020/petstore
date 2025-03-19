<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\json;

class PetController extends Controller
{
    //

    public function addPet(Request $request){

        $name = $request->input('name');
        $addId = $request->input('addId');
        $status = $request->input('status');
        $categoryId = $request->input('categoryId');
        $categoryName = $request->input('categoryName');
        $tagId = $request->input('tagId');
        $tagName = $request->input('tagName');

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

            // Wybór większej ilości danych

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

    public function findPet(Request $request){

        $petId = $request->input('petId');

        $response = Http::withHeaders(['Accept' => 'application/json'])->get("https://petstore.swagger.io/v2/pet/{$petId}");

            if($response->successful()){
                return redirect()->route('pets.index')->with('success', 'Pet found successfuly')
                ->with('petId', $petId)
                ->with('petData', $response->json());
            } else {
                return redirect()->route('pets.index')->with('error', "We have no pet with id: {$petId}");
            }

    }

    public function editPet(Request $request)
{
    $editId = $request->input('editId');

    // Pobranie obecnych danych peta przed edycją
    $getResponse = Http::withHeaders(['Accept' => 'application/json'])
                        ->get("https://petstore.swagger.io/v2/pet/{$editId}");

    if (!$getResponse->successful()) {
        return redirect()->route('pets.index')->with('error', 'Pet not found');
    }

    $beforeEditData = $getResponse->json();

    // Przygotowanie danych do aktualizacji - zmiana tylko przesłanych pól
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

    // Wysłanie danych PUT
    $response = Http::withHeaders(['Accept' => 'application/json'])
                    ->put('https://petstore.swagger.io/v2/pet', $updatedData);

    if ($response->successful()) {
        // Pet po edycji
        $afterEditData = $response->json();

        return redirect()->route('pets.index')
            ->with('success', 'Pet edited successfully')
            ->with('beforeEdit', $beforeEditData)
            ->with('afterEdit', $afterEditData);
    } else {
        return redirect()->route('pets.index')->with('error', 'Something went wrong');
    }
}

    public function deletePet(Request $request){

        $delId = $request->input('delId');
        

        $response = Http::withHeaders(['Accept' => 'application/json'])->delete("https://petstore.swagger.io/v2/pet/{$delId}");

        if($response->successful()){
            return redirect()->route('pets.index')->with('success', 'Pet deleted successfully')->with('petId', $delId);
        } else {
            return redirect()->route('pets.index')->with('error', 'Choose ID to delete the pet');
        }
        
    }
}
