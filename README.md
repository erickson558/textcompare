# TextCompare Pro

Comparador de textos en PHP + JavaScript con experiencia inspirada en text-compare.com, arquitectura separada entre backend y frontend y versionado estricto `Vx.x.x`.

## Caracteristicas

- API backend en PHP (`backend/api`) con validacion de entrada y respuestas JSON.
- Motor de comparacion por lineas basado en LCS para detectar:
  - lineas iguales
  - lineas agregadas
  - lineas eliminadas
- Frontend desacoplado (`frontend/assets`) con:
  - interfaz responsive tipo text-compare (desktop/movil)
  - resultados lado a lado con numeracion de lineas
  - scroll sincronizado entre paneles
  - botones de productividad: comparar, limpiar, intercambiar, editar, copiar, enviar por email y filtrar diferencias
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
- Runtime del navegador: API `fetch`, `localStorage`, `navigator.clipboard` (con fallback visual en caso de bloqueo).
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
5. Workflow valida `Vx.x.x`, valida que el `CHANGELOG.md` comience con la misma version, crea tag anotado y genera release automaticamente.

Checklist recomendado por commit:

1. Confirmar que `VERSION`, `CHANGELOG.md` y app muestran la misma version.
2. No reutilizar versiones previas; cada commit en `main` debe tener una version nueva.
3. Hacer `push` solo de la rama `main` (el workflow se encarga del tag/release).

## Botones y funciones principales

- `Email this comparison`: abre el cliente de correo con ambos textos en el cuerpo.
- `Edit texts ...` / `Hide texts`: muestra u oculta los editores.
- `Switch texts`: intercambia texto A y B y relanza la comparacion.
- `Compare!`: solicita el diff al backend y actualiza paneles + metricas.
- `Clear all`: limpia ambos textos y reinicia resultados.
- `Copy left` / `Copy right`: copia texto fuente al portapapeles.
- `Differences only`: oculta temporalmente lineas sin cambios.

## Release automatica por push

Archivo: `.github/workflows/release.yml`

En cada push a `main`:

1. Exige cambios en `VERSION` y `CHANGELOG.md`.
2. Valida formato `VERSION` en `Vx.x.x`.
3. Valida que la cabecera superior de `CHANGELOG.md` coincida con `VERSION`.
4. Rechaza tags existentes (obliga version nueva).
5. Crea tag anotado y publica GitHub Release.

## Seguridad y buenas practicas

- Validacion de metodo HTTP y JSON en backend.
- Limite de tamano de entrada para prevenir abuso.
- Escape HTML en frontend para evitar inyeccion en render de diff.
- Separacion de responsabilidades por capas.

## Licencia

Este proyecto esta bajo Apache License 2.0. Ver `LICENSE`.
