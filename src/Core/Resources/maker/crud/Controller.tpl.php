<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use <?= $service_class_path ?>;
use WS\Core\Library\CRUD\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("<?= $route_path ?>", name="cms_<?= $route_prefix ?>_")
 */
class <?= $class_name ?> extends AbstractController<?= "\n" ?>
{
    protected $service;

    public function __construct(<?= $service_class_name ?> $service)
    {
        $this->service = $service;
    }

    protected function getListFields(): array
    {
        return [
            ['name' => '<?php echo $list_fields[0] ?>'],
        ];
    }
}
