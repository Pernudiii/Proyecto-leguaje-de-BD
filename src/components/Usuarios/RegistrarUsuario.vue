<template>
    <section>
      <br>
      <p class="title is-1 has-text-weight-bold">
        <b-icon icon="account-group" size="is-large" type="is-primary"></b-icon>
        Registrar usuario
      </p>
  
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
  import HttpService from '../../Servicios/HttpService'
  
  export default {
    name: "RegistrarUsuario",
    data() {
      return {
        usuario: {
          nombre: '',
          correo: '',
          id_rol: null
        }
      }
    },
    methods: {
      registrar() {
        const datos = {
          ...this.usuario,
          password: "temporal123" // Contraseña por defecto
        }
  
        HttpService.registrar(datos, "registrar_usuario.php")
          .then(resultado => {
            if (resultado) {
              this.$buefy.toast.open("Usuario registrado correctamente")
              this.$router.push({ name: "Usuarios" })
            }else {
                this.$buefy.toast.open("No se pudo registrar el usuario")
            }
          })
      }
    }
  }
  </script>
  
