import { expect, Locator, Page } from '@playwright/test';

export type RoleName = 'admin' | 'waiter' | 'cook';

export const credentials: Record<RoleName, { username: string; password: string }> = {
  admin: {
    username: process.env.QA_ADMIN_USER || 'qa_admin',
    password: process.env.QA_ADMIN_PASSWORD || 'qa_admin123',
  },
  waiter: {
    username: process.env.QA_WAITER_USER || 'qa_waiter',
    password: process.env.QA_WAITER_PASSWORD || 'qa_waiter123',
  },
  cook: {
    username: process.env.QA_COOK_USER || 'qa_cook',
    password: process.env.QA_COOK_PASSWORD || 'qa_cook123',
  },
};

export function uniqueUsername(prefix: string) {
  return `${prefix}_${Date.now()}_${Math.floor(Math.random() * 10000)}`;
}

export async function selectOptionContaining(select: Locator, visibleText: string) {
  const value = await select.locator('option').evaluateAll((options, text) => {
    const option = options.find(item => item.textContent?.includes(String(text)));
    return option?.getAttribute('value') || '';
  }, visibleText);

  if (!value) {
    throw new Error(`No option containing "${visibleText}" was found.`);
  }

  await select.selectOption(value);
}

export async function expectPageHeading(page: Page, text: string | RegExp) {
  await expect(page.getByRole('heading', { name: text })).toBeVisible({ timeout: 10000 });
}

