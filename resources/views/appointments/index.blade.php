@extends('layouts.app')

@section('content')
<div class="container">
    <h1 style="text-align: center;">Calendario de Turnos</h1>

    <!-- Calendario de FullCalendar -->
    <div id='calendar'></div>

    <!-- Modal para agregar o editar turnos -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('appointments.store') }}" method="POST" id="appointmentForm">
                @csrf
                <input type="hidden" name="appointment_id" id="appointmentId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="appointmentModalLabel">Agregar/Editar Turno</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Seleccionar Paciente -->
                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Paciente</label>
                            <select name="patient_id" class="form-control" required>
                                <option value="">Seleccione un paciente</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Fecha</label>
                            <input type="date" name="date" class="form-control" id="appointmentDate" readonly required>
                        </div>
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Hora de Inicio</label>
                            <input type="time" name="start_time" class="form-control" id="appointmentStartTime" readonly required>
                        </div>
                        <div class="mb-3">
                            <label for="end_time" class="form-label">Hora de Fin</label>
                            <input type="time" name="end_time" class="form-control" id="appointmentEndTime" readonly required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Menu desplegable para las opciones -->
    <div id="contextMenu" class="dropdown-menu" style="display:none; position:absolute;">
        <a class="dropdown-item" href="#" id="editAppointment">Editar</a>
        <a class="dropdown-item" href="#" id="addAppointment">Agregar Nuevo</a>
    </div>
</div>

<!-- Scripts de FullCalendar -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            slotDuration: '00:20:00',
            slotMinTime: '08:00:00',
            slotMaxTime: '19:00:00',
            allDaySlot: false,
            hiddenDays: [0, 6], // Ocultar domingo y sábado
            selectable: true,
            editable: true,
            eventOverlap: true, // Permitir superposición
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridDay,timeGridWeek,dayGridMonth'
            },
            events: @json($appointments), // Cargar eventos desde el backend

            // Al hacer clic en un evento (turno), mostrar el menú de edición/agregar
            eventClick: function(info) {
                showContextMenu(info.jsEvent, 'edit', info);
            },

            // Comportamiento normal para las celdas vacías
            select: function(info) {
                showContextMenu(info.jsEvent, 'add', info);
            }
        });

        calendar.render();

        // Función para mostrar el menú contextual en la posición del clic
        function showContextMenu(jsEvent, action, info) {
            var menu = document.getElementById('contextMenu');
            menu.style.display = 'block';
            menu.style.left = jsEvent.pageX + 'px';
            menu.style.top = jsEvent.pageY + 'px';

            // Editar un turno existente
            document.getElementById('editAppointment').onclick = function() {
                if (action === 'edit') {
                    $('#appointmentModalLabel').text('Editar Turno');
                    $('#appointmentForm').attr('action', '/appointments/' + info.event.id);
                    $('#appointmentForm').append('<input type="hidden" name="_method" value="PUT">');
                    $('#appointmentDate').val(info.event.start.toISOString().split('T')[0]);
                    $('#appointmentStartTime').val(info.event.start.toISOString().split('T')[1].slice(0, 5));
                    $('#appointmentEndTime').val(info.event.end.toISOString().split('T')[1].slice(0, 5));
                    $('#appointmentModal').modal('show');
                }
                menu.style.display = 'none';
            };

            // Agregar un nuevo turno en una celda vacía
            document.getElementById('addAppointment').onclick = function() {
                if (action === 'add') {
                    $('#appointmentModalLabel').text('Agregar Turno');
                    $('#appointmentForm').attr('action', '{{ route("appointments.store") }}');
                    $('#appointmentDate').val(info.startStr.split('T')[0]);
                    $('#appointmentStartTime').val(info.startStr.split('T')[1].slice(0, 5));
                    $('#appointmentEndTime').val(info.endStr.split('T')[1].slice(0, 5));
                    $('#appointmentModal').modal('show');
                }
                menu.style.display = 'none';
            };
        }

        // Ocultar el menú cuando se haga clic en cualquier parte de la página
        document.addEventListener('click', function() {
            document.getElementById('contextMenu').style.display = 'none';
        });
    });
</script>
@endsection
