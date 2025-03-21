-- Funciones
--1. usuario por ID
CREATE OR REPLACE FUNCTION getUsuario(usuarioId IN NUMBER) RETURN USUARIO%ROWTYPE IS
usuarioRecord USUARIO%ROWTYPE;
BEGIN
SELECT * INTO usuarioRecord FROM USUARIO WHERE id = usuarioId;
RETURN usuarioRecord;
END;
/
--2. producto por ID
CREATE OR REPLACE FUNCTION getProducto(productoId IN NUMBER) RETURN PRODUCTO%ROWTYPE IS
productoRecord PRODUCTO%ROWTYPE;
BEGIN
SELECT * INTO productoRecord FROM PRODUCTO WHERE id = productoId;
RETURN productoRecord;
END;
/
--3. categoría por ID
CREATE OR REPLACE FUNCTION getCategoria(categoriaId IN NUMBER) RETURN CATEGORIA%ROWTYPE IS
categoriaRecord CATEGORIA%ROWTYPE;
BEGIN
SELECT * INTO categoriaRecord FROM CATEGORIA WHERE id = categoriaId;
RETURN categoriaRecord;
END;
/
--4. pedido por ID
CREATE OR REPLACE FUNCTION getPedido(pedidoId IN NUMBER) RETURN PEDIDO%ROWTYPE IS
pedidoRecord PEDIDO%ROWTYPE;
BEGIN
SELECT * INTO pedidoRecord FROM PEDIDO WHERE id = pedidoId;
RETURN pedidoRecord;
END;
/
--5. ingreso por ID
CREATE OR REPLACE FUNCTION getIngreso(ingresoId IN NUMBER) RETURN INGRESO%ROWTYPE IS
ingresoRecord INGRESO%ROWTYPE;
BEGIN
SELECT * INTO ingresoRecord FROM INGRESO WHERE id = ingresoId;
RETURN ingresoRecord;
END;
/
--6. egreso por ID
CREATE OR REPLACE FUNCTION getEgreso(egresoId IN NUMBER) RETURN EGRESO%ROWTYPE IS
egresoRecord EGRESO%ROWTYPE;
BEGIN
SELECT * INTO egresoRecord FROM EGRESO WHERE id = egresoId;
RETURN egresoRecord;
END;
/
--7. rol por ID
CREATE OR REPLACE FUNCTION getRol(rolId IN NUMBER) RETURN ROL%ROWTYPE IS
rolRecord ROL%ROWTYPE;
BEGIN
SELECT * INTO rolRecord FROM ROL WHERE id = rolId;
RETURN rolRecord;
END;
/
--8. cliente por ID
CREATE OR REPLACE FUNCTION getCliente(clienteId IN NUMBER) RETURN CLIENTE%ROWTYPE IS
clienteRecord CLIENTE%ROWTYPE;
BEGIN
SELECT * INTO clienteRecord FROM CLIENTE WHERE id = clienteId;
RETURN clienteRecord;
END;
/
--9. dirección por ID
CREATE OR REPLACE FUNCTION getDireccion(direccionId IN NUMBER) RETURN DIRECCION%ROWTYPE IS
direccionRecord DIRECCION%ROWTYPE;
BEGIN
SELECT * INTO direccionRecord FROM DIRECCION WHERE id = direccionId;
RETURN direccionRecord;
END;
/
--10. provincia por ID
CREATE OR REPLACE FUNCTION getProvincia(provinciaId IN NUMBER) RETURN PROVINCIA%ROWTYPE IS
provinciaRecord PROVINCIA%ROWTYPE;
BEGIN
SELECT * INTO provinciaRecord FROM PROVINCIA WHERE id = provinciaId;
RETURN provinciaRecord;
END;
/
--11. cantón por ID
CREATE OR REPLACE FUNCTION getCanton(cantonId IN NUMBER) RETURN CANTON%ROWTYPE IS
cantonRecord CANTON%ROWTYPE;
BEGIN
SELECT * INTO cantonRecord FROM CANTON WHERE id = cantonId;
RETURN cantonRecord;
END;
/
--12. distrito por ID
CREATE OR REPLACE FUNCTION getDistrito(distritoId IN NUMBER) RETURN DISTRITO%ROWTYPE IS
distritoRecord DISTRITO%ROWTYPE;
BEGIN
SELECT * INTO distritoRecord FROM DISTRITO WHERE id = distritoId;
RETURN distritoRecord;
END;
/
--13. unidad de medida por ID
CREATE OR REPLACE FUNCTION getUnidadMedida(unidadMedidaId IN NUMBER) RETURN UNIDAD_MEDIDA%ROWTYPE IS
unidadMedidaRecord UNIDAD_MEDIDA%ROWTYPE;
BEGIN
SELECT * INTO unidadMedidaRecord FROM UNIDAD_MEDIDA WHERE id = unidadMedidaId;
RETURN unidadMedidaRecord;
END;
/
--14. producto-insumo por ID
CREATE OR REPLACE FUNCTION getProductoInsumo(productoInsumoId IN NUMBER) RETURN PRODUCTO_INSUMO%ROWTYPE IS
productoInsumoRecord PRODUCTO_INSUMO%ROWTYPE;
BEGIN
SELECT * INTO productoInsumoRecord FROM PRODUCTO_INSUMO WHERE id = productoInsumoId;
RETURN productoInsumoRecord;
END;
/
--15. estado de pedido por ID
CREATE OR REPLACE FUNCTION getEstadoPedido(estadoPedidoId IN NUMBER) RETURN ESTADO_PEDIDO%ROWTYPE IS
estadoPedidoRecord ESTADO_PEDIDO%ROWTYPE;
BEGIN
SELECT * INTO estadoPedidoRecord FROM ESTADO_PEDIDO WHERE id = estadoPedidoId;
RETURN estadoPedidoRecord;
END;
/