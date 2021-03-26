<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use <?= $type_full_class_name ?>;
use WS\Core\Library\CRUD\AbstractService;

class <?= $class_name ?> extends AbstractService<?= "\n" ?>
{
    public function getEntityClass(): string
    {
        return <?= $entity_class_name ?>::class;
    }

    public function getFormClass(): ?string
    {
        return <?= $entity_type_name ?>::class;
    }

    public function getSortFields(): array
    {
        return [<?php array_walk($sort_fields, function(&$x) {$x = "'$x'";}); echo implode(', ', $sort_fields); ?>];
    }

    protected function getListFields(): array
    {
        return [
            ['name' => '<?php echo $list_fields[0] ?>'],
        ];
    }
}
