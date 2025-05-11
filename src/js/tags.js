(function () {
  const tagsInput = document.querySelector("#tags_input");
  const tagsInputHidden = document.querySelector("[name='tags']");

  if (tagsInput) {
    const tagsDiv = document.querySelector("#tags");
    let tags = [];

    // Recuperar el input oculto
    if (tagsInputHidden.value !== "") {
      tags = tagsInputHidden.value.split(",");
      mostrarTags();
    }

    // Escuchar los cambios en el input
    tagsInput.addEventListener("keypress", guardarTag);

    function guardarTag(e) {
      // Verificar si se presionó la coma
      if (e.keyCode === 44) {
        // Verificar si el campo está vacío
        if (e.target.value.trim() === "" || e.target.value < 1) {
          return;
        }
        // Evitamos que escriba la coma en el input
        e.preventDefault();

        // Hacemos una copia actual del arreglo y agregamos el nuevo tag
        tags = [...tags, e.target.value.trim()];

        // Limpiamos el input
        tagsInput.value = "";

        // Mostramos los tags en el DOM
        mostrarTags();
      }
    }

    function mostrarTags() {
      // Limpiamos el contenido del div
      tagsDiv.textContent = "";

      // Iteramos en todos los tags y los agregamos al div
      // DOM SCRIPTING
      tags.forEach((tag) => {
        const etiqueta = document.createElement("LI");
        etiqueta.classList.add("formulario__tag");
        etiqueta.textContent = tag;
        etiqueta.ondblclick = eliminarTag;
        tagsDiv.appendChild(etiqueta);
      });

      // Actualizamos el value de nuestro input
      actualizarInputHidden();
    }

    function eliminarTag(e) {
      // Eliminamos el tag del DOM
      e.target.remove();

      // Sacamos el tag de nuestro arreglo
      tags = tags.filter((tag) => tag !== e.target.textContent);

      // Actualizamos el value de nuestro input
      actualizarInputHidden();
    }

    function actualizarInputHidden() {
      tagsInputHidden.value = tags.toString();
    }
  }
})();
