import { test as base } from 'playwright-bdd';
import { LoginPage } from '../pages/LoginPage';
import { RegisterPage } from '../pages/RegisterPage';
import { AdminUsersPage } from '../pages/AdminUsersPage';
import { AdminProductsPage } from '../pages/AdminProductsPage';
import { WaiterOrdersPage } from '../pages/WaiterOrdersPage';
import { CookDashboardPage } from '../pages/CookDashboardPage';

export type ScenarioState = {
  username?: string;
  pendingRole?: 'waiter' | 'cook' | 'admin';
};

type Fixtures = {
  loginPage: LoginPage;
  registerPage: RegisterPage;
  adminUsersPage: AdminUsersPage;
  adminProductsPage: AdminProductsPage;
  waiterOrdersPage: WaiterOrdersPage;
  cookDashboardPage: CookDashboardPage;
  state: ScenarioState;
};

export const test = base.extend<Fixtures>({
  loginPage: async ({ page }, use) => {
    await use(new LoginPage(page));
  },
  registerPage: async ({ page }, use) => {
    await use(new RegisterPage(page));
  },
  adminUsersPage: async ({ page }, use) => {
    await use(new AdminUsersPage(page));
  },
  adminProductsPage: async ({ page }, use) => {
    await use(new AdminProductsPage(page));
  },
  waiterOrdersPage: async ({ page }, use) => {
    await use(new WaiterOrdersPage(page));
  },
  cookDashboardPage: async ({ page }, use) => {
    await use(new CookDashboardPage(page));
  },
  state: async ({}, use) => {
    await use({});
  },
});

