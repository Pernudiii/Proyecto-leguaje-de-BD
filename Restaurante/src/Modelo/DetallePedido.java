package Modelo;

public class DetallePedido {
    private int id;
    private String nombre;
    private String comentario;
    private int cantidad;
    private double precio;
    private int id_pedido;
    private int id_producto;

    public String getNombre() {
        return nombre;
    }

    public void setNombre(String nombre) {
        this.nombre = nombre;
    }

    public String getComentario() {
        return comentario;
    }

    public void setComentario(String comentario) {
        this.comentario = comentario;
    }

    public DetallePedido() {
    }

    public DetallePedido(int id, int cantidad, double precio, int id_pedido, int id_producto) {
        this.id = id;
        this.cantidad = cantidad;
        this.precio = precio;
        this.id_pedido = id_pedido;
        this.id_producto = id_producto;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getCantidad() {
        return cantidad;
    }

    public void setCantidad(int cantidad) {
        this.cantidad = cantidad;
    }

    public double getPrecio() {
        return precio;
    }

    public void setPrecio(double precio) {
        this.precio = precio;
    }

    // AQUI SE HIZO UN CAMBIO: Método relacionado con la tabla PEDIDO.
    public int getId_pedido() {
        return id_pedido;
    }

    // AQUI SE HIZO UN CAMBIO: Método relacionado con la tabla PEDIDO.
    public void setId_pedido(int id_pedido) {
        this.id_pedido = id_pedido;
    }

    public int getId_producto() {
        return id_producto;
    }

    public void setId_producto(int id_producto) {
        this.id_producto = id_producto;
    }
    
}
