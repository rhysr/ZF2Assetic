<?php

namespace ZF2Assetic;

use Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

class ContentTypeResolverFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        if (!isset($config['zf2_assetic'])) {
            throw new InvalidArgumentException('Missing zf2assetic config key');
        }

        $resolver = new ContentTypeResolver();
        if (isset($config['zf2_assetic']['contentTypeMap'])) {
            foreach ($config['zf2_assetic']['contentTypeMap'] as $extension => $contentType) {
                $resolver->addMapping($extension, $contentType);
            }
        }
        return $resolver;
    }
}

