<template>
    <section>
        <p class="title is-1 has-text-weight-bold">Editar usuario</p>
        <b-breadcrumb align="is-left" >
            <b-breadcrumb-item tag='router-link' to="/">Inicio</b-breadcrumb-item>
            <b-breadcrumb-item tag='router-link' to="/usuarios">Usuarios</b-breadcrumb-item>
        </b-breadcrumb>
        <datos-usuario @registrado="onRegistrado" :usuario="usuario"></datos-usuario>        
        <b-loading :is-full-page="true" v-model="cargando" :can-cancel="false"></b-loading>

    </section>
</template>
<script>
import HttpService from '../../Servicios/HttpService'
import DatosUsuario from './DatosUsuario.vue'

export default ({
    name: "EditarUsuario",
    components: { DatosUsuario },

    data: () => ({
        usuario: {},
        cargando: false
    }),

    mounted() {
        // Obtiene el ID de la ruta (parámetro 'id')
        const userId = this.$route.params.id;

        // Validación básica del ID
        if (!userId || isNaN(parseInt(userId))) {
            console.error("ID de usuario inválido o no encontrado en la ruta:", userId);
            this.$buefy.toast.open({ message: 'ID de usuario inválido', type: 'is-danger' });
            this.$router.push({ name: 'Usuarios' }); // Redirige si no hay ID
            return;
        }
        this.cargando = true

        HttpService.obtenerConDatos({ id: parseInt(userId) }, "obtener_usuario_id.php")
        .then(resultado => {
            // Verifica si la API devolvió un objeto de error PHP
            if (resultado && resultado.error) {
                console.error("Error desde la API al obtener usuario:", resultado.error);
                this.$buefy.toast.open({
                    message: `Error: ${resultado.error}`,
                    type: 'is-danger',
                    duration: 5000
                });
                this.usuario = {}; // Limpia datos
            }

                // Verifica si el resultado es válido (no null y no un array vacío si se espera objeto)
            else if (resultado && typeof resultado === 'object' && Object.keys(resultado).length > 0) {
                this.usuario = resultado; // Asigna los datos recibidos
                console.log("Usuario cargado para edición:", this.usuario);
            } else {
                // Caso donde no hubo error explícito pero no se encontraron datos (ej. 404 no manejado como error)
                console.warn("No se encontraron datos para el usuario ID:", userId, "Respuesta:", resultado);
                this.$buefy.toast.open({
                    message: 'Usuario no encontrado.',
                    type: 'is-warning'
                });
                this.usuario = {}; // Limpia datos
                // Considera redirigir si el usuario no existe
                // this.$router.push({ name: 'Usuarios' });
            }
            this.cargando = false;
        })
        .catch(error => {
            // Captura errores de red, o si JSON.parse falla en HttpService
            console.error("Error en HttpService o red al obtener usuario:", error);
            this.$buefy.toast.open({
                message: 'Error de comunicación al cargar datos del usuario.',
                type: 'is-danger',
                duration: 5000
            });
            this.cargando = false;
            this.usuario = {}; // Limpia datos
        });
    },

    methods: {
        onRegistrado(usuario){
            this.usuario = usuario
            this.cargando = true
            HttpService.registrar(this.usuario, "editar_usuario.php")
            .then(editado => {
                if(editado){
                    this.$buefy.toast.open({
                        message: 'Información actualizada',
                        type: 'is-success'
                    })
                    this.cargando = false
                    this.$router.push({
                        name: "Usuarios",
                    })
                }
            })
        }
    }
})
</script>
