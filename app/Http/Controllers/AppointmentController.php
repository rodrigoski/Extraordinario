<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\StoreClientAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Mail\AppointmentConfirmationMail;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Appointment::class);

        $appointments = Appointment::with(['client', 'employee', 'service'])
            ->when(Auth::user()->role === 'staff', function ($query) {
                // El staff solo ve sus propias citas.
                $query->where('employee_id', Auth::user()->employee?->id);
            })
            ->latest('appointment_date')
            ->paginate(15);

        return view('appointments.index', compact('appointments'));
    }

    public function create(): View
    {
        $this->authorize('create', Appointment::class);

        return view('appointments.form', [
            'appointment' => new Appointment,
            'clients' => Client::all(),
            'employees' => Employee::all(),
            'services' => Service::where('active', true)->get(),
        ]);
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $this->authorize('create', Appointment::class);

        $data = $request->validated();
        $data['status'] = $data['status'] ?? 'pending';

        $appointment = Appointment::create($data);
        $this->sendConfirmationEmail($appointment);

        return redirect()
            ->route($request->user()->role === 'admin' ? 'admin.appointments.index' : 'staff.appointments.index')
            ->with('success', 'Cita creada correctamente.');
    }

    public function show(Appointment $appointment): View
    {
        $this->authorize('view', $appointment);

        $appointment->load(['client', 'employee', 'service']);

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment): View
    {
        $this->authorize('update', $appointment);

        return view('appointments.form', [
            'appointment' => $appointment,
            'clients' => Client::all(),
            'employees' => Employee::all(),
            'services' => Service::where('active', true)->get(),
        ]);
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $this->authorize('update', $appointment);

        $data = $request->validated();
        $data['status'] = $data['status'] ?? $appointment->status;

        $appointment->update($data);
        $this->sendConfirmationEmail($appointment);

        return redirect()
            ->route($request->user()->role === 'admin' ? 'admin.appointments.index' : 'staff.appointments.index')
            ->with('success', 'Cita actualizada correctamente.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $this->authorize('delete', $appointment);

        $appointment->delete();

        return redirect()->route('admin.appointments.index')->with('success', 'Cita eliminada correctamente.');
    }

    public function downloadPdf(Appointment $appointment)
    {
        $this->authorize('view', $appointment);

        if (! $appointment->pdf_path || ! Storage::disk('public')->exists($appointment->pdf_path)) {
            abort(404, 'El comprobante no existe.');
        }

        return response()->file(Storage::disk('public')->path($appointment->pdf_path));
    }

    public function myAppointments(): View
    {
        $client = Auth::user()->client;

        $appointments = Appointment::query()
            ->when($client, fn ($query) => $query->where('client_id', $client->id))
            ->with(['employee', 'service'])
            ->latest('appointment_date')
            ->paginate(10);

        return view('appointments.my', compact('appointments'));
    }

    public function createForClient(): View
    {
        $this->authorize('create', Appointment::class);

        return view('appointments.client_form', [
            'employees' => Employee::where('active', true)->get(),
            'services' => Service::where('active', true)->get(),
        ]);
    }

    public function storeForClient(StoreClientAppointmentRequest $request): RedirectResponse
    {
        $this->authorize('create', Appointment::class);

        $data = $request->validated();
        $data['status'] = 'pending';

        $appointment = Appointment::create($data);
        $this->sendConfirmationEmail($appointment);

        return redirect()
            ->route('cliente.appointments')
            ->with('success', 'Tu cita fue reservada correctamente.');
    }

    public function clientHistory(): View
    {
        $client = Auth::user()->client;

        $appointments = Appointment::query()
            ->where('client_id', $client?->id)
            ->whereIn('status', ['completed', 'confirmed'])
            ->with(['employee', 'service'])
            ->latest('appointment_date')
            ->paginate(10);

        return view('cliente.historial', compact('appointments'));
    }

    /**
     * Genera el PDF del comprobante, lo guarda y envía el correo
     * de confirmación al cliente.
     */
    protected function sendConfirmationEmail(Appointment $appointment): void
    {
        $appointment->load(['client', 'employee', 'service']);

        $pdfPath = 'appointments/appointment-'.$appointment->id.'.pdf';
        $html = view('pdf.appointment', ['appointment' => $appointment])->render();
        $pdf = Pdf::loadHTML($html);

        Storage::disk('public')->put($pdfPath, $pdf->output());

        $appointment->pdf_path = $pdfPath;
        $appointment->save();

        if ($appointment->client && $appointment->client->email) {
            Mail::to($appointment->client->email)
                ->send(new AppointmentConfirmationMail($appointment, $pdfPath));
        }
    }
}
