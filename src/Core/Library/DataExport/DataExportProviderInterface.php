<?php

namespace WS\Core\Library\DataExport;

interface DataExportProviderInterface
{
    public function getFormat(): string;

    public function export(DataExport $data): string;

    public function headers(): array;
}
