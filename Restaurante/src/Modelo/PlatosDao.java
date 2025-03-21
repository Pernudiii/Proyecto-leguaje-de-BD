package Modelo;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class PlatosDao {

    Connection con;
    Conexion cn = new Conexion();
    PreparedStatement ps;
    ResultSet rs;

    // AQUI SE HIZO UN CAMBIO:
    // Método para registrar un nuevo producto (Plato) en la tabla PRODUCTO
    public boolean Registrar(Platos pla) {
        // Insertamos nombre, descripción, precio y categoría. 
        // Suponemos que 'id_producto' se genera automáticamente (p.ej. con secuencias en Oracle).
        String sql = "INSERT INTO producto (nombre, descripcion, precio, id_categoria) VALUES (?,?,?,?)";
        try {
            con = cn.getConnection();
            ps = con.prepareStatement(sql);
            ps.setString(1, pla.getNombre());
            ps.setString(2, pla.getDescripcion());
            ps.setDouble(3, pla.getPrecio());
            ps.setInt(4, pla.getId_categoria());
            ps.execute();
            return true;
        } catch (SQLException e) {
            System.out.println(e.toString());
            return false;
        } finally {
            try {
                con.close();
            } catch (SQLException ex) {
                System.out.println(ex.toString());
            }
        }
    }

    // AQUI SE HIZO UN CAMBIO:
    // Método para listar productos. Se permite un filtro por nombre (valor).
    // Si 'valor' está vacío, se listan todos los productos.
    public List<Platos> Listar(String valor) {
        List<Platos> lista = new ArrayList<>();
        String sql = "SELECT * FROM producto";
        String consulta = "SELECT * FROM producto WHERE nombre LIKE '%" + valor + "%'";
        try {
            con = cn.getConnection();
            if (valor.isEmpty()) {
                ps = con.prepareStatement(sql);
            } else {
                ps = con.prepareStatement(consulta);
            }
            rs = ps.executeQuery();
            while (rs.next()) {
                Platos pl = new Platos();
                // AQUI SE HIZO UN CAMBIO: Ajustamos a los campos de la tabla PRODUCTO
                pl.setId_producto(rs.getInt("id_producto"));
                pl.setNombre(rs.getString("nombre"));
                pl.setDescripcion(rs.getString("descripcion"));
                pl.setPrecio(rs.getDouble("precio"));
                pl.setId_categoria(rs.getInt("id_categoria"));
                lista.add(pl);
            }
        } catch (SQLException e) {
            System.out.println(e.toString());
        } finally {
            try {
                con.close();
            } catch (SQLException e) {
                System.out.println(e.toString());
            }
        }
        return lista;
    }

    // AQUI SE HIZO UN CAMBIO:
    // Método para eliminar un producto por su ID
    public boolean Eliminar(int id_producto) {
        String sql = "DELETE FROM producto WHERE id_producto = ?";
        try {
            con = cn.getConnection();
            ps = con.prepareStatement(sql);
            ps.setInt(1, id_producto);
            ps.execute();
            return true;
        } catch (SQLException e) {
            System.out.println(e.toString());
            return false;
        } finally {
            try {
                con.close();
            } catch (SQLException ex) {
                System.out.println(ex.toString());
            }
        }
    }

    // AQUI SE HIZO UN CAMBIO:
    // Método para modificar un producto (Plato) en la tabla PRODUCTO
    public boolean Modificar(Platos pla) {
        // Actualizamos nombre, descripción, precio y categoría.
        // El producto a modificar se identifica por 'id_producto'.
        String sql = "UPDATE producto SET nombre=?, descripcion=?, precio=?, id_categoria=? WHERE id_producto=?";
        try {
            con = cn.getConnection();
            ps = con.prepareStatement(sql);
            ps.setString(1, pla.getNombre());          // Completar correctamente
            ps.setString(2, pla.getDescripcion());
            ps.setDouble(3, pla.getPrecio());
            ps.setInt(4, pla.getId_categoria());
            ps.setInt(5, pla.getId_producto());
            ps.executeUpdate();
            return true;
        } catch (SQLException e) {
            System.out.println(e.toString());
            return false;
        } finally {
            try {
                con.close();
            } catch (SQLException ex) {
                System.out.println(ex.toString());
            }
        }
    }
}
