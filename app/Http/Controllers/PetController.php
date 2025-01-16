<?php

namespace App\Http\Controllers;
use App\Enums\PetStatus;
use App\Facades\PetAPIClient;
use App\Models\Category;
use App\Models\Pet;
use App\Models\PetTag;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class PetController extends BaseController
{
    public function index()
    {
        return view(
            'pet.index',
            [
                'categories' => Category::all(),
                'tags' => Tag::all(),
                'statuses' => PetStatus::values(),
            ]
        );
    }

    // TODO: nie jestem pewny jaka dokładnie logika miałaby za tym stać, czy faktycznie zapis tych danych do bazy jest potrzebny, potencjalnie jeśli pełniłbym rolę tylko łącznika to zdecydowanie przesadziłem z logiką
    public function add(Request $request)
    {
        $urls = array_map('trim', explode(',', $request->input('urls')));

        if (empty($urls)) {
            return back(400);
        }

        $request->validate([
            'name' => ['required'],
            'category' => ['required'],
        ]);

        $transactionIsSuccess = DB::transaction(function () use ($request, $urls) {
            $category = Category::where('name', $request->input('category'))->first();
            $tagModels = Tag::whereIn('name', $request->input('tag') ?? [])->get()->all();

            if (!$category || !$tagModels) {
                DB::rollBack();
                return false;
            }

            if (is_bool($apiPet = PetAPIClient::add(
                null,
                $category,
                $request->input('name'),
                $urls,
                PetStatus::from($request->input('status')),
                $tagModels,
            ))) {
                DB::rollBack();

                return false;
            }

            $category = Category::where('id', $apiPet['category']['id'])->first();

            if (!$category) {
                DB::rollBack();
                return false;
            }

            $pet = new Pet([
                'id' => $apiPet['id'],
                'name' => $apiPet['name'],
                'status' => PetStatus::from($apiPet['status']),
                'photo_urls' => $apiPet['photoUrls'],
            ]);

            $pet->category()->associate($category);
            $pet->save();

            foreach ($apiPet['tags'] as $tag) {
                PetTag::create([
                    'pet_id' => $apiPet['id'],
                    'name' => $tag['name'],
                ]);
            }

            return true;
        });

        if (!$transactionIsSuccess) {
            return back(400);
        }

        return back(201);
    }

    public function list()
    {
        return view('pet.list',['pets' => Pet::all()]);
    }

    public function show(int $id)
    {
        $petInfo = PetAPIClient::get($id);

        if (!$petInfo) {
            return back(400);
        }

        return view('pet.show', ['petInfo' => $petInfo]);
    }

    public function editView()
    {
        return view(
            'pet.edit',
            [
                'categories' => Category::all(),
                'tags' => Tag::all(),
                'statuses' => PetStatus::values(),
            ]);
    }

    // TODO: potencjalnie to i dodawanie można by ograć w jednej metodzie z rozpoznawaniem edycji/dodania na podstawie (nie)obecności id
    public function edit(Request $request)
    {
        $urls = array_map('trim', explode(',', $request->input('urls')));

        if (empty($urls)) {
            return back(400);
        }

        $request->validate([
            'name' => ['required'],
            'category' => ['required'],
        ]);

        if (!($pet = Pet::where('id', $request->input('id'))->first())) {
            return back(404);
        }

        $transactionIsSuccess = DB::transaction(function () use ($request, $urls, $pet) {
            $category = Category::where('name', $request->input('category'))->first();
            $tagModels = Tag::whereIn('name', $request->input('tag') ?? [])->get()->all();

            if (!$category || !$tagModels) {
                DB::rollBack();
                return false;
            }

            if (is_bool($apiPet = PetAPIClient::edit(
                null,
                $category,
                $request->input('name'),
                $urls,
                PetStatus::from($request->input('status')),
                $tagModels,
            ))) {
                DB::rollBack();

                return false;
            }

            foreach ($pet->petTags->all() as $tag) {
                $tag->delete();
            }

            $category = Category::where('id', $apiPet['category']['id'])->first();

            if (!$category) {
                DB::rollBack();
                return false;
            }

            $pet->name = $apiPet['name'];
            $pet->status = PetStatus::from($apiPet['status']);
            $pet->photo_urls = $apiPet['photoUrls'];
            $pet->category()->associate($category);
            $pet->save();

            foreach ($apiPet['tags'] as $tag) {
                PetTag::create([
                    'pet_id' => $apiPet['id'],
                    'name' => $tag['name'],
                ]);
            }

            return true;
        });

        if (!$transactionIsSuccess) {
            return back(400);
        }

        return back(201);
    }

    public function deleteView()
    {
        return view('pet.delete');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required'],
        ]);

        if (!($pet = Pet::where('id', $request->input('id'))->first())) {
            return back(404);
        }

        $transactionIsSuccess = DB::transaction(function () use ($request, $pet) {
            if (is_bool(PetAPIClient::delete($request->input('id')))) {
                DB::rollBack();

                return false;
            }

            $pet->delete();

            return true;
        });

        if (!$transactionIsSuccess) {
            return back(400);
        }

        return back(201);
    }
}
