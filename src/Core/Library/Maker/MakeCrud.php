<?php

namespace WS\Core\Library\Maker;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Common\Inflector\Inflector;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Routing\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Validator\Validation;

class MakeCrud extends AbstractMaker
{
    private $doctrineHelper;

    public function __construct(DoctrineHelper $doctrineHelper)
    {
        $this->doctrineHelper = $doctrineHelper;
    }

    public static function getCommandName(): string
    {
        return 'ws:make:crud';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->setDescription('Creates CRUD for Doctrine entity class')
            ->addArgument('entity-class', InputArgument::REQUIRED, sprintf('The class name of the entity to create CRUD (e.g. <fg=yellow>%s</>)', Str::asClassName(Str::getRandomTerm())))
        ;

        $inputConfig->setArgumentAsNonInteractive('entity-class');
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
        $dependencies->addClassDependency(Route::class, 'router');

        $dependencies->addClassDependency(AbstractType::class, 'form');

        $dependencies->addClassDependency(Validation::class, 'validator');

        $dependencies->addClassDependency(TwigBundle::class, 'twig-bundle');

        $dependencies->addClassDependency(DoctrineBundle::class, 'orm-pack');

        $dependencies->addClassDependency(CsrfTokenManager::class, 'security-csrf');

        $dependencies->addClassDependency(ParamConverter::class, 'annotations');
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $entityClassDetails = $generator->createClassNameDetails(
            Validator::entityExists($input->getArgument('entity-class'), $this->doctrineHelper->getEntitiesForAutocomplete()),
            'Entity\\'
        );

        $entityDoctrineDetails = $this->doctrineHelper->createDoctrineDetails($entityClassDetails->getFullName());

        $fieldTypeUseStatements = [];
        $formFields = [];
        $listFields = [];

        $metadataFields = false;
        $publishingFields = false;

        $entityFormFields = $entityDoctrineDetails->getFormFields();
        foreach ($entityFormFields as $name => $fieldTypeOptions) {

            // remove internal fields
            if (in_array($name, ['domain', 'createdBy', 'modifiedAt', 'createdAt'])) {
                unset($entityFormFields[$name]);
                continue;
            } elseif (in_array($name, ['metadataTitle', 'metadataDescription', 'metadataKeywords'])) {
                unset($entityFormFields[$name]);
                $metadataFields = true;
                continue;
            } elseif (in_array($name, ['publishStatus', 'publishSince', 'publishUntil'])) {
                unset($entityFormFields[$name]);
                $publishingFields = true;
                continue;
            }

            $fieldTypeOptions = $fieldTypeOptions ?? ['type' => null, 'options_code' => null];

            if (isset($fieldTypeOptions['type'])) {
                $fieldTypeUseStatements[] = $fieldTypeOptions['type'];
                $fieldTypeOptions['type'] = Str::getShortClassName($fieldTypeOptions['type']);
            }

            $formFields[$name] = $fieldTypeOptions;
            if (null === $fieldTypeOptions['type'] && !$fieldTypeOptions['options_code']) {
                $listFields[] = $name;
            }
        }

        // repository class generation
        $repositoryClassDetails = $generator->createClassNameDetails(
            $entityClassDetails->getRelativeNameWithoutSuffix().'Repository',
            'Repository\\',
            'Repository'
        );

        $filterFields = [$listFields[0]];
        $generator->generateClass(
            $repositoryClassDetails->getFullName(),
            __DIR__.'/../../Resources/maker/crud/Repository.tpl.php',
            [
                'entity_full_class_name' => $entityClassDetails->getFullName(),
                'entity_class_name' => $entityClassDetails->getShortName(),
                'filter_fields' => $filterFields,
                'publishing_fields' => $publishingFields
            ]
        );

        // form type class generation
        $iter = 0;
        do {
            $formClassDetails = $generator->createClassNameDetails(
                $entityClassDetails->getRelativeNameWithoutSuffix().($iter ?: '').'Type',
                'Form\\CMS\\',
                'Type'
            );
            ++$iter;
        } while (class_exists($formClassDetails->getFullName()));

        sort($fieldTypeUseStatements);

        $generator->generateClass(
            $formClassDetails->getFullName(),
            __DIR__.'/../../Resources/maker/crud/FormType.tpl.php',
            [
                'bounded_full_class_name' => $entityClassDetails->getFullName(),
                'bounded_class_name' => $entityClassDetails->getShortName(),
                'form_fields' => $formFields,
                'field_type_use_statements' => $fieldTypeUseStatements,
                'metadata_fields' => $metadataFields,
                'publishing_fields' => $publishingFields

            ]
        );

        // service class generations
        $serviceClassDetails = $generator->createClassNameDetails(
            $entityClassDetails->getRelativeNameWithoutSuffix().'Service',
            'Service\\',
            'Service'
        );

        $sortFields = [$listFields[0]];
        $generator->generateClass(
            $serviceClassDetails->getFullName(),
            __DIR__.'/../../Resources/maker/crud/Service.tpl.php',
            [
                'type_full_class_name' => $formClassDetails->getFullName(),
                'entity_class_name' => $serviceClassDetails->getShortName(),
                'entity_type_name' => $formClassDetails->getShortName(),
                'sort_fields' => $sortFields
            ]
        );

        // controller class generation
        $controllerClassDetails = $generator->createClassNameDetails(
            $entityClassDetails->getRelativeNameWithoutSuffix().'Controller',
            'Controller\\CMS\\',
            'Controller'
        );

        $entityVarSingular = lcfirst(Inflector::singularize($entityClassDetails->getShortName()));

        $listFields = [$listFields[0]];
        $generator->generateController(
            $controllerClassDetails->getFullName(),
            __DIR__.'/../../Resources/maker/crud/Controller.tpl.php',
            [
                'service_class_path' => $serviceClassDetails->getFullName(),
                'service_class_name' => $serviceClassDetails->getShortName(),
                'route_path' => Str::asRoutePath($controllerClassDetails->getRelativeNameWithoutSuffix()),
                'route_prefix' => $entityVarSingular,
                'list_fields' => $listFields
            ]
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);

        $io->text(sprintf('Next: Check your new CRUD by going to <fg=yellow>%s/</>', Str::asRoutePath($controllerClassDetails->getRelativeNameWithoutSuffix())));
    }
}
