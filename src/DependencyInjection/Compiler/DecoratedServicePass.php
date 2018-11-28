<?php

declare(strict_types=1);

namespace LiLinen\DecorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DecoratedServicePass implements CompilerPassInterface
{
    private const TAG = 'decorated';
    private const FACTORY = 'lilinen_decor.factory';
    private const SERVICE_ARG = '$decorator';
    private const CLASS_ARG = '$class';

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has(self::FACTORY) === false) {
            return;
        }

        $decoratedServices = $container->findTaggedServiceIds(self::TAG);

        foreach ($decoratedServices as $id => $tags) {
            $service = $container->findDefinition($id);

            $serviceFactoryName = self::FACTORY . '.' . $service->getClass();

            $serviceFactory = (new ChildDefinition(self::FACTORY))
                ->setAutowired(true)
                ->setPublic(false)
                ->setArgument('$class', $service->getClass());

            $container->setDefinition($serviceFactoryName, $serviceFactory);

            $service->setFactory([new Reference($serviceFactoryName), 'create']);
        }
    }
}
