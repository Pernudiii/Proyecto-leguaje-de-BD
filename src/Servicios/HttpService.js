const RUTA_GLOBAL = "http://localhost/botanero-ventas/api/"

const HttpService = {
    async registrar(datos, ruta){
        const respuesta = await fetch(RUTA_GLOBAL + ruta, {
            method: "post",
            body: JSON.stringify(datos),
        });
        let resultado = await respuesta.json()
        return resultado
    },

    async obtenerConDatos(datos, ruta){
        const respuesta = await fetch(RUTA_GLOBAL + ruta, {
            method: "post",
            body: JSON.stringify(datos),
        });
    
        const texto = await respuesta.text();  // leer respuesta cruda
        try {
            return JSON.parse(texto);  // intentar parsear como JSON
        } catch (e) {
            console.error(`Error parseando JSON desde ${ruta}:`, texto);
            throw e;
        }
    },


    async obtener(ruta){
        let respuesta = await fetch(RUTA_GLOBAL + ruta)
        let datos = await respuesta.json()
        return datos
    },

    async eliminar(ruta, id) {
        const respuesta = await fetch(RUTA_GLOBAL + ruta, {
            method: "post",
            body: JSON.stringify(id),
        });
        let resultado = await respuesta.json()
        return resultado
    }

}

export default  HttpService 