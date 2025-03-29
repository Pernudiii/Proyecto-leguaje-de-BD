<template>
  <div id="app">
    <login @logeado="onLog" v-if="!logeado"></login>
    <div v-if="logeado">
      <encabezado @cerrar="onClose"/> 
      <div class="container">
        <router-view/>
      </div> 
      <pie /> 
    </div>
  </div>
</template>

<script>
import Login from './components/Usuarios/Login.vue'
import Encabezado from './components/Encabezado.vue'
import Pie from './components/Pie.vue'
import HttpService from './Servicios/HttpService'

export default {
  components: { Encabezado, Pie, Login},
  name: 'App',

  data: ()=> ({
    logeado: false,
    datos: "",
  }),

  mounted(){
    this.verificarInformacion()
    let logeado = this.verificarSesion()
    console.log("[App.vue] ¿Estaba logeado desde localStorage?", logeado);

    if(logeado) {
      this.logeado = true;
      console.log("[App.vue] Estado actualizado: logeado = true");
      console.log("Usuario:", localStorage.getItem("nombreUsuario"));
      console.log("ID Usuario:", localStorage.getItem("idUsuario"));
    } else {
      console.log("[App.vue] No hay sesión activa en localStorage");
    }
  },

  methods: {
    verificarInformacion(){
      HttpService.obtener("verificar_tablas.php")
      .then(resultado => {
        if(resultado.resultado > 0){
          this.configurar = false
          return
        }

        this.configurar = true
        return
      })
    },

    verificarSesion(){
      return localStorage.getItem('logeado') === "true"
    },

    onLog(logeado){

      console.log("[App.vue] Recibido en onLog:", logeado);
      console.log("logeado.resultado:", logeado.resultado);
      console.log("logeado.datos:", logeado.datos);

      if (logeado.resultado == true) {
        this.logeado = true
        localStorage.setItem('logeado', true)
        localStorage.setItem('nombreUsuario', logeado.datos.nombreUsuario)
        localStorage.setItem('idUsuario', logeado.datos.idUsuario)
        console.log("🗂 Guardado en localStorage");
      } else {
        console.log("Credenciales incorrectas en App.vue");
        this.$buefy.toast.open({
          message: 'Credenciales incorrectas',
          type: 'is-danger'
        });
      }
    },

    onClose(logeado){
      this.logeado = logeado
    }
  }
}
</script>

