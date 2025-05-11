import Swal from "sweetalert2";

(function () {
  let eventos = [];
  const resumen = document.querySelector("#registro-resumen");

  if (resumen) {
    const eventosBoton = document.querySelectorAll(".evento__agregar");

    eventosBoton.forEach((boton) =>
      boton.addEventListener("click", seleccionarEvento)
    );

    const formularioRegistro = document.querySelector("#registro");
    formularioRegistro.addEventListener("submit", submitFormulario);

    mostrarEventos();

    function seleccionarEvento(e) {
      const { target } = e;

      // Validar si supero el numero de eventos
      if (eventos.length < 5) {
        // Deshabilitar el evento para evitar duplicados
        target.disabled = true;
        eventos = [
          ...eventos,
          {
            id: target.dataset.id,
            titulo: target.parentElement
              .querySelector(".evento__nombre")
              .textContent.trim(),
          },
        ];

        mostrarEventos();
      } else {
        Swal.fire({
          title: "Error",
          text: "Máximo 5 eventos por registro",
          icon: "error",
          confirmButtonText: "Ok",
        });
      }
    }

    function mostrarEventos() {
      // Limpiar el HTML previo
      limpiarEventos();

      if (eventos.length > 0) {
        eventos.forEach((evento) => {
          const eventoDOM = document.createElement("DIV");
          eventoDOM.classList.add("registro__evento");
          const titulo = document.createElement("H3");
          titulo.classList.add("registro__nombre");
          titulo.textContent = evento.titulo;

          const botonEliminar = document.createElement("BUTTON");
          botonEliminar.classList.add("registro__eliminar");
          botonEliminar.innerHTML = `<i class="fa-solid fa-trash"></i>`;
          botonEliminar.onclick = function () {
            eliminarEvento(evento.id);
          };

          // Renderizar el HTML
          eventoDOM.appendChild(titulo);
          eventoDOM.appendChild(botonEliminar);
          resumen.appendChild(eventoDOM);
        });
      } else {
        const noRegistros = document.createElement("P");
        noRegistros.textContent = "No hay eventos, añade hasta 5";
        noRegistros.classList.add("registro__texto");
        resumen.appendChild(noRegistros);
      }
    }

    function eliminarEvento(id) {
      eventos = eventos.filter((evento) => evento.id !== id);

      // Volvemos habilitar el evento
      const botonAgregar = document.querySelector(`[data-id="${id}"]`);
      botonAgregar.disabled = false;

      // Renderizar de nuevo
      mostrarEventos();
    }

    function limpiarEventos() {
      while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
      }
    }

    async function submitFormulario(e) {
      e.preventDefault();

      // Obtener el regalo
      const regaloId = document.querySelector("#regalo").value;

      // Obtenemos los id de los eventos seleccionados por el usuario
      const eventosId = eventos.map((evento) => evento.id);

      // Validamos que haya seleccionado al menos 1 evento y un regalo

      if (eventosId.length === 0 || regaloId === "") {
        Swal.fire({
          title: "Error",
          text: "Elige al menos un evento y un regalo",
          icon: "error",
          confirmButtonText: "Ok",
        });
        return;
      }

      // Objeto de FormData
      const datos = new FormData();
      datos.append("eventos", eventosId);
      datos.append("regalo", regaloId);

      const url = "/finalizar-registro/conferencias";
      const respuesta = await fetch(url, {
        method: "POST",
        body: datos,
      });

      const resultado = await respuesta.json();

      console.log(resultado);
      if (resultado.resultado) {
        Swal.fire(
          "Registro Exitoso",
          "Tus conferencias se han almacenado y tu registro fue exitoso, te esperamos en DevWebCamp",
          "success"
        ).then(() => {
          location.href = `/boleto?id=${resultado.token}`;
        });
      } else {
        Swal.fire({
          title: "Error",
          text: "Hubo un error, intenta de nuevo",
          icon: "error",
          confirmButtonText: "Ok",
        }).then(() => {
          location.reload();
        });
      }
    }
  }
})();
