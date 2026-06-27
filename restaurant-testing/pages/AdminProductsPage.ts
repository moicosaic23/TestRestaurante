import { expect, Page } from '@playwright/test';

export class AdminProductsPage {
  constructor(readonly page: Page) {}

  async goto() {
    await this.page.goto('?route=admin/products');
    await expect(this.page.getByRole('heading', { name: /Productos/i })).toBeVisible();
  }

  rowByProduct(productName: string) {
    return this.page.locator('tbody tr').filter({ hasText: productName }).first();
  }

  async enableProductIfNeeded(productName: string, price = '12.50') {
    await this.goto();
    const row = this.rowByProduct(productName);
    await expect(row).toBeVisible({ timeout: 10000 });
    const enabledText = (await row.locator('td').nth(3).innerText()).trim();
    if (enabledText.startsWith('N')) {
      await row.locator('input[name="price"]').fill(price);
      await row.getByRole('button', { name: /Habilitar/i }).click();
      await this.goto();
    }
  }

  async disableProduct(productName: string, reason: string) {
    await this.enableProductIfNeeded(productName);
    const row = this.rowByProduct(productName);
    await expect(row).toBeVisible({ timeout: 10000 });
    await row.locator('input[name="reason"]').fill(reason);
    await row.getByRole('button', { name: /Deshabilitar/i }).click();
    await this.goto();
  }

  async expectProductDisabled(productName: string) {
    const row = this.rowByProduct(productName);
    await expect(row).toBeVisible({ timeout: 10000 });
    await expect(row.locator('td').nth(3)).toContainText(/No/);
  }
}
