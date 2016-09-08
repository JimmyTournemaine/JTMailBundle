<?php

namespace JT\MailBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class JTMailExtension extends Extension
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

        /* From email settings */
        $mailerDefinition = $container->getDefinition('jt_mail.mailer');
        if(isset($config['from'])){
            $from = $config['from'];
            $mailerDefinition->addMethodCall('setFrom', array($from['address'], $from['name']));
        }
        /* Header settings */
        if(isset($config['header'])){
            $header = $config['header'];
            $template = $header['template'];
            $parameters = array();
            foreach ($header['parameters'] as $parameter){
                $parameters[$parameter['key']] = $parameter['value'];
            }
            $mailerDefinition->addMethodCall('setHeaderTemplate', array($template, $parameters));
        }
        /* Footer settings */
        if(isset($config['footer'])){
            $header = $config['footer'];
            $template = $header['template'];
            $parameters = array();
            foreach ($header['parameters'] as $parameter){
                $parameters[$parameter['key']] = $parameter['value'];
            }
            $mailerDefinition->addMethodCall('setFooterTemplate', array($template, $parameters));
        }

        /* PreMailer settings */
		if(isset($config['pre_mailer'])) {
		    $generateText = $config['pre_mailer']['generate_text'];
		    unset($config['pre_mailer']['generate_text']);
		    $container->setParameter('jt_mail.pre_mailer.generate_text', $generateText);
			$container->setParameter('jt_mail.pre_mailer.options', $config['pre_mailer']);
			$loader->load('premailer.yml');
		}
    }
}
