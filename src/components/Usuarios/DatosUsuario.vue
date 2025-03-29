<template>
    <section>
         <ul v-if="errores.length > 0">
            <li class="has-text-danger has-text-centered" v-for="error in errores" :key="error">{{ error }}</li>
        </ul>
        <b-field label="Correo electrónico">
            <b-input v-model="usuario.correo" placeholder="Correo del usuario"></b-input>
        </b-field>

        <b-field label="Nombre">
            <b-input v-model="usuario.nombre" placeholder="Nombre del usuario"></b-input>
        </b-field>

        <b-field label="Rol">
            <b-select v-model="usuario.id_rol" placeholder="Selecciona un rol">
            <option :value="1">Administrador</option>
            <option :value="2">Asistente</option>
        </b-select>
        </b-field>

    <b-button type="is-success" size="is-large" icon-left="check" @click="registrar">
      Registrar
    </b-button>
  </section>
</template>
<script>
import Utiles from '../../Servicios/Utiles'

export default ({
    name: "DatosUsuario",
    props: ["usuario"],

    data: () => ({
        errores: []
    }),

    methods: {
        registrar(){
            let datos = {
                correo: this.usuario.correo,
                nombre: this.usuario.nombre,
                
            }
            this.errores = Utiles.validar(datos)
            if(this.errores.length > 0) return
            this.$emit("registrado", this.usuario)
        }
    }
})
</script>
