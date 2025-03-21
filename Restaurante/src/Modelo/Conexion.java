package Modelo;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class Conexion {

    Connection con;

    public Connection getConnection(){
        try {
            // Cadena de conexión para Oracle con SID
            String myBD = "jdbc:oracle:thin:@localhost:1521:xe";
            
            // Usuario y contraseña
            con = DriverManager.getConnection(myBD, "ADMINISTRADOR", "123");
            return con;
        } catch (SQLException e) { 
            System.out.println(e.toString());
        }
        return null;
    }
}
