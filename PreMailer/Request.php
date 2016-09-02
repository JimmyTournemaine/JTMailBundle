<?php
namespace JT\MailBundle\PreMailer;

class Request
{
    const API_URL = 'http://premailer.dialect.ca/api/0.1/documents';

    private $configuration;
    private $options;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    public function prepare($html)
    {
        if($this->options === null){
            $this->config();
        }

        return $this->setHtml($html);
    }

    /**
     * Set options for query
     */
    protected function config()
    {
        $this->options = array(
            'adapter' => $this->configuration['adapter'],
            'line_length' => $this->configuration['line_length'],
            'preserve_styles' => $this->configuration['preserve_styles'],
            'remove_ids' => $this->configuration['remove_ids'],
            'remove_classes' => $this->configuration['remove_classes'],
            'remove_comments' => $this->configuration['remove_comments'],
        );
        if(isset($this->options['base_url'])){
            $this->options['base_url'] = $this->configuration['base_url'];
        }
        if(isset($this->options['link_query_string'])){
            $this->options['link_query_string'] = $this->configuration['link_query_string'];
        }
    }

    /**
     * Execute the query
     */
    protected function setHtml($html)
    {
        /* Clone array to add HTML */
        $options = array();
        foreach ($this->options as $key => $value){
            $options[$key] = $value;
        }
        $options['html'] = $html;

        return $options;
    }
}