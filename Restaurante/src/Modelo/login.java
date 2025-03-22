
package Modelo;

public class login {
    private int id;
    private String nombre;
    private String correo;
    private String pass;
    private int id_rol;

    public login() {
    }

    public login(int id, String nombre, String correo, String pass, int id_rol) {
        this.id = id;
        this.nombre = nombre;
        this.correo = correo;
        this.pass = pass;
        this.id_rol = id_rol;
    }


    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getNombre() {
        return nombre;
    }

    public void setNombre(String nombre) {
        this.nombre = nombre;
    }

    public String getCorreo() {
        return correo;
    }

    public void setCorreo(String correo) {
        this.correo = correo;
    }

    public String getPass() {
        return pass;
    }

    public void setPass(String pass) {
        this.pass = pass;
    }

    
    public void setId_Rol(int id_rol) {
        this.id_rol = id_rol;
    }

    public int getId_Rol() {
        return id_rol;
    }

    public void setPass(char[] pass) {
        throw new UnsupportedOperationException("Not supported yet."); // Generated from nbfs://nbhost/SystemFileSystem/Templates/Classes/Code/GeneratedMethodBody
    }
    
}
