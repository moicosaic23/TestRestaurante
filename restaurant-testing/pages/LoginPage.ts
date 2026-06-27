import { expect, Page } from '@playwright/test';
import { credentials, RoleName } from './helpers';

export class LoginPage {
  constructor(readonly page: Page) {}

  get usernameInput() {
    return this.page.locator('input[name="username"]');
  }

  get passwordInput() {
    return this.page.locator('input[name="password"]');
  }

  get submitButton() {
    return this.page.getByRole('button', { name: /Ingresar/i });
  }

  get errorMessage() {
    return this.page.locator('.error');
  }

  async goto() {
    await this.page.goto('/?route=auth/login');
    await expect(this.usernameInput).toBeVisible();
  }

  async login(username: string, password: string) {
    await this.usernameInput.fill(username);
    await this.passwordInput.fill(password);
    await this.submitButton.click();
  }

  async loginAs(role: RoleName) {
    const data = credentials[role];
    await this.login(data.username, data.password);
  }

  async submitEmpty() {
    await this.submitButton.click();
  }

  async expectInvalidCredentials() {
    await expect(this.errorMessage).toContainText(/Usuario o contrase/i);
  }

  async expectLoginVisible() {
    await expect(this.usernameInput).toBeVisible({ timeout: 10000 });
  }

  async expectRequiredFields() {
    const usernameValid = await this.usernameInput.evaluate((el: HTMLInputElement) => el.validity.valid);
    const passwordValid = await this.passwordInput.evaluate((el: HTMLInputElement) => el.validity.valid);
    expect(usernameValid).toBeFalsy();
    expect(passwordValid).toBeFalsy();
  }
}

