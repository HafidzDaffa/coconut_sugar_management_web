# Graph Report - coconut_sugar_management_web  (2026-09-01)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 262 nodes · 303 edges · 30 communities (14 shown, 5 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `6aed727a`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Illuminate\Http\Request
- composer.json
- scripts
- User
- Illuminate\Database\Migrations\Migration
- package.json
- Role
- Branches/Index.vue
- devDependencies
- UserFactory.php
- require-dev
- config
- AppServiceProvider
- logging.php
- AppLayout.vue
- ExampleTest
- console.php
- Login.vue
- Dashboard/Index.vue

## God Nodes (most connected - your core abstractions)
1. `User` - 13 edges
2. `Role` - 9 edges
3. `require-dev` - 9 edges
4. `scripts` - 9 edges
5. `Branch` - 8 edges
6. `setup` - 7 edges
7. `LoginController` - 6 edges
8. `BranchController` - 6 edges
9. `AppServiceProvider` - 5 edges
10. `require` - 5 edges

## Surprising Connections (you probably didn't know these)
- `ExampleTest` --inherits--> `TestCase`  [EXTRACTED]
  tests/Feature/ExampleTest.php → tests/TestCase.php

## Import Cycles
- None detected.

## Communities (30 total, 5 thin omitted)

### Community 0 - "Illuminate\Http\Request"
Cohesion: 0.11
Nodes (16): LoginController, BranchController, Controller, HandleInertiaRequests, Branch, Controller, Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions (+8 more)

### Community 1 - "composer.json"
Cohesion: 0.07
Nodes (26): autoload, autoload-dev, psr-4, psr-4, description, extra, laravel, keywords (+18 more)

### Community 2 - "scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 3 - "User"
Cohesion: 0.12
Nodes (10): User, Illuminate\Database\Eloquent\Attributes\Fillable, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Relations\BelongsTo, Illuminate\Foundation\Auth\User, Illuminate\Foundation\Testing\TestCase, Illuminate\Notifications\Notifiable, ExampleTest (+2 more)

### Community 4 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.14
Nodes (3): Illuminate\Database\Migrations\Migration, Illuminate\Database\Schema\Blueprint, Illuminate\Support\Facades\Schema

### Community 5 - "package.json"
Cohesion: 0.11
Nodes (18): axios, @inertiajs/vue3, @laravel/multiplex, dependencies, axios, @inertiajs/vue3, @vitejs/plugin-vue, vue (+10 more)

### Community 6 - "Role"
Cohesion: 0.18
Nodes (9): Role, DatabaseSeeder, RoleSeeder, UserSeeder, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\Relations\HasMany, Illuminate\Database\Seeder (+1 more)

### Community 7 - "Branches/Index.vue"
Cohesion: 0.12
Nodes (10): closeModal(), deletingBranch, editingId, form, isEditing, props, saveBranch(), searchQuery (+2 more)

### Community 8 - "devDependencies"
Cohesion: 0.18
Nodes (11): concurrently, laravel-vite-plugin, devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite (+3 more)

### Community 9 - "UserFactory.php"
Cohesion: 0.22
Nodes (5): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Str, Pdo\Mysql, static

### Community 10 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/pail, laravel/pao, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision (+1 more)

### Community 11 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 13 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 14 - "AppLayout.vue"
Cohesion: 0.40
Nodes (3): currentUrl, page, user

## Knowledge Gaps
- **77 isolated node(s):** `Controller`, `description`, `dont-discover`, `license`, `minimum-stability` (+72 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 138 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **5 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Branch` connect `Illuminate\Http\Request` to `Role`?**
  _High betweenness centrality (0.046) - this node is a cross-community bridge._
- **Why does `scripts` connect `scripts` to `composer.json`?**
  _High betweenness centrality (0.039) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `UserFactory.php`, `Role`?**
  _High betweenness centrality (0.035) - this node is a cross-community bridge._
- **What connects `Controller`, `description`, `dont-discover` to the rest of the system?**
  _77 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Illuminate\Http\Request` be split into smaller, more focused modules?**
  _Cohesion score 0.11290322580645161 - nodes in this community are weakly interconnected._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.07407407407407407 - nodes in this community are weakly interconnected._
- **Should `scripts` be split into smaller, more focused modules?**
  _Cohesion score 0.08 - nodes in this community are weakly interconnected._