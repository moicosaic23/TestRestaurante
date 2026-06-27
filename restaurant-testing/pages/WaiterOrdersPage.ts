import { expect, Locator, Page } from '@playwright/test';
import { selectOptionContaining } from './helpers';

type OrderItem = {
  producto: string;
  cantidad: string;
};

export class WaiterOrdersPage {
  constructor(readonly page: Page) {}

  async gotoOrders() {
    await this.page.goto('?route=waiter/orders');
    await expect(this.page.getByRole('heading', { name: 'Pedidos', exact: true })).toBeVisible();
  }

  async gotoCreate() {
    await this.page.goto('?route=waiter/create');
    await expect(this.page.getByRole('heading', { name: /Crear Pedido/i })).toBeVisible();
  }

  get productSelect() {
    return this.page.locator('#product-select');
  }

  get currentOrdersSection() {
    return this.page.locator('section.orders').filter({ hasText: 'Pedidos Actuales' }).first();
  }

  get cancelledOrdersSection() {
    return this.page.locator('section.orders').filter({ hasText: 'Pedidos Cancelados' }).first();
  }

  pendingRows() {
    return this.currentOrdersSection.locator('tbody tr').filter({ hasText: 'pending' });
  }

  async addProduct(productName: string, quantity: string) {
    await selectOptionContaining(this.productSelect, productName);
    await this.page.locator('#qty').fill(quantity);
    await this.page.locator('#add-item').click();
    await expect(this.page.locator('#items-table tbody')).toContainText(productName);
  }

  async createOrder(items: OrderItem[]) {
    await this.gotoCreate();
    for (const item of items) {
      await this.addProduct(item.producto, item.cantidad);
    }
    await this.page.getByRole('button', { name: /Guardar Pedido/i }).click();
    await expect(this.page).toHaveURL(/route=waiter\/orders|route=waiter\/dashboard/);
  }

  async expectCurrentOrderVisible() {
    await this.gotoOrders();
    await expect(this.currentOrdersSection.locator('tbody tr').first()).toBeVisible({ timeout: 10000 });
  }

  async expectProductAvailable(productName: string) {
    await expect(this.productSelect).toContainText(productName);
  }

  async expectProductNotAvailable(productName: string) {
    await expect(this.productSelect).not.toContainText(productName);
  }

  async expectPendingOrderVisible() {
    await this.gotoOrders();
    await expect(this.pendingRows().first()).toBeVisible({ timeout: 10000 });
  }

  async firstPendingRow(): Promise<Locator> {
    await this.expectPendingOrderVisible();
    return this.pendingRows().first();
  }

  async editFirstPendingAndAdd(productName: string, quantity: string) {
    const row = await this.firstPendingRow();
    await row.getByRole('link', { name: /Editar/i }).click();
    await expect(this.page.getByRole('heading', { name: /Editar Pedido/i })).toBeVisible();
    await selectOptionContaining(this.page.locator('#product-select'), productName);
    await this.page.locator('#qty-add').fill(quantity);
    await this.page.locator('#add-item-btn').click();
    await expect(this.page.locator('#order-items-body')).toContainText(productName);
    await this.page.getByRole('button', { name: /^Guardar$/i }).click();
    await expect(this.page).toHaveURL(/route=waiter\/orders|route=waiter\/dashboard/);
  }

  async editFirstPendingAndRemove() {
    const row = await this.firstPendingRow();
    await row.getByRole('link', { name: /Editar/i }).click();
    await expect(this.page.getByRole('heading', { name: /Editar Pedido/i })).toBeVisible();
    await this.page.locator('.remove-btn').first().click();
    await this.page.getByRole('button', { name: /^Guardar$/i }).click();
    await expect(this.page).toHaveURL(/route=waiter\/orders|route=waiter\/dashboard/);
  }

  async cancelFirstPending(reason: string) {
    const row = await this.firstPendingRow();
    await row.getByRole('link', { name: /Cancelar/i }).click();
    await expect(this.page.getByRole('heading', { name: /Cancelar Pedido/i })).toBeVisible();
    await this.page.locator('textarea[name="comment"]').fill(reason);
    await this.page.getByRole('button', { name: /Cancelar pedido/i }).click();
    await expect(this.page).toHaveURL(/route=waiter\/orders|route=waiter\/dashboard/);
  }

  async attemptCancelFirstPendingWithoutReason() {
    const row = await this.firstPendingRow();
    await row.getByRole('link', { name: /Cancelar/i }).click();
    await expect(this.page.getByRole('heading', { name: /Cancelar Pedido/i })).toBeVisible();
    await this.page.getByRole('button', { name: /Cancelar pedido/i }).click();
  }

  async expectCancelReasonRequired() {
    const isValid = await this.page.locator('textarea[name="comment"]').evaluate((el: HTMLTextAreaElement) => el.validity.valid);
    expect(isValid).toBeFalsy();
  }

  async expectCancelledOrderVisible() {
    await this.gotoOrders();
    await expect(this.cancelledOrdersSection.locator('tbody tr').first()).toBeVisible({ timeout: 10000 });
  }
}
