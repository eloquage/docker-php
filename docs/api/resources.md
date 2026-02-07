---
title: Resources
layout: default
nav_order: 2
parent: API reference
---

# Resources

Resources are accessed via `DockerPhp::resourceName()` and expose methods that send Saloon requests. All extend `BaseResource` and use the shared connector.

| Resource | Class | Main methods |
|----------|--------|---------------|
| **System** | `SystemResource` | `ping()`, `info()`, `version()`, `events()`, `df()`, `prune()` |
| **Containers** | `ContainersResource` | `list()`, `create()`, `inspect()`, `start()`, `stop()`, `restart()`, `kill()`, `pause()`, `unpause()`, `logs()`, `stats()`, `top()`, `changes()`, `exec()`, `prune()`, … |
| **Images** | `ImagesResource` | `list()`, `create()`, `search()`, `inspect()`, `history()`, `tag()`, `push()`, `delete()`, `load()`, `prune()` |
| **Networks** | `NetworksResource` | `list()`, `create()`, `inspect()`, `delete()`, `connect()`, `disconnect()`, `prune()` |
| **Volumes** | `VolumesResource` | `list()`, `create()`, `inspect()`, `delete()`, `prune()` |
| **Auth** | `AuthResource` | `auth()` |
| **Build** | `BuildResource` | `build()`, `prune()` |
| **Exec** | `ExecResource` | `inspect()`, `start()`, `resize()` |
| **Swarm** | `SwarmResource` | `get()`, `init()`, `join()`, `leave()`, `update()`, `unlockKey()`, `unlock()` |
| **Services** | `ServicesResource` | `list()`, `create()`, `inspect()`, `delete()`, `update()`, `logs()` |
| **Nodes** | `NodesResource` | `list()`, `inspect()`, `delete()`, `update()` |
| **Tasks** | `TasksResource` | `list()`, `inspect()` |
| **Secrets** | `SecretsResource` | `list()`, `create()`, `inspect()`, `delete()`, `update()` |
| **Configs** | `ConfigsResource` | `list()`, `create()`, `inspect()`, `delete()`, `update()` |
| **Plugins** | `PluginsResource` | `list()`, `pull()`, `create()`, `inspect()`, `enable()`, `disable()`, `upgrade()`, … |
| **Distribution** | `DistributionResource` | `inspect()` |

Method signatures and parameters follow the Docker Engine API v1.53. Use your IDE or the source under `src/Resources/` for full signatures.
