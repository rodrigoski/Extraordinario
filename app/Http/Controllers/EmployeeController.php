<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Employee::class);

        $employees = Employee::with('user')->latest()->paginate(15);

        return view('employees.index', compact('employees'));
    }

    public function create(): View
    {
        $this->authorize('create', Employee::class);

        return view('employees.form', [
            'employee' => new Employee,
            'users' => User::where('role', 'staff')->get(),
        ]);
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $this->authorize('create', Employee::class);

        Employee::create($request->validated());

        return redirect()->route('admin.employees.index')->with('success', 'Empleado registrado correctamente.');
    }

    public function show(Employee $employee): View
    {
        $this->authorize('view', $employee);

        $employee->load(['user', 'appointments.client', 'appointments.service']);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        $this->authorize('update', $employee);

        return view('employees.form', [
            'employee' => $employee,
            'users' => User::where('role', 'staff')->get(),
        ]);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $this->authorize('update', $employee);

        $employee->update($request->validated());

        return redirect()->route('admin.employees.index')->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $this->authorize('delete', $employee);

        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Empleado eliminado correctamente.');
    }
}
