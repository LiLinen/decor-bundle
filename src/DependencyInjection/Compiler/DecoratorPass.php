<?php

declare(strict_types=1);

namespace LiLinen\DecorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DecoratorPass implements CompilerPassInterface
{
    private const COLLECTION = 'lilinen_decor.decoration.collection';
    private const TAG = 'lilinen_decor.decorator';

    public function process(ContainerBuilder $container)
    {
        if ($container->has(self::COLLECTION) === false) {
            return;
        }

        $definition = $container->findDefinition(self::COLLECTION);

        $taggedServices = $container->findTaggedServiceIds(self::TAG);
        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addDecorator', [new Reference($id)]);
        }
    }
}
