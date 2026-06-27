const fs = require('fs');
const path = require('path');

const reportPath = path.join(__dirname, 'test-results', 'report.json');
const minThreshold = Number(process.env.MIN_PASS_THRESHOLD || '90');

if (!fs.existsSync(reportPath)) {
  console.error('No se encontro test-results/report.json.');
  process.exit(1);
}

const report = JSON.parse(fs.readFileSync(reportPath, 'utf8'));

function collectSpecs(suite, specs = []) {
  if (suite.specs) specs.push(...suite.specs);
  if (suite.suites) suite.suites.forEach(child => collectSpecs(child, specs));
  return specs;
}

const specs = [];
for (const suite of report.suites || []) collectSpecs(suite, specs);

let totalTests = 0;
let passedTests = 0;

for (const spec of specs) {
  for (const test of spec.tests || []) {
    const results = test.results || [];
    if (!results.length) continue;
    totalTests++;
    const finalResult = results[results.length - 1];
    if (finalResult.status === 'passed') passedTests++;
  }
}

if (!totalTests) {
  console.error('No se encontraron pruebas ejecutadas en el reporte.');
  process.exit(1);
}

const passPercentage = (passedTests / totalTests) * 100;

console.log(`Total de pruebas: ${totalTests}`);
console.log(`Pruebas exitosas: ${passedTests}`);
console.log(`Porcentaje de exito: ${passPercentage.toFixed(2)}%`);
console.log(`Umbral minimo: ${minThreshold.toFixed(2)}%`);

if (passPercentage < minThreshold) {
  process.exit(1);
}

