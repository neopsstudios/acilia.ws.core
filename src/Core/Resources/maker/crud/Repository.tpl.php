<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use <?= $entity_full_class_name ?>;
use WS\Core\Library\CRUD\AbstractRepository;
<?php if ($publishing_fields): ?>
use WS\Core\Library\Publishing\PublishingRepositoryTrait;
<?php endif ?>

/**
* @method <?= $entity_class_name ?>|null find($id, $lockMode = null, $lockVersion = null)
* @method <?= $entity_class_name ?>|null findOneBy(array $criteria, array $orderBy = null)
* @method <?= $entity_class_name ?>[]    findAll()
* @method <?= $entity_class_name ?>[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
*/
class <?= $class_name ?> extends AbstractRepository<?= "\n" ?>
{
<?php if ($publishing_fields): ?>
    use PublishingRepositoryTrait;
<?php endif ?>

    public function getEntityClass(): string
    {
        return <?= $entity_class_name ?>::class;
    }

    public function getFilterFields()
    {
        return [<?php array_walk($filter_fields, function(&$x) {$x = "'$x'";}); echo implode(', ', $filter_fields); ?>];
    }
}
