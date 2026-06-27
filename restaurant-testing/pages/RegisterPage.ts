import { expect, Page } from '@playwright/test';

export class RegisterPage {
  constructor(readonly page: Page) {}

  get nameInput() {
    return this.page.locator('input[name="name"]');
  }

  get usernameInput() {
    return this.page.locator('input[name="username"]');
  }

  get passwordInput() {
    return this.page.locator('input[name="password"]');
  }

  get submitButton() {
    return this.page.getByRole('button', { name: /Registrar/i });
  }

  get errorMessage() {
    return this.page.locator('.error');
  }

  async goto() {
    await this.page.goto('?route=auth/register');
    await expect(this.usernameInput).toBeVisible();
  }

  async register(name: string, username: string, password: string) {
    await this.nameInput.fill(name);
    await this.usernameInput.fill(username);
    await this.passwordInput.fill(password);
    await this.submitButton.click();
  }

  async expectWaitingApproval() {
    await expect(this.page.locator('body')).toContainText(/Espera aprobaci|Esperando aprobaci/i);
  }

  async expectDuplicateUser() {
    await expect(this.errorMessage).toContainText(/Usuario ya existe/i);
  }
}
