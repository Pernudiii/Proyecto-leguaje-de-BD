-- 1. Usuario por ID
CREATE OR REPLACE FUNCTION getUsuario(p_id IN NUMBER) RETURN VARCHAR2 IS
v_nombre VARCHAR2(100);
v_email VARCHAR2(100);
v_contraseña VARCHAR2(20);
v_id_rol NUMBER;
BEGIN
SELECT nombre, email, contraseña, id_rol
INTO v_nombre, v_email, v_contraseña, v_id_rol
FROM USUARIO WHERE id = p_id;
RETURN 'ID: ' || p_id || ', Nombre: ' || v_nombre || ', Email: ' || v_email ||
', Contraseña: ' || v_contraseña || ', Rol: ' || v_id_rol;
EXCEPTION
WHEN NO_DATA_FOUND THEN RETURN 'Usuario no encontrado';
WHEN OTHERS THEN RETURN 'Error al obtener el usuario';
END;
/
-- 2. Producto por ID
CREATE OR REPLACE FUNCTION getProducto(p_id IN NUMBER) RETURN VARCHAR2 IS
v_nombre VARCHAR2(100);
v_precio NUMBER;
v_categoria_id NUMBER;
BEGIN
SELECT nombre, precio, categoria_id
INTO v_nombre, v_precio, v_categoria_id
FROM PRODUCTO WHERE id = p_id;
RETURN 'ID: ' || p_id || ', Nombre: ' || v_nombre || ', Precio: ' || v_precio || ', Categoría: ' || v_categoria_id;
EXCEPTION
WHEN NO_DATA_FOUND THEN RETURN 'Producto no encontrado';
WHEN OTHERS THEN RETURN 'Error al obtener el producto';
END;
/
-- 3. Categoría por ID
CREATE OR REPLACE FUNCTION getCategoria(p_id IN NUMBER) RETURN VARCHAR2 IS
v_nombre VARCHAR2(100);
BEGIN
SELECT nombre INTO v_nombre FROM CATEGORIA WHERE id = p_id;
RETURN 'ID: ' || p_id || ', Nombre: ' || v_nombre;
EXCEPTION
WHEN NO_DATA_FOUND THEN RETURN 'Categoría no encontrada';
WHEN OTHERS THEN RETURN 'Error al obtener la categoría';
END;
/
-- 4. Pedido por ID
CREATE OR REPLACE FUNCTION getPedido(p_id IN NUMBER) RETURN VARCHAR2 IS
v_fecha DATE;
v_cliente_id NUMBER;
v_estado_id NUMBER;
BEGIN
SELECT fecha, cliente_id, estado_id
INTO v_fecha, v_cliente_id, v_estado_id
FROM PEDIDO WHERE id = p_id;
RETURN 'ID: ' || p_id || ', Fecha: ' || TO_CHAR(v_fecha) || ', Cliente: ' || v_cliente_id || ', Estado: ' || v_estado_id;
EXCEPTION
WHEN NO_DATA_FOUND THEN RETURN 'Pedido no encontrado';
WHEN OTHERS THEN RETURN 'Error al obtener el pedido';
END;
/
-- 5. Ingreso por ID
CREATE OR REPLACE FUNCTION getIngreso(p_id IN NUMBER) RETURN VARCHAR2 IS
v_monto NUMBER(10,2);
v_fecha DATE;
BEGIN
SELECT monto, fecha INTO v_monto, v_fecha FROM INGRESO WHERE id = p_id;
RETURN 'ID: ' || p_id || ', Monto: ' || v_monto || ', Fecha: ' || TO_CHAR(v_fecha);
EXCEPTION
WHEN NO_DATA_FOUND THEN RETURN 'Ingreso no encontrado';
WHEN OTHERS THEN RETURN 'Error al obtener el ingreso';
END;
/
-- 6. Egreso por ID
CREATE OR REPLACE FUNCTION getEgreso(p_id IN NUMBER) RETURN VARCHAR2 IS
v_monto NUMBER(10,2);
v_fecha DATE;
BEGIN
SELECT monto, fecha INTO v_monto, v_fecha FROM EGRESO WHERE id = p_id;
RETURN 'ID: ' || p_id || ', Monto: ' || v_monto || ', Fecha: ' || TO_CHAR(v_fecha);
EXCEPTION
WHEN NO_DATA_FOUND THEN RETURN 'Egreso no encontrado';
WHEN OTHERS THEN RETURN 'Error al obtener el egreso';
END;
/
-- 7. Rol por ID
CREATE OR REPLACE FUNCTION getRol(p_id IN NUMBER) RETURN VARCHAR2 IS
v_nombre VARCHAR2(100);
BEGIN
SELECT nombre INTO v_nombre FROM ROL WHERE id = p_id;
RETURN 'ID: ' || p_id || ', Nombre: ' || v_nombre;
EXCEPTION
WHEN NO_DATA_FOUND THEN RETURN 'Rol no encontrado';
WHEN OTHERS THEN RETURN 'Error al obtener el rol';
END;
/
-- 8. Cliente por ID
CREATE OR REPLACE FUNCTION getCliente(p_id IN NUMBER) RETURN VARCHAR2 IS
v_nombre VARCHAR2(100);
v_email VARCHAR2(100);
v_telefono VARCHAR2(20);
BEGIN
SELECT nombre, email, telefono
INTO v_nombre, v_email, v_telefono
FROM CLIENTE WHERE id = p_id;
RETURN 'ID: ' || p_id || ', Nombre: ' || v_nombre || ', Email: ' || v_email || ', Teléfono: ' || v_telefono;
EXCEPTION
WHEN NO_DATA_FOUND THEN RETURN 'Cliente no encontrado';
WHEN OTHERS THEN RETURN 'Error al obtener el cliente';
END;
/
-- 9. Dirección por ID
CREATE OR REPLACE FUNCTION getDireccion(p_id IN NUMBER) RETURN VARCHAR2 IS
v_cliente_id NUMBER;
v_calle VARCHAR2(100);
v_ciudad VARCHAR2(100);
v_provincia_id NUMBER;
v_canton_id NUMBER;
v_distrito_id NUMBER;
BEGIN
SELECT cliente_id, calle, ciudad, provincia_id, canton_id, distrito_id
INTO v_cliente_id, v_calle, v_ciudad, v_provincia_id, v_canton_id, v_distrito_id
FROM DIRECCION WHERE id = p_id;
RETURN 'ID: ' || p_id || ', Cliente: ' || v_cliente_id || ', Calle: ' || v_calle ||
', Ciudad: ' || v_ciudad || ', Provincia: ' || v_provincia_id ||
', Cantón: ' || v_canton_id || ', Distrito: ' || v_distrito_id;
EXCEPTION
WHEN NO_DATA_FOUND THEN RETURN 'Dirección no encontrada';
WHEN OTHERS THEN RETURN 'Error al obtener la dirección';
END;
/
-- 10. Provincia por ID
CREATE OR REPLACE FUNCTION getProvincia(p_id IN NUMBER) RETURN VARCHAR2 IS
v_nombre VARCHAR2(100);
BEGIN
SELECT nombre INTO v_nombre FROM PROVINCIA WHERE id = p_id;
RETURN 'ID: ' || p_id || ', Nombre: ' || v_nombre;
EXCEPTION
WHEN NO_DATA_FOUND THEN RETURN 'Provincia no encontrada';
WHEN OTHERS THEN RETURN 'Error al obtener la provincia';
END;
/
-- 11. Cantón por ID
CREATE OR REPLACE FUNCTION getCanton(p_id IN NUMBER) RETURN VARCHAR2 IS
v_nombre VARCHAR2(100);
BEGIN
SELECT nombre INTO v_nombre FROM CANTON WHERE id = p_id;
RETURN 'ID: ' || p_id || ', Nombre: ' || v_nombre;
EXCEPTION
WHEN NO_DATA_FOUND THEN RETURN 'Cantón no encontrado';
WHEN OTHERS THEN RETURN 'Error al obtener el cantón';
END;
/
-- 12. Distrito por ID
CREATE OR REPLACE FUNCTION getDistrito(p_id IN NUMBER) RETURN VARCHAR2 IS
v_nombre VARCHAR2(100);
BEGIN
SELECT nombre INTO v_nombre FROM DISTRITO WHERE id = p_id;
RETURN 'ID: ' || p_id || ', Nombre: ' || v_nombre;
EXCEPTION
WHEN NO_DATA_FOUND THEN RETURN 'Distrito no encontrado';
WHEN OTHERS THEN RETURN 'Error al obtener el distrito';
END;
/
-- 13. Unidad de medida por ID
CREATE OR REPLACE FUNCTION getUnidadMedida(p_id IN NUMBER) RETURN VARCHAR2 IS
v_nombre VARCHAR2(100);
BEGIN
SELECT nombre INTO v_nombre FROM UNIDAD_MEDIDA WHERE id = p_id;
RETURN 'ID: ' || p_id || ', Nombre: ' || v_nombre;
EXCEPTION
WHEN NO_DATA_FOUND THEN RETURN 'Unidad de medida no encontrada';
WHEN OTHERS THEN RETURN 'Error al obtener la unidad de medida';
END;
/
-- 14. Producto-insumo por ID
CREATE OR REPLACE FUNCTION getProductoInsumo(p_id IN NUMBER) RETURN VARCHAR2 IS
v_producto_id NUMBER;
v_insumo_id NUMBER;
v_cantidad NUMBER;
BEGIN
SELECT producto_id, insumo_id, cantidad
INTO v_producto_id, v_insumo_id, v_cantidad
FROM PRODUCTO_INSUMO WHERE id = p_id;
RETURN 'ID: ' || p_id || ', Producto: ' || v_producto_id || ', Insumo: ' || v_insumo_id || ', Cantidad: ' || v_cantidad;
EXCEPTION
WHEN NO_DATA_FOUND THEN RETURN 'Producto-Insumo no encontrado';
WHEN OTHERS THEN RETURN 'Error al obtener el registro';
END;
/
-- 15. Estado de pedido por ID
CREATE OR REPLACE FUNCTION getEstadoPedido(p_id IN NUMBER) RETURN VARCHAR2 IS
v_nombre VARCHAR2(100);
BEGIN
SELECT nombre INTO v_nombre FROM ESTADO_PEDIDO WHERE id = p_id;
RETURN 'ID: ' || p_id || ', Nombre: ' || v_nombre;
EXCEPTION
WHEN NO_DATA_FOUND THEN RETURN 'Estado no encontrado';
WHEN OTHERS THEN RETURN 'Error al obtener el estado';
END;
/
