<?php

namespace WS\Core\Library\DataExport;

interface DataExportInterface
{
    public function getDataExport(string $filter = '', string $sort = '', string $dir = ''): DataExport;
}
