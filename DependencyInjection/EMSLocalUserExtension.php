<?php

namespace EMS\LocalUserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class EMSLocalUserExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
    

    public function prepend(ContainerBuilder $container) {
    	// get all bundles
    	$bundles = $container->getParameter('kernel.bundles');

    	//preset doctrine config: specify the ems user entity
    	if (isset($bundles['DoctrineBundle'])) {
    		$container->prependExtensionConfig('doctrine', [
    			'orm' => [
    				'resolve_target_entities' => [
    					'EMS\CoreBundle\Entity\User' => 'EMS\LocalUserBundle\Entity\User'
    				]
    			],
    		]);
    	}

    	$configs = $container->getExtensionConfig('ems_core');
    	
    	$fromEmail = [
    			'address' => 'noreply@example.com',
    			'sender_name' => 'elasticms',
    	];
    	
    	if(isset($configs[0]['from_email'])){
    		$fromEmail = $configs[0]['from_email'];
    	}
    	
    	//preset fos user config for elasticms
    	if (isset($bundles['FOSUserBundle'])) {
    		$container->prependExtensionConfig('fos_user', [
    			'db_driver' => 'orm',
    			'from_email' => $fromEmail,
    			'firewall_name' => 'main',
    			'user_class' => 'EMS\LocalUserBundle\Entity\User',
    			'profile' => [
    				'form' => [
    					'type' => 'EMS\LocalUserBundle\Form\UserProfileType'
    				]
    			]
    		]);
    	}
    	
    }
}
