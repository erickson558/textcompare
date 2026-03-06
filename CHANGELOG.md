# Changelog

## V1.0.3 - 2026-03-05

- Workflow de release endurecido para exigir `VERSION` y `CHANGELOG.md` actualizados en cada push a `main`.
- Bloqueo de tags duplicados para forzar una version nueva por commit.
- Creacion de tag anotado y release automatica por cada version nueva.

## V1.0.2 - 2026-03-05

- Correccion del workflow de release para evitar fallo cuando el tag ya existe en el mismo commit.
- Validacion de consistencia: si el tag existe pero apunta a otro commit, el pipeline falla con mensaje para incrementar version.

## V1.0.1 - 2026-03-05

- Correccion de error 500 por incompatibilidad de sintaxis con PHP 5.4.
- Refactor de backend para compatibilidad con entornos EasyPHP legacy.
- Sincronizacion de version para app, commit y release.

## V1.0.0 - 2026-03-05

- Lanzamiento inicial de TextCompare Pro.
- Arquitectura separada backend/frontend.
- API de comparacion de textos y endpoint de version.
- UI responsive con animaciones y modo claro/oscuro.
- Integracion de version centralizada via archivo `VERSION`.
- Configuracion de workflow para release automatico en `main`.
