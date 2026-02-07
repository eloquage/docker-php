---
title: Requests
layout: default
nav_order: 3
parent: API reference
---

# Requests

All request classes extend `Eloquage\DockerPhp\Requests\DockerRequest` (Saloon) and live under `src/Requests/` by category.

## By category

| Category | Namespace | Count | Examples |
|----------|-----------|--------|----------|
| **System** | `Requests\System` | 6 | PingRequest, InfoRequest, VersionRequest, SystemDfRequest, GetEventsRequest, SystemPruneRequest |
| **Containers** | `Requests\Containers` | 26 | ListContainersRequest, CreateContainerRequest, InspectContainerRequest, ContainerLogsRequest, … |
| **Images** | `Requests\Images` | 12 | ListImagesRequest, CreateImageRequest, InspectImageRequest, TagImageRequest, PruneImagesRequest, … |
| **Networks** | `Requests\Networks` | 7 | ListNetworksRequest, CreateNetworkRequest, InspectNetworkRequest, … |
| **Volumes** | `Requests\Volumes` | 5 | ListVolumesRequest, CreateVolumeRequest, InspectVolumeRequest, PruneVolumesRequest, … |
| **Swarm** | `Requests\Swarm` | 7 | GetSwarmRequest, InitSwarmRequest, JoinSwarmRequest, LeaveSwarmRequest, … |
| **Services** | `Requests\Services` | 6 | ListServicesRequest, CreateServiceRequest, InspectServiceRequest, ServiceLogsRequest, … |
| **Nodes** | `Requests\Nodes` | 4 | ListNodesRequest, InspectNodeRequest, UpdateNodeRequest, DeleteNodeRequest |
| **Tasks** | `Requests\Tasks` | 2 | ListTasksRequest, InspectTaskRequest |
| **Secrets** | `Requests\Secrets` | 5 | ListSecretsRequest, CreateSecretRequest, InspectSecretRequest, … |
| **Configs** | `Requests\Configs` | 5 | ListConfigsRequest, CreateConfigRequest, InspectConfigRequest, … |
| **Plugins** | `Requests\Plugins` | 11 | ListPluginsRequest, InspectPluginRequest, EnablePluginRequest, … |
| **Build** | `Requests\Build` | 3 | BuildRequest, BuildPruneRequest, CommitRequest |
| **Exec** | `Requests\Exec` | 3 | ExecInspectRequest, ExecStartRequest, ExecResizeRequest |
| **Distribution** | `Requests\Distribution` | 1 | InspectDistributionRequest |
| **Auth** | `Requests\Auth` | 1 | AuthRequest |

Each request defines the HTTP method and endpoint path. Optional query parameters and body are passed via the constructor or resource methods. See `src/Resources/` and `src/Requests/` in the package source for full details.
