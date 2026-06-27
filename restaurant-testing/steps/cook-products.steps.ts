import { createBdd } from 'playwright-bdd';
import { test } from './fixtures';

const { When, Then } = createBdd(test);

When('abre el panel del cocinero', async ({ cookDashboardPage }) => {
  await cookDashboardPage.goto();
});

When('marca el primer pedido pendiente como en preparacion', async ({ cookDashboardPage }) => {
  await cookDashboardPage.markFirstPendingPreparing();
});

When('deshabilita el plato {string} con razon {string}', async ({ adminProductsPage }, producto: string, razon: string) => {
  await adminProductsPage.disableProduct(producto, razon);
});

Then('debe ver la seccion de pedidos pendientes', async ({ cookDashboardPage }) => {
  await cookDashboardPage.expectPendingSectionVisible();
});

Then('debe ver secciones de pendientes y preparacion', async ({ cookDashboardPage }) => {
  await cookDashboardPage.expectPendingAndPreparingSectionsVisible();
});

Then('el pedido debe aparecer en la seccion En Preparacion', async ({ cookDashboardPage }) => {
  await cookDashboardPage.goto();
  await cookDashboardPage.expectPreparingOrderVisible();
});

Then('el plato {string} debe figurar deshabilitado', async ({ adminProductsPage }, producto: string) => {
  await adminProductsPage.expectProductDisabled(producto);
});

