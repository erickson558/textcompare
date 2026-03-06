# TextCompare Pro

Comparador de textos moderno en PHP con arquitectura separada entre backend y frontend.

## Caracteristicas

- API backend en PHP (`backend/api`) con validacion de entrada y respuestas JSON.
- Motor de comparacion por lineas basado en LCS para detectar:
  - lineas iguales
  - lineas agregadas
  - lineas eliminadas
- Frontend desacoplado (`frontend/assets`) con:
  - interfaz responsive
  - animaciones de entrada y resultados
  - modo claro y modo oscuro
- Version centralizada en archivo `VERSION` para mantener consistencia entre app, Git y releases.

## Estructura del proyecto

```text
textcompare/
  backend/
    api/
      compare.php
      version.php
    src/
      Application/
      Domain/
      Infrastructure/
  frontend/
    assets/
      css/
      js/
  .github/workflows/release.yml
  index.php
  VERSION
```

## Requisitos

- PHP 5.4 o superior (compatible con EasyPHP 14.1b2).
- Recomendado para desarrollo moderno: PHP 8.x.
- Servidor local (EasyPHP, Apache, etc.).
- Git.
- GitHub CLI (`gh`) para crear el repositorio desde terminal.

## Dependencias

- Backend: PHP nativo (sin Composer en esta version).
- Frontend: HTML/CSS/JavaScript nativo (sin build step).
- CDN usados en runtime: Google Fonts.

## Ejecucion local

1. Colocar el proyecto en tu directorio web.
2. Abrir en navegador: `http://localhost:888/monitoreos/textcompare/`
3. Escribir o pegar ambos textos y presionar `Comparar`.

## API

### `POST backend/api/compare.php`

Request JSON:

```json
{
  "leftText": "linea A",
  "rightText": "linea B"
}
```

Response JSON:

```json
{
  "data": {
    "operations": [
      {"type":"equal","text":"..."},
      {"type":"add","text":"..."},
      {"type":"remove","text":"..."}
    ],
    "stats": {
      "leftLines": 1,
      "rightLines": 1,
      "equalLines": 0,
      "similarity": 0
    }
  }
}
```

### `GET backend/api/version.php`

Devuelve la version actual leida desde `VERSION`.

## Versionado (Vx.x.x)

Se usa formato `Vx.x.x`.

Reglas recomendadas:

- `patch` (`V1.0.1`): correcciones pequenas.
- `minor` (`V1.1.0`): nuevas funciones compatibles.
- `major` (`V2.0.0`): cambios incompatibles o rediseno grande.

Para cada commit de producto:

1. Incrementar `VERSION`.
2. Actualizar `CHANGELOG.md`.
3. Commit con mensaje claro.
4. Push a `main`.
5. Workflow valida `Vx.x.x`, crea tag anotado y genera release automaticamente.

Checklist recomendado por commit:

1. Confirmar que `VERSION`, `CHANGELOG.md` y app muestran la misma version.
2. No reutilizar versiones previas; cada commit en `main` debe tener una version nueva.
3. Hacer `push` solo de la rama `main` (el workflow se encarga del tag/release).

## Seguridad y buenas practicas

- Validacion de metodo HTTP y JSON en backend.
- Limite de tamano de entrada para prevenir abuso.
- Escape HTML en frontend para evitar inyeccion en render de diff.
- Separacion de responsabilidades por capas.

## Licencia

Este proyecto esta bajo Apache License 2.0. Ver `LICENSE`.
