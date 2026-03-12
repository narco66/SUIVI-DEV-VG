<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Institution;
use App\Models\User;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with(['institution', 'commissaire'])->latest()->paginate(15);
        $institutions = Institution::orderBy('name')->get();
        $users = User::orderBy('name')->get(); // Simplification: in prod, filter by role
        return view('departments.index', compact('departments', 'institutions', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'institution_id' => 'required|exists:institutions,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'commissaire_id' => 'nullable|exists:users,id',
        ]);
        Department::create($validated);
        return redirect()->route('departments.index')->with('success', 'Département ajouté avec succès.');
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'institution_id' => 'required|exists:institutions,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'commissaire_id' => 'nullable|exists:users,id',
        ]);
        $department->update($validated);
        return redirect()->route('departments.index')->with('success', 'Département modifié avec succès.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Département supprimé avec succès.');
    }
}
