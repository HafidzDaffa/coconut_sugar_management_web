# Graph Report - coconut_sugar_management_web  (2026-08-31)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 245 nodes · 270 edges · 31 communities (14 shown, 6 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `a41e32e5`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Illuminate\Http\Request
- composer.json
- scripts
- User
- package.json
- 0001_01_01_000000_create_users_table.php
- Cabang/Index.vue
- devDependencies
- require-dev
- config
- AppServiceProvider
- TestCase
- bootstrap/app.php
- Illuminate\Support\Str
- logging.php
- AppLayout.vue
- ExampleTest
- console.php
- Login.vue
- Dashboard/Index.vue

## God Nodes (most connected - your core abstractions)
1. `User` - 9 edges
2. `scripts` - 9 edges
3. `require-dev` - 9 edges
4. `Cabang` - 8 edges
5. `setup` - 7 edges
6. `CabangController` - 6 edges
7. `LoginController` - 5 edges
8. `AppServiceProvider` - 5 edges
9. `UserFactory` - 5 edges
10. `require` - 5 edges

## Surprising Connections (you probably didn't know these)
- `LoginController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/LoginController.php → app/Http/Controllers/Controller.php
- `ExampleTest` --inherits--> `TestCase`  [EXTRACTED]
  tests/Feature/ExampleTest.php → tests/TestCase.php

## Import Cycles
- None detected.

## Communities (31 total, 6 thin omitted)

### Community 0 - "Illuminate\Http\Request"
Cohesion: 0.13
Nodes (15): LoginController, CabangController, Controller, HandleInertiaRequests, Cabang, Controller, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model (+7 more)

### Community 1 - "composer.json"
Cohesion: 0.07
Nodes (26): autoload, autoload-dev, psr-4, psr-4, description, extra, laravel, keywords (+18 more)

### Community 2 - "scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 3 - "User"
Cohesion: 0.13
Nodes (12): User, UserFactory, DatabaseSeeder, UserSeeder, Illuminate\Database\Eloquent\Attributes\Fillable, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Database\Seeder (+4 more)

### Community 4 - "package.json"
Cohesion: 0.11
Nodes (18): axios, @inertiajs/vue3, @laravel/multiplex, dependencies, axios, @inertiajs/vue3, @vitejs/plugin-vue, vue (+10 more)

### Community 5 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.16
Nodes (3): Illuminate\Database\Migrations\Migration, Illuminate\Database\Schema\Blueprint, Illuminate\Support\Facades\Schema

### Community 6 - "Cabang/Index.vue"
Cohesion: 0.12
Nodes (10): closeModal(), deletingCabang, editingId, form, isEditing, props, saveCabang(), searchQuery (+2 more)

### Community 7 - "devDependencies"
Cohesion: 0.18
Nodes (11): concurrently, laravel-vite-plugin, devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite (+3 more)

### Community 8 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/pail, laravel/pao, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision (+1 more)

### Community 9 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 11 - "TestCase"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Testing\TestCase, ExampleTest, TestCase

### Community 12 - "bootstrap/app.php"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware

### Community 14 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 15 - "AppLayout.vue"
Cohesion: 0.40
Nodes (3): currentUrl, page, user

## Knowledge Gaps
- **76 isolated node(s):** `description`, `dont-discover`, `license`, `minimum-stability`, `name` (+71 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 135 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **6 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `scripts` connect `scripts` to `composer.json`?**
  _High betweenness centrality (0.045) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `Illuminate\Http\Request`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **What connects `description`, `dont-discover`, `license` to the rest of the system?**
  _76 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Illuminate\Http\Request` be split into smaller, more focused modules?**
  _Cohesion score 0.12643678160919541 - nodes in this community are weakly interconnected._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.07407407407407407 - nodes in this community are weakly interconnected._
- **Should `scripts` be split into smaller, more focused modules?**
  _Cohesion score 0.08 - nodes in this community are weakly interconnected._
- **Should `User` be split into smaller, more focused modules?**
  _Cohesion score 0.12554112554112554 - nodes in this community are weakly interconnected._