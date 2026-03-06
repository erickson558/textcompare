# Changelog

## V1.2.1 - 2026-03-05

- Traduccion completa al espanol de los textos visibles en la interfaz.
- Traduccion al espanol de mensajes dinamicos en JavaScript (tema, estados, botones, mensajes de copia y placeholders).
- Ajuste de documentacion para reflejar etiquetas y mensajes actuales en espanol.
- Traduccion al espanol de mensajes de error expuestos por la API de comparacion.
- Traduccion al espanol de nombres descriptivos y mensajes del workflow de release.

## V1.2.0 - 2026-03-05

- Rediseño visual completo con identidad propia `Nebula Compare` para evitar apariencia de copia exacta.
- Fondo aurora animado con orbes, textura de grilla y capas glassmorphism.
- Mejoras de microinteracciones: brillo en CTA principal, animacion de pulso al hacer click y transiciones mas suaves.
- Cabecera renovada con branding, subtitulo explicativo y controles mas claros.
- Ajustes responsive para mejor lectura y usabilidad en pantallas moviles.

## V1.1.0 - 2026-03-05

- Rediseño de interfaz para acercarla a la experiencia de text-compare.com.
- Vista de resultados lado a lado con numeración de líneas y sincronización de scroll.
- Nuevos botones y flujos: `Email this comparison`, `Edit texts ...`, `Switch texts`, `Compare!`, `Clear all`, `Copy left/right`, `Differences only`.
- Persistencia local de preferencias de tema, visibilidad de editores y filtro de diferencias.
- Endurecimiento del workflow: validación de coincidencia exacta entre `VERSION` y la cabecera superior de `CHANGELOG.md`.

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
