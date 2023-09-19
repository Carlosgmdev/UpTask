(function() {  
    const d = document;
    obtenerTareas();
    let tareas = [];
    let filtradas = [];

    const nuevaTareaBtn = d.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', function() {
        mostrarFormulario();
    });

    const filtros = d.querySelectorAll('#filtros input[type="radio"]');
    filtros.forEach(radio => {
        radio.addEventListener('input', filtrarTareas);
    });

    function filtrarTareas(e) {
        const filtro = e.target.value;
        if(filtro !== '') {
            filtradas = tareas.filter(tarea => tarea.estado === filtro);
        } else {
            filtradas = [];
        }
        mostrarTareas();
    };

    function mostrarFormulario(editar = false, tarea = {}) {
        const modal = d.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>${editar ? 'Editar tarea' : 'Añade una nueva tarea'}</legend>
                <div class="campo">
                    <label for="tarea">Tarea</label>
                    <input 
                        type="text" 
                        name="tarea" 
                        id="tarea" 
                        placeholder="${tarea.nombre ? 'Edita la tarea' : 'Añade una tarea'}"
                        value="${tarea.nombre ? tarea.nombre : ''}">
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="${editar ? 'Guardar cambios' : 'Añadir tarea'}">
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
        `;
        
        setTimeout(() => {
            const formulario = d.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        modal.addEventListener('click', (e) => {
            e.preventDefault();
            if(e.target.classList.contains('cerrar-modal')) {
                const formulario = d.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 500);
            }

            if(e.target.classList.contains('submit-nueva-tarea')) {
                const nombreTarea = d.querySelector('#tarea').value.trim();
                if(nombreTarea === '') {
                    mostrarAlerta('DEBES DARLE UN NOMBRE A LA TAREA', 'error','.formulario legend');
                    return;
                }
                
                if(editar) {
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                } else {
                    agregarTarea(nombreTarea);
                }
            }
        })

        d.querySelector('.dashboard').appendChild(modal);
    }

    function mostrarAlerta(message, type, reference) {
        const alertaPrevia = d.querySelector('.alerta');
        if(alertaPrevia) {
            alertaPrevia.remove();
        }
        const alerta = d.createElement('DIV');
        alerta.textContent = message;
        alerta.classList.add('alerta', type);
        const target = d.querySelector(reference);
        target.parentElement.insertBefore(alerta, target.nextElementSibling);

        setTimeout(() => {
            alerta.remove();
        }, 5000);
    }

    async function agregarTarea(tarea) {
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoId', obtenerProyecto());
        

        try {
            const url = 'http://localhost:8080/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            
            const resultado = await respuesta.json();

            mostrarAlerta(
                resultado.mensaje,
                resultado.tipo,
                '.formulario legend'
            );

            if(resultado.tipo === 'exito') {
                const modal = d.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 2000);

                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: 0,
                    proyectoId: resultado.proyectoId
                };
                tareas = [...tareas, tareaObj];
                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }
    }

    function obtenerProyecto() {
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
    }

    async function obtenerTareas() {
        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?id=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            tareas = resultado.tareas;
            mostrarTareas();
        
        } catch (error) {
            console.log(error)
        }
    }

    function mostrarTareas() {
        limpiarTareas();
        totalPendientes();
        totalCompletas();

        const arrayTareas = filtradas.length ? filtradas : tareas;

        if(arrayTareas.length === 0) {
            const contenedorTareas = d.querySelector('#listado-tareas');
            const textoNoTareas = d.createElement('LI');
            textoNoTareas.textContent = 'Aun no tienes tareas, comienza agregando una 📝.';
            textoNoTareas.classList.add('no-tareas');
            contenedorTareas.appendChild(textoNoTareas);
            return; 
        }

        const estados = {
            0: 'Pendiente',
            1: 'Completado'
        };

        arrayTareas.forEach(tarea => {
            const contenedorTarea = d.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');

            const nombreTarea = d.createElement('P');
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function() {
                mostrarFormulario(true, {...tarea});
            };

            const opcionesDiv = d.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            const btnEstadoTarea = d.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function() {
                cambiarEstadoTarea({...tarea});
            };

            const btnEliminarTarea = d.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id; 
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.ondblclick = function() {
                confirmarEliminarTarea({...tarea});
            };

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);
            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const contenedorTareas = d.querySelector('#listado-tareas');
            contenedorTareas.appendChild(contenedorTarea);
        });
    }

    function totalPendientes() {
        const totalPendientes = tareas.filter(tarea => tarea.estado === '0');
        const inputPendientes = d.querySelector('#pendientes');
        if(totalPendientes.length === 0) { 
            inputPendientes.disabled = true;
        } else {
            inputPendientes.disabled = false;
        }
    };

    function totalCompletas() {
        const totalCompletas = tareas.filter(tarea => tarea.estado === '1');
        const inputCompletas = d.querySelector('#completas');
        if(totalCompletas.length === 0) { 
            inputCompletas.disabled = true;
        } else {
            inputCompletas.disabled = false;
        }
    };

    function limpiarTareas() {
        const listadoTareas = d.querySelector('#listado-tareas');
        while(listadoTareas.firstChild) {
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }

    function cambiarEstadoTarea(tarea) {
        const nuevoEstado = tarea.estado === '1' ? '0' : '1';
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
    }

    async function actualizarTarea(tarea) {
        const {estado, id, nombre} = tarea;
        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());
        try {
            const url = 'http://localhost:8080/api/tarea/actualizar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            if(resultado.respuesta.tipo === 'exito') {
                Swal.fire(
                    resultado.respuesta.mensaje,
                    resultado.respuesta.mensaje,
                    'success'
                );

                const modal = d.querySelector('.modal');
                if(modal) {
                    tareaMemoria.estado = estado;

                }

                tareas = tareas.map(tareaMemoria => {
                    if(tareaMemoria.id === id) {
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }
                    return tareaMemoria;
                });
                mostrarTareas();
            }
        } catch (error) {
            console.log(error);
        }
    }

    function confirmarEliminarTarea(tarea) {
        Swal.fire({
            title: '¿Estas seguro de eliminar la tarea?',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            }
        })
    }

    async function eliminarTarea(tarea) {
        const {estado, id, nombre} = tarea;
        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());
        try {
            const url = 'http://localhost:8080/api/tarea/eliminar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            if(resultado.resultado) {
                Swal.fire(
                    '¡Eliminado!',
                    resultado.mensaje,
                    'success'
                );
                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);
                mostrarTareas();
            }
        } catch (error) {
            console.log(error);
        }
    }

})();