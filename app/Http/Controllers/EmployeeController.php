<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Employee::with('department')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:employees',
            'phone' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'joining_date' => 'required|date',
        ]);
    
        return Employee::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return $employee->load('department');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:employees,email,' . $employee->id,
        'phone' => 'required|string',
        'department_id' => 'required|exists:departments,id',
        'joining_date' => 'required|date',
    ]);

    $employee->update($request->all());

    return $employee;
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
{
    $employee->delete();
    return response()->json(['message' => 'Employee deleted successfully']);
}


// Manager: View employees in own department
public function managerIndex()
{
    $managerDeptId = Auth::user()->department_id;

    return Employee::where('department_id', $managerDeptId)->get();
}

// Manager: Update employees in own department
public function managerUpdate(Request $request, Employee $employee)
{
    $managerDeptId = Auth::user()->department_id;

    // Check access
    if ($employee->department_id !== $managerDeptId) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $request->validate([
        'name' => 'required|string',
        'phone' => 'required|string',
        'joining_date' => 'required|date',
    ]);

    $employee->update($request->only(['name', 'phone', 'joining_date']));

    return $employee;
}
//employee can see profile
public function profile()
{
    $user = Auth::user();

    return response()->json([
        'name' => $user->name,
        'email' => $user->email,
        'department_id' => $user->department_id,
        'role' => $user->getRoleNames()->first() ?? 'no role assigned'
    ]);
}

}
