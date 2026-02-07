---
title: Dashboard
layout: default
nav_order: 1
parent: UI guide
---

# Dashboard

The dashboard loads three API calls in parallel (info, version, system df) using Saloon’s pool for concurrency.

## Content

- **System info** — Host name, CPUs, memory, kernel, OS, container/image counts
- **Version** — Docker and API version
- **Disk usage** — Images, containers, volumes, build cache (from `/system/df`)

## Prune actions

Buttons to prune:

- Containers (stopped)
- Images (dangling)
- Volumes (unused)
- Networks (unused)
- System (all of the above)

Each action calls the corresponding API and shows success or error via the notification area.
