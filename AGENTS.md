# AGENTS.md

## Project overview

This project is a Laravel CMS built with:

- Laravel 12
- PHP 8.2+
- Filament 5
- Inertia.js
- Vue 3
- TypeScript
- Vite
- Tailwind CSS
- Sass
- Spatie Permission
- Filament Shield
- Spatie Translatable
- Laravel Localization

The project is a custom CMS/site builder.

Filament is used for the admin area.
Inertia/Vue is used for the public frontend.

The main goal for Codex is to help generate mechanical, repetitive project structures safely:

- new CMS blocks;
- new Vue blocks;
- new Filament block definitions;
- new Filament Resources;
- new Models matching Filament Resources;
- new Inertia-oriented Controllers;
- optional routes;
- permissions and seeders for new Models/Resources.

Codex must prefer controlled generation over broad refactoring.

---

## Operating principles

### Default behavior

When a task is precise, implement only the requested scope.

When a task is broad, risky, architectural, or ambiguous, first produce a plan and wait for approval before modifying files.

Examples:

```text
Create a Carousel block for the homepage template.
```

This means: create the full block structure.

```text
Create only the Vue block CarouselBlock for homepage.
```

This means: create only the Vue file and do not touch PHP files.

```text
Create only the Filament block Carousel for homepage.
```

This means: create only the PHP block file and update `TemplateManager.php`.

```text
Refactor the block system.
```

This is broad and requires a plan first.

### Scope control

Do not assume every related layer must be changed.

Only create or modify:

- the files explicitly required by the requested task;
- the registration files required to make those files work;
- permission seeders when the task involves creating a Model/Resource.

When unsure, ask a question before editing.

---

## Areas to avoid unless explicitly requested

Do not refactor or modify legacy JavaScript modules unless explicitly asked.

Legacy JavaScript files under:

```text
resources/js/modules
```

are maintained manually for now.

Do not work on the ResourceGenerator system unless explicitly asked.

Treat these as future/advanced areas:

```text
app/Filament/ResourceGenerators
app/Models/ResourceGenerator.php
app/Http/Controllers/GeneratedResourceController.php
app/Filament/DynamicTemplatePreviewResource.php
```

Do not modify these files or related architecture unless the user explicitly requests it.

---

## Dependencies

Do not add Composer or NPM dependencies unless explicitly requested.

Prefer existing project dependencies and existing project patterns.

This is important because the deployment pipeline may stop when high-severity dependency issues are detected.

Never add a package just for convenience.

Do not silently change dependency constraints.

Do not downgrade packages unless explicitly approved.

Do not remove packages unless explicitly approved.

---

## Verification commands

For now, the standard frontend verification command is:

```bash
npm run build
```

Run it when frontend or Vite-related files are changed.

Do not invent unavailable commands such as:

```bash
npm run lint
npm run typecheck
```

unless they are later added to `package.json`.

Backend commands may be inspected from `composer.json`, but do not run unnecessary commands unless relevant to the change.

---

## Security audit commands

For dependency/security checks, run:

```bash
npm audit
composer audit
```

Use these commands to detect vulnerabilities and report them clearly.

Codex must not run:

```bash
npm audit fix --force
npm update --force
composer update --with-all-dependencies
composer update
```

unless the user explicitly asks for that specific command after reviewing the risks.

Never use force-based dependency fixes.

Never bypass or ignore security advisories just to make the audit pass.

By default, Codex must only report audit problems and propose fixes.

Codex may modify dependency files only when the user explicitly approves one proposed fix.

If `npm audit` or `composer audit` reports vulnerabilities with severity `high` or `critical`, Codex must:

1. identify the affected package;
2. identify whether it is a direct or transitive dependency;
3. identify the package that introduces it;
4. check whether a safe non-breaking upgrade exists;
5. propose one or more remediation options;
6. explain possible risks;
7. wait for user approval before changing dependency versions.

Codex may suggest safe fixes such as:

- updating a direct dependency within the existing semver range;
- updating the lockfile if the fixed version is already allowed by `package.json` or `composer.json`;
- upgrading a direct dependency to a newer minor/patch version if compatible;
- replacing an abandoned dependency only after approval;
- proposing a major upgrade only as a separate, explicit decision.

If the vulnerability cannot be fixed without a major upgrade, a forced update, or replacing a package, Codex must stop and present a short report instead of applying the change.

### Security audit report format

When audits find vulnerabilities, report them using this format:

```text
Security audit result

NPM
- Status: pass/fail
- High/Critical issues:
  - package:
  - severity:
  - direct/transitive:
  - introduced by:
  - fixed version available:
  - safe fix possible without force:
  - recommended action:

Composer
- Status: pass/fail
- High/Critical issues:
  - package:
  - severity:
  - direct/transitive:
  - advisory:
  - fixed version available:
  - safe fix possible without broad update:
  - recommended action:

Proposed next step
- Option A:
- Option B:
- Risks:
```

---

## Migrations

Never modify existing migrations unless explicitly requested.

Always create a new additive migration for schema changes.

Before creating a migration, inspect existing files for conflicts:

```text
database/migrations
app/Models
app/Filament/Resources
app/Filament/Clusters
routes/web.php
```

Check for:

- existing table names;
- existing model names;
- existing resource names;
- duplicate columns;
- conflicting route names;
- conflicting slugs;
- catch-all route conflicts.

If a conflict is possible, stop and explain it before editing.

When Codex creates a new Resource that includes a new migration, Codex must run the migration after creating it.

Use:

```bash
php artisan migrate
```

Do not run destructive migration commands such as:

```bash
php artisan migrate:fresh
php artisan migrate:refresh
php artisan migrate:reset
php artisan db:wipe
```

unless the user explicitly requests them and confirms the risk.

If `php artisan migrate` fails, Codex must:

1. stop further implementation steps that depend on the database schema;
2. report the exact migration failure;
3. explain the likely cause;
4. propose a fix;
5. wait for user approval before applying risky changes.

Codex must never edit old migrations to fix a failed migration unless explicitly requested.

---

## Frontend direction

Vue 3 + Inertia + TypeScript is the official frontend direction.

New Vue files should use:

```vue
<script setup lang="ts">
```

Use TypeScript interfaces for props.

Prefer Tailwind CSS for Vue component styling.

Avoid inline CSS.

Do not create component-specific SCSS unless explicitly requested.

Global Sass is used for global/shared styling, not for local styling of new Vue blocks.

Use the existing alias:

```ts
@/*
```

for imports from `resources/js`.

---

## CMS blocks

CMS blocks have two sides:

1. Filament block definitions in PHP.
2. Vue block components for frontend rendering.

### Naming conventions

For a block named:

```text
Featured Projects
```

use:

```text
PHP class: FeaturedProjects
Filament block key: featured_projects
Vue component: FeaturedProjectsBlock.vue
```

For a block named:

```text
Carousel
```

use:

```text
PHP class: Carousel
Filament block key: carousel
Vue component: CarouselBlock.vue
```

Use:

- PascalCase for PHP classes;
- snake_case for Filament block keys and component types;
- PascalCase plus `Block.vue` for Vue components.

---

## Common blocks vs template-specific blocks

Common Filament blocks go here:

```text
app/Filament/Schemas/Components/blocks
```

Common Vue blocks go here:

```text
resources/js/blocks/default
```

Template-specific Filament blocks go here:

```text
app/Filament/Schemas/Components/templates/{template}
```

Template-specific Vue blocks go here:

```text
resources/js/blocks/{template}
```

Example for a homepage-only Carousel block:

```text
app/Filament/Schemas/Components/templates/homepage/Carousel.php
resources/js/blocks/homepage/CarouselBlock.vue
```

---

## Block name conflict check

Before creating any new CMS block, Codex must check whether a block with the same name, class, block key, component type, or Vue component already exists.

Codex must inspect the relevant locations before creating files.

For common blocks, check:

```text
app/Filament/Schemas/Components/blocks
resources/js/blocks/default
app/Services/TemplateManager.php
```

For template-specific blocks, check:

```text
app/Filament/Schemas/Components/templates/{template}
resources/js/blocks/{template}
app/Services/TemplateManager.php
```

For a requested block named `Carousel`, Codex must check for conflicts such as:

```text
Carousel.php
CarouselBlock.vue
Block::make('carousel')
'carousel'
```

For a requested block named `Featured Projects`, Codex must check for conflicts such as:

```text
FeaturedProjects.php
FeaturedProjectsBlock.vue
Block::make('featured_projects')
'featured_projects'
```

If a block with the requested name already exists, Codex must not overwrite it.

If a block with the requested name already exists, Codex must:

1. stop before creating or editing files;
2. report where the existing block was found;
3. suggest one or more alternative names;
4. ask the user to choose a new name or explicitly confirm how to proceed.

Codex must not automatically rename the requested block.

Codex must not create duplicate blocks with suffixes such as `Carousel2`, `NewCarousel`, or `CarouselCopy` unless the user explicitly chooses that name.

Codex must not modify the existing block unless the user explicitly asks to update that existing block.

---

## Block generation behavior

When the user asks:

```text
Create a Carousel block for the homepage template.
```

Codex must create:

```text
app/Filament/Schemas/Components/templates/homepage/Carousel.php
resources/js/blocks/homepage/CarouselBlock.vue
```

and update:

```text
app/Services/TemplateManager.php
```

When the user asks:

```text
Create only the Vue block CarouselBlock for homepage.
```

Codex must create only:

```text
resources/js/blocks/homepage/CarouselBlock.vue
```

and must not update PHP files.

When the user asks:

```text
Create only the Filament block Carousel for homepage.
```

Codex must create:

```text
app/Filament/Schemas/Components/templates/homepage/Carousel.php
```

and update:

```text
app/Services/TemplateManager.php
```

When a Filament block PHP file is created, always update `TemplateManager.php`.

For a PHP block, update both:

- the Filament block registration;
- the component type list.

For homepage-specific blocks, update:

- `getTemplateSpecificBlocks('homepage')`;
- `getTemplateSpecificComponentTypes('homepage')`.

For common blocks, update:

- `getCommonBlocks()`;
- `getCommonComponentTypes()`.

---

## TemplateManager modification rules

`app/Services/TemplateManager.php` is a registration file and must be treated as a sensitive file.

When adding a new block, Codex must only perform the minimal registration needed for that specific block.

Codex must not refactor `TemplateManager.php`.

Codex must not:

- reorder existing blocks;
- rename existing methods;
- rename existing variables;
- change method signatures;
- change return structures;
- change indentation style beyond the added lines;
- extract logic into new methods;
- simplify existing code;
- remove existing comments;
- rewrite arrays;
- normalize formatting;
- modify unrelated templates;
- modify unrelated component type lists.

When creating a template-specific block, Codex must update only the target template.

Example:

```text
Create a Carousel block for the homepage template.
```

Codex may only add:

- the PHP import for `Carousel`;
- `Carousel::make()` inside the homepage-specific block list;
- `'carousel'` inside the homepage-specific component type list.

Codex must not touch blocks or component types for other templates.

When creating a common block, Codex may only update:

- common block imports;
- `getCommonBlocks()`;
- `getCommonComponentTypes()`.

Codex must preserve the existing structure exactly.

The correct behavior is a surgical append-only edit.

If Codex cannot confidently identify the exact place where the new block should be registered, it must stop and ask instead of rewriting the file.

---

## Block field proposal workflow

When the user asks Codex to create a new CMS block but does not provide the fields, Codex must not create files immediately.

Instead, Codex must infer a reasonable field proposal from the block name and target template, then ask for confirmation before editing files.

Codex must present the proposed fields as an editable Markdown checklist.

Example:

```text
Proposed fields for Carousel block

- [x] title — TextInput — string
- [x] subtitle — Textarea — string
- [x] images — FileUpload multiple — string[]
- [x] autoplay — Toggle — boolean
- [x] interval — TextInput numeric — number

Optional fields you may want to add:
- [ ] cta_label — TextInput — string
- [ ] cta_url — TextInput — string
```

The user may deselect fields, add fields, rename fields, or change field types by replying in text.

Codex must wait for user confirmation before creating or modifying files.

In normal Codex tasks, field selection must happen through a Markdown checklist and a confirmation reply from the user.

Each proposed field must include:

- field name;
- Filament form component type;
- expected Vue prop type;
- whether it is required or optional when relevant;
- short reason when the field is inferred from the block name.

Codex must prefer existing Filament form components already used in the project.

Codex may use common Filament form components such as:

```text
TextInput
Textarea
RichEditor
Toggle
Select
FileUpload
Repeater
Builder
Checkbox
Radio
DatePicker
DateTimePicker
TimePicker
ColorPicker
KeyValue
TagsInput
Hidden
```

Before using less common field types, Codex must inspect existing project usage or ask the user.

Each confirmed Filament field must have a matching Vue prop.

Use these mappings:

```text
TextInput -> string
TextInput numeric -> number
Textarea -> string
RichEditor -> string
TinyEditor -> string
Toggle -> boolean
Checkbox -> boolean
Select -> string
Radio -> string
DatePicker -> string
DateTimePicker -> string
TimePicker -> string
ColorPicker -> string
FileUpload single -> string path
FileUpload multiple -> array of string paths
Repeater -> array of typed objects
Builder -> array of typed block objects
KeyValue -> Record<string, string>
TagsInput -> string[]
Hidden -> string | number | boolean, depending on usage
```

The Vue block receives data for the current locale only.

Do not expect each block prop to be a multilingual object unless the user explicitly says so.

---

## Vue block props

When creating a Vue block, define a typed props interface.

Example:

```ts
interface Props {
  title?: string
  description?: string
  autoplay?: boolean
  images?: string[]
}

const props = withDefaults(defineProps<Props>(), {
  title: '',
  description: '',
  autoplay: false,
  images: () => [],
})
```

For FileUpload paths from the public disk, use existing project patterns first.

If no existing helper is available, use `/storage/${path}` carefully and only when the stored value is a relative storage path.

---

## Filament block PHP structure

New Filament block classes should follow the existing project pattern:

```php
<?php

namespace App\Filament\Schemas\Components\templates\homepage;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Builder\Block;

class Carousel
{
    public static function make(): Block
    {
        return Block::make('carousel')
            ->label('Carousel')
            ->schema([
                TextInput::make('title')
                    ->label('Title'),
            ]);
    }
}
```

Adjust imports based on the actual fields used.

Do not add unused imports.

---

## Filament Resources

When the user asks to create a Resource without mentioning a cluster, create a standard non-cluster Resource.

For a model named:

```text
Partner
```

create:

```text
app/Models/Partner.php
database/migrations/xxxx_xx_xx_xxxxxx_create_partners_table.php
app/Http/Controllers/PartnerController.php
app/Filament/Resources/Partners/PartnerResource.php
app/Filament/Resources/Partners/Pages/ListPartners.php
app/Filament/Resources/Partners/Pages/CreatePartner.php
app/Filament/Resources/Partners/Pages/EditPartner.php
app/Filament/Resources/Partners/Pages/ViewPartner.php
app/Filament/Resources/Partners/Schemas/PartnerForm.php
app/Filament/Resources/Partners/Schemas/PartnerInfolist.php
app/Filament/Resources/Partners/Tables/PartnersTable.php
```

Use this naming convention:

```text
Model: Partner
Plural resource folder: Partners
Resource class: PartnerResource
Form schema: PartnerForm
Infolist schema: PartnerInfolist
Table class: PartnersTable
Pages:
  ListPartners
  CreatePartner
  EditPartner
  ViewPartner
Controller:
  PartnerController
Table:
  partners
```

Before generating these files, inspect existing models, migrations, resources, clusters, controllers and routes for naming conflicts.

If the user asks to create a Resource but does not provide fields, ask for the fields before generating migration, model, form schema and table.

Do not invent database columns beyond safe defaults such as:

```text
id
timestamps
```

unless the user explicitly asks for a draft scaffold.

For public CMS content, ask whether common fields are needed:

```text
title
slug
description
content
blocks
status
published_at
meta_title
meta_description
meta_keywords
featured_image
```

---

## Filament Cluster behavior

If the user asks to create a Cluster, ask:

1. Which Resources should be inside the cluster?
2. Should existing Resources be moved into the cluster?
3. Should new Resources be generated inside the cluster?
4. Should the cluster affect navigation only, or also permissions and organization?

Do not move existing Resources into a Cluster unless explicitly requested.

If the user asks for a Resource inside a specific Cluster, follow the cluster path convention already used by the project.

Example cluster Resource path pattern:

```text
app/Filament/Clusters/{ClusterName}/Resources/{ResourcePlural}/{ResourceName}Resource.php
app/Filament/Clusters/{ClusterName}/Resources/{ResourcePlural}/Pages
app/Filament/Clusters/{ClusterName}/Resources/{ResourcePlural}/Schemas
app/Filament/Clusters/{ClusterName}/Resources/{ResourcePlural}/Tables
```

The Resource must declare the cluster class.

When the user simply asks for a Resource and does not mention a Cluster, create a normal Resource outside clusters.

---

## Controllers

When creating a new Model/Resource, create a matching Controller by default.

The controller should be Inertia-oriented.

For a model named:

```text
Partner
```

create:

```text
app/Http/Controllers/PartnerController.php
```

The controller may include standard public methods such as:

```php
index()
show()
```

Use Inertia responses.

However, do not create or register public routes unless the user explicitly approves them.

---

## Routes

Do not add public routes automatically.

Ask before adding routes.

This project has localized public routes and a catch-all page route. Route order matters.

When adding routes:

- place specific routes before dynamic routes;
- never place anything after the final catch-all page route unless there is a very specific reason;
- preserve localization middleware and prefixes;
- avoid conflicts with `news`, `admin`, `api`, `filament`, `dashboard`, `storage`, `livewire`, and `up`.

Before editing `routes/web.php`, explain where the new routes will be placed and why.

---

## Public Inertia pages

Do not create public Inertia pages unless public routes are approved or explicitly requested.

If approved, follow existing Inertia page conventions under:

```text
resources/js/Pages
```

For a Resource named `Partner`, possible pages may be:

```text
resources/js/Pages/Partners/Index.vue
resources/js/Pages/Partners/Show.vue
```

Only create these when the user asks for public/frontend pages or approves public routes.

---

## Permissions

This project uses Spatie Permission and Filament Shield.

When creating a new Resource/Model:

- create/update standard permissions;
- update the relevant permission seeder;
- inspect existing seeders before editing;
- preserve existing permission naming conventions.

Use standard Resource permissions by default.

Before adding record-level granular permissions, ask the user.

Granular permissions are permissions such as:

```text
view partner {id}
update partner {id}
```

Do not add granular permissions unless the user approves them.

If granular permissions are approved, follow existing model patterns and ensure navigation access is compatible with granular permissions.

---

## Seeders

Seeders are mainly used during project startup/bootstrap.

Do not run seeders automatically during normal generation tasks.

When creating permissions for a new Model/Resource, update the relevant seeder files when required, but do not run them unless the user explicitly asks.

Likely files include:

```text
database/seeders/RolePermissionSeeder.php
database/seeders/SuperAdminSeeder.php
```

Do not replace seeders wholesale.

Apply minimal, focused edits.

Keep existing roles and permission conventions intact.

---

## Translatable models

This project uses Spatie Translatable for public CMS content.

When generating a new public-facing CMS model, ask whether fields should be translatable.

Do not add `HasTranslations` by default unless the user requests multilingual content or the requested model clearly follows an existing multilingual CMS pattern.

When adding translatable fields:

- add the `HasTranslations` trait;
- define `$translatable`;
- cast JSON/array fields where appropriate;
- follow existing slug generation patterns when relevant.

---

## Styling

Use Tailwind CSS in Vue components.

Avoid inline CSS.

Use Sass only for global/shared styling.

Do not create or modify SCSS partials for a Vue block unless explicitly requested.

---

## Existing architecture

Prefer existing project patterns over new abstractions.

Before creating a new Resource, inspect similar existing Resources.

Before creating a new block, inspect similar existing blocks.

Before creating a new controller, inspect similar existing controllers.

Do not simplify, rewrite, reorder, normalize, or refactor:

- Vite entrypoints;
- TemplateManager structure, order, methods, arrays, and existing registrations;
- localization behavior;
- Filament Resource structure;
- Filament Cluster structure;
- route ordering;
- legacy JavaScript modules.

All edits to existing architecture files must be surgical and limited to the exact requested addition.

---

## Agent organization strategy

Use the root `AGENTS.md` for global project rules that must always apply.

Examples:

- dependency rules;
- migration rules;
- security audit rules;
- route safety rules;
- naming conventions;
- global architecture constraints;
- TemplateManager surgical edit rules.

Use more specific nested `AGENTS.md` files for areas with detailed local behavior.

Recommended future files:

```text
app/Filament/AGENTS.md
app/Filament/Schemas/Components/AGENTS.md
resources/js/AGENTS.md
resources/js/blocks/AGENTS.md
app/Services/AGENTS.md
```

Recommended responsibilities:

```text
app/Filament/AGENTS.md
- Resource generation rules
- Cluster rules
- Filament page/table/form/infolist conventions

app/Filament/Schemas/Components/AGENTS.md
- Filament block PHP generation rules
- Field proposal rules
- Template-specific vs common block rules

resources/js/AGENTS.md
- Vue 3 + Inertia + TypeScript conventions
- Tailwind styling rules
- Page/component conventions

resources/js/blocks/AGENTS.md
- Vue block props
- Block rendering assumptions
- FileUpload path handling
- Component naming rules

app/Services/AGENTS.md
- TemplateManager append-only registration rules
```

Do not split instructions too early if the same rule is needed everywhere.

Start with the root `AGENTS.md`. Add nested files only when the root file becomes too large or when Codex repeatedly makes mistakes in a specific area.

Subagents are useful for complex parallel analysis, audits, or multi-area investigations.

Do not use subagents for simple mechanical generation tasks such as creating one block or one Resource.

For routine block generation, one agent with clear local instructions is better than multiple agents.

For broad tasks, Codex may use specialized subagents only when explicitly requested by the user.

Examples of tasks where subagents may help:

```text
Audit the whole block system and propose improvements.
Analyze Resource generation patterns across all clusters.
Investigate build failures across PHP, Vite, Vue and routes.
Plan a large migration away from legacy JavaScript.
```

---

## Final response requirements

At the end of each task, report:

- files created;
- files modified;
- commands run;
- whether `php artisan migrate` passed, failed, or was not relevant;
- whether `npm run build` passed, failed, or was not relevant;
- whether `npm audit` and `composer audit` passed, failed, or were not relevant;
- any high/critical vulnerabilities found;
- any skipped actions due to missing clarification;
- any risks or follow-up steps.

If a command fails, explain the failure and do not claim success.

If an audit finds high or critical vulnerabilities, do not apply automatic fixes. Report the issue and propose safe remediation options first.