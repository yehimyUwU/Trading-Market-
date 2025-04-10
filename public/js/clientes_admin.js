fetch("../../controllers/php/obtener_clientes_admin.php")
    .then(response => {
        console.log("Respuesta del servidor:", response);
        return response.json(); // Procesa la respuesta como JSON
    })
    .then(data => {
        console.log("Datos recibidos:", data);
        if (data.success) {
            const clientes = data.data;
            const tableBody = document.querySelector("#clientesTable tbody");
            
            // Agrega las filas a la tabla
            clientes.forEach(cliente => {
                const row = document.createElement("tr");
            
                row.innerHTML = `
                    <td>${cliente.id_usuario}</td>
                    <td>${cliente.tipo_documento}</td>
                    <td>${cliente.documento}</td>
                    <td>${cliente.nombre}</td>
                    <td>${cliente.apellido}</td>
                    <td>${cliente.fecha_nacimiento}</td>
                    <td>${cliente.genero}</td>
                    <td>${cliente.email}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="eliminarCliente(${cliente.id_usuario})">Borrar</button>
                    </td>
                `;
            
                tableBody.appendChild(row);
            });
            
        } else {
            console.error("Error en los datos:", data.message);
        }
    })
    .catch(error => {
        console.error("Error al cargar los datos:", error);
    });


    function eliminarCliente(id) {
        if (confirm("¿Estás seguro de que deseas eliminar este cliente?")) {
            fetch(`../../controllers/php/eliminar_clientes_admin.php?id=${id}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Cliente eliminado exitosamente.");
                    // Remover la fila de la tabla
                    const rowToDelete = document.querySelector(`button[onclick="eliminarCliente(${id})"]`).closest("tr");
                    rowToDelete.remove();
                } else {
                    alert("Error al eliminar el cliente: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error al eliminar el cliente:", error);
                alert("Error inesperado. Intente nuevamente más tarde.");
            });
        }
    }
    