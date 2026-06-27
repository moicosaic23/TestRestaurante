import { expect, Page } from '@playwright/test';

export class AdminUsersPage {
  constructor(readonly page: Page) {}

  get usersTable() {
    return this.page.locator('table');
  }

  async goto() {
    await this.page.goto('?route=admin/users');
    await expect(this.page.getByRole('heading', { name: /Usuarios/i })).toBeVisible();
  }

  rowByUsername(username: string) {
    return this.page.locator('tbody tr').filter({ hasText: username }).first();
  }

  async approveUser(username: string, role: 'waiter' | 'cook' | 'admin') {
    await this.goto();
    const row = this.rowByUsername(username);
    await expect(row).toBeVisible({ timeout: 10000 });
    await row.locator('select[name="role"]').selectOption(role);
    await row.locator('input[name="approved"]').setChecked(true);
    await row.locator('form').first().getByRole('button', { name: /Guardar/i }).click();
    await this.goto();
  }

  async changeRole(username: string, role: 'waiter' | 'cook' | 'admin') {
    await this.approveUser(username, role);
  }

  async expectUserApproved(username: string) {
    const row = this.rowByUsername(username);
    await expect(row).toBeVisible({ timeout: 10000 });
    const approvedText = await row.locator('td').nth(4).innerText();
    expect(approvedText.trim().startsWith('S')).toBeTruthy();
  }

  async expectUserRole(username: string, role: string) {
    const row = this.rowByUsername(username);
    await expect(row).toBeVisible({ timeout: 10000 });
    await expect(row.locator('td').nth(3)).toContainText(role);
  }

  async expectApprovalStatesVisible() {
    await expect(this.usersTable).toContainText(/Si|No|S./);
  }
}
