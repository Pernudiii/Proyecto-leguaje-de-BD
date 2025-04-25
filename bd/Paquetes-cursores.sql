create or replace NONEDITIONABLE PACKAGE FIDE_GESTION_PKG AS
    -- Gestión de Productos
    PROCEDURE insertar_producto(
        P_ID_PRODUCTO IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_DESCRIPCION IN VARCHAR2,
        P_PRECIO IN NUMBER,
        P_ID_CATEGORIA IN NUMBER,
        P_ID_UNIDAD_MEDIDA IN NUMBER
    );

    PROCEDURE eliminar_producto(P_ID IN NUMBER);

    PROCEDURE obtener_producto_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR);

    -- Gestión de Categorías
    PROCEDURE insertar_categoria(
        P_ID_CATEGORIA IN NUMBER,
        P_NOMBRE IN VARCHAR2
    );

    PROCEDURE eliminar_categoria(P_ID IN NUMBER);

    PROCEDURE obtener_categoria_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR);

    -- Gestión de Usuarios
    PROCEDURE insertar_usuario(
        P_ID_USUARIO IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_CORREO IN VARCHAR2,
        P_CONTRASENA IN VARCHAR2,
        P_ID_ROL IN NUMBER
    );

    PROCEDURE eliminar_usuario(P_ID IN NUMBER);

    PROCEDURE obtener_usuario_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR);

    -- Gestión de Ventas
    PROCEDURE registrar_venta(
        P_ID_VENTA IN NUMBER,
        P_ID_MESA IN NUMBER,
        P_CLIENTE IN VARCHAR2,
        P_FECHA IN DATE,
        P_TOTAL IN NUMBER,
        P_PAGADO IN NUMBER,
        P_ID_USUARIO IN NUMBER
    );

    PROCEDURE obtener_venta_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR);

    -- Gestión de Insumos
    PROCEDURE insertar_insumo(
        P_ID_INSUMO IN NUMBER,
        P_NOMBRE IN VARCHAR2,
        P_DESCRIPCION IN VARCHAR2,
        P_PRECIO IN NUMBER,
        P_TIPO IN VARCHAR2,
        P_CATEGORIA IN NUMBER
    );

    PROCEDURE obtener_insumo_por_id(P_ID IN NUMBER, P_RESULTADO OUT SYS_REFCURSOR);
END FIDE_GESTION_PKG;
