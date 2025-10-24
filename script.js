const boton = document.getElementById("guardarProducto");
const botonVer = document.getElementById("verProductos");
const container = document.createElement("div");
document.body.appendChild(container);


boton.addEventListener("click", () => {
    // 1️⃣ Armar los datos a enviar
    const data = {
        nombre: document.getElementById("nombre").value,
        precio: document.getElementById("precio").value
    };

    // 2️⃣ Enviar la solicitud a la API
    fetch("http://localhost/CRUD/api/products", {
        method: "POST", // importante
        headers: {
            "Content-Type": "application/json" // indicamos que es JSON
        },
        body: JSON.stringify(data) // convertimos los datos a JSON
    })
        .then(response => response.json()) // parseamos la respuesta
        .then(result => {
            console.log("Respuesta del servidor:", result);

            if (result.error) {
                alert("Error: " + result.error);
            } else {
                alert("Producto guardado correctamente ✅");
                document.getElementById("nombre").value = "";
                document.getElementById("precio").value = "";
            }
        })
        .catch(error => {
            console.error("Error al conectar con la API:", error);
        });
});



botonVer.addEventListener("click", () => {

    fetch('http://localhost/CRUD/api/products', {
        method: "GET",
        headers: {
            "Content-Type": "application/json"
        }
    }).then(response => response.json().then(resultado => {
        console.log("Respuesta del servidor:", resultado);
        if(container.innerHTML != ""){
            container.innerHTML = "";
        }
        resultado.data.forEach(resul => {
            const children = document.createElement("div")
            children.innerHTML = `<p>Id: ${resul.id} - Nombre: ${resul.nombre} - Precio: ${resul.precio} - Creado: ${resul.creado_en}</p>`;
            container.appendChild(children);


        });
    })).catch(error => {
        console.error("Error en la respuesta", error);
    });



});