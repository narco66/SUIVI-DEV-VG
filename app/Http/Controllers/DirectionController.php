<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Direction;
use App\Models\Department;
use App\Models\User;

class DirectionController extends Controller
{
    public function index()
    {
        $directions = Direction::with(['department', 'director'])->latest()->paginate(15);
        $departments = Department::orderBy('name')->get();
        $users = User::orderBy('name')->get(); // Simplification for demo
        return view('directions.index', compact('directions', 'departments', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'director_id' => 'nullable|exists:users,id',
        ]);
        Direction::create($validated);
        return redirect()->route('directions.index')->with('success', 'Direction ajoutée avec succès.');
    }

    public function update(Request $request, Direction $direction)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'director_id' => 'nullable|exists:users,id',
        ]);
        $direction->update($validated);
        return redirect()->route('directions.index')->with('success', 'Direction modifiée avec succès.');
    }

    public function destroy(Direction $direction)
    {
        $direction->delete();
        return redirect()->route('directions.index')->with('success', 'Direction supprimée avec succès.');
    }
}
