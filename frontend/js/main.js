document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll("form");
    forms.forEach(form => {
        form.addEventListener("submit", async e => {
            e.preventDefault();

            // Obtener los datos del formulario
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            try {
                // Hacer una solicitud POST al backend
                const response = await fetch('http://localhost:9000/api/reservas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data),
                });

                if (response.ok) {
                    const result = await response.json();
                    alert(result.mensaje);  // Mostrar mensaje de éxito
                } else {
                    const error = await response.json();
                    alert('Error: ' + error.error);  // Mostrar mensaje de error
                }
            } catch (err) {
                alert('Error al conectar con el servidor.');
            }
        });
    });
});

