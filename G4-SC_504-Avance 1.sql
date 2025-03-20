1. USUARIO
-- Crear tabla
CREATE TABLE USUARIO (
    id NUMBER PRIMARY KEY,
    nombre VARCHAR2(100),
    email VARCHAR2(100)
);

-- CRUD
-- Crear
CREATE OR REPLACE PROCEDURE createUsuario(usuarioData IN USUARIO%ROWTYPE) IS
BEGIN
    INSERT INTO USUARIO (id, nombre, email) 
    VALUES (usuarioData.id, usuarioData.nombre, usuarioData.email);
END;
/

-- Leer
CREATE OR REPLACE FUNCTION getUsuario(id IN NUMBER) RETURN USUARIO%ROWTYPE IS
    usuarioRecord USUARIO%ROWTYPE;
BEGIN
    SELECT * INTO usuarioRecord FROM USUARIO WHERE id = id;
    RETURN usuarioRecord;
END;
/

-- Actualizar
CREATE OR REPLACE PROCEDURE updateUsuario(id IN NUMBER, usuarioData IN USUARIO%ROWTYPE) IS
BEGIN
    UPDATE USUARIO 
    SET nombre = usuarioData.nombre, email = usuarioData.email 
    WHERE id = id;
END;
/

-- Eliminar
CREATE OR REPLACE PROCEDURE deleteUsuario(id IN NUMBER) IS
BEGIN
    DELETE FROM USUARIO WHERE id = id;
END;
/

2. PRODUCTO
-- Crear tabla
CREATE TABLE PRODUCTO (
    id NUMBER PRIMARY KEY,
    nombre VARCHAR2(100),
    precio NUMBER(10, 2),
    categoria_id NUMBER,
    CONSTRAINT fk_categoria FOREIGN KEY (categoria_id) REFERENCES CATEGORIA(id)
);

-- CRUD
-- Crear
CREATE OR REPLACE PROCEDURE createProducto(productoData IN PRODUCTO%ROWTYPE) IS
BEGIN
    INSERT INTO PRODUCTO (id, nombre, precio, categoria_id) 
    VALUES (productoData.id, productoData.nombre, productoData.precio, productoData.categoria_id);
END;
/

-- Leer
CREATE OR REPLACE FUNCTION getProducto(id IN NUMBER) RETURN PRODUCTO%ROWTYPE IS
    productoRecord PRODUCTO%ROWTYPE;
BEGIN
    SELECT * INTO productoRecord FROM PRODUCTO WHERE id = id;
    RETURN productoRecord;
END;
/

-- Actualizar
CREATE OR REPLACE PROCEDURE updateProducto(id IN NUMBER, productoData IN PRODUCTO%ROWTYPE) IS
BEGIN
    UPDATE PRODUCTO 
    SET nombre = productoData.nombre, precio = productoData.precio, categoria_id = productoData.categoria_id 
    WHERE id = id;
END;
/

-- Eliminar
CREATE OR REPLACE PROCEDURE deleteProducto(id IN NUMBER) IS
BEGIN
    DELETE FROM PRODUCTO WHERE id = id;
END;
/

3. CATEGORIA
-- Crear tabla
CREATE TABLE CATEGORIA (
    id NUMBER PRIMARY KEY,
    nombre VARCHAR2(100)
);

-- CRUD
-- Crear
CREATE OR REPLACE PROCEDURE createCategoria(categoriaData IN CATEGORIA%ROWTYPE) IS
BEGIN
    INSERT INTO CATEGORIA (id, nombre) 
    VALUES (categoriaData.id, categoriaData.nombre);
END;
/

-- Leer
CREATE OR REPLACE FUNCTION getCategoria(id IN NUMBER) RETURN CATEGORIA%ROWTYPE IS
    categoriaRecord CATEGORIA%ROWTYPE;
BEGIN
    SELECT * INTO categoriaRecord FROM CATEGORIA WHERE id = id;
    RETURN categoriaRecord;
END;
/

-- Actualizar
CREATE OR REPLACE PROCEDURE updateCategoria(id IN NUMBER, categoriaData IN CATEGORIA%ROWTYPE) IS
BEGIN
    UPDATE CATEGORIA SET nombre = categoriaData.nombre WHERE id = id;
END;
/

-- Eliminar
CREATE OR REPLACE PROCEDURE deleteCategoria(id IN NUMBER) IS
BEGIN
    DELETE FROM CATEGORIA WHERE id = id;
END;
/

4. PEDIDO
-- Crear tabla
CREATE TABLE PEDIDO (
    id NUMBER PRIMARY KEY,
    fecha DATE,
    cliente_id NUMBER,
    estado_id NUMBER,
    CONSTRAINT fk_cliente FOREIGN KEY (cliente_id) REFERENCES CLIENTE(id),
    CONSTRAINT fk_estado FOREIGN KEY (estado_id) REFERENCES ESTADO_PEDIDO(id)
);

-- CRUD
-- Crear
CREATE OR REPLACE PROCEDURE createPedido(pedidoData IN PEDIDO%ROWTYPE) IS
BEGIN
    INSERT INTO PEDIDO (id, fecha, cliente_id, estado_id) 
    VALUES (pedidoData.id, pedidoData.fecha, pedidoData.cliente_id, pedidoData.estado_id);
END;
/

-- Leer
CREATE OR REPLACE FUNCTION getPedido(id IN NUMBER) RETURN PEDIDO%ROWTYPE IS
    pedidoRecord PEDIDO%ROWTYPE;
BEGIN
    SELECT * INTO pedidoRecord FROM PEDIDO WHERE id = id;
    RETURN pedidoRecord;
END;
/

-- Actualizar
CREATE OR REPLACE PROCEDURE updatePedido(id IN NUMBER, pedidoData IN PEDIDO%ROWTYPE) IS
BEGIN
    UPDATE PEDIDO SET fecha = pedidoData.fecha, cliente_id = pedidoData.cliente_id, estado_id = pedidoData.estado_id 
    WHERE id = id;
END;
/

-- Eliminar
CREATE OR REPLACE PROCEDURE deletePedido(id IN NUMBER) IS
BEGIN
    DELETE FROM PEDIDO WHERE id = id;
END;
/

5. INGRESO
-- Crear tabla
CREATE TABLE INGRESO (
    id NUMBER PRIMARY KEY,
    monto NUMBER(10, 2),
    fecha DATE
);

-- CRUD
-- Crear
CREATE OR REPLACE PROCEDURE createIngreso(ingresoData IN INGRESO%ROWTYPE) IS
BEGIN
    INSERT INTO INGRESO (id, monto, fecha) 
    VALUES (ingresoData.id, ingresoData.monto, ingresoData.fecha);
END;
/

-- Leer
CREATE OR REPLACE FUNCTION getIngreso(id IN NUMBER) RETURN INGRESO%ROWTYPE IS
    ingresoRecord INGRESO%ROWTYPE;
BEGIN
    SELECT * INTO ingresoRecord FROM INGRESO WHERE id = id;
    RETURN ingresoRecord;
END;
/

-- Actualizar
CREATE OR REPLACE PROCEDURE updateIngreso(id IN NUMBER, ingresoData IN INGRESO%ROWTYPE) IS
BEGIN
    UPDATE INGRESO SET monto = ingresoData.monto, fecha = ingresoData.fecha WHERE id = id;
END;
/

-- Eliminar
CREATE OR REPLACE PROCEDURE deleteIngreso(id IN NUMBER) IS
BEGIN
    DELETE FROM INGRESO WHERE id = id;
END;
/

6. EGRESO
-- Crear tabla
CREATE TABLE EGRESO (
    id NUMBER PRIMARY KEY,
    monto NUMBER(10, 2),
    fecha DATE
);

-- CRUD
-- Crear
CREATE OR REPLACE PROCEDURE createEgreso(egresoData IN EGRESO%ROWTYPE) IS
BEGIN
    INSERT INTO EGRESO (id, monto, fecha) 
    VALUES (egresoData.id, egresoData.monto, egresoData.fecha);
END;
/

-- Leer
CREATE OR REPLACE FUNCTION getEgreso(id IN NUMBER) RETURN EGRESO%ROWTYPE IS
    egresoRecord EGRESO%ROWTYPE;
BEGIN
    SELECT * INTO egresoRecord FROM EGRESO WHERE id = id;
    RETURN egresoRecord;
END;
/

-- Actualizar
CREATE OR REPLACE PROCEDURE updateEgreso(id IN NUMBER, egresoData IN EGRESO%ROWTYPE) IS
BEGIN
    UPDATE EGRESO SET monto = egresoData.monto, fecha = egresoData.fecha WHERE id = id;
END;
/

-- Eliminar
CREATE OR REPLACE PROCEDURE deleteEgreso(id IN NUMBER) IS
BEGIN
    DELETE FROM EGRESO WHERE id = id;
END;
/

7. ROL
-- Crear tabla
CREATE TABLE ROL (
    id NUMBER PRIMARY KEY,
    nombre VARCHAR2(100)
);

-- CRUD
-- Crear
CREATE OR REPLACE PROCEDURE createRol(rolData IN ROL%ROWTYPE) IS
BEGIN
    INSERT INTO ROL (id, nombre) 
    VALUES (rolData.id, rolData.nombre);
END;
/

-- Leer
CREATE OR REPLACE FUNCTION getRol(id IN NUMBER) RETURN ROL%ROWTYPE IS
    rolRecord ROL%ROWTYPE;
BEGIN
    SELECT * INTO rolRecord FROM ROL WHERE id = id;
    RETURN rolRecord;
END;
/

-- Actualizar
CREATE OR REPLACE PROCEDURE updateRol(id IN NUMBER, rolData IN ROL%ROWTYPE) IS
BEGIN
    UPDATE ROL SET nombre = rolData.nombre WHERE id = id;
END;
/

-- Eliminar
CREATE OR REPLACE PROCEDURE deleteRol(id IN NUMBER) IS
BEGIN
    DELETE FROM ROL WHERE id = id;
END;
/

8. CLIENTE
-- Crear tabla
CREATE TABLE CLIENTE (
    id NUMBER PRIMARY KEY,
    nombre VARCHAR2(100),
    email VARCHAR2(100),
    telefono VARCHAR2(20)
);

-- CRUD
-- Crear
CREATE OR REPLACE PROCEDURE createCliente(clienteData IN CLIENTE%ROWTYPE) IS
BEGIN
    INSERT INTO CLIENTE (id, nombre, email, telefono) 
    VALUES (clienteData.id, clienteData.nombre, clienteData.email, clienteData.telefono);
END;
/

-- Leer
CREATE OR REPLACE FUNCTION getCliente(id IN NUMBER) RETURN CLIENTE%ROWTYPE IS
    clienteRecord CLIENTE%ROWTYPE;
BEGIN
    SELECT * INTO clienteRecord FROM CLIENTE WHERE id = id;
    RETURN clienteRecord;
END;
/

-- Actualizar
CREATE OR REPLACE PROCEDURE updateCliente(id IN NUMBER, clienteData IN CLIENTE%ROWTYPE) IS
BEGIN
    UPDATE CLIENTE SET nombre = clienteData.nombre, email = clienteData.email, telefono = clienteData.telefono WHERE id = id;
END;
/

-- Eliminar
CREATE OR REPLACE PROCEDURE deleteCliente(id IN NUMBER) IS
BEGIN
    DELETE FROM CLIENTE WHERE id = id;
END;
/

9. DIRECCION
-- Crear tabla
CREATE TABLE DIRECCION (
    id NUMBER PRIMARY KEY,
    cliente_id NUMBER,
    calle VARCHAR2(100),
    ciudad VARCHAR2(100),
    provincia_id NUMBER,
    canton_id NUMBER,
    distrito_id NUMBER,
    CONSTRAINT fk_cliente_direccion FOREIGN KEY (cliente_id) REFERENCES CLIENTE(id),
    CONSTRAINT fk_provincia FOREIGN KEY (provincia_id) REFERENCES PROVINCIA(id),
    CONSTRAINT fk_canton FOREIGN KEY (canton_id) REFERENCES CANTON(id),
    CONSTRAINT fk_distrito FOREIGN KEY (distrito_id) REFERENCES DISTRITO(id)
);

-- CRUD
-- Crear
CREATE OR REPLACE PROCEDURE createDireccion(direccionData IN DIRECCION%ROWTYPE) IS
BEGIN
    INSERT INTO DIRECCION (id, cliente_id, calle, ciudad, provincia_id, canton_id, distrito_id) 
    VALUES (direccionData.id, direccionData.cliente_id, direccionData.calle, direccionData.ciudad, direccionData.provincia_id, direccionData.canton_id, direccionData.distrito_id);
END;
/

-- Leer
CREATE OR REPLACE FUNCTION getDireccion(id IN NUMBER) RETURN DIRECCION%ROWTYPE IS
    direccionRecord DIRECCION%ROWTYPE;
BEGIN
    SELECT * INTO direccionRecord FROM DIRECCION WHERE id = id;
    RETURN direccionRecord;
END;
/

-- Actualizar
CREATE OR REPLACE PROCEDURE updateDireccion(id IN NUMBER, direccionData IN DIRECCION%ROWTYPE) IS
BEGIN
    UPDATE DIRECCION SET cliente_id = direccionData.cliente_id, calle = direccionData.calle, ciudad = direccionData.ciudad, provincia_id = direccionData.provincia_id, canton_id = direccionData.canton_id, distrito_id = direccionData.distrito_id WHERE id = id;
END;
/

-- Eliminar
CREATE OR REPLACE PROCEDURE deleteDireccion(id IN NUMBER) IS
BEGIN
    DELETE FROM DIRECCION WHERE id = id;
END;
/

10. PROVINCIA
-- Crear tabla
CREATE TABLE PROVINCIA (
    id NUMBER PRIMARY KEY,
    nombre VARCHAR2(100)
);

-- CRUD
-- Crear
CREATE OR REPLACE PROCEDURE createProvincia(provinciaData IN PROVINCIA%ROWTYPE) IS
BEGIN
    INSERT INTO PROVINCIA (id, nombre) 
    VALUES (provinciaData.id, provinciaData.nombre);
END;
/

-- Leer
CREATE OR REPLACE FUNCTION getProvincia(id IN NUMBER) RETURN PROVINCIA%ROWTYPE IS
    provinciaRecord PROVINCIA%ROWTYPE;
BEGIN
    SELECT * INTO provinciaRecord FROM PROVINCIA WHERE id = id;
    RETURN provinciaRecord;
END;
/

-- Actualizar
CREATE OR REPLACE PROCEDURE updateProvincia(id IN NUMBER, provinciaData IN PROVINCIA%ROWTYPE) IS
BEGIN
    UPDATE PROVINCIA SET nombre = provinciaData.nombre WHERE id = id;
END;
/

-- Eliminar
CREATE OR REPLACE PROCEDURE deleteProvincia(id IN NUMBER) IS
BEGIN
    DELETE FROM PROVINCIA WHERE id = id;
END;
/

11. CANTON
-- Crear tabla
CREATE TABLE CANTON (
    id NUMBER PRIMARY KEY,
    nombre VARCHAR2(100)
);

-- CRUD
-- Crear
CREATE OR REPLACE PROCEDURE createCanton(cantonData IN CANTON%ROWTYPE) IS
BEGIN
    INSERT INTO CANTON (id, nombre) 
    VALUES (cantonData.id, cantonData.nombre);
END;
/

-- Leer
CREATE OR REPLACE FUNCTION getCanton(id IN NUMBER) RETURN CANTON%ROWTYPE IS
    cantonRecord CANTON%ROWTYPE;
BEGIN
    SELECT * INTO cantonRecord FROM CANTON WHERE id = id;
    RETURN cantonRecord;
END;
/

-- Actualizar
CREATE OR REPLACE PROCEDURE updateCanton(id IN NUMBER, cantonData IN CANTON%ROWTYPE) IS
BEGIN
    UPDATE CANTON SET nombre = cantonData.nombre WHERE id = id;
END;
/

-- Eliminar
CREATE OR REPLACE PROCEDURE deleteCanton(id IN NUMBER) IS
BEGIN
    DELETE FROM CANTON WHERE id = id;
END;
/

12. DISTRITO
-- Crear tabla
CREATE TABLE DISTRITO (
    id NUMBER PRIMARY KEY,
    nombre VARCHAR2(100)
);

-- CRUD
-- Crear
CREATE OR REPLACE PROCEDURE createDistrito(distritoData IN DISTRITO%ROWTYPE) IS
BEGIN
    INSERT INTO DISTRITO (id, nombre) 
    VALUES (distritoData.id, distritoData.nombre);
END;
/

-- Leer
CREATE OR REPLACE FUNCTION getDistrito(id IN NUMBER) RETURN DISTRITO%ROWTYPE IS
    distritoRecord DISTRITO%ROWTYPE;
BEGIN
    SELECT * INTO distritoRecord FROM DISTRITO WHERE id = id;
    RETURN distritoRecord;
END;
/

-- Actualizar
CREATE OR REPLACE PROCEDURE updateDistrito(id IN NUMBER, distritoData IN DISTRITO%ROWTYPE) IS
BEGIN
    UPDATE DISTRITO SET nombre = distritoData.nombre WHERE id = id;
END;
/

-- Eliminar
CREATE OR REPLACE PROCEDURE deleteDistrito(id IN NUMBER) IS
BEGIN
    DELETE FROM DISTRITO WHERE id = id;
END;
/

13. UNIDAD_MEDIDA
-- Crear tabla
CREATE TABLE UNIDAD_MEDIDA (
    id NUMBER PRIMARY KEY,
    nombre VARCHAR2(100)
);

-- CRUD
-- Crear
CREATE OR REPLACE PROCEDURE createUnidadMedida(unidadMedidaData IN UNIDAD_MEDIDA%ROWTYPE) IS
BEGIN
    INSERT INTO UNIDAD_MEDIDA (id, nombre) 
    VALUES (unidadMedidaData.id, unidadMedidaData.nombre);
END;
/

-- Leer
CREATE OR REPLACE FUNCTION getUnidadMedida(id IN NUMBER) RETURN UNIDAD_MEDIDA%ROWTYPE IS
    unidadMedidaRecord UNIDAD_MEDIDA%ROWTYPE;
BEGIN
    SELECT * INTO unidadMedidaRecord FROM UNIDAD_MEDIDA WHERE id = id;
    RETURN unidadMedidaRecord;
END;
/

-- Actualizar
CREATE OR REPLACE PROCEDURE updateUnidadMedida(id IN NUMBER, unidadMedidaData IN UNIDAD_MEDIDA%ROWTYPE) IS
BEGIN
    UPDATE UNIDAD_MEDIDA SET nombre = unidadMedidaData.nombre WHERE id = id;
END;
/

-- Eliminar
CREATE OR REPLACE PROCEDURE deleteUnidadMedida(id IN NUMBER) IS
BEGIN
    DELETE FROM UNIDAD_MEDIDA WHERE id = id;
END;
/

14. PRODUCTO_INSUMO
-- Crear tabla
CREATE TABLE PRODUCTO_INSUMO (
    id NUMBER PRIMARY KEY,
    producto_id NUMBER,
    insumo_id NUMBER,
    cantidad NUMBER,
    CONSTRAINT fk_producto FOREIGN KEY (producto_id) REFERENCES PRODUCTO(id),
    CONSTRAINT fk_insumo FOREIGN KEY (insumo_id) REFERENCES INGRESO(id)
);

-- CRUD
-- Crear
CREATE OR REPLACE PROCEDURE createProductoInsumo(productoInsumoData IN PRODUCTO_INSUMO%ROWTYPE) IS
BEGIN
    INSERT INTO PRODUCTO_INSUMO (id, producto_id, insumo_id, cantidad) 
    VALUES (productoInsumoData.id, productoInsumoData.producto_id, productoInsumoData.insumo_id, productoInsumoData.cantidad);
END;
/

-- Leer
CREATE OR REPLACE FUNCTION getProductoInsumo(id IN NUMBER) RETURN PRODUCTO_INSUMO%ROWTYPE IS
    productoInsumoRecord PRODUCTO_INSUMO%ROWTYPE;
BEGIN
    SELECT * INTO productoInsumoRecord FROM PRODUCTO_INSUMO WHERE id = id;
    RETURN productoInsumoRecord;
END;
/

-- Actualizar
CREATE OR REPLACE PROCEDURE updateProductoInsumo(id IN NUMBER, productoInsumoData IN PRODUCTO_INSUMO%ROWTYPE) IS
BEGIN
    UPDATE PRODUCTO_INSUMO SET producto_id = productoInsumoData.producto_id, insumo_id = productoInsumoData.insumo_id, cantidad = productoInsumoData.cantidad WHERE id = id;
END;
/

-- Eliminar
CREATE OR REPLACE PROCEDURE deleteProductoInsumo(id IN NUMBER) IS
BEGIN
    DELETE FROM PRODUCTO_INSUMO WHERE id = id;
END;
/

15. ESTADO_PEDIDO
-- Crear tabla
CREATE TABLE ESTADO_PEDIDO (
    id NUMBER PRIMARY KEY,
    nombre VARCHAR2(100)
);

-- CRUD
-- Crear
CREATE OR REPLACE PROCEDURE createEstadoPedido(estadoPedidoData IN ESTADO_PEDIDO%ROWTYPE) IS
BEGIN
    INSERT INTO ESTADO_PEDIDO (id, nombre) 
    VALUES (estadoPedidoData.id, estadoPedidoData.nombre);
END;
/

-- Leer
CREATE OR REPLACE FUNCTION getEstadoPedido(id IN NUMBER) RETURN ESTADO_PEDIDO%ROWTYPE IS
    estadoPedidoRecord ESTADO_PEDIDO%ROWTYPE;
BEGIN
    SELECT * INTO estadoPedidoRecord FROM ESTADO_PEDIDO WHERE id = id;
    RETURN estadoPedidoRecord;
END;
/

-- Actualizar
CREATE OR REPLACE PROCEDURE updateEstadoPedido(id IN NUMBER, estadoPedidoData IN ESTADO_PEDIDO%ROWTYPE) IS
BEGIN
    UPDATE ESTADO_PEDIDO SET nombre = estadoPedidoData.nombre WHERE id = id;
END;
/

-- Eliminar
CREATE OR REPLACE PROCEDURE deleteEstadoPedido(id IN NUMBER) IS
BEGIN
    DELETE FROM ESTADO_PEDIDO WHERE id = id;
END;
/

16. ORDEN_COMPRA
-- Crear tabla
CREATE TABLE ORDEN_COMPRA (
    id NUMBER PRIMARY KEY,
    cliente_id NUMBER,
    total NUMBER,
    estado_id NUMBER,
    fecha DATE,
    CONSTRAINT fk_cliente FOREIGN KEY (cliente_id) REFERENCES CLIENTE(id),
    CONSTRAINT fk_estado FOREIGN KEY (estado_id) REFERENCES ESTADO_PEDIDO(id)
);

-- CRUD
-- Crear
CREATE OR REPLACE PROCEDURE createOrdenCompra(ordenCompraData IN ORDEN_COMPRA%ROWTYPE) IS
BEGIN
    INSERT INTO ORDEN_COMPRA (id, cliente_id, total, estado_id, fecha) 
    VALUES (ordenCompraData.id, ordenCompraData.cliente_id, ordenCompraData.total, ordenCompraData.estado_id, ordenCompraData.fecha);
END;
/

-- Leer
CREATE OR REPLACE FUNCTION getOrdenCompra(id IN NUMBER) RETURN ORDEN_COMPRA%ROWTYPE IS
    ordenCompraRecord ORDEN_COMPRA%ROWTYPE;
BEGIN
    SELECT * INTO ordenCompraRecord FROM ORDEN_COMPRA WHERE id = id;
    RETURN ordenCompraRecord;
END;
/

-- Actualizar
CREATE OR REPLACE PROCEDURE updateOrdenCompra(id IN NUMBER, ordenCompraData IN ORDEN_COMPRA%ROWTYPE) IS
BEGIN
    UPDATE ORDEN_COMPRA SET cliente_id = ordenCompraData.cliente_id, total = ordenCompraData.total, estado_id = ordenCompraData.estado_id, fecha = ordenCompraData.fecha WHERE id = id;
END;
/

-- Eliminar
CREATE OR REPLACE PROCEDURE deleteOrdenCompra(id IN NUMBER) IS
BEGIN
    DELETE FROM ORDEN_COMPRA WHERE id = id;
END;
/

