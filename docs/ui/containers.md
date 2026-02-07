---
title: Containers
layout: default
nav_order: 2
parent: UI guide
---

# Containers

## List

The container list shows running and optionally stopped containers. You can search/filter by name or ID. Actions per row:

- **Inspect** — Opens the container inspect page (state, config, mounts, network, etc.).
- **Logs** — Opens the logs page (streaming output).
- **Start / Stop / Restart / Kill / Pause / Unpause** — Lifecycle actions.
- **Remove** — Delete the container (with optional force).

## Inspect

Displays full container JSON: state, config (Cmd, Entrypoint, Env, ExposedPorts, etc.), mounts, network settings, and resource limits.

## Logs

Streams container stdout/stderr. The package decodes Docker’s multiplexed stream format (raw stream) into readable output.
