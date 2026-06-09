<?php
namespace NanoPHP\Core;

interface DataSourceInterface {
    public function getAll(string $resource, array $params = []): array;
    public function get(string $resource, string $id): ?array;
}
