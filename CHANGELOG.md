# Changelog

Vse pomembne spremembe v T-Platform WooCommerce Theme bodo dokumentirane v tej datoteki.

## [0.2.1] - 2026-07-04

### Spremenjeno
- Odstranjene nepotrebne metode za template override (ne delujejo na Block Theme)
- Odstranjen templates/woocommerce direktorij
- Očiščen init_hooks() od neuporabnih filtrov
- Priprava za custom WordPress temo

---

## [0.2.0] - 2026-07-04

### Dodano
- Admin menu z zavihki (General, Homepage)
- General Settings stran z nastavitvami:
  - Primary Color picker
  - Secondary Color picker
  - Accent Color picker
  - Logo upload z WordPress Media Library
- Homepage Settings (placeholder za prihodnje verzije)
- Admin CSS datoteka (t-platform-admin.css)
- Admin JavaScript datoteka (t-platform-admin.js)
- WordPress Color Picker integracija
- WordPress Media Uploader integracija
- Shranjevanje nastavitev v WordPress options
- Success message po shranjevanju
- WooCommerce HPOS (High-Performance Order Storage) kompatibilnost
- WooCommerce Product Block Editor kompatibilnost

### Tehnicni detajli
- Verzija posodobljena: 0.1.0 → 0.2.0
- Dodane metode v class-t-platform-setup.php:
  - admin_enqueue_scripts() - posodobljena z WordPress odvisnostmi
  - render_settings_page() - posodobljena z dejanskimi nastavitvami
  - save_settings() - nova metoda za shranjevanje
  - declare_woocommerce_compatibility() - nova metoda za HPOS
- Hook dodan: before_woocommerce_init

### Testirano
- ✅ Admin menu viden v WordPress admin-u
- ✅ Settings stran z zavihki deluje
- ✅ Color picker-ji delujejo
- ✅ Logo upload deluje
- ✅ Shranjevanje nastavitev deluje
- ✅ WooCommerce compatibility opozorilo odpravljeno
- ✅ PHP syntax preverjena
- ✅ WordPress error log čist

---

## [0.1.0] - 2026-01-XX

### Dodano
- Initial plugin structure
- Main plugin file (t-platform-woo-theme.php)
- Setup class (class-t-platform-setup.php)
- Basic CSS framework with CSS variables
- Basic JavaScript functionality
- Plugin activation/deactivation hooks
- Version management system
- Enqueue scripts/styles system
- Theme support features
- Custom product fields (CPU, RAM, Storage, Screen, Warranty, Quality Grade)
- Comprehensive documentation (README.md, TODO.md, CHANGELOG.md)

### Tehnicni detajli
- Plugin name: T-Platform WooCommerce Theme
- Version: 0.1.0
- Requires at least: WordPress 5.0
- Requires PHP: 7.4
- Tested up to: WordPress 6.4
- WC requires at least: 5.0
- WC tested up to: 8.4

---

## [0.0.1] - 2026-01-XX

### Dodano
- Initial repository setup
- Basic README.md

---

## Verzija Policy

Za ta projekt uporabljamo 0.01 increment za vsako najmanjšo spremembo.
