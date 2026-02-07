---
title: Streaming
layout: default
nav_order: 2
parent: Advanced
---

# Streaming

## Image pull (SSE)

Pulling an image in the UI uses Server-Sent Events so the browser can show progress without polling.

```mermaid
sequenceDiagram
  participant Browser
  participant Controller
  participant Connector
  participant Docker
  Browser->>Controller: POST /docker/images/pull-stream (fromImage, tag)
  Controller->>Connector: CreateImageRequest (stream)
  Connector->>Docker: POST /images/create?fromImage=...
  Docker-->>Connector: NDJSON stream
  Connector-->>Controller: stream
  loop each line
    Controller-->>Browser: SSE data: {json}
  end
  Controller-->>Browser: data: {"status":"complete"}
  Browser->>Browser: Livewire.dispatch('images-updated')
```

- The **ImagePullStreamController** validates input, builds a connector with `timeout => 0`, and sends `CreateImageRequest`. It reads the response body line-by-line (NDJSON), sends each line as an SSE `data:` event, then sends a final `{"status":"complete"}`. On error it sends one SSE event with `error`.
- The UI uses `fetch()` with the request body, then reads the response stream and parses SSE to update progress and status. Cancel uses `AbortController`.

## Container logs

Container logs use Docker’s multiplexed stream format (8-byte header: 1 byte stream type, 3 padding, 4 bytes size). The **StreamDecoder** in `src/Support/StreamDecoder.php` decodes frames into stdout/stderr. The UI or your code can use this to display logs correctly.
