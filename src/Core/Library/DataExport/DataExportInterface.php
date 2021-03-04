<?php

namespace WS\Core\Library\DataExport;

interface DataExportInterface
{
    public function getDataExport(string $search = '', ?array $filter = null, string $sort = '', string $dir = ''): DataExport;
}
