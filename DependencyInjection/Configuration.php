<?php

namespace JT\MailBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\IntegerNode;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jt_mail');

        $rootNode
            ->children()
                ->arrayNode('header')
                    ->children()
                        ->scalarNode('template')->isRequired()->end()
                        ->arrayNode('parameters')
                        ->defaultValue([])
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('key')->end()
                                    ->scalarNode('value')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('footer')
                    ->children()
                        ->scalarNode('template')->isRequired()->end()
                        ->arrayNode('parameters')
                        ->defaultValue([])
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('key')->end()
                                    ->scalarNode('value')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('pre_mailer')
                    ->addDefaultsIfNotSet()
				    ->children()
					   ->enumNode('adapter')
					       ->defaultValue('hpricot')
					       ->values(array('hpricot', 'nokogiri'))
					       ->info('Which document handler to use')
					   ->end()
					   ->scalarNode('base_url')
					       ->info('Base URL for converting relative links')
					   ->end()
					   ->integerNode('line_length')
					       ->defaultValue(65)
					       ->info('Length of lines in the plain text version')
					   ->end()
					   ->scalarNode('link_query_string')
					       ->info('Query string appended to links')
					   ->end()
					   ->booleanNode('preserve_styles')
					       ->defaultTrue()
					       ->info('Whether to preserve any ```link rel=stylesheet``` and ```style``` elements')
					   ->end()
					   ->booleanNode('remove_ids')
					       ->defaultFalse()
					       ->info('Remove IDs from the HTML document?')
					   ->end()
					   ->booleanNode('remove_classes')
					       ->defaultFalse()
					       ->info('Remove classes from the HTML document?')
					   ->end()
					   ->booleanNode('remove_comments')
					       ->defaultFalse()
					       ->info('Remove comments from the HTML document?')
					   ->end()
					   ->booleanNode('generate_text')
					       ->defaultTrue()
					       ->info('Generate a text document from the generated HTML.')
					->end()
				->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

