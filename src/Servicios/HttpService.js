const RUTA_GLOBAL = "http://localhost/botanero-ventas/api/"

const HttpService = {
    async registrar(datos, ruta){
        try {
            const respuesta = await fetch(RUTA_GLOBAL + ruta, {
                method: "post",
                body: JSON.stringify(datos),
            });
            const texto = await respuesta.text();
            return JSON.parse(texto);
        } catch (e) {
            console.error(`Error en registrar() desde ${ruta}:`, e);
            throw e;
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
