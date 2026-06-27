import { createBdd } from 'playwright-bdd';
import { test } from './fixtures';

const { Given, When, Then } = createBdd(test);

Given('existe un pedido pendiente visible para el camarero', async ({ waiterOrdersPage }) => {
  await waiterOrdersPage.expectPendingOrderVisible();
});

When('crea un pedido con el producto {string} y cantidad {int}', async ({ waiterOrdersPage }, producto: string, cantidad: number) => {
  await waiterOrdersPage.createOrder([{ producto, cantidad: String(cantidad) }]);
});

When('crea un pedido con los productos:', async ({ waiterOrdersPage }, dataTable) => {
  const items = dataTable.hashes().map((row: { producto: string; cantidad: string }) => ({
    producto: row.producto,
    cantidad: row.cantidad,
  }));
  await waiterOrdersPage.createOrder(items);
});

When('abre el formulario de crear pedido', async ({ waiterOrdersPage }) => {
  await waiterOrdersPage.gotoCreate();
});

When('edita el primer pedido pendiente y agrega el producto {string} con cantidad {int}', async ({ waiterOrdersPage }, producto: string, cantidad: number) => {
  await waiterOrdersPage.editFirstPendingAndAdd(producto, String(cantidad));
});

When('edita el primer pedido pendiente y elimina un producto', async ({ waiterOrdersPage }) => {
  await waiterOrdersPage.editFirstPendingAndRemove();
});

When('cancela el primer pedido pendiente con motivo {string}', async ({ waiterOrdersPage }, motivo: string) => {
  await waiterOrdersPage.cancelFirstPending(motivo);
});

When('intenta cancelar el primer pedido pendiente sin motivo', async ({ waiterOrdersPage }) => {
  await waiterOrdersPage.attemptCancelFirstPendingWithoutReason();
});

Then('el pedido debe aparecer en Pedidos Actuales', async ({ waiterOrdersPage }) => {
  await waiterOrdersPage.expectCurrentOrderVisible();
});

Then('el producto {string} debe estar disponible para pedido', async ({ waiterOrdersPage }, producto: string) => {
  await waiterOrdersPage.expectProductAvailable(producto);
});

Then('el producto {string} no debe estar disponible para pedido', async ({ waiterOrdersPage }, producto: string) => {
  await waiterOrdersPage.expectProductNotAvailable(producto);
});

Then('el pedido editado debe guardar los cambios correctamente', async ({ waiterOrdersPage }) => {
  await waiterOrdersPage.expectCurrentOrderVisible();
});

Then('el pedido cancelado aparece en la seccion Pedidos Cancelados', async ({ waiterOrdersPage }) => {
  await waiterOrdersPage.expectCancelledOrderVisible();
});

Then('el navegador impide enviar la cancelacion sin motivo', async ({ waiterOrdersPage }) => {
  await waiterOrdersPage.expectCancelReasonRequired();
});

