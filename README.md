# DaveSport Theme (moodco)

Config-driven WordPress theme for the Read Network (15+ sports news sites).

## Architecture

One theme, per-site JSON configs. Each site defines its colors, category slugs, analytics, and company info.

## Setup

1. Add `define('MOODCO_SITE_KEY', 'readarsenal');` to the site's `wp-config.php`
2. Deploy the `src/` directory as the WordPress theme
3. Ensure `sites/{site_key}.json` exists with the site's config
4. Activate the theme, set a static homepage using the "Home" template

## Directory Structure

```
src/                    # The WordPress theme (deploy this)
├── inc/
│   ├── config-loader.php   # Reads sites/{key}.json, injects CSS vars + analytics
│   ├── enqueue.php         # All wp_enqueue calls (no hardcoded script tags)
│   └── setup.php           # Theme supports, menus, widgets
├── template-parts/
│   ├── hero-slider.php     # Swiper hero banner
│   └── homepage-section.php # Reusable category section (slug-based, not ID-based)
├── page-home.php           # Homepage template (reads sections from config)
├── header.php              # Clean header (config-driven)
├── footer.php              # Clean footer (config-driven company info)
└── functions.php           # Modular loader

sites/                  # Per-site JSON configs
├── _template.json      # Base template
├── readarsenal.json
└── ...
```

## Adding a New Site

1. Copy `sites/_template.json` → `sites/{site_key}.json`
2. Fill in colors, category slugs, analytics, domain
3. Add `define('MOODCO_SITE_KEY', '{site_key}');` to the site's `wp-config.php`
4. Deploy theme

Category sections use **slugs** (not IDs), so they survive migrations.

## Requirements

- WordPress 6.0+
- ACF Pro (Theme Options for logo, social media, news bar)
- PHP 7.4+
# Deploy: bash ~/deploy.sh
