# Olapus

Olapus is a reusable application foundation for building Laravel-based web applications and administration systems.

The project provides a prepared backend structure, authentication, administration layout, reusable CRUD modules, configuration conventions, and a modern frontend toolchain. It is intended to serve as a starting point for custom business applications rather than as a finished end-user product.

## Technology

Olapus currently uses:

* Laravel 13
* PHP 8.3+
* AdminLTE 4
* Bootstrap 5
* Bootstrap Icons
* Vite
* SQLite / MySQL
* Laravel UI authentication
* Spatie Laravel Media Library
* Spatie Laravel Permission
* Laravolt Avatar
* Quill
* Tom Select
* Flatpickr
* Tabulator
* ApexCharts
* FullCalendar

Additional frontend libraries are available through the common Vite bundle and can be used by individual modules when needed.

## Features

The application foundation includes:

* authentication and password reset
* configurable administration area
* responsive AdminLTE-based backend
* configurable sidebar navigation
* users, multiple user groups (Spatie roles), and application settings
* reusable CRUD module structure and stub-based module generator
* multi-value relationship and column filters with removable active-filter chips
* filter shortcuts and optional all-words search
* isolated multi-column Eloquent relationship example (Compoships)
* example projects with an owner, multiple labels and editable child tasks
* categories and hierarchical content structures
* articles and pages
* comments and feedback
* sliders and reusable content components
* advertising modules
* form validation and user notifications
* spam protection using honeypot and rate limiting
* configurable filesystem support including Amazon S3
* localization support
* reusable Blade layouts and components

Individual installations can enable, remove, or extend modules according to the requirements of the application.

## Project Structure

The application follows the standard Laravel project structure.

Important application-specific locations include:

```text
app/
    Models/
    Support/
    Console/
        Commands/
    Http/
        Controllers/
        Middleware/

config/
    admin/
        menu.php
    olapus.php

resources/
    css/
    js/
    views/
        admin/

routes/
    web.php
    auth.php
    admin.php
```

Administration modules are located primarily under:

```text
resources/views/admin/modules/
```

The administration navigation is configured in:

```text
config/admin/menu.php
```

## Installation

These commands describe a **new installation**. For an existing installation receiving the RecruitMe feature port, keep its `.env`, application key, database and users. Follow [the upgrade instructions](docs/RECRUITME-PORT.md#installing-the-port-in-an-existing-olapus).

Install PHP dependencies:

```bash
composer install
```

Olapus uses `spatie/laravel-permission` with its default Role/Permission models and
standard table names. The package configuration is intentionally not copied into the
application because Olapus does not override any of its defaults; this reduces config
drift during future package upgrades.

The optional integration example additionally requires Compoships. When applying the
Autotech feature patch, install it once and commit the resulting Composer manifest/lock:

```bash
composer require "awobaz/compoships:^3.1"
```

The patch does not replace your dependency files. See [Autotech port instructions](docs/AUTOTECH-PORT.md).

Install frontend dependencies:

```bash
npm install
```

Create the environment file:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Configure the database and other environment settings in `.env`.

Run database migrations as required by the application:

```bash
php artisan migrate
```

Build frontend assets:

```bash
npm run build
```

For local frontend development:

```bash
npm run dev
```

## Web Server

The web server document root should point directly to:

```text
public/
```

Do not expose the project root as the public web directory.

For Apache installations, Laravel routing is handled by:

```text
public/.htaccess
```

## Configuration

Application-specific configuration is stored primarily in:

```text
config/olapus.php
```

Typical environment settings include:

```dotenv
APP_NAME=Olapus
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
APP_ADMIN_URL=admin

APP_TIMEZONE=Europe/Prague
APP_LOCALE=cs
APP_FALLBACK_LOCALE=en

DB_CONNECTION=sqlite

CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

FILESYSTEM_DISK=s3
```

Environment variables should only be accessed through Laravel configuration. Application code and Blade templates should use `config()` rather than calling `env()` directly.

## Administration

The administration area uses AdminLTE 4 and Bootstrap 5.

The main layout is divided into reusable Blade partials:

```text
resources/views/admin/
    master.blade.php
    head.blade.php
    header.blade.php
    left_sidebar.blade.php
    content.blade.php
    footer.blade.php
    foot.blade.php
```

Modules extend the common administration layout and provide only their page-specific content and scripts.

Sidebar items are defined in:

```text
config/admin/menu.php
```

Each menu item may define its label, route, CSS identifier, icon, and optional child items.

## User groups and Spatie roles

The administration label **User groups** is backed directly by Spatie roles. Olapus
does not maintain a parallel `usergroup` table or a `users.usergroup_id` column. A user
may belong to any number of roles through Spatie's standard `model_has_roles` pivot.

The `User` model uses:

```php
use Spatie\Permission\Traits\HasRoles;
```

The user form assigns all selected groups with Spatie's `syncRoles()` method. The
User groups module works directly with `Spatie\Permission\Models\Role`, so no
application-specific Role subclass needs to be maintained when the package is upgraded.

Olapus keeps Spatie's standard permission-table migration unchanged and adds only one
application migration for the optional human-readable `roles.description` column. The
Spatie role `name` remains the programmatic identifier, for example `admin` or `editor`;
`guard_name` is kept as `web` and is not exposed as an editable administration field.

For a project receiving this feature after its existing Composer lock was created, run:

```bash
composer update spatie/laravel-permission --with-all-dependencies
php artisan optimize:clear
php artisan migrate
```

Then commit both `composer.json` and the newly generated `composer.lock`. No conversion
of the historical Olapus `usergroup` data is performed.

## Frontend Assets

Frontend assets are managed through Vite.

Main entry points:

```text
resources/css/adminlte.css
resources/js/adminlte.js
```

Production assets are generated into:

```text
public/build/
```

The generated build directory should not be committed to Git unless required by a specific deployment process.

## Storage

The application can use Laravel filesystem disks.

For installations using Amazon S3, configure:

```dotenv
FILESYSTEM_DISK=s3

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false
```

Local Laravel storage remains available where needed.

## Authentication and Security

Authentication is based on Laravel's session authentication.

The application uses:

* CSRF protection
* POST logout
* request throttling
* honeypot spam protection
* Laravel password hashing
* Laravel authorization middleware

Public forms should use Laravel validation and CSRF protection and may additionally use honeypot protection where appropriate.

## Development

Clear Laravel caches after configuration or structural changes when necessary:

```bash
php artisan optimize:clear
```

Useful commands:

```bash
php artisan about
php artisan route:list
php artisan config:show olapus
```

Run tests with:

```bash
php artisan test
```

or:

```bash
vendor/bin/phpunit
```

## Extending Olapus

Olapus is designed to be extended by adding application-specific modules.

A typical module consists of:

```text
Controller
Model
Migration
Routes
Blade views
Translations
Menu configuration
```

Existing modules can be used as templates for new functionality.

The goal is to keep common infrastructure reusable while allowing individual applications to remain simple and application-specific.

## Relationship filters and editable relations

A model declares the relationships that can be filtered. Field names and explicit
Eloquent table names remain under application control:

```php
protected $adminFilters = [
    'owner' => ['relation' => 'owner', 'label' => 'admin_module_project.fields.owner'],
    'label' => ['relation' => 'labels', 'label' => 'admin_module_project.fields.labels'],
];
```

`?relation=label:1,label:2,owner:7` means **label 1 OR label 2**, combined with
**owner 7**. Different filter groups use AND. Add `'match' => 'all'` to a
many-to-many filter definition when every selected value must be present.
Filters are applied with Eloquent `whereHas`, not joins that multiply parent rows.
The URL is shareable; only approved filter/search/sort parameters are remembered.
Use the chips to remove one selection, or **Clear all** to reset the table.

Controllers declare editable single and multiple relations separately:

```php
protected $relationFields = [
    'owner_id' => 'owner',
    'label_ids' => 'labels',
];
```

For a multiple select, include a presence marker so selecting nothing means
"detach everything", while omitting the field entirely means "leave unchanged":

```blade
<input type="hidden" name="_relations_present[label_ids]" value="1">
<select name="label_ids[]" multiple class="form-select" data-enhance="select">
    {{-- options --}}
</select>
```

Related IDs are checked against the actual related model/table. Parent and pivot
changes run in one database transaction. This is not a replacement for your
application's authorization rules.

### Relationship demonstration

The included **Projects**, **Project labels** and **Project tasks** modules show
`belongsTo`, `belongsToMany` and `hasMany`, including filters in table headings.
Tasks may also be added, edited and removed inside a project form. Submitted child
IDs are checked against that project; IDs from another parent are rejected.

```bash
php artisan migrate
# Optional, in local/development/testing only; never creates a user:
php artisan db:seed --class=ProjectRelationsDemoSeeder
```

Open the **Relationship examples** menu, or `/admin/project` with the default
admin prefix. The migration uses the existing `users` table. Authentication remains
Laravel UI; the example seeder does not change administrator accounts.

## Column filters, shortcuts and word search

Models may declare `$adminColumnFilters` separately from relationship `$adminFilters`:

```php
protected $adminColumnFilters = [
    'name' => ['type' => 'like', 'label' => 'admin_module_project.fields.name'],
    'created_from' => [
        'column' => 'created_at', 'type' => 'date_from', 'label' => 'admin_filters.created_from',
    ],
    'created_to' => [
        'column' => 'created_at', 'type' => 'date_to', 'label' => 'admin_filters.created_to',
    ],
];
```

Only explicitly configured columns may be filtered. `in` plus `options` creates a
multi-select; `exact`, `like`, `starts_with`, `date`, `date_from`, `date_to` are also supported.
Multiple values use OR within a field; fields and relationships use AND between groups.
The shared table partial handles controls, chips, removal and preserving other filters.

`$adminFilterPresets` provides developer-defined shortcuts. Override
`getAdminFilterPresets()` for dynamic dates, as in Project's **This month** shortcut.
The toolbar offers **Whole phrase** / **All words**; phrase mode remains the default.
All-words mode may match separate words in different `$fulltextFields` columns.

See [the complete configuration examples](docs/AUTOTECH-PORT.md), and try the Project
and Project task tables. No frontend rebuild or external API is involved.

## Multi-column relationship demonstration

Install `awobaz/compoships:^3.1`, apply pending migrations, then optionally run locally:

```bash
php artisan db:seed --class=ExternalRelationsDemoSeeder
```

Open **Integration example**, or `/admin/externalRecord`. Record `100` exists in two
sources; each must show only its own child rows. `ExternalRecord` and `ExternalRow`
use Compoships on both sides. Matching uses `(source_id, external_id)`, while normal
internal IDs remain available for URLs, editing and the existing relationship filters.
No financial/e-commerce model or user account is imported from Autotech.

## Generating an admin module

Use a **singular PascalCase** model name:

```bash
php artisan make:admin-module DocumentType --icon=bi-file-text --dry-run
php artisan make:admin-module DocumentType --icon=bi-file-text
```

Optional arguments:

```bash
php artisan make:admin-module Note --table=note --icon=bi-sticky --label="Notes"
```

The command creates a model under `App\Models`, a CRUD controller, two Blade views,
a migration, and a PHP translation file for each installed locale. The initial
schema has `id`, `name`, timestamps and soft deletes.
It also registers the resource and menu item in:

```text
routes/admin_generated.php
config/admin/generated_menu.php
```

The route registry is loaded **inside** the existing authenticated admin group.
The hand-written `config/admin/menu.php` is not rewritten by the command. Edit the
generated entries freely, but keep shared custom menu groups in the hand-written
file. The generator refuses existing module files; it does not have a destructive
`--force` mode and never executes a migration for you.

After generation, review the files, adjust field validation/translations, then run:

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan migrate --pretend
php artisan migrate
```

Customize the six generator templates here, not inside `vendor`:

```text
stubs/olapus/admin_model.stub
stubs/olapus/admin_controller.stub
stubs/olapus/admin_migration.stub
stubs/olapus/admin_index_view.stub
stubs/olapus/admin_create_edit_view.stub
stubs/olapus/admin_lang.stub
```

Multiword conventions are consistent: `DocumentType` maps to table
`document_types`, route `admin.documentType.index`, view directory `documenttype`
and translation group `admin_module_documenttype`. `--table` changes only the table.
The `--label` text is an initial module name for all locales; edit those names when
specific translations are needed. Shared field captions come from the translated
`admin_scaffold.php` files.

For a future relation, write the Eloquent method, migration, validation and
`$adminFilters` / `$relationFields` mappings explicitly. The generator deliberately
does not guess your business schema from a module name.

## Documentation

The relationship/filter/generator port is documented in [docs/RECRUITME-PORT.md](docs/RECRUITME-PORT.md), including installation, source mapping and limitations. Detailed documentation for the rest of the application is planned separately.

This README intentionally contains only the basic project overview, installation instructions, and architectural conventions required to start working with the codebase.

## License

See the `LICENSE` file for licensing information.
