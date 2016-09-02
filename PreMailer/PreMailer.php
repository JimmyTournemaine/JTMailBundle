<?php
namespace JT\MailBundle\PreMailer;

use JT\MailBundle\Exception\PreMailingException;
use Buzz\Browser;

/**
 * SwiftMailer wrapper to add PreSendEvent
 * @author Jimmy Tournemaine <jimmy.tournemaine@yahoo.fr>
 *
 */
class PreMailer
{
    private $buzz;
    private $request;
    private $generateText;
    private $html;
    private $text;

    public function __construct(Browser $browser, Request $request, $generateText)
    {
        $this->buzz = $browser;
        $this->request = $request;
        $this->generateText = $generateText;
    }

    public function process($html)
    {
        $response = $this->buzz->post(Request::API_URL, array(), $this->request->prepare($html));
        $this->checkResponse($response, 201);
        $response = json_decode($response->getContent(), true);

        $htmlResponse = $this->buzz->get($response['documents']['html']);
        $this->checkResponse($htmlResponse);
        $this->html = $htmlResponse->getContent();

        if($this->generateText === true){
            $textResponse = $this->buzz->get($response['documents']['txt']);
            $this->checkResponse($textResponse);
            $this->text = $textResponse->getContent();
        }
    }

    private function checkResponse(\Buzz\Message\Response $buzzResponse, $statusCode = 200)
    {
        if($buzzResponse->getStatusCode() !== $statusCode){
            throw new PreMailingException($buzzResponse->getStatusCode());
        }
    }

    public function getHtml()
    {
        return $this->html;
    }

    public function getText()
    {
        return $this->text;
    }
}