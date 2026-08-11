<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        return match (Auth::user()->role) {
            'admin' => $this->admin($request),
            'staff' => $this->staff($request),
            'cliente' => $this->cliente($request),
            default => redirect()->route('login'),
        };
    }

    public function admin(Request $request)
    {
        return view('dashboard', [
            'clients' => Client::count(),
            'employees' => Employee::where('active', true)->count(),
            'services' => Service::where('active', true)->count(),
            'appointmentsToday' => Appointment::whereDate('appointment_date', today())->count(),
            'pendingAppointments' => Appointment::where('status', 'pending')->count(),
            'salesToday' => Appointment::whereDate('appointment_date', today())->with('service')->get()->sum(fn ($appointment) => $appointment->service?->price ?? 0),
            'upcomingAppointments' => Appointment::with(['client', 'employee', 'service'])
                ->whereDate('appointment_date', '>=', today())
                ->whereNotIn('status', ['cancelled', 'completed'])
                ->orderBy('appointment_date')
                ->orderBy('start_time')
                ->limit(6)
                ->get(),
        ]);
    }

    public function staff(Request $request)
    {
        $employee = Auth::user()->employee;

        return view('dashboard', [
            'myAppointmentsToday' => $employee ? Appointment::where('employee_id', $employee->id)->whereDate('appointment_date', today())->count() : 0,
            'pendingAppointments' => $employee ? Appointment::where('employee_id', $employee->id)->where('status', 'pending')->count() : 0,
            'completedAppointments' => $employee ? Appointment::where('employee_id', $employee->id)->where('status', 'completed')->count() : 0,
            'upcomingAppointments' => $employee
                ? Appointment::with(['client', 'service'])
                    ->where('employee_id', $employee->id)
                    ->whereDate('appointment_date', '>=', today())
                    ->whereNotIn('status', ['cancelled', 'completed'])
                    ->orderBy('appointment_date')
                    ->orderBy('start_time')
                    ->limit(6)
                    ->get()
                : collect(),
        ]);
    }

    public function cliente(Request $request)
    {
        $client = Auth::user()->client;

        return view('dashboard', [
            'nextAppointment' => $client ? Appointment::where('client_id', $client->id)->where('appointment_date', '>=', today())->orderBy('appointment_date')->first() : null,
            'totalAppointments' => $client ? Appointment::where('client_id', $client->id)->count() : 0,
            'lastService' => $client ? Appointment::where('client_id', $client->id)->with('service')->latest('appointment_date')->first() : null,
            'upcomingAppointments' => $client
                ? Appointment::with(['employee', 'service'])
                    ->where('client_id', $client->id)
                    ->whereDate('appointment_date', '>=', today())
                    ->whereNotIn('status', ['cancelled'])
                    ->orderBy('appointment_date')
                    ->orderBy('start_time')
                    ->limit(6)
                    ->get()
                : collect(),
        ]);
    }
}
