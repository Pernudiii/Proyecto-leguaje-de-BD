--TODAS LAS VISTAS
-- Vista 1: Clientes con pedidos realizados
CREATE OR REPLACE VIEW FIDE_CLIENTES_CON_PEDIDOS_V AS
SELECT 
    C.id_cliente,
    C.nombre AS nombre_cliente,
    COUNT(P.id_pedido) AS cantidad_pedidos,
    SUM(P.total) AS total_gastado
FROM 
    FIDE_CLIENTE_TB C
LEFT JOIN 
    FIDE_PEDIDO_TB P ON C.id_cliente = P.id_cliente
GROUP BY 
    C.id_cliente, C.nombre;
-- Vista 2: Productos por categoría
CREATE OR REPLACE VIEW FIDE_PRODUCTOS_POR_CATEGORIA_V AS
SELECT 
    CAT.id_categoria,
    CAT.nombre AS nombre_categoria,
    P.id_producto,
    P.nombre AS nombre_producto,
    P.precio
FROM 
    FIDE_CATEGORIA_TB CAT
INNER JOIN 
    FIDE_PRODUCTO_TB P ON CAT.id_categoria = P.id_categoria;
-- Vista 3: Insumos por unidad de medida
CREATE OR REPLACE VIEW FIDE_INSUMOS_POR_UNIDAD_V AS
SELECT 
    U.id_unidad_medida,
    U.nombre AS unidad_medida,
    I.id_insumo,
    I.nombre AS nombre_insumo
FROM 
    FIDE_UNIDAD_MEDIDA_TB U
INNER JOIN 
    FIDE_INSUMO_TB I ON U.id_unidad_medida = I.id_unidad_medida;
-- Vista 4: Usuarios por rol
CREATE OR REPLACE VIEW FIDE_USUARIOS_POR_ROL_V AS
SELECT 
    R.id_rol,
    R.nombre AS nombre_rol,
    U.id_usuario,
    U.nombre AS nombre_usuario
FROM 
    FIDE_ROL_TB R
INNER JOIN 
    FIDE_USUARIO_TB U ON R.id_rol = U.id_rol;
-- Vista 5: Pedidos con su estado
CREATE OR REPLACE VIEW FIDE_PEDIDOS_CON_ESTADO_V AS
SELECT 
    P.id_pedido,
    P.fecha_pedido,
    P.total,
    E.nombre AS estado_pedido
FROM 
    FIDE_PEDIDO_TB P
INNER JOIN 
    FIDE_ESTADO_PEDIDO_TB E ON P.id_estado = E.id_estado;
-- Vista 6: Proveedores y órdenes de compra
CREATE OR REPLACE VIEW FIDE_PROVEEDORES_ORDENES_V AS
SELECT 
    PR.id_proveedor,
    PR.nombre AS nombre_proveedor,
    COUNT(OC.id_orden_compra) AS cantidad_ordenes,
    SUM(OC.total) AS total_compras
FROM 
    FIDE_PROVEEDOR_TB PR
LEFT JOIN 
    FIDE_ORDEN_COMPRA_TB OC ON PR.id_proveedor = OC.id_proveedor
GROUP BY 
    PR.id_proveedor, PR.nombre;
-- Vista 7: Productos más vendidos
CREATE OR REPLACE VIEW FIDE_PRODUCTOS_MAS_VENDIDOS_V AS
SELECT 
    P.id_producto,
    P.nombre AS nombre_producto,
    SUM(PED.cantidad) AS cantidad_vendida,
    SUM(PED.subtotal) AS ingresos_generados
FROM 
    FIDE_PRODUCTO_TB P
INNER JOIN 
    FIDE_PEDIDO_TB PED ON P.id_producto = PED.id_producto
GROUP BY 
    P.id_producto, P.nombre
ORDER BY 
    cantidad_vendida DESC;
-- Vista 8: Pedidos por cliente y dirección
CREATE OR REPLACE VIEW FIDE_PEDIDOS_CLIENTE_DIRECCION_V AS
SELECT 
    CL.id_cliente,
    CL.nombre AS nombre_cliente,
    DIR.direccion,
    PED.id_pedido,
    PED.total AS total_pedido
FROM 
    FIDE_CLIENTE_TB CL
INNER JOIN 
    FIDE_DIRECCION_TB DIR ON CL.id_cliente = DIR.id_cliente
INNER JOIN 
    FIDE_PEDIDO_TB PED ON CL.id_cliente = PED.id_cliente;
-- Vista 9: Insumos por proveedor
CREATE OR REPLACE VIEW FIDE_INSUMOS_POR_PROVEEDOR_V AS
SELECT 
    PROV.id_proveedor,
    PROV.nombre AS nombre_proveedor,
    OC.id_orden_compra,
    INS.id_insumo,
    INS.nombre AS nombre_insumo
FROM 
    FIDE_PROVEEDOR_TB PROV
INNER JOIN 
    FIDE_ORDEN_COMPRA_TB OC ON PROV.id_proveedor = OC.id_proveedor
INNER JOIN 
    FIDE_PRODUCTO_INSUMO_TB PI ON OC.id_orden_compra = PI.id_producto_insumo
INNER JOIN 
    FIDE_INSUMO_TB INS ON PI.id_insumo = INS.id_insumo;
-- Vista 10: Usuarios activos y roles
CREATE OR REPLACE VIEW FIDE_USUARIOS_ACTIVOS_V AS
SELECT 
    U.id_usuario,
    U.nombre AS nombre_usuario,
    R.nombre AS nombre_rol
FROM 
    FIDE_USUARIO_TB U
INNER JOIN 
    FIDE_ROL_TB R ON U.id_rol = R.id_rol
WHERE 
    U.correo IS NOT NULL; -- Considerando que usuarios activos tienen correo registrado