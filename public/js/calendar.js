document.addEventListener('DOMContentLoaded', function() {
    const calendar = document.getElementById('calendar');
    
    // Renderiza el calendario inicial
    renderCalendar();

    function renderCalendar() {
        const hours = ['08:00', '08:20', '08:40', '09:00', '09:20', '09:40', '10:00', '10:20', '10:40', '11:00', '11:20', '11:40', '12:00', '12:20', '12:40', '13:00', '13:20', '13:40', '14:00', '14:20', '14:40', '15:00', '15:20', '15:40', '16:00', '16:20', '16:40', '17:00', '17:20', '17:40', '18:00', '18:20', '18:40', '19:00'];
        const days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        calendar.innerHTML = ''; // Limpiar el contenido existente

        days.forEach((day, dayIndex) => {
            hours.forEach((hour, hourIndex) => {
                const timeSlot = document.createElement('div');
                timeSlot.classList.add('time-slot');
                timeSlot.dataset.day = day;
                timeSlot.dataset.hour = hour;
                timeSlot.addEventListener('click', openModal);

                calendar.appendChild(timeSlot);
            });
        });
    }

    function openModal(event) {
        const timeSlot = event.currentTarget;
        const day = timeSlot.dataset.day;
        const hour = timeSlot.dataset.hour;
        
        document.getElementById('appointmentDate').value = ''; // Deja vacío el campo de fecha en nuevo turno
        document.getElementById('appointmentTime').value = ''; // Deja vacío el campo de hora
        document.getElementById('appointment_id').value = '';  // Deja vacío para nuevo turno

        // Muestra el modal para agregar
        $('#appointmentModal').modal('show');
    }

    // Guardar o editar turno (a través del modal)
    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const appointmentId = formData.get('appointment_id');
        const url = appointmentId ? `/appointments/${appointmentId}/update` : '/appointments/store';

        // Realiza la solicitud AJAX para guardar o editar
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresca el calendario o agrega el nuevo evento en el front
                renderCalendar();
                $('#appointmentModal').modal('hide');
            } else {
                alert('Error al guardar el turno');
            }
        });
    });

    // Funcionalidad de mover eventos
    let draggedEvent = null;

    document.querySelectorAll('.event').forEach(event => {
        event.addEventListener('dragstart', (e) => {
            draggedEvent = e.currentTarget;
        });

        event.addEventListener('dragend', (e) => {
            const newSlot = e.target.closest('.time-slot');
            if (newSlot) {
                // Cambiar el horario del evento y actualizar en la base de datos
                updateAppointment(draggedEvent.dataset.id, newSlot.dataset.day, newSlot.dataset.hour);
            }
        });
    });

    function updateAppointment(id, newDay, newHour) {
        fetch(`/appointments/${id}/update`, {
            method: 'POST',
            body: JSON.stringify({ day: newDay, hour: newHour }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderCalendar();
            } else {
                alert('Error al actualizar el turno');
            }
        });
    }
});
