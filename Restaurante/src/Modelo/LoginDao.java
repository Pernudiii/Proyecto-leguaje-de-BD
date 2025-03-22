package Modelo;

import java.sql.Statement;
import java.sql.CallableStatement;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;
import oracle.jdbc.OracleTypes;

public class LoginDao {
    Connection con;
    CallableStatement cs;
    ResultSet rs;
    Conexion cn = new Conexion();
    
    public login log(String email, String contraseña){
        login l = new login();
        String sql = "SELECT * FROM USUARIO WHERE email = ? AND contraseña = ?";
        try {
            con = cn.getConnection();
            cs = con.prepareCall(sql);
            cs.setString(1, email);
            cs.setString(2, contraseña);
            rs = cs.executeQuery();
            if (rs.next()) {
                System.out.println("ID: " + rs.getInt("id"));
                System.out.println("Nombre: " + rs.getString("nombre"));
                System.out.println("Email: " + rs.getString("email"));
                System.out.println("Contraseña: " + rs.getString("contraseña"));
                
                l.setId(rs.getInt("id"));
                l.setNombre(rs.getString("nombre"));
                l.setCorreo(rs.getString("email"));       
                l.setPass(rs.getString("contraseña"));       
                l.setId_Rol(rs.getInt("id_rol")); 
            }
        } catch (SQLException e) {
            System.out.println("Error en log(): " + e.getMessage());
        } finally {
            try {
                if (rs != null) rs.close();
                if (cs != null) cs.close();
                if (con != null) con.close();
            } catch (SQLException e) {
                System.out.println("Error cerrando recursos en log(): " + e.getMessage());
            }
        }
        return l;
    }
    
    // Método para otorgar privilegios al usuario si su rol es "Administrador"
    public boolean grantPrivilegesToAdmin(login user) {
        // Verifica que el rol del usuario sea "Administrador"
        if (user.getId_Rol()!= 1) {
            System.out.println("El usuario no tiene el rol de Administrador, no se otorgan privilegios.");
            return false;
        }
        
        Connection con = null;
        Statement stmt = null;
        try {
            // Es muy importante que esta conexión se realice con un usuario que tenga permiso para otorgar privilegios
            con = cn.getConnection();
            stmt = con.createStatement();
            
            // Suponiendo que el nombre de usuario en Oracle coincide con, por ejemplo, el correo del usuario
            // O puedes usar otro atributo que identifique el usuario en la base de datos
            String oracleUser = user.getCorreo();  
            
            // Construir la sentencia GRANT
            String sql = "GRANT ALL PRIVILEGES TO " + oracleUser;
            stmt.executeUpdate(sql);
            System.out.println("Privilegios otorgados al usuario: " + oracleUser);
            return true;
        } catch (SQLException e) {
            System.out.println("Error otorgando privilegios: " + e.getMessage());
            return false;
        } finally {
            try {
                if (stmt != null) stmt.close();
                if (con != null) con.close();
            } catch (SQLException ex) {
                System.out.println("Error cerrando recursos: " + ex.getMessage());
            }
        }
    }
    
    public boolean grantPrivilegesToAsistente(login user) {
        // Verifica que el rol del usuario sea "Asistente" (por ejemplo, id_rol == 2)
        if (user.getId_Rol() != 2 && user.getId_Rol() != 1) {
            System.out.println("El usuario no tiene el rol de Asistente, no se otorgan privilegios.");
            return false;
        }

        Connection con = null;
        Statement stmt = null;
        try {
            // Conexión con un usuario que tenga permiso para otorgar privilegios
            con = cn.getConnection();
            stmt = con.createStatement();

            // Suponiendo que el nombre de usuario en Oracle coincide con, por ejemplo, el correo del usuario
            String oracleUser = user.getCorreo();

            // Otorgar privilegios para la tabla PEDIDOS: SELECT, INSERT y UPDATE (no DELETE)
            String sqlPedidos = "GRANT SELECT, INSERT, UPDATE ON PEDIDO  TO " + oracleUser;
            stmt.executeUpdate(sqlPedidos);

            // Otorgar privilegios para la tabla SALAS: SELECT, INSERT y UPDATE (no DELETE)
            String sqlSalas = "GRANT SELECT, INSERT, UPDATE ON SALAS TO " + oracleUser;
            stmt.executeUpdate(sqlSalas);

            // Otorgar privilegios para la tabla EMPRESA: solo SELECT (no se permite editar datos de la empresa)
            String sqlEmpresa = "GRANT SELECT ON EMPRESA TO " + oracleUser;
            stmt.executeUpdate(sqlEmpresa);

            // Otorgar privilegios para la tabla PLATOS: solo SELECT (no se pueden crear, modificar o eliminar platos)
            String sqlPlatos = "GRANT SELECT ON PRODUCTO TO " + oracleUser;
            stmt.executeUpdate(sqlPlatos);

            System.out.println("Privilegios otorgados al usuario Asistente: " + oracleUser);
            return true;
        } catch (SQLException e) {
            System.out.println("Error otorgando privilegios a Asistente: " + e.getMessage());
            return false;
        } finally {
            try {
                if (stmt != null) stmt.close();
                if (con != null) con.close();
            } catch (SQLException ex) {
                System.out.println("Error cerrando recursos: " + ex.getMessage());
            }
        }
    }
    
    // Método para registrar un usuario usando el procedimiento almacenado createUsuario
    public boolean Registrar(login reg) {
        Connection con = null;
        CallableStatement cs = null;
        try {
            con = cn.getConnection();
            // Se asume que el procedimiento createUsuario tiene la siguiente firma:
            // createUsuario(P_ID NUMBER, P_NOMBRE VARCHAR2, P_EMAIL VARCHAR2, P_CONTRASEÑA VARCHAR2, P_ID_ROL NUMBER)
            // Si el campo ID es generado automáticamente, puedes enviar 0 o ajustar el procedimiento para omitirlo.
            cs = con.prepareCall("{ call createUsuario(?, ?, ?, ?) }");
            cs.setString(1, reg.getNombre());
            cs.setString(2, reg.getCorreo());
            cs.setString(3, reg.getPass());
            cs.setInt(4, reg.getId_Rol());  
            cs.execute();
            
            // Si el usuario es administrador, otorga privilegios
            if (reg.getId_Rol()== 1) {
                grantPrivilegesToAdmin(reg);
            }else if (reg.getId_Rol() == 2) {
                // Si es asistente
                grantPrivilegesToAsistente(reg);
            }
            return true;
            
        } catch (SQLException e) {
            System.out.println("Error en Registrar(): " + e.getMessage());
            return false;
        } finally {
            try {
                if (cs != null) cs.close();
                if (con != null) con.close();
            } catch (SQLException e) {
                System.out.println("Error cerrando recursos en Registrar(): " + e.getMessage());
            }
        }
    }
    
    public List<login> ListarUsuarios() {
        List<login> lista = new ArrayList<>();
        try {
            con = cn.getConnection();
            // Llamamos al procedimiento getUsuarios que retorna un SYS_REFCURSOR
            cs = con.prepareCall("{ call getUsuarios(?) }");
            cs.registerOutParameter(1, OracleTypes.CURSOR);
            cs.execute();

            rs = (ResultSet) cs.getObject(1);
            while (rs.next()) {               
                login lg = new login();
                lg.setId(rs.getInt("id"));
                lg.setNombre(rs.getString("nombre"));
                lg.setCorreo(rs.getString("email"));
                lg.setId_Rol(rs.getInt("id_rol"));
                lista.add(lg);
            }
        } catch (SQLException e) {
            System.out.println("Error en ListarUsuarios(): " + e.getMessage());
        } finally {
            try {
                if (rs != null) rs.close();
                if (cs != null) cs.close();
                if (con != null) con.close();
            } catch (SQLException ex) {
                System.out.println("Error cerrando recursos en ListarUsuarios(): " + ex.getMessage());
            }
        }
        return lista;
    }

    public boolean updateUsuario(login reg) {
        Connection con = null;
        CallableStatement cs = null;
        try {
            con = cn.getConnection();
            // Se asume que el procedimiento updateUsuario tiene la siguiente firma:
            // updateUsuario(P_ID NUMBER, P_NOMBRE VARCHAR2, P_EMAIL VARCHAR2, P_CONTRASEÑA VARCHAR2, P_ID_ROL NUMBER)
            cs = con.prepareCall("{ call updateUsuario(?, ?, ?, ?, ?) }");
            cs.setInt(1, reg.getId());
            cs.setString(2, reg.getNombre());
            cs.setString(3, reg.getCorreo());
            cs.setString(4, reg.getPass());
            cs.setInt(5, reg.getId_Rol());
            cs.execute();
            return true;
        } catch (SQLException e) {
            System.out.println("Error en updateUsuario(): " + e.getMessage());
            return false;
        } finally {
            try {
                if (cs != null) cs.close();
                if (con != null) con.close();
            } catch (SQLException e) {
                System.out.println("Error cerrando recursos en updateUsuario(): " + e.getMessage());
            }
        }
    }
    
    // Método para eliminar un usuario usando el procedimiento deleteUsuario
    public boolean deleteUsuario(int id) {
        Connection con = null;
        CallableStatement cs = null;
        try {
            con = cn.getConnection();
            // deleteUsuario(P_ID NUMBER)
            cs = con.prepareCall("{ call deleteUsuario(?) }");
            cs.setInt(1, id);
            cs.execute();
            return true;
        } catch (SQLException e) {
            System.out.println("Error en deleteUsuario(): " + e.getMessage());
            return false;
        } finally {
            try {
                if (cs != null) cs.close();
                if (con != null) con.close();
            } catch (SQLException e) {
                System.out.println("Error cerrando recursos en deleteUsuario(): " + e.getMessage());
            }
        }
    }
    
    /*public Config datosEmpresa(){
        Config conf = new Config();
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
    }*/
}
