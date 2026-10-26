# Kantum — Frontend Development Guidelines

## 1. Principios generales

- Priorizar siempre la simplicidad, la reutilización y la mantenibilidad del código.
- Utilizar **Bootstrap 5 como herramienta principal para construir las interfaces**.
- Evitar escribir CSS personalizado cuando Bootstrap ya proporciona una solución adecuada.
- No reinventar componentes existentes de Bootstrap.
- Evitar código innecesario, estilos duplicados y soluciones excesivamente complejas.
- Mantener un diseño consistente y responsive en toda la aplicación.

## 2. Prioridad al desarrollar interfaces

Seguir siempre este orden:

1. **Bootstrap nativo:** utilizar primero los componentes y clases existentes (`btn`, `card`, `form-control`, `d-flex`, `row`, `col`, `gap`, `p`, `m`, etc.).
2. **Personalización de Bootstrap:** si el diseño necesita pequeños cambios de colores, bordes, tamaños o tipografía, personalizar los estilos existentes.
3. **CSS personalizado:** crear clases CSS propias únicamente cuando Bootstrap no proporcione una solución adecuada o su uso complique innecesariamente el código.

No crear estilos personalizados para reproducir funcionalidades que Bootstrap ya implementa.

## 3. Estructura CSS

Mantener la siguiente organización:

```text
resources/
└── css/
    ├── app.css
    ├── bootstrap-custom.css
    └── pages/
        ├── login.css
        ├── register.css
        ├── dashboard.css
        └── ...
```

### Responsabilidad de cada archivo

**`app.css`**
- Punto de entrada principal de los estilos.
- Contiene las variables y reglas globales compartidas.
- Gestiona las importaciones de `bootstrap-custom.css` y de los estilos específicos de página.

**`bootstrap-custom.css`**
- Exclusivamente modificaciones de componentes o clases de Bootstrap.
- Priorizar cambios mínimos, como colores, bordes, fondos o tamaños.
- Utilizar variables CSS de Bootstrap cuando sea posible.
- No modificar directamente los archivos originales de Bootstrap.

**`pages/*.css`**
- Estilos exclusivos de cada página.
- Crear estos archivos solamente cuando sean necesarios.
- No duplicar estilos existentes en Bootstrap o en los archivos globales.
- No introducir cambios globales de Bootstrap en estos archivos.

## 4. Normas para archivos Blade

- Priorizar clases de Bootstrap directamente en el HTML.
- No incluir bloques `<style>` dentro de las vistas Blade.
- Evitar atributos `style=""`.
- Utilizar layouts y componentes Blade reutilizables cuando corresponda.
- No crear componentes adicionales para elementos simples que Bootstrap ya resuelve.
- Mantener las vistas lo más limpias y sencillas posible.

## 5. Reglas de modificación

Antes de escribir CSS personalizado, comprobar:

1. ¿Bootstrap ya permite conseguir este resultado?
2. ¿Puedo resolverlo combinando clases de Bootstrap?
3. ¿Es un cambio global de un componente Bootstrap?
4. ¿Es un estilo compartido o exclusivo de una página?

Aplicar siempre la solución más sencilla y mantenible.

No modificar componentes globales para resolver problemas exclusivos de una página.

## 6. Convenciones de trabajo

- Respetar el diseño y comportamiento existentes.
- Evitar refactorizaciones innecesarias.
- Modificar únicamente los archivos necesarios para la tarea.
- Mantener una apariencia coherente entre páginas.
- No introducir nuevas librerías CSS sin una necesidad justificada.
- Comprobar que los cambios funcionan correctamente en móviles y escritorio.
- Seguir la estructura existente del proyecto y evitar duplicaciones.

**Principio fundamental:** Bootstrap debe resolver la mayor parte del diseño. El CSS personalizado debe utilizarse solamente para aportar la identidad visual de Kantum o cubrir necesidades que Bootstrap no resuelva adecuadamente.