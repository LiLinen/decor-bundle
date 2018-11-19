<?php

namespace LiLinen\DecorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

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

        $abstractFactory = $container->findDefinition(self::FACTORY);

        $decoratedServices = $container->findTaggedServiceIds(self::TAG);

        foreach ($decoratedServices as $id => $tags) {
            $service = $container->findDefinition($id);

            $serviceFactory = (new Definition())
                ->setClass($abstractFactory->getClass())
                ->setArguments([
                    self::SERVICE_ARG, $abstractFactory->getArgument(self::SERVICE_ARG),
                    self::CLASS_ARG, $service->getClass(),
                ]);

            $container->setDefinition(self::FACTORY . '.' . $service->getClass(), $serviceFactory);

            $service->setFactory($serviceFactory);
        }
    }
}
