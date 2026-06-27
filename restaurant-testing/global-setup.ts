import { execFileSync } from 'child_process';
import path from 'path';

export default async function globalSetup() {
  execFileSync('php', [path.join(__dirname, 'fixtures', 'seed-test-data.php')], {
    stdio: 'inherit',
  });
}

