---
title: Images
layout: default
nav_order: 3
parent: UI guide
---

# Images

## List

Lists images with repo tags, size, and architecture/OS. You can search by tag name. Actions:

- **Pull** — Opens the pull modal (image name, tag, platform). Pull runs via SSE for real-time progress (see [Streaming](advanced/streaming.md)).
- **Tag** — Opens a modal to add a new repo:tag to the image.
- **Inspect** — Opens the image inspect page.
- **Remove** — Delete the image (with optional force).

## Pull and progress

Click **Pull**, enter image (e.g. `nginx`) and optional tag/platform, then **Pull**. The UI uses Server-Sent Events: the browser calls `POST /docker/images/pull-stream`, and the server streams NDJSON from the Docker API as SSE events. Alpine.js updates progress bars and status text. On completion, `images-updated` is dispatched and the list refreshes.

## Search Docker Hub

The **Search Hub** button opens a modal. Enter a search term; the UI calls `GET /images/search` and shows name, description, star count, and official badge. Click **Pull** on a result to pre-fill the pull modal.

## Inspect and history

The image inspect page shows overview (ID, architecture, OS, size, created, repoTags), config (Cmd, Entrypoint, Env, etc.), RootFS layers, labels, and a **History** table (layer ID, created, size, createdBy). Data is loaded from `inspect` and `history` API calls; both are cached when caching is enabled.

## Prune

The **Prune** button calls the images prune API. The UI shows how many images were deleted and how much space was reclaimed.
