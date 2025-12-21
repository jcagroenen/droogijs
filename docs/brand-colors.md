# Tailwind v4 Brand Color System

## The Problem

Need dynamic brand colors that change based on which site is loaded. Body gets class like `brand-thuis`, `brand-horeca`, or `brand-industrie`.

## What Didn't Work

### Attempt 1: @utility with wildcard
```css
@utility bg-brand-* {
  background-color: var(--brand-*);
}
```
Error: "Unexpected token" - Tailwind v4 doesn't support wildcards in @utility.

### Attempt 2: @utility with --value()
```css
@utility bg-brand-600 {
  background-color: --value(--brand-600);
}
```
Doesn't work because `--value()` only looks up static `@theme` values, not runtime CSS variables.

## Why It Fails

Tailwind v4's `@theme` and `--value()` are for **compile-time** values. The CSS is generated at build time.

Our brand colors are **runtime** values - they change based on body class, which is set by PHP when the page loads. Tailwind can't know at build time which brand will be active.

## The Solution

Define colors in `@theme` (static), then map to CSS variables (dynamic), then create explicit utility classes.

### Step 1: Static colors in @theme
```css
@theme {
  --color-thuis-600: #0891b2;
  --color-horeca-600: #d97706;
  --color-industrie-600: #475569;
}
```

### Step 2: Dynamic CSS variables
```css
:root {
  --brand-600: var(--color-thuis-600);
}
.brand-thuis {
  --brand-600: var(--color-thuis-600);
}
.brand-horeca {
  --brand-600: var(--color-horeca-600);
}
.brand-industrie {
  --brand-600: var(--color-industrie-600);
}
```

### Step 3: Explicit utility classes
```css
.bg-brand-600 { background-color: var(--brand-600); }
.text-brand-600 { color: var(--brand-600); }
.border-brand-600 { border-color: var(--brand-600); }
```

## Usage in Blade

```blade
<div class="bg-brand-600 text-white">
  This background changes color per site
</div>
```

## File Location

All brand CSS is in `resources/css/app.css`.
