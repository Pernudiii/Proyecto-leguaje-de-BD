/
CREATE OR REPLACE TRIGGER FIDE_VALIDAR_PRECIO_PRODUCTO_TRG
BEFORE INSERT OR UPDATE ON FIDE_PRODUCTO_TB
FOR EACH ROW
BEGIN
    IF :NEW.precio < 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'El precio del producto no puede ser negativo.');
    END IF;
END;
/
CREATE OR REPLACE TRIGGER FIDE_VALIDAR_CANTIDAD_PEDIDO_TRG
BEFORE INSERT OR UPDATE ON FIDE_PEDIDO_TB
FOR EACH ROW
BEGIN
    IF :NEW.cantidad <= 0 THEN
        RAISE_APPLICATION_ERROR(-20002, 'La cantidad del pedido debe ser mayor que cero.');
    END IF;
END;
/
CREATE OR REPLACE TRIGGER FIDE_VALIDAR_TOTAL_ORDEN_TRG
BEFORE INSERT OR UPDATE ON FIDE_ORDEN_COMPRA_TB
FOR EACH ROW
BEGIN
    IF :NEW.total <= 0 THEN
        RAISE_APPLICATION_ERROR(-20003, 'El total de la orden de compra debe ser mayor que cero.');
    END IF;
END;
/
CREATE OR REPLACE TRIGGER FIDE_VALIDAR_TELEFONO_CLIENTE_TRG
BEFORE INSERT OR UPDATE ON FIDE_CLIENTE_TB
FOR EACH ROW
BEGIN
    IF REGEXP_LIKE(:NEW.telefono, '[^0-9]') THEN
        RAISE_APPLICATION_ERROR(-20004, 'El teléfono del cliente debe contener solo números.');
    END IF;
END;
/
CREATE OR REPLACE TRIGGER FIDE_VALIDAR_CORREO_USUARIO_TRG
BEFORE INSERT OR UPDATE ON FIDE_USUARIO_TB
FOR EACH ROW
BEGIN
    IF NOT REGEXP_LIKE(:NEW.correo, '@') THEN
        RAISE_APPLICATION_ERROR(-20007, 'El correo del usuario debe contener "@".');
    END IF;
END;
/
-- Trigger 1: Validar precio positivo de producto
CREATE OR REPLACE TRIGGER FIDE_VALIDAR_PRECIO_PRODUCTO_TRG
BEFORE INSERT OR UPDATE ON FIDE_PRODUCTO_TB
FOR EACH ROW
BEGIN
    IF :NEW.precio <= 0 THEN
        RAISE_APPLICATION_ERROR(-20100, 'El precio debe ser mayor a cero.');
    END IF;
END;
/
-- Trigger 2: Calcular total de pedido
CREATE OR REPLACE TRIGGER FIDE_PEDIDO_CALCULO_TOTAL_TRG
BEFORE INSERT OR UPDATE ON FIDE_PEDIDO_TB
FOR EACH ROW
BEGIN
    :NEW.total := :NEW.cantidad * :NEW.precio_unitario;
END;
/
-- Trigger 3: Log de nombre del cliente
CREATE OR REPLACE TRIGGER FIDE_LOG_NOMBRE_CLIENTE_TRG
AFTER INSERT ON FIDE_CLIENTE_TB
FOR EACH ROW
BEGIN
    DBMS_OUTPUT.PUT_LINE('Cliente agregado: ' || :NEW.nombre);
END;
/
-- Trigger 4: Log de nombre de producto
CREATE OR REPLACE TRIGGER FIDE_LOG_NOMBRE_PRODUCTO_TRG
AFTER INSERT ON FIDE_PRODUCTO_TB
FOR EACH ROW
BEGIN
    DBMS_OUTPUT.PUT_LINE('Producto agregado: ' || :NEW.nombre);
END;
/
-- Trigger 5: Validar stock antes de egreso
CREATE OR REPLACE TRIGGER FIDE_VALIDAR_STOCK_EGRESO_TRG
BEFORE INSERT ON FIDE_EGRESO_TB
FOR EACH ROW
DECLARE
    v_stock NUMBER;
BEGIN
    SELECT 
        NVL((SELECT SUM(cantidad) FROM FIDE_INGRESO_TB WHERE id_producto = :NEW.id_producto), 0) -
        NVL((SELECT SUM(cantidad) FROM FIDE_EGRESO_TB WHERE id_producto = :NEW.id_producto), 0)
    INTO v_stock
    FROM DUAL;

    IF v_stock < :NEW.cantidad THEN
        RAISE_APPLICATION_ERROR(-20101, 'Stock insuficiente para egreso.');
    END IF;
END;
/
-- Trigger 6: Validar única dirección por cliente
CREATE OR REPLACE TRIGGER FIDE_UNICA_DIRECCION_CLIENTE_TRG
BEFORE INSERT ON FIDE_DIRECCION_TB
FOR EACH ROW
DECLARE
    v_count NUMBER;
BEGIN
    SELECT COUNT(*) INTO v_count
    FROM FIDE_DIRECCION_TB
    WHERE id_cliente = :NEW.id_cliente;

    IF v_count >= 1 THEN
        RAISE_APPLICATION_ERROR(-20102, 'El cliente ya tiene una dirección registrada.');
    END IF;
END;
/
-- Trigger 7: Validar correo único en usuario
CREATE OR REPLACE TRIGGER FIDE_VALIDAR_CORREO_USUARIO_TRG
BEFORE INSERT ON FIDE_USUARIO_TB
FOR EACH ROW
DECLARE
    v_count NUMBER;
BEGIN
    SELECT COUNT(*) INTO v_count
    FROM FIDE_USUARIO_TB
    WHERE correo = :NEW.correo;

    IF v_count > 0 THEN
        RAISE_APPLICATION_ERROR(-20103, 'El correo ya está registrado.');
    END IF;
END;
/
-- Trigger 8: Calcular subtotal en pedido
CREATE OR REPLACE TRIGGER FIDE_CALCULAR_SUBTOTAL_TRG
BEFORE INSERT OR UPDATE ON FIDE_PEDIDO_TB
FOR EACH ROW
BEGIN
    :NEW.subtotal := :NEW.cantidad * :NEW.precio_unitario;
END;
/
-- Trigger 9: Mostrar rol del usuario tras insert
CREATE OR REPLACE TRIGGER FIDE_MOSTRAR_ROL_USUARIO_TRG
AFTER INSERT ON FIDE_USUARIO_TB
FOR EACH ROW
DECLARE
    v_rol VARCHAR2(100);
BEGIN
    SELECT nombre INTO v_rol
    FROM FIDE_ROL_TB
    WHERE id_rol = :NEW.id_rol;

    DBMS_OUTPUT.PUT_LINE('Rol asignado: ' || v_rol);
END;
/
-- Trigger 10: Advertencia si categoría tiene muchos productos
CREATE OR REPLACE TRIGGER FIDE_VALIDAR_CANTIDAD_PRODUCTOS_TRG
BEFORE INSERT ON FIDE_PRODUCTO_TB
FOR EACH ROW
DECLARE
    v_count NUMBER;
BEGIN
    SELECT COUNT(*) INTO v_count
    FROM FIDE_PRODUCTO_TB
    WHERE id_categoria = :NEW.id_categoria;

    IF v_count >= 50 THEN
        RAISE_APPLICATION_ERROR(-20104, 'Demasiados productos en esta categoría.');
    END IF;
END;
/
-- Trigger 11: Validar total de orden mayor a cero
CREATE OR REPLACE TRIGGER FIDE_VALIDAR_TOTAL_ORDEN_TRG
BEFORE INSERT OR UPDATE ON FIDE_ORDEN_COMPRA_TB
FOR EACH ROW
BEGIN
    IF :NEW.total <= 0 THEN
        RAISE_APPLICATION_ERROR(-20105, 'El total debe ser mayor que cero.');
    END IF;
END;
/
-- Trigger 12: Mostrar proveedor al insertar orden
CREATE OR REPLACE TRIGGER FIDE_LOG_PROVEEDOR_ORDEN_TRG
AFTER INSERT ON FIDE_ORDEN_COMPRA_TB
FOR EACH ROW
DECLARE
    v_nombre VARCHAR2(100);
BEGIN
    SELECT nombre INTO v_nombre
    FROM FIDE_PROVEEDOR_TB
    WHERE id_proveedor = :NEW.id_proveedor;

    DBMS_OUTPUT.PUT_LINE('Proveedor asignado a la orden: ' || v_nombre);
END;
/
-- Trigger 13: Validar stock disponible antes de insertar pedido
CREATE OR REPLACE TRIGGER FIDE_VALIDAR_STOCK_PEDIDO_TRG
BEFORE INSERT ON FIDE_PEDIDO_TB
FOR EACH ROW
DECLARE
    v_stock NUMBER;
BEGIN
    SELECT 
        NVL((SELECT SUM(cantidad) FROM FIDE_INGRESO_TB WHERE id_producto = :NEW.id_producto), 0) -
        NVL((SELECT SUM(cantidad) FROM FIDE_EGRESO_TB WHERE id_producto = :NEW.id_producto), 0)
    INTO v_stock
    FROM DUAL;

    IF v_stock < :NEW.cantidad THEN
        RAISE_APPLICATION_ERROR(-20106, 'Stock insuficiente para el pedido.');
    END IF;
END;
/
-- Trigger 14: Log de categoría al insertar producto
CREATE OR REPLACE TRIGGER FIDE_LOG_CATEGORIA_PRODUCTO_TRG
AFTER INSERT ON FIDE_PRODUCTO_TB
FOR EACH ROW
DECLARE
    v_nombre VARCHAR2(100);
BEGIN
    SELECT nombre INTO v_nombre
    FROM FIDE_CATEGORIA_TB
    WHERE id_categoria = :NEW.id_categoria;

    DBMS_OUTPUT.PUT_LINE('Producto insertado en categoría: ' || v_nombre);
END;
/
-- Trigger 15: Log de unidad al insertar insumo
CREATE OR REPLACE TRIGGER FIDE_LOG_UNIDAD_INSUMO_TRG
AFTER INSERT ON FIDE_INSUMO_TB
FOR EACH ROW
DECLARE
    v_nombre VARCHAR2(100);
BEGIN
    SELECT nombre INTO v_nombre
    FROM FIDE_UNIDAD_MEDIDA_TB
    WHERE id_unidad_medida = :NEW.id_unidad_medida;

    DBMS_OUTPUT.PUT_LINE('Insumo registrado con unidad: ' || v_nombre);
END;
/