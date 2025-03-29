<template>
    <section class="hero is-fullheight fondo">
       
        <div class="hero-body">
           
            <div class="container">
        <p class="title is-1 has-text-weight-bold has-text-white has-text-centered titulo">
            Sistema KO's Burger Gestión <br>
            <b-icon
                icon="account"
                size="is-large"
                >
            </b-icon>
            Iniciar sesión
        </p> 
            <div class="columns is-centered">
                <div class="column is-5-tablet is-6-desktop is-6-widescreen" >
                <form action="" class="box">
                    <img src="@/assets/logo_principal.png" alt="" height="150" width="150" style="display: block; margin:0 auto;">
                    <p class="title is-3 has-text-centered"></p>
                    <b-field>
                        <b-input placeholder="Correo electrónico"
                            type="email"
                            icon="email"
                            v-model="correo">
                        </b-input>
                    </b-field>

                    <b-field>
                        <b-input type="password"
                            placeholder="Contraseña"
                            v-model="password"
                            password-reveal>
                        </b-input>
                    </b-field>

                    <div class="field has-text-centered">
                    <b-button icon-left="login" type="is-primary" size="is-large" @click="ingresar">Ingresar</b-button>
                    </div>
                </form>
                </div>
            </div>        
            <b-loading :is-full-page="true" v-model="cargando" :can-cancel="false"></b-loading>
            </div>
        </div>
    </section>
</template>
<script>
import HttpService from '../../Servicios/HttpService'

export default {
    name: "Login",

    data: () => ({
        correo: "", 
        password: "",
        cargando:false
    }),

    methods: {
        ingresar(){
            if(!this.correo) {
                this.$buefy.toast.open({
                    message: 'Debes colocar el correo',
                    type: 'is-warning'
                })
                return
            }
            if(!this.password) {
                this.$buefy.toast.open({
                    message: 'Debes colocar la contraseña',
                    type: 'is-warning'
                })
                return
            }
            this.cargando = true
            let payload = {
                correo: this.correo,
                password: this.password
            }

            HttpService.obtenerConDatos(payload, "iniciar_sesion.php")
            .then(log => {
                console.log("Login response desde PHP:", log); // 👈 Verifica la respuesta
                console.log("log.resultado:", log.resultado);
                console.log("log.datos:", log.datos);

                if (log.resultado) {
                    this.$buefy.toast.open({
                        message: 'Datos correctos. Bienvenido',
                        type: 'is-success'
                    })

                    localStorage.setItem('logeado', "true");
                    console.log("localStorage después del login:", localStorage.getItem('logeado'));

                    localStorage.setItem('nombreUsuario', log.datos.nombreUsuario);
                    localStorage.setItem('idUsuario', log.datos.idUsuario);
                    this.$emit("logeado", log);
                    this.cargando = false;
                } else {
                    this.$buefy.toast.open({
                        message: 'Datos incorrectos. Verifica tu información',
                        type: 'is-danger'
                    })
                    this.cargando = false
                }
            })
            
        }
    }

}
</script>
<style>

 @import url('https://fonts.googleapis.com/css?family=Amaranth');
.fondo {
background-color: #8f0202;
background-attachment: fixed;
background-size: contain;
}

   
.titulo{
    font-family: Amaranth, sans-serif;
    text-align: center;
    font-size: 75px;
    letter-spacing: 2px;
    word-spacing: 3px;
    color: rgb(245, 251, 255);
    text-shadow: rgb(0, 0, 0) -4px 2px 2px;
}

</style>