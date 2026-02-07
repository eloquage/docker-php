---
title: UI guide
layout: default
nav_order: 5
has_children: true
---

# UI guide

The package includes a Livewire-based management UI styled with [Flux UI](https://flux.laravel.com) and Tailwind CSS. It is available when `ui.enabled` is `true` (default) at the configured prefix (default `/docker`).

## Layout

- **Sidebar:** Navigation for Dashboard, Containers, Images, Volumes, Networks, Swarm (if initialized), Services, Nodes, Tasks, Secrets, Configs, and Plugins.
- **Main area:** The active Livewire component (dashboard, list, inspect, or logs).
- **Dark mode:** Supported via Flux; follows system or user preference where applicable.

## Sections

- [Dashboard](dashboard.md) — System info, version, disk usage, prune actions
- [Containers](containers.md) — List, inspect, logs, start/stop/restart/kill/pause/remove
- [Images](images.md) — List, pull with progress, search Hub, tag, inspect, history, prune
- [Networks and volumes](networks.md) — List, create, inspect, remove, prune
- [Swarm](swarm.md) — Initialize, join, leave, services, nodes, tasks, secrets, configs

## Middleware

Default middleware is `['web']`. To require authentication, set `ui.middleware` to `['web', 'auth']` in `config/docker-php.php`.
