import { expect } from '@playwright/test';
import { createBdd } from 'playwright-bdd';
import { test } from './fixtures';
import { credentials, RoleName, uniqueUsername } from '../pages/helpers';

const { Given, When, Then } = createBdd(test);

function toRole(role: string): RoleName {
  if (role !== 'admin' && role !== 'waiter' && role !== 'cook') {
    throw new Error(`Rol no soportado: ${role}`);
  }
  return role;
}

Given('el usuario esta en la pagina de login', async ({ loginPage }) => {
  await loginPage.goto();
});

Given('el usuario esta en la pagina de registro', async ({ registerPage }) => {
  await registerPage.goto();
});

Given('el usuario ha iniciado sesion como {string}', async ({ loginPage }, role: string) => {
  await loginPage.goto();
  await loginPage.loginAs(toRole(role));
});

When('inicia sesion como {string}', async ({ loginPage }, role: string) => {
  await loginPage.goto();
  await loginPage.loginAs(toRole(role));
});

When('intenta iniciar sesion con usuario {string} y contrasena {string}', async ({ loginPage }, username: string, password: string) => {
  await loginPage.goto();
  await loginPage.login(username, password);
});

When('intenta iniciar sesion sin completar credenciales', async ({ loginPage }) => {
  await loginPage.submitEmpty();
});

When('registra un nuevo usuario de prueba con nombre {string}', async ({ registerPage, state }, name: string) => {
  const username = uniqueUsername('qa_registro');
  state.username = username;
  await registerPage.register(name, username, 'qa_registro123');
});

When('intenta registrarse con usuario existente {string}', async ({ registerPage }, username: string) => {
  await registerPage.register('QA Duplicado', username, 'qa_registro123');
});

When('cierra sesion', async ({ page, loginPage }) => {
  await page.goto('?route=auth/logout');
  await loginPage.expectLoginVisible();
});

When('navega directamente a la ruta {string}', async ({ page }, route: string) => {
  await page.goto(`?route=${route}`);
});

Then('el sistema muestra la pantalla de espera de aprobacion', async ({ registerPage }) => {
  await registerPage.expectWaitingApproval();
});

Then('el sistema muestra error de usuario existente', async ({ registerPage }) => {
  await registerPage.expectDuplicateUser();
});

Then('el sistema muestra error de credenciales invalidas', async ({ loginPage }) => {
  await loginPage.expectInvalidCredentials();
});

Then('el navegador mantiene requerido el formulario de login', async ({ loginPage }) => {
  await loginPage.expectRequiredFields();
});

Then('debe ver el panel de pedidos del camarero', async ({ page }) => {
  await expect(page.getByRole('heading', { name: 'Pedidos', exact: true })).toBeVisible({ timeout: 10000 });
  await expect(page.getByRole('link', { name: /Crear pedido/i })).toBeVisible();
});

Then('debe ver el panel del cocinero', async ({ page }) => {
  await expect(page.getByRole('heading', { name: /Panel Cocinero/i })).toBeVisible({ timeout: 10000 });
});

Then('debe ver el panel de administrador', async ({ page }) => {
  await expect(page.getByRole('heading', { name: /Panel Administrador/i })).toBeVisible({ timeout: 10000 });
});

Then('debe ser redirigido al login', async ({ loginPage }) => {
  await loginPage.expectLoginVisible();
});

Then('debe ver opciones exclusivas de administrador', async ({ page }) => {
  await expect(page.getByRole('link', { name: /Gestionar Usuarios/i })).toBeVisible();
  await expect(page.getByRole('link', { name: /Gestionar Platos/i })).toBeVisible();
  await expect(page.locator('body')).toContainText(/Estadisticas|Pedidos Hoy/i);
});

export { credentials };
