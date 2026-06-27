const fs = require('fs');
const path = require('path');

const reportPath = path.join(__dirname, 'test-results', 'report.json');
const outputDirFallos = path.join(__dirname, 'Evidencias_Fallos');
const outputDirExitos = path.join(__dirname, 'Evidencias_Exitos');

if (!fs.existsSync(reportPath)) {
  console.error('No se encontro test-results/report.json.');
  process.exit(1);
}

fs.mkdirSync(outputDirFallos, { recursive: true });
fs.mkdirSync(outputDirExitos, { recursive: true });

const report = JSON.parse(fs.readFileSync(reportPath, 'utf8'));

function collectSpecs(suite, specs = []) {
  if (suite.specs) specs.push(...suite.specs);
  if (suite.suites) suite.suites.forEach(child => collectSpecs(child, specs));
  return specs;
}

const specs = [];
for (const suite of report.suites || []) collectSpecs(suite, specs);

let copied = 0;

for (const spec of specs) {
  const testId = spec.title.replace(/[<>:"/\\|?*]/g, '').trim().slice(0, 120);
  for (const test of spec.tests || []) {
    for (const result of test.results || []) {
      const targetDir = result.status === 'passed' ? outputDirExitos : outputDirFallos;
      for (const attachment of result.attachments || []) {
        if (!attachment.path) continue;
        if (attachment.name !== 'video' && attachment.name !== 'screenshot') continue;
        const ext = path.extname(attachment.path) || (attachment.name === 'video' ? '.webm' : '.png');
        const dest = path.join(targetDir, `${testId}-${attachment.name}${ext}`);
        fs.copyFileSync(attachment.path, dest);
        copied++;
      }
    }
  }
}

console.log(`Evidencias exportadas: ${copied}`);

