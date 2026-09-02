# Graph Report - coconut_sugar_management_web  (2026-09-02)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 325 nodes · 406 edges · 30 communities (14 shown, 5 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `966c2901`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- User
- Illuminate\Http\Request
- composer.json
- Users/Index.vue
- package.json
- scripts
- Illuminate\Database\Migrations\Migration
- Branches/Index.vue
- UserFactory.php
- config
- Illuminate\Database\Seeder
- AppServiceProvider
- logging.php
- TestCase
- AppLayout.vue
- ExampleTest
- console.php
- Login.vue
- Dashboard/Index.vue

## God Nodes (most connected - your core abstractions)
1. `User` - 27 edges
2. `UserManagementTest` - 17 edges
3. `require-dev` - 9 edges
4. `scripts` - 9 edges
5. `Branch` - 8 edges
6. `setup` - 7 edges
7. `Role` - 6 edges
8. `RoleTest` - 6 edges
9. `BranchController` - 6 edges
10. `UserController` - 6 edges

## Surprising Connections (you probably didn't know these)
- `UserManagementTest` --references--> `User`  [EXTRACTED]
  tests/Feature/UserManagementTest.php → app/Models/User.php
- `ExampleTest` --inherits--> `TestCase`  [EXTRACTED]
  tests/Feature/ExampleTest.php → tests/TestCase.php

## Import Cycles
- None detected.

## Communities (30 total, 5 thin omitted)

### Community 0 - "User"
Cohesion: 0.07
Nodes (17): Role, User, Branch, UserSeeder, Illuminate\Database\Eloquent\Attributes\Fillable, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model (+9 more)

### Community 1 - "Illuminate\Http\Request"
Cohesion: 0.10
Nodes (17): LoginController, BranchController, Controller, UserController, HandleInertiaRequests, Branch, Controller, Illuminate\Foundation\Application (+9 more)

### Community 2 - "composer.json"
Cohesion: 0.06
Nodes (35): autoload, autoload-dev, psr-4, psr-4, description, extra, laravel, keywords (+27 more)

### Community 3 - "Users/Index.vue"
Cohesion: 0.06
Nodes (27): activeUsers, applyFilters(), authUser, closeDeleteModal(), closeModal(), deleteUser(), deletingUser, editingId (+19 more)

### Community 4 - "package.json"
Cohesion: 0.07
Nodes (29): axios, concurrently, @inertiajs/vue3, @laravel/multiplex, laravel-vite-plugin, dependencies, axios, @inertiajs/vue3 (+21 more)

### Community 5 - "scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 6 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.13
Nodes (3): Illuminate\Database\Migrations\Migration, Illuminate\Database\Schema\Blueprint, Illuminate\Support\Facades\Schema

### Community 7 - "Branches/Index.vue"
Cohesion: 0.12
Nodes (10): closeModal(), deletingBranch, editingId, form, isEditing, props, saveBranch(), searchQuery (+2 more)

### Community 8 - "UserFactory.php"
Cohesion: 0.20
Nodes (6): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Facades\Hash, Illuminate\Support\Str, Pdo\Mysql, static

### Community 9 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 10 - "Illuminate\Database\Seeder"
Cohesion: 0.38
Nodes (3): DatabaseSeeder, RoleSeeder, Illuminate\Database\Seeder

### Community 12 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 13 - "TestCase"
Cohesion: 0.50
Nodes (3): Illuminate\Foundation\Testing\TestCase, ExampleTest, TestCase

### Community 14 - "AppLayout.vue"
Cohesion: 0.40
Nodes (3): currentUrl, page, user

## Knowledge Gaps
- **98 isolated node(s):** `Controller`, `currentUrl`, `page`, `user`, `form` (+93 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 177 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **5 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `UserFactory.php`, `Illuminate\Http\Request`?**
  _High betweenness centrality (0.058) - this node is a cross-community bridge._
- **Why does `scripts` connect `scripts` to `composer.json`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **What connects `Controller`, `currentUrl`, `page` to the rest of the system?**
  _98 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `User` be split into smaller, more focused modules?**
  _Cohesion score 0.07439613526570048 - nodes in this community are weakly interconnected._
- **Should `Illuminate\Http\Request` be split into smaller, more focused modules?**
  _Cohesion score 0.1036036036036036 - nodes in this community are weakly interconnected._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.05555555555555555 - nodes in this community are weakly interconnected._
- **Should `Users/Index.vue` be split into smaller, more focused modules?**
  _Cohesion score 0.06218487394957983 - nodes in this community are weakly interconnected._