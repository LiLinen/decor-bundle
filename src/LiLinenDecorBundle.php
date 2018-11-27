<?php

declare(strict_types=1);

namespace LiLinen\DecorBundle;

use Doctrine\Common\Annotations\AnnotationRegistry;
use LiLinen\DecorBundle\DependencyInjection\Compiler\DecoratedServicePass;
use LiLinen\DecorBundle\DependencyInjection\Compiler\DecoratorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LiLinenDecorBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        AnnotationRegistry::registerLoader('class_exists');
    }

    /**
     * @inheritdoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DecoratorPass());
        $container->addCompilerPass(new DecoratedServicePass());
    }
}
