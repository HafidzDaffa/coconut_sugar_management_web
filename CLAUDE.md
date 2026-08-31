# Project: Coconut Sugar Management Web

> A management web application for coconut sugar production and business operations.

---

# Tech Stack

> **Backend:** Laravel (PHP)
> **Bridge:** Inertia.js
> **Frontend:** Vue 3 (Composition API)
> **Styling:** Tailwind CSS (via Laravel default)
> **Database:** MySQL
> **DevOps:** Docker via Laravel Sail

## Architecture

- **Laravel** handles routing, controllers, middleware, auth, database (Eloquent ORM), and business logic.
- **Inertia.js** replaces the traditional API layer — controllers return Inertia responses that render Vue components directly. No need for a separate REST/GraphQL API.
- **Vue 3** with Composition API (`<script setup>`) for all frontend components.
- **Vite** as the build tool (Laravel's default asset bundler).
- **Laravel Sail** as the Docker development environment — all services (PHP, MySQL, Redis, etc.) run in containers.
- **No local PHP or Composer required** — everything runs inside Docker containers. Only Docker Desktop needs to be installed on the host machine.

## Conventions

- Use `<script setup>` syntax in all Vue components.
- Props from Laravel controllers are passed via Inertia — access with `defineProps()`.
- Shared data (auth user, flash messages) via `usePage()` from `@inertiajs/vue3`.
- Forms use `useForm()` from Inertia for submission with built-in validation error handling.
- Page components live in `resources/js/Pages/`.
- Reusable components live in `resources/js/Components/`.
- Layouts live in `resources/js/Layouts/`.
- Routes are defined in `routes/web.php` — Inertia uses server-side routing, not vue-router.

## First-time Setup (No Local PHP Needed)

> Only **Docker Desktop** must be installed on the host machine. PHP and Composer run inside temporary Docker containers.

```powershell
# Step 1 — Create Laravel project using Composer Docker image
docker run --rm -v "${PWD}:/app" -w /app composer:latest create-project laravel/laravel .

# Step 2 — Install Laravel Sail
docker run --rm -v "${PWD}:/app" -w /app composer:latest require laravel/sail --dev

# Step 3 — Publish Sail's compose.yaml (choose: mysql, redis)
docker run --rm -v "${PWD}:/app" -w /app php:8.4-cli php artisan sail:install --with=mysql,redis

# Step 4 — Install Inertia + Vue
docker run --rm -v "${PWD}:/app" -w /app composer:latest require inertiajs/inertia-laravel

# Step 5 — Start Sail (all subsequent work uses Sail)
.\vendor\bin\sail up -d

# Step 6 — Install Node dependencies & Vue
.\vendor\bin\sail npm install @inertiajs/vue3 vue @vitejs/plugin-vue

# Step 7 — Run first migration
.\vendor\bin\sail artisan migrate
```

## Key Commands

All commands run through Sail. On Windows PowerShell, set alias:
```powershell
Set-Alias sail ".\vendor\bin\sail"
```

```bash
# Docker / Sail — compose file is compose.yaml (not docker-compose.yml)
sail up -d                 # Start all containers in background
sail down                  # Stop all containers
sail build --no-cache      # Rebuild containers

# Development (run both simultaneously)
sail up -d                 # Backend
sail npm run dev           # Frontend (Vite)

# Database
sail artisan migrate                   # Run migrations
sail artisan migrate:fresh --seed      # Reset and seed database
sail mysql                             # Open MySQL CLI

# Code generation
sail artisan make:controller NameController
sail artisan make:model Name -mfs      # Model + migration + factory + seeder
sail artisan make:middleware Name

# Testing
sail artisan test          # Run PHPUnit tests
sail npm run build         # Production build

# Composer & NPM
sail composer install
sail npm install
```

## Docker Services

Defined in `compose.yaml` (managed by Sail):

| Service | Port | Description |
|---|---|---|
| `laravel.test` | `80` | PHP 8.4 application |
| `mysql` | `3306` | MySQL 8.0 database |
| `redis` | `6379` | Redis cache/queue |
| `mailpit` | `8025` | Local email testing |

---

# Frontend Design

> **name:** frontend-design
> **description:** Guidance for distinctive, intentional visual design when building new UI or reshaping an existing one. Helps with aesthetic direction, typography, and making choices that don't read as templated defaults.

## Project Color Palette

> **This is a project-specific directive that overrides generic color defaults.**
> The Coconut Sugar Management web must incorporate **blue** as its primary brand color across all UI. Every page, component, and design decision must reflect this.

### Palette

| Role | Name | Hex | Usage |
|---|---|---|---|
| **Primary** | Ocean Blue | `#1E40AF` | Buttons, active states, links, key CTAs |
| **Primary Light** | Sky Blue | `#3B82F6` | Hover states, icons, accents, badges |
| **Primary Pale** | Blue Tint | `#EFF6FF` | Backgrounds for cards, table headers, highlights |
| **Primary Dark** | Deep Navy | `#1E3A5F` | Sidebar, navbar, headers, dark mode base |
| **Neutral Dark** | Slate 900 | `#0F172A` | Body text, headings |
| **Neutral Mid** | Slate 400 | `#94A3B8` | Placeholder text, secondary labels |
| **Neutral Light** | Slate 50 | `#F8FAFC` | Page backgrounds, empty states |
| **Success** | Emerald | `#10B981` | Status: selesai, lunas, aktif |
| **Warning** | Amber | `#F59E0B` | Status: pending, proses |
| **Danger** | Rose | `#F43F5E` | Error, hapus, batal |

### Rules

- **Blue is the identity.** Every page must feel distinctively blue — not as decoration, but as structure. Navbar, sidebar, primary buttons, and active states are all blue.
- **Use `#1E40AF` for primary actions** (simpan, submit, konfirmasi). Never use generic gray or black buttons for primary CTAs.
- **Background stays light.** Use `#F8FAFC` (Slate 50) as the page background — the blue makes its statement through components, not through overwhelming the background.
- **Avoid flat blue everywhere.** Use the full range: dark navy for structure, ocean blue for interactions, sky blue for accents, and blue tint for subtle surface differentiation.
- **Tables**: header rows use `#EFF6FF` (Blue Tint), active row highlight uses `#DBEAFE`.
- **Typography on blue surfaces** must always be white (`#FFFFFF`) or very light (`#E0F2FE`). Never use dark text directly on blue backgrounds darker than `#3B82F6`.


## Approach

Approach this as the design lead at a small studio known for giving every client a visual identity that could not be mistaken for anyone else's. This client has already rejected proposals that felt templated, and is paying for a distinctive point of view: make deliberate, opinionated choices about palette, typography, and layout that are specific to this brief, and take one real aesthetic risk you can justify.

## Ground it in the subject

If the brief does not pin down what the product or subject is, pin it yourself before designing: name one concrete subject, its audience, and the page's single job, and state your choice. If there's any information in your memory about the human's preferences, context about what they're building, or designs you've made before – use that as a hint. The subject's own world, its materials, instruments, artifacts, and vernacular, is where distinctive choices come from. Build with the brief's real content and subject matter throughout.

## Design principles

For web designs, the hero is a thesis. Open with the most characteristic thing in the subject's world, in whatever form makes sense for it: a headline, an image, an animation, a live demo, an interactive moment. Be deliberate with your choice: a big number with a small label, supporting stats, and a gradient accent is the template answer, only use if that's truly the best option.

**Typography** carries the personality of the page. Pair the display and body faces deliberately, not the same families you would reach for on any other project, and set a clear type scale with intentional weights, widths, and spacing. Make the type treatment itself a memorable part of the design, not a neutral delivery vehicle for the content.

**Structure is information.** Structural devices, numbering, eyebrows, dividers, labels, should encode something true about the content, not decorate it. Many generic designs use numbered markers (01 / 02 / 03), but that's only appropriate if the content actually is a sequence - like a real process or a typed timeline where order carries information the reader needs. Question if choices like numbered markers actually make sense before incorporating them.

**Leverage motion deliberately.** Think about where and if animation can serve the subject: a page-load sequence, a scroll-triggered reveal, hover micro-interactions, ambient atmosphere. An orchestrated moment usually lands harder than scattered effects; choose what the direction calls for. However, sometimes less is more, and extra animation contributes to the feeling that the design is AI-generated.

**Match complexity to the vision.** Maximalist directions need elaborate execution; minimal directions need precision in spacing, type, and detail. Elegance is executing the chosen vision well.

**Consider written content carefully.** Often a design brief may not contain real content, and it's up to you to come up with copy. Copy can make a design feel as templated as the design itself. See the below section on writing for more guidance.

## Process: brainstorm, explore, plan, critique, build, critique again

For calibration: AI-generated design right now clusters around three looks: (1) a warm cream background (near #F4F1EA) with a high-contrast serif display and a terracotta accent; (2) a near-black background with a single bright acid-green or vermilion accent; (3) a broadsheet-style layout with hairline rules, zero border-radius, and dense newspaper-like columns. All three are legitimate for some briefs, but they are defaults rather than choices, and they appear regardless of subject. Where the brief pins down a visual direction, follow it exactly — the brief's own words always win, including when it asks for one of these looks. Where it leaves an axis free, don't spend that freedom on one of these defaults. Just like a human designer who's hired, there's often a careful balance between doing what you're good at and taking each project as a chance to experiment and learn.

Work in two passes. First, brainstorm a short design plan based on the human's design brief: create a compact token system with color, type, layout, and signature. Color: describe the palette as 4–6 named hex values. Type: the typefaces for 2+ roles (a characterful display face that's used with restraint, a complementary body face, and a utility face for captions or data if needed). Layout: a layout concept, using one-sentence prose descriptions and ASCII wireframes to ideate and compare. Signature: the single unique element this page will be remembered by that embodies the brief in an appropriate way.

Then review that plan against the brief before building: if any part of it reads like the generic default you would produce for any similar page (work through a similar prompt to see if you arrive somewhere similar) rather than a choice made for this specific brief — revise that part, say what you changed and why. Only after you've confirmed the relative uniqueness of your design plan should you start to write the code, following the revised plan exactly and deriving every color and type decision from it.

When writing the code, be careful of structuring your CSS selector specificities. It's easy to generate CSS classes that cancel each other out (especially with a type-based selector like `.section` and a element-based selector like `.cta`). This can happen often with paddings/margins between sections.

Try to do a lot of this planning and iteration in your thinking, and only show ideas to the user when you have higher confidence it'll delight them.

## Restraint and self-critique

Spend your boldness in one place. Let the signature element be the one memorable thing, keep everything around it quiet and disciplined, and cut any decoration that does not serve the brief. Not taking a risk can be a risk itself! Build to a quality floor without announcing it: responsive down to mobile, visible keyboard focus, reduced motion respected. Critique your own work as you build, taking screenshots if your environment supports it – a picture is worth 1000 tokens. Consider Chanel's advice: before leaving the house, take a look in the mirror and remove one accessory. Human creators have memory and always try to do something new, so if you have a space to quickly jot down notes about what you've tried, it can help you in future passes.

## Writing in design

Words appear in a design for one reason: to make it easier to understand, and therefore easier to use. They are design material, not decoration. Bring the same intentionality to copy that you would bring to spacing and color. Before writing anything, ask what the design needs to say, and how it can best be said to help the person navigate the experience.

Write from the end user's side of the screen. Name things by what people control and recognize, never by how the system is built. A person manages notifications, not webhook config. Describe what something does in plain terms rather than selling it. Being specific is always better than being clever.

Use active voice as default. A control should say exactly what happens when it's used: "Save changes," not "Submit." An action keeps the same name through the whole flow, so the button that says "Publish" produces a toast that says "Published." The vocabulary of an interface is the signposting for someone navigating the product. Cohesion and consistency are how people learn their way around.

Treat failure and emptiness as moments for direction, not mood. Explain what went wrong and how to fix it, in the interface's voice rather than a person's. Errors don't apologize, and they are never vague about what happened. An empty screen is an invitation to act.

Keep the register conversational and tuned: plain verbs, sentence case, no filler, with tone matched to the brand and the audience. Let each element do exactly one job. A label labels, an example demonstrates, and nothing quietly does double duty.

---

# Behavioral Guidelines

> Behavioral guidelines to reduce common LLM coding mistakes. Merge with project-specific instructions as needed.
>
> **Tradeoff:** These guidelines bias toward caution over speed. For trivial tasks, use judgment.

## 1. Think Before Coding

Don't assume. Don't hide confusion. Surface tradeoffs.

Before implementing:

- State your assumptions explicitly. If uncertain, ask.
- If multiple interpretations exist, present them — don't pick silently.
- If a simpler approach exists, say so. Push back when warranted.
- If something is unclear, stop. Name what's confusing. Ask.

## 2. Simplicity First

Minimum code that solves the problem. Nothing speculative.

- No features beyond what was asked.
- No abstractions for single-use code.
- No "flexibility" or "configurability" that wasn't requested.
- No error handling for impossible scenarios.

Ask yourself: "Would a senior engineer say this is overcomplicated?" If yes, simplify.

## 3. Surgical Changes

Touch only what you must. Clean up only your own mess.

**When editing existing code:**

- Don't "improve" adjacent code, comments, or formatting.
- Don't refactor things that aren't broken.
- Match existing style, even if you'd do it differently.
- If you notice unrelated dead code, mention it — don't delete it.

**When your changes create orphans:**

- Remove imports/variables/functions that YOUR changes made unused.
- Don't remove pre-existing dead code unless asked.

The test: Every changed line should trace directly to the user's request.

## 4. Goal-Driven Execution

Define success criteria. Loop until verified.

Transform tasks into verifiable goals:

- "Add validation" → "Write tests for invalid inputs, then make them pass"
- "Fix the bug" → "Write a test that reproduces it, then make it pass"
- "Refactor X" → "Ensure tests pass before and after"

For multi-step tasks, state a brief plan:

```
1. [Step] → verify: [check]
2. [Step] → verify: [check]
3. [Step] → verify: [check]
```

Strong success criteria let you loop independently. Weak criteria ("make it work") require constant clarification.

---

*These guidelines are working if: fewer unnecessary changes in diffs, fewer rewrites due to overcomplication, and clarifying questions come before implementation rather than after mistakes.*

---

# Git Workflow

> All code changes must go through a new branch and a pull request. Do not push directly to `master`.

## Rules

- **Create a new branch** for every change. Use the following naming format:
  - `feature/feature-name` — for new features
  - `fix/bug-name` — for bug fixes
  - `refactor/component-name` — for refactoring
  - `docs/document-name` — for documentation changes
- **Commit with a clear message** using the format: `type: short description` (e.g., `feature: add login page`)
- **Create a pull request** to the master branch (`master`) once changes are complete.
- **Do not commit directly to `master`.**

---

# Code Knowledge Graph (Graphify)

> Use Graphify to generate a knowledge graph of the codebase so AI assistants can understand project structure, relationships, and dependencies without scanning every file.

## Why

AI assistants work better when they have a structured map of the codebase rather than grepping through flat files. Graphify uses tree-sitter to parse code into a queryable knowledge graph with nodes (functions, classes, modules) and edges (calls, imports, inheritance).

## Setup

```bash
# Install (note: the PyPI package is "graphifyy" with two y's)
uv tool install graphifyy

# Register the skill with your AI assistant
graphify install
```

## Rules

- **Run Graphify after significant changes** — whenever new modules, major features, or architectural changes are added, regenerate the graph.
- **Keep the graph outputs committed** — the `graphify-out/` directory (containing `graph.json`, `GRAPH_REPORT.md`, `graph.html`) should be tracked in version control so every AI session starts with up-to-date context.
- **Use graph queries over file scanning** — when exploring how components connect, prefer `graphify query` or `graphify path` to trace relationships rather than manually reading through files.
- **Review the GRAPH_REPORT.md** — this human-readable summary of key concepts and connections serves as a quick onboarding doc for both humans and AI.
