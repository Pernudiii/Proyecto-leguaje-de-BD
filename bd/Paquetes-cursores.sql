-- Gestion de productos --

CREATE OR REPLACE PACKAGE FIDE_PRODUCTO_PKG AS
    PROCEDURE insertar_producto(
        P_ID_PRODUCTO IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_DESCRIPCION IN VARCHAR2,
        P_PRECIO IN NUMBER,
        P_ID_CATEGORIA IN NUMBER,
        P_ID_UNIDAD_MEDIDA IN NUMBER
    );

    PROCEDURE actualizar_producto(
        P_ID_PRODUCTO IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_DESCRIPCION IN VARCHAR2,
        P_PRECIO IN NUMBER,
        P_ID_CATEGORIA IN NUMBER,
        P_ID_UNIDAD_MEDIDA IN NUMBER
    );

    PROCEDURE eliminar_producto(P_ID IN NUMBER);
    PROCEDURE obtener_producto_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR);
    PROCEDURE obtener_todos_los_productos(P_RESULTADO OUT SYS_REFCURSOR);
END FIDE_PRODUCTO_PKG;
/

-- Cuerpo del Paquete de Productos
CREATE OR REPLACE PACKAGE BODY FIDE_PRODUCTO_PKG AS
    PROCEDURE insertar_producto(
        P_ID_PRODUCTO IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_DESCRIPCION IN VARCHAR2,
        P_PRECIO IN NUMBER,
        P_ID_CATEGORIA IN NUMBER,
        P_ID_UNIDAD_MEDIDA IN NUMBER
    ) IS
    BEGIN
        INSERT INTO FIDE_PRODUCTO_TB (
            id_producto, nombre, descripcion, precio, id_categoria, id_unidad_medida
        ) VALUES (
            P_ID_PRODUCTO, P_NOMBRE, P_DESCRIPCION, P_PRECIO, P_ID_CATEGORIA, P_ID_UNIDAD_MEDIDA
        );
    END insertar_producto;

    PROCEDURE actualizar_producto(
        P_ID_PRODUCTO IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_DESCRIPCION IN VARCHAR2,
        P_PRECIO IN NUMBER,
        P_ID_CATEGORIA IN NUMBER,
        P_ID_UNIDAD_MEDIDA IN NUMBER
    ) IS
    BEGIN
        UPDATE FIDE_PRODUCTO_TB
        SET nombre = P_NOMBRE,
            descripcion = P_DESCRIPCION,
            precio = P_PRECIO,
            id_categoria = P_ID_CATEGORIA,
            id_unidad_medida = P_ID_UNIDAD_MEDIDA
        WHERE id_producto = P_ID_PRODUCTO;
    END actualizar_producto;

    PROCEDURE eliminar_producto(P_ID IN NUMBER) IS
    BEGIN
        DELETE FROM FIDE_PRODUCTO_TB
        WHERE id_producto = P_ID;
    END eliminar_producto;

    PROCEDURE obtener_producto_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_producto IS
            SELECT * FROM FIDE_PRODUCTO_TB
            WHERE id_producto = P_ID;
    BEGIN
        OPEN P_RESULTADO FOR c_producto;
    END obtener_producto_por_id;

    PROCEDURE obtener_todos_los_productos(P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_productos IS
            SELECT * FROM FIDE_PRODUCTO_TB;
    BEGIN
        OPEN P_RESULTADO FOR c_productos;
    END obtener_todos_los_productos;
END FIDE_PRODUCTO_PKG;
/

-- Gestion de categorias -- 

CREATE OR REPLACE PACKAGE FIDE_CATEGORIA_PKG AS
    PROCEDURE insertar_categoria(
        P_ID_CATEGORIA IN NUMBER,
        P_NOMBRE IN VARCHAR2
    );

    PROCEDURE actualizar_categoria(
        P_ID_CATEGORIA IN NUMBER,
        P_NOMBRE IN VARCHAR2
    );

    PROCEDURE eliminar_categoria(P_ID IN NUMBER);
    PROCEDURE obtener_categoria_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR);
    PROCEDURE obtener_todas_las_categorias(P_RESULTADO OUT SYS_REFCURSOR);
END FIDE_CATEGORIA_PKG;
/

-- Cuerpo del Paquete de Categorías
CREATE OR REPLACE PACKAGE BODY FIDE_CATEGORIA_PKG AS
    PROCEDURE insertar_categoria(
        P_ID_CATEGORIA IN NUMBER,
        P_NOMBRE IN VARCHAR2
    ) IS
    BEGIN
        INSERT INTO FIDE_CATEGORIA_TB (id_categoria, nombre)
        VALUES (P_ID_CATEGORIA, P_NOMBRE);
    END insertar_categoria;

    PROCEDURE actualizar_categoria(
        P_ID_CATEGORIA IN NUMBER,
        P_NOMBRE IN VARCHAR2
    ) IS
    BEGIN
        UPDATE FIDE_CATEGORIA_TB
        SET nombre = P_NOMBRE
        WHERE id_categoria = P_ID_CATEGORIA;
    END actualizar_categoria;

    PROCEDURE eliminar_categoria(P_ID IN NUMBER) IS
    BEGIN
        DELETE FROM FIDE_CATEGORIA_TB
        WHERE id_categoria = P_ID;
    END eliminar_categoria;

    PROCEDURE obtener_categoria_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_categoria IS
            SELECT * FROM FIDE_CATEGORIA_TB
            WHERE id_categoria = P_ID;
    BEGIN
        OPEN P_RESULTADO FOR c_categoria;
    END obtener_categoria_por_id;

    PROCEDURE obtener_todas_las_categorias(P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_categorias IS
            SELECT * FROM FIDE_CATEGORIA_TB;
    BEGIN
        OPEN P_RESULTADO FOR c_categorias;
    END obtener_todas_las_categorias;
END FIDE_CATEGORIA_PKG;
/

-- Gestion de usuarios --

CREATE OR REPLACE PACKAGE FIDE_USUARIO_PKG AS
    PROCEDURE insertar_usuario(
        P_ID_USUARIO IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_CORREO IN VARCHAR2,
        P_CONTRASENA IN VARCHAR2,
        P_ID_ROL IN NUMBER
    );

    PROCEDURE actualizar_usuario(
        P_ID_USUARIO IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_CORREO IN VARCHAR2,
        P_CONTRASENA IN VARCHAR2,
        P_ID_ROL IN NUMBER
    );

    PROCEDURE eliminar_usuario(P_ID IN NUMBER);
    PROCEDURE obtener_usuario_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR);
    PROCEDURE obtener_todos_los_usuarios(P_RESULTADO OUT SYS_REFCURSOR);
END FIDE_USUARIO_PKG;
/

-- Cuerpo del Paquete de Usuarios
CREATE OR REPLACE PACKAGE BODY FIDE_USUARIO_PKG AS
    PROCEDURE insertar_usuario(
        P_ID_USUARIO IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_CORREO IN VARCHAR2,
        P_CONTRASENA IN VARCHAR2,
        P_ID_ROL IN NUMBER
    ) IS
    BEGIN
        INSERT INTO FIDE_USUARIO_TB (
            id_usuario, nombre, correo, contrasena, id_rol
        ) VALUES (
            P_ID_USUARIO, P_NOMBRE, P_CORREO, P_CONTRASENA, P_ID_ROL
        );
    END insertar_usuario;

    PROCEDURE actualizar_usuario(
        P_ID_USUARIO IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_CORREO IN VARCHAR2,
        P_CONTRASENA IN VARCHAR2,
        P_ID_ROL IN NUMBER
    ) IS
    BEGIN
        UPDATE FIDE_USUARIO_TB
        SET nombre = P_NOMBRE,
            correo = P_CORREO,
            contrasena = P_CONTRASENA,
            id_rol = P_ID_ROL
        WHERE id_usuario = P_ID_USUARIO;
    END actualizar_usuario;

    PROCEDURE eliminar_usuario(P_ID IN NUMBER) IS
    BEGIN
        DELETE FROM FIDE_USUARIO_TB
        WHERE id_usuario = P_ID;
    END eliminar_usuario;

    PROCEDURE obtener_usuario_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_usuario IS
            SELECT * FROM FIDE_USUARIO_TB
            WHERE id_usuario = P_ID;
    BEGIN
        OPEN P_RESULTADO FOR c_usuario;
    END obtener_usuario_por_id;

    PROCEDURE obtener_todos_los_usuarios(P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_usuarios IS
            SELECT * FROM FIDE_USUARIO_TB;
    BEGIN
        OPEN P_RESULTADO FOR c_usuarios;
    END obtener_todos_los_usuarios;
END FIDE_USUARIO_PKG;
/

-- Gestion de ventas --

CREATE OR REPLACE PACKAGE FIDE_VENTA_PKG AS
    PROCEDURE registrar_venta(
        P_ID_VENTA IN NUMBER,
        P_ID_MESA IN NUMBER,
        P_CLIENTE IN VARCHAR2,
        P_FECHA IN DATE,
        P_TOTAL IN NUMBER,
        P_PAGADO IN NUMBER,
        P_ID_USUARIO IN NUMBER
    );

    PROCEDURE actualizar_venta(
        P_ID_VENTA IN NUMBER,
        P_ID_MESA IN NUMBER,
        P_CLIENTE IN VARCHAR2,
        P_FECHA IN DATE,
        P_TOTAL IN NUMBER,
        P_PAGADO IN NUMBER,
        P_ID_USUARIO IN NUMBER
    );

    PROCEDURE eliminar_venta(P_ID IN NUMBER);
    PROCEDURE obtener_venta_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR);
    PROCEDURE obtener_ventas_por_fecha(P_FECHA_INICIO IN DATE, P_FECHA_FIN IN DATE, P_RESULTADO OUT SYS_REFCURSOR);
    PROCEDURE obtener_ventas_por_usuario(P_ID_USUARIO IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR);
END FIDE_VENTA_PKG;
/

-- Cuerpo del Paquete de Ventas
CREATE OR REPLACE PACKAGE BODY FIDE_VENTA_PKG AS
    PROCEDURE registrar_venta(
        P_ID_VENTA IN NUMBER,
        P_ID_MESA IN NUMBER,
        P_CLIENTE IN VARCHAR2,
        P_FECHA IN DATE,
        P_TOTAL IN NUMBER,
        P_PAGADO IN NUMBER,
        P_ID_USUARIO IN NUMBER
    ) IS
    BEGIN
        INSERT INTO FIDE_VENTA_TB (
            id_venta, idMesa, cliente, fecha, total, pagado, idUsuario
        ) VALUES (
            P_ID_VENTA, P_ID_MESA, P_CLIENTE, P_FECHA, P_TOTAL, P_PAGADO, P_ID_USUARIO
        );
    END registrar_venta;

    PROCEDURE actualizar_venta(
        P_ID_VENTA IN NUMBER,
        P_ID_MESA IN NUMBER,
        P_CLIENTE IN VARCHAR2,
        P_FECHA IN DATE,
        P_TOTAL IN NUMBER,
        P_PAGADO IN NUMBER,
        P_ID_USUARIO IN NUMBER
    ) IS
    BEGIN
        UPDATE FIDE_VENTA_TB
        SET idMesa = P_ID_MESA,
            cliente = P_CLIENTE,
            fecha = P_FECHA,
            total = P_TOTAL,
            pagado = P_PAGADO,
            idUsuario = P_ID_USUARIO
        WHERE id_venta = P_ID_VENTA;
    END actualizar_venta;

    PROCEDURE eliminar_venta(P_ID IN NUMBER) IS
    BEGIN
        DELETE FROM FIDE_VENTA_TB
        WHERE id_venta = P_ID;
    END eliminar_venta;

    PROCEDURE obtener_venta_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_venta IS
            SELECT * FROM FIDE_VENTA_TB
            WHERE id_venta = P_ID;
    BEGIN
        OPEN P_RESULTADO FOR c_venta;
    END obtener_venta_por_id;

    PROCEDURE obtener_ventas_por_fecha(P_FECHA_INICIO IN DATE, P_FECHA_FIN IN DATE, P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_ventas IS
            SELECT * FROM FIDE_VENTA_TB
            WHERE fecha BETWEEN P_FECHA_INICIO AND P_FECHA_FIN;
    BEGIN
        OPEN P_RESULTADO FOR c_ventas;
    END obtener_ventas_por_fecha;

    PROCEDURE obtener_ventas_por_usuario(P_ID_USUARIO IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_ventas IS
            SELECT * FROM FIDE_VENTA_TB
            WHERE idUsuario = P_ID_USUARIO;
    BEGIN
        OPEN P_RESULTADO FOR c_ventas;
    END obtener_ventas_por_usuario;
END FIDE_VENTA_PKG;
/

-- Gestion de insumos --

CREATE OR REPLACE PACKAGE FIDE_INSUMO_PKG AS
    PROCEDURE insertar_insumo(
        P_ID_INSUMO IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_DESCRIPCION IN VARCHAR2,
        P_PRECIO IN NUMBER,
        P_TIPO IN VARCHAR2,
        P_CATEGORIA IN NUMBER
    );

    PROCEDURE actualizar_insumo(
        P_ID_INSUMO IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_DESCRIPCION IN VARCHAR2,
        P_PRECIO IN NUMBER,
        P_TIPO IN VARCHAR2,
        P_CATEGORIA IN NUMBER
    );

    PROCEDURE eliminar_insumo(P_ID IN NUMBER);
    PROCEDURE obtener_insumo_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR);
    PROCEDURE obtener_todos_los_insumos(P_RESULTADO OUT SYS_REFCURSOR);
END FIDE_INSUMO_PKG;
/

-- Cuerpo del Paquete de Insumos
CREATE OR REPLACE PACKAGE BODY FIDE_INSUMO_PKG AS
    PROCEDURE insertar_insumo(
        P_ID_INSUMO IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_DESCRIPCION IN VARCHAR2,
        P_PRECIO IN NUMBER,
        P_TIPO IN VARCHAR2,
        P_CATEGORIA IN NUMBER
    ) IS
    BEGIN
        INSERT INTO FIDE_INSUMO_TB (
            id_insumo, nombre, descripcion, precio, tipo, categoria
        ) VALUES (
            P_ID_INSUMO, P_NOMBRE, P_DESCRIPCION, P_PRECIO, P_TIPO, P_CATEGORIA
        );
    END insertar_insumo;

    PROCEDURE actualizar_insumo(
        P_ID_INSUMO IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_DESCRIPCION IN VARCHAR2,
        P_PRECIO IN NUMBER,
        P_TIPO IN VARCHAR2,
        P_CATEGORIA IN NUMBER
    ) IS
    BEGIN
        UPDATE FIDE_INSUMO_TB
        SET nombre = P_NOMBRE,
            descripcion = P_DESCRIPCION,
            precio = P_PRECIO,
            tipo = P_TIPO,
            categoria = P_CATEGORIA
        WHERE id_insumo = P_ID_INSUMO;
    END actualizar_insumo;

    PROCEDURE eliminar_insumo(P_ID IN NUMBER) IS
    BEGIN
        DELETE FROM FIDE_INSUMO_TB
        WHERE id_insumo = P_ID;
    END eliminar_insumo;

    PROCEDURE obtener_insumo_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_insumo IS
            SELECT * FROM FIDE_INSUMO_TB
            WHERE id_insumo = P_ID;
    BEGIN
        OPEN P_RESULTADO FOR c_insumo;
    END obtener_insumo_por_id;

    PROCEDURE obtener_todos_los_insumos(P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_insumos IS
            SELECT * FROM FIDE_INSUMO_TB;
    BEGIN
        OPEN P_RESULTADO FOR c_insumos;
    END obtener_todos_los_insumos;
END FIDE_INSUMO_PKG;
/

-- Gestion de mesas --

CREATE OR REPLACE PACKAGE FIDE_MESA_PKG AS
    PROCEDURE insertar_mesa(
        P_ID_MESA IN NUMBER,
        P_NUMERO_MESA IN NUMBER,
        P_CAPACIDAD IN NUMBER,
        P_UBICACION IN VARCHAR2
    );

    PROCEDURE actualizar_mesa(
        P_ID_MESA IN NUMBER,
        P_NUMERO_MESA IN NUMBER,
        P_CAPACIDAD IN NUMBER,
        P_UBICACION IN VARCHAR2
    );

    PROCEDURE eliminar_mesa(P_ID IN NUMBER);
    PROCEDURE obtener_mesa_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR);
    PROCEDURE obtener_todas_las_mesas(P_RESULTADO OUT SYS_REFCURSOR);
END FIDE_MESA_PKG;
/

-- Cuerpo del Paquete de Mesas
CREATE OR REPLACE PACKAGE BODY FIDE_MESA_PKG AS
    PROCEDURE insertar_mesa(
        P_ID_MESA IN NUMBER,
        P_NUMERO_MESA IN NUMBER,
        P_CAPACIDAD IN NUMBER,
        P_UBICACION IN VARCHAR2
    ) IS
    BEGIN
        INSERT INTO FIDE_MESA_TB (
            id_mesa, numero_mesa, capacidad, ubicacion
        ) VALUES (
            P_ID_MESA, P_NUMERO_MESA, P_CAPACIDAD, P_UBICACION
        );
    END insertar_mesa;

    PROCEDURE actualizar_mesa(
        P_ID_MESA IN NUMBER,
        P_NUMERO_MESA IN NUMBER,
        P_CAPACIDAD IN NUMBER,
        P_UBICACION IN VARCHAR2
    ) IS
    BEGIN
        UPDATE FIDE_MESA_TB
        SET numero_mesa = P_NUMERO_MESA,
            capacidad = P_CAPACIDAD,
            ubicacion = P_UBICACION
        WHERE id_mesa = P_ID_MESA;
    END actualizar_mesa;

    PROCEDURE eliminar_mesa(P_ID IN NUMBER) IS
    BEGIN
        DELETE FROM FIDE_MESA_TB
        WHERE id_mesa = P_ID;
    END eliminar_mesa;

    PROCEDURE obtener_mesa_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_mesa IS
            SELECT * FROM FIDE_MESA_TB
            WHERE id_mesa = P_ID;
    BEGIN
        OPEN P_RESULTADO FOR c_mesa;
    END obtener_mesa_por_id;

    PROCEDURE obtener_todas_las_mesas(P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_mesas IS
            SELECT * FROM FIDE_MESA_TB;
    BEGIN
        OPEN P_RESULTADO FOR c_mesas;
    END obtener_todas_las_mesas;
END FIDE_MESA_PKG;
/

-- Gestion de clientes --

CREATE OR REPLACE PACKAGE FIDE_CLIENTE_PKG AS
    PROCEDURE insertar_cliente(
        P_ID_CLIENTE IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_CORREO IN VARCHAR2,
        P_TELEFONO IN VARCHAR2
    );

    PROCEDURE actualizar_cliente(
        P_ID_CLIENTE IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_CORREO IN VARCHAR2,
        P_TELEFONO IN VARCHAR2
    );

    PROCEDURE eliminar_cliente(P_ID IN NUMBER);
    PROCEDURE obtener_cliente_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR);
    PROCEDURE obtener_todos_los_clientes(P_RESULTADO OUT SYS_REFCURSOR);
END FIDE_CLIENTE_PKG;
/

-- Cuerpo del Paquete de Clientes
CREATE OR REPLACE PACKAGE BODY FIDE_CLIENTE_PKG AS
    PROCEDURE insertar_cliente(
        P_ID_CLIENTE IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_CORREO IN VARCHAR2,
        P_TELEFONO IN VARCHAR2
    ) IS
    BEGIN
        INSERT INTO FIDE_CLIENTE_TB (
            id_cliente, nombre, correo, telefono
        ) VALUES (
            P_ID_CLIENTE, P_NOMBRE, P_CORREO, P_TELEFONO
        );
    END insertar_cliente;

    PROCEDURE actualizar_cliente(
        P_ID_CLIENTE IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_CORREO IN VARCHAR2,
        P_TELEFONO IN VARCHAR2
    ) IS
    BEGIN
        UPDATE FIDE_CLIENTE_TB
        SET nombre = P_NOMBRE,
            correo = P_CORREO,
            telefono = P_TELEFONO
        WHERE id_cliente = P_ID_CLIENTE;
    END actualizar_cliente;

    PROCEDURE eliminar_cliente(P_ID IN NUMBER) IS
    BEGIN
        DELETE FROM FIDE_CLIENTE_TB
        WHERE id_cliente = P_ID;
    END eliminar_cliente;

    PROCEDURE obtener_cliente_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_cliente IS
            SELECT * FROM FIDE_CLIENTE_TB
            WHERE id_cliente = P_ID;
    BEGIN
        OPEN P_RESULTADO FOR c_cliente;
    END obtener_cliente_por_id;

    PROCEDURE obtener_todos_los_clientes(P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_clientes IS
            SELECT * FROM FIDE_CLIENTE_TB;
    BEGIN
        OPEN P_RESULTADO FOR c_clientes;
    END obtener_todos_los_clientes;
END FIDE_CLIENTE_PKG;
/

-- Informes y Estadisticas --

CREATE OR REPLACE PACKAGE FIDE_INFORMES_PKG AS
    PROCEDURE obtener_ventas_por_mes(P_FECHA_INICIO IN DATE, P_FECHA_FIN IN DATE, P_RESULTADO OUT SYS_REFCURSOR);
    PROCEDURE obtener_productos_mas_vendidos(P_RESULTADO OUT SYS_REFCURSOR);
    PROCEDURE obtener_ingresos_totales(P_FECHA_INICIO IN DATE, P_FECHA_FIN IN DATE, P_RESULTADO OUT SYS_REFCURSOR);
    PROCEDURE obtener_estadisticas_clientes(P_RESULTADO OUT SYS_REFCURSOR);
END FIDE_INFORMES_PKG;
/

-- Cuerpo del Paquete de Informes y Estadísticas
CREATE OR REPLACE PACKAGE BODY FIDE_INFORMES_PKG AS
    PROCEDURE obtener_ventas_por_mes(P_FECHA_INICIO IN DATE, P_FECHA_FIN IN DATE, P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_ventas IS
            SELECT 
                TO_CHAR(fecha, 'YYYY-MM') AS mes,
                SUM(total) AS total_ventas
            FROM 
                FIDE_VENTA_TB
            WHERE 
                fecha BETWEEN P_FECHA_INICIO AND P_FECHA_FIN
            GROUP BY 
                TO_CHAR(fecha, 'YYYY-MM')
            ORDER BY 
                mes;
    BEGIN
        OPEN P_RESULTADO FOR c_ventas;
    END obtener_ventas_por_mes;

    PROCEDURE obtener_productos_mas_vendidos(P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_productos IS
            SELECT 
                P.id_producto,
                P.nombre AS nombre_producto,
                SUM(IV.cantidad) AS cantidad_vendida,
                SUM(IV.cantidad * IV.precio) AS ingresos_generados
            FROM 
                FIDE_PRODUCTO_TB P
            JOIN 
                FIDE_INSUMOS_VENTA_TB IV ON P.id_producto = IV.idInsumo
            GROUP BY 
                P.id_producto, P.nombre
            ORDER BY 
                cantidad_vendida DESC;
    BEGIN
        OPEN P_RESULTADO FOR c_productos;
    END obtener_productos_mas_vendidos;

    PROCEDURE obtener_ingresos_totales(P_FECHA_INICIO IN DATE, P_FECHA_FIN IN DATE, P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_ingresos IS
            SELECT 
                SUM(total) AS total_ingresos
            FROM 
                FIDE_VENTA_TB
            WHERE 
                fecha BETWEEN P_FECHA_INICIO AND P_FECHA_FIN;
    BEGIN
        OPEN P_RESULTADO FOR c_ingresos;
    END obtener_ingresos_totales;

    PROCEDURE obtener_estadisticas_clientes(P_RESULTADO OUT SYS_REFCURSOR) IS
        CURSOR c_estadisticas IS
            SELECT 
                C.id_cliente,
                C.nombre AS nombre_cliente,
                COUNT(V.id_venta) AS cantidad_pedidos,
                SUM(V.total) AS total_gastado
            FROM 
                FIDE_CLIENTE_TB C
            LEFT JOIN 
                FIDE_VENTA_TB V ON C.id_cliente = V.idMesa
            GROUP BY 
                C.id_cliente, C.nombre;
    BEGIN
        OPEN P_RESULTADO FOR c_estadisticas;
    END obtener_estadisticas_clientes;
END FIDE_INFORMES_PKG;
/