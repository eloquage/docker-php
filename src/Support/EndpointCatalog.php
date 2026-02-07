<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Support;

/**
 * Docker Engine API v1.53 endpoint reference.
 * Paths are relative to the versioned base (e.g. /v1.53).
 *
 * @see https://docs.docker.com/reference/api/engine/version/v1.53/
 */
final class EndpointCatalog
{
    public const PING = 'GET /_ping';

    public const EVENTS = 'GET /events';

    public const INFO = 'GET /info';

    public const VERSION = 'GET /version';

    public const SYSTEM_DF = 'GET /system/df';

    public const SYSTEM_PRUNE = 'POST /system/prune';

    public const AUTH = 'POST /auth';

    public const BUILD = 'POST /build';

    public const BUILD_PRUNE = 'POST /build/prune';

    public const COMMIT = 'POST /commit';

    public const CONTAINERS_LIST = 'GET /containers/json';

    public const CONTAINERS_CREATE = 'POST /containers/create';

    public const CONTAINERS_INSPECT = 'GET /containers/{id}/json';

    public const CONTAINERS_TOP = 'GET /containers/{id}/top';

    public const CONTAINERS_LOGS = 'GET /containers/{id}/logs';

    public const CONTAINERS_CHANGES = 'GET /containers/{id}/changes';

    public const CONTAINERS_EXPORT = 'GET /containers/{id}/export';

    public const CONTAINERS_STATS = 'GET /containers/{id}/stats';

    public const CONTAINERS_RESIZE = 'POST /containers/{id}/resize';

    public const CONTAINERS_START = 'POST /containers/{id}/start';

    public const CONTAINERS_STOP = 'POST /containers/{id}/stop';

    public const CONTAINERS_RESTART = 'POST /containers/{id}/restart';

    public const CONTAINERS_KILL = 'POST /containers/{id}/kill';

    public const CONTAINERS_UPDATE = 'POST /containers/{id}/update';

    public const CONTAINERS_RENAME = 'POST /containers/{id}/rename';

    public const CONTAINERS_PAUSE = 'POST /containers/{id}/pause';

    public const CONTAINERS_UNPAUSE = 'POST /containers/{id}/unpause';

    public const CONTAINERS_ATTACH = 'POST /containers/{id}/attach';

    public const CONTAINERS_ATTACH_WS = 'GET /containers/{id}/attach/ws';

    public const CONTAINERS_WAIT = 'POST /containers/{id}/wait';

    public const CONTAINERS_DELETE = 'DELETE /containers/{id}';

    public const CONTAINERS_ARCHIVE_GET = 'GET /containers/{id}/archive';

    public const CONTAINERS_ARCHIVE_PUT = 'PUT /containers/{id}/archive';

    public const CONTAINERS_EXEC = 'POST /containers/{id}/exec';

    public const CONTAINERS_PRUNE = 'POST /containers/prune';

    public const EXEC_INSPECT = 'GET /exec/{id}/json';

    public const EXEC_START = 'POST /exec/{id}/start';

    public const EXEC_RESIZE = 'POST /exec/{id}/resize';

    public const IMAGES_LIST = 'GET /images/json';

    public const IMAGES_CREATE = 'POST /images/create';

    public const IMAGES_SEARCH = 'GET /images/search';

    public const IMAGES_INSPECT = 'GET /images/{name}/json';

    public const IMAGES_HISTORY = 'GET /images/{name}/history';

    public const IMAGES_GET = 'GET /images/{name}/get';

    public const IMAGES_TAG = 'POST /images/{name}/tag';

    public const IMAGES_PUSH = 'POST /images/{name}/push';

    public const IMAGES_DELETE = 'DELETE /images/{name}';

    public const IMAGES_LOAD = 'POST /images/load';

    public const IMAGES_PRUNE = 'POST /images/prune';

    public const NETWORKS_LIST = 'GET /networks';

    public const NETWORKS_CREATE = 'POST /networks/create';

    public const NETWORKS_INSPECT = 'GET /networks/{id}';

    public const NETWORKS_DELETE = 'DELETE /networks/{id}';

    public const NETWORKS_CONNECT = 'POST /networks/{id}/connect';

    public const NETWORKS_DISCONNECT = 'POST /networks/{id}/disconnect';

    public const NETWORKS_PRUNE = 'POST /networks/prune';

    public const VOLUMES_LIST = 'GET /volumes';

    public const VOLUMES_CREATE = 'POST /volumes/create';

    public const VOLUMES_INSPECT = 'GET /volumes/{name}';

    public const VOLUMES_DELETE = 'DELETE /volumes/{name}';

    public const VOLUMES_PRUNE = 'POST /volumes/prune';

    public const SWARM_GET = 'GET /swarm';

    public const SWARM_INIT = 'POST /swarm/init';

    public const SWARM_JOIN = 'POST /swarm/join';

    public const SWARM_LEAVE = 'POST /swarm/leave';

    public const SWARM_UPDATE = 'POST /swarm/update';

    public const SWARM_UNLOCK_KEY = 'GET /swarm/unlockkey';

    public const SWARM_UNLOCK = 'POST /swarm/unlock';

    public const SERVICES_LIST = 'GET /services';

    public const SERVICES_CREATE = 'POST /services/create';

    public const SERVICES_INSPECT = 'GET /services/{id}';

    public const SERVICES_DELETE = 'DELETE /services/{id}';

    public const SERVICES_UPDATE = 'POST /services/{id}/update';

    public const SERVICES_LOGS = 'GET /services/{id}/logs';

    public const NODES_LIST = 'GET /nodes';

    public const NODES_INSPECT = 'GET /nodes/{id}';

    public const NODES_DELETE = 'DELETE /nodes/{id}';

    public const NODES_UPDATE = 'POST /nodes/{id}/update';

    public const TASKS_LIST = 'GET /tasks';

    public const TASKS_INSPECT = 'GET /tasks/{id}';

    public const SECRETS_LIST = 'GET /secrets';

    public const SECRETS_CREATE = 'POST /secrets/create';

    public const SECRETS_INSPECT = 'GET /secrets/{id}';

    public const SECRETS_DELETE = 'DELETE /secrets/{id}';

    public const SECRETS_UPDATE = 'POST /secrets/{id}/update';

    public const CONFIGS_LIST = 'GET /configs';

    public const CONFIGS_CREATE = 'POST /configs/create';

    public const CONFIGS_INSPECT = 'GET /configs/{id}';

    public const CONFIGS_DELETE = 'DELETE /configs/{id}';

    public const CONFIGS_UPDATE = 'POST /configs/{id}/update';

    public const PLUGINS_LIST = 'GET /plugins';

    public const PLUGINS_PRIVILEGES = 'GET /plugins/privileges';

    public const PLUGINS_PULL = 'POST /plugins/pull';

    public const PLUGINS_CREATE = 'POST /plugins/create';

    public const PLUGINS_INSPECT = 'GET /plugins/{name}/json';

    public const PLUGINS_DELETE = 'DELETE /plugins/{name}';

    public const PLUGINS_ENABLE = 'POST /plugins/{name}/enable';

    public const PLUGINS_DISABLE = 'POST /plugins/{name}/disable';

    public const PLUGINS_UPGRADE = 'POST /plugins/{name}/upgrade';

    public const PLUGINS_SET = 'POST /plugins/{name}/set';

    public const PLUGINS_PUSH = 'POST /plugins/{name}/push';

    public const DISTRIBUTION_INSPECT = 'GET /distribution/{name}/json';
}
