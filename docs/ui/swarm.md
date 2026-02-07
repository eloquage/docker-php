---
title: Swarm
layout: default
nav_order: 5
parent: UI guide
---

# Swarm

When Swarm is not initialized, the Swarm page allows you to **Initialize** or **Join** a swarm (with join token and manager address). When Swarm is active, you can **Leave** or view **Unlock key**.

## Services

List services with name, image, mode, replicas, and ports. Create a service (image, replicas, ports, env, etc.), inspect, update (scale, image), view logs, and remove.

## Nodes

List nodes (hostname, role, availability, status). Inspect a node, update availability, or remove.

## Tasks

List tasks (service, slot, node, desired state, current state). Inspect a task for details.

## Secrets and configs

- **Secrets** — List, create (name, data), inspect, remove.
- **Configs** — List, create (name, data), inspect, remove.

Secrets and configs are used by services. The sidebar entries for Secrets and Configs are only active when Swarm is initialized.
