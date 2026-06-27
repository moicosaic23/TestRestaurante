# Restaurant Functional Testing

Suite de pruebas funcionales E2E para el sistema MVC de restaurante. Esta carpeta esta aislada del codigo de la aplicacion: no modifica `app/`, `public/` ni `sql/`.

## Requisitos

- Node.js 20 o superior
- PHP con PDO MySQL
- MySQL/MariaDB con la base `restaurant_mvc`
- La app corriendo con la ruta configurada en `app/config.php`

## Instalacion

```bash
cd restaurant-testing
npm install
npx playwright install
```

## Datos de prueba

La suite usa usuarios y platos prefijados con `qa_` / `QA `. Para prepararlos:

```bash
cd restaurant-testing
npm run db:seed
```

## Ejecucion local

Si tu app esta en la ruta configurada por defecto:

```bash
npm test
```

Si esta en otra URL:

```bash
BASE_URL=http://localhost/IngSof_Proyecto/public npm test
```

## Reportes

```bash
npm run report
npm run threshold
npm run evidences
```

## Casos automatizados

La suite contiene 30 escenarios funcionales seleccionados del Excel original: autenticacion, gestion de usuarios, pedidos del mozo, cocina y productos.

