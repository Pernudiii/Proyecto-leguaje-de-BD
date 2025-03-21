package Modelo;

public class Platos {
    // AQUI SE HIZO UN CAMBIO: Renombramos 'id' a 'id_producto' para mayor claridad con la tabla PRODUCTO
    private int id_producto;
    private String nombre;
    private String descripcion; // AQUI SE HIZO UN CAMBIO: Se agregó el campo 'descripcion'
    private double precio;
    private int id_categoria;   // AQUI SE HIZO UN CAMBIO: Se mantiene para representar la FK a CATEGORIA

    public Platos() {
    }

    // AQUI SE HIZO UN CAMBIO: Constructor adaptado a los campos del nuevo modelo
    public Platos(int id_producto, String nombre, String descripcion, double precio, int id_categoria) {
        this.id_producto = id_producto;
        this.nombre = nombre;
        this.descripcion = descripcion;
        this.precio = precio;
        this.id_categoria = id_categoria;
    }

    public int getId_producto() {
        return id_producto;
    }

    public void setId_producto(int id_producto) {
        this.id_producto = id_producto;
    }

    public String getNombre() {
        return nombre;
    }

    public void setNombre(String nombre) {
        this.nombre = nombre;
    }

    public String getDescripcion() {
        return descripcion;
    }

    public void setDescripcion(String descripcion) {
        this.descripcion = descripcion;
    }

    public double getPrecio() {
        return precio;
    }

    public void setPrecio(double precio) {
        this.precio = precio;
    }

    public int getId_categoria() {
        return id_categoria;
    }

    public void setId_categoria(int id_categoria) {
        this.id_categoria = id_categoria;
    }
}
