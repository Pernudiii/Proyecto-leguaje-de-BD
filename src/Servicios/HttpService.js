const RUTA_GLOBAL = "http://localhost/botanero-ventas/api/"

const HttpService = {
    async registrar(datos, ruta) {
        try {
            const respuesta = await fetch(RUTA_GLOBAL + ruta, {
                method: "POST",
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json' // Indica que prefieres recibir JSON
                },
                body: JSON.stringify(datos),
            });
    
           // Añadir manejo básico de errores HTTP antes de parsear
           if (!respuesta.ok) {
                let errorMsg = `Error HTTP ${respuesta.status}: ${respuesta.statusText}`;
                try {
                    // Intenta leer el cuerpo del error por si PHP envió algo útil
                    const errorBody = await respuesta.text();
                    errorMsg += ` - ${errorBody}`;
                } catch(e) { /* Ignora si no se puede leer el cuerpo */ }
                throw new Error(errorMsg); // Lanza un error para el catch
           }
    
            const texto = await respuesta.text();
            try {
                 // Intenta parsear, puede fallar si PHP envió algo inesperado
                 return JSON.parse(texto);
            } catch (parseError) {
                 console.error(`Error parseando JSON en registrar() desde ${ruta}. Respuesta recibida:`, texto);
                 throw new Error(`Respuesta inválida del servidor: ${parseError.message}`); // Lanza error de parseo
            }
        } catch (e) {
            console.error(`Error en HttpService.registrar() desde ${ruta}:`, e);
            throw e; // Re-lanza para que el componente Vue lo capture
        }
    },
    async obtenerConDatos(datos, ruta){
        const respuesta = await fetch(RUTA_GLOBAL + ruta, {
            method: "post",
            body: JSON.stringify(datos),
        });
    
        const texto = await respuesta.text();  
        try {
            return JSON.parse(texto);  
        } catch (e) {
            console.error(`Error parseando JSON desde ${ruta}:`, texto);
            throw e;
        }
    },

    async obtener(ruta){
        try {
            const respuesta = await fetch(RUTA_GLOBAL + ruta);
            const texto = await respuesta.text();
            return JSON.parse(texto);
        } catch (e) {
            console.error(`Error en obtener() desde ${ruta}:`, e);
            throw e;
        }
    },

    async eliminar(ruta, id) {
        try {
            const respuesta = await fetch(RUTA_GLOBAL + ruta, {
                method: "post",
                body: JSON.stringify({ id }),  
            });
            const texto = await respuesta.text();
            return JSON.parse(texto);
        } catch (e) {
            console.error(`Error en eliminar() desde ${ruta}:`, e);
            throw e;
        }
    }
}

export default HttpService;
