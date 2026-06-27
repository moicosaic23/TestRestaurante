import { expect, Page } from '@playwright/test';

export class CookDashboardPage {
  constructor(readonly page: Page) {}

  async goto() {
    await this.page.goto('?route=cook/dashboard');
    await expect(this.page.getByRole('heading', { name: /Panel Cocinero/i })).toBeVisible();
  }

  get pendingSection() {
    return this.page.locator('section.orders-pend');
  }

  get preparingSection() {
    return this.page.locator('section.orders-start');
  }

  async expectPendingSectionVisible() {
    await expect(this.pendingSection).toBeVisible();
    await expect(this.pendingSection).toContainText(/Pedidos Pendientes/i);
  }

  async expectPendingAndPreparingSectionsVisible() {
    await this.expectPendingSectionVisible();
    await expect(this.preparingSection).toBeVisible();
    await expect(this.preparingSection).toContainText(/Pedidos en Preparaci/i);
  }

  async markFirstPendingPreparing() {
    await this.goto();
    const firstButton = this.pendingSection.getByRole('button', { name: /Comenzar Preparaci/i }).first();
    await expect(firstButton).toBeVisible({ timeout: 10000 });
    await firstButton.click();
    await expect(this.page).toHaveURL(/route=cook\/dashboard/);
  }

  async expectPreparingOrderVisible() {
    await expect(this.preparingSection.locator('tbody tr').first()).toBeVisible({ timeout: 10000 });
  }
}
