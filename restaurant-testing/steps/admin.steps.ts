import { expect } from '@playwright/test';
import { createBdd } from 'playwright-bdd';
import { test } from './fixtures';
import { uniqueUsername } from '../pages/helpers';

const { Given, When, Then } = createBdd(test);

function toManagedRole(role: string): 'waiter' | 'cook' | 'admin' {
  if (role !== 'waiter' && role !== 'cook' && role !== 'admin') {
    throw new Error(`Rol no soportado: ${role}`);
  }
  return role;
}

Given('existe un usuario nuevo pendiente para rol {string}', async ({ registerPage, state }, role: string) => {
  const managedRole = toManagedRole(role);
  const username = uniqueUsername(`qa_${managedRole}_pendiente`);
  state.username = username;
  state.pendingRole = managedRole;
  await registerPage.goto();
  await registerPage.register(`QA ${managedRole} pendiente`, username, 'qa_pendiente123');
  await registerPage.expectWaitingApproval();
});

When('el administrador aprueba al usuario pendiente como {string}', async ({ adminUsersPage, state }, role: string) => {
  if (!state.username) throw new Error('No existe usuario pendiente en el estado del escenario.');
  await adminUsersPage.approveUser(state.username, toManagedRole(role));
});

When('el administrador cambia el rol del usuario {string} a {string}', async ({ adminUsersPage }, username: string, role: string) => {
  await adminUsersPage.changeRole(username, toManagedRole(role));
});

When('abre la gestion de usuarios', async ({ adminUsersPage }) => {
  await adminUsersPage.goto();
});

When('abre el panel de administrador', async ({ page }) => {
  await page.goto('/?route=admin/dashboard');
});

Then('el usuario debe figurar como aprobado en Gestionar Usuarios', async ({ adminUsersPage, state }) => {
  if (!state.username) throw new Error('No existe usuario pendiente en el estado del escenario.');
  await adminUsersPage.expectUserApproved(state.username);
});

Then('el usuario debe figurar con rol {string} en Gestionar Usuarios', async ({ adminUsersPage, state }, role: string) => {
  if (!state.username) throw new Error('No existe usuario pendiente en el estado del escenario.');
  await adminUsersPage.expectUserRole(state.username, role);
});

Then('el usuario {string} debe figurar con rol {string} en Gestionar Usuarios', async ({ adminUsersPage }, username: string, role: string) => {
  await adminUsersPage.expectUserRole(username, role);
});

Then('debe ver la tabla de usuarios', async ({ adminUsersPage }) => {
  await expect(adminUsersPage.usersTable).toBeVisible();
});

Then('la tabla de usuarios muestra estados de aprobacion', async ({ adminUsersPage }) => {
  await adminUsersPage.expectApprovalStatesVisible();
});

