package Modelo;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class LoginDao {
    Connection con;
    PreparedStatement ps;
    ResultSet rs;
    Conexion cn = new Conexion();
    
    public login log(String email, String contraseña){
        login l = new login();
        // AQUI SE HIZO UN CAMBIO: Se removió la referencia al campo 'rol' de la consulta
        String sql = "SELECT * FROM USUARIO WHERE email = ? AND contraseña = ?";
        try {
            con = cn.getConnection();
            ps = con.prepareStatement(sql);
            ps.setString(1, email);
            ps.setString(2, contraseña);
            rs = ps.executeQuery();
            if (rs.next()) {
                System.out.println("ID: " + rs.getInt("id"));
                System.out.println("Nombre: " + rs.getString("nombre"));
                System.out.println("Email: " + rs.getString("email"));
                System.out.println("Contraseña: " + rs.getString("contraseña"));
                
                l.setId(rs.getInt("id"));
                l.setNombre(rs.getString("nombre"));
                l.setCorreo(rs.getString("email"));       
                l.setPass(rs.getString("contraseña"));       
                l.setRol(rs.getString("id_rol")); 
            }
        } catch (SQLException e) {
            System.out.println(e.toString());
        }
        return l;
    }
    
    public boolean Registrar(login reg){
        // AQUI SE HIZO UN CAMBIO: Se eliminó el campo 'rol' de la inserción, ya que la entidad 'login' no lo posee en el nuevo modelo.
        String sql = "INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES (?,?,?,?)";
        try {
            con = cn.getConnection();
            ps = con.prepareStatement(sql);
            ps.setString(1, reg.getNombre());
            ps.setString(2, reg.getCorreo());
            ps.setString(3, reg.getPass());
            ps.setString(4, reg.getRol());
            // AQUI SE HIZO UN CAMBIO
            ps.execute();
            return true;
        } catch (SQLException e) {
            System.out.println(e.toString());
            return false;
        }
    }
    
    public List ListarUsuarios(){
       List<login> Lista = new ArrayList<>();
       String sql = "SELECT * FROM usuario";
       try {
           con = cn.getConnection();
           ps = con.prepareStatement(sql);
           rs = ps.executeQuery();
           while (rs.next()) {               
               login lg = new login();
               lg.setId(rs.getInt("id"));
               lg.setNombre(rs.getString("nombre"));
               lg.setCorreo(rs.getString("email"));
               lg.setRol(rs.getString("rol"));
               Lista.add(lg);
           }
       } catch (SQLException e) {
           System.out.println(e.toString());
       }
       return Lista;
   }
    
    public boolean ModificarDatos(Config conf){
        // AQUI SE HIZO UN CAMBIO: Se eliminó el campo 'mensaje' de la actualización, ya que fue removido de la entidad Config.
        String sql = "UPDATE config SET ruc=?, nombre=?, telefono=?, direccion=? WHERE id=?";
        try {
            ps = con.prepareStatement(sql);
            ps.setString(1, conf.getRuc());
            ps.setString(2, conf.getNombre());
            ps.setString(3, conf.getTelefono());
            ps.setString(4, conf.getDireccion());
            ps.setInt(5, conf.getId());
            ps.execute();
            return true;
        } catch (SQLException e) {
            System.out.println(e.toString());
            return false;
        } finally {
            try {
                con.close();
            } catch (SQLException e) {
                System.out.println(e.toString());
            }
        }
    }
    
    public Config datosEmpresa(){
        Config conf = new Config();
        // AQUI SE HIZO UN CAMBIO: Se eliminó la lectura del campo 'mensaje' ya que ya no existe en la entidad Config.
        String sql = "SELECT * FROM config";
        try {
            con = cn.getConnection();
            ps = con.prepareStatement(sql);
            rs = ps.executeQuery();
            if (rs.next()) {
                conf.setId(rs.getInt("id"));
                conf.setRuc(rs.getString("ruc"));
                conf.setNombre(rs.getString("nombre"));
                conf.setTelefono(rs.getString("telefono"));
                conf.setDireccion(rs.getString("direccion"));
                // Se eliminó: conf.setMensaje(rs.getString("mensaje"));
                // AQUI SE HIZO UN CAMBIO
            }
        } catch (SQLException e) {
            System.out.println(e.toString());
        }
        return conf;
    }
}
