<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hero;


class HeroController extends Controller
{

public function index()
{
    $heroes = Hero::latest()->get();
    return view('admin.heroes.index', compact('heroes'));
}

public function create()
{
    return view('admin.heroes.create');
}

public function store(Request $request)
{
    $hero = Hero::create($request->all());

    if ($request->hasFile('image')) {
        $hero->addMediaFromRequest('image')
             ->toMediaCollection('hero_image');
    }

    return redirect()->route('admin.heroes.index')
        ->with('success','Hero created');
}

public function edit(Hero $hero)
{
    return view('admin.heroes.edit', compact('hero'));
}

public function update(Request $request, Hero $hero)
{
    $hero->update($request->all());

    if ($request->hasFile('image')) {
        $hero->clearMediaCollection('hero_image');

        $hero->addMediaFromRequest('image')
             ->toMediaCollection('hero_image');
    }

    return redirect()->route('admin.heroes.index')
        ->with('success','Updated');
}

public function destroy(Hero $hero)
{
    $hero->delete();
    return back()->with('success','Deleted');
}
}
