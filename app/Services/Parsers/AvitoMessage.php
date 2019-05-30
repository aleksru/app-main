<?php


namespace App\Services\Parsers;
use Symfony\Component\DomCrawler\Crawler;

class AvitoMessage
{
    private $parser;

    /**
     * @param string $text
     */
    public function setString(string $text) : void
    {
        $this->getParser()->clear();
        $text = str_replace('\n', '', $text);
        $this->getParser()->add($text);
    }

    /**
     * @return Crawler
     */
    protected function getParser() : Crawler
    {
        if(!$this->parser) {
            $this->parser = new Crawler();
        }

        return $this->parser;
    }

    /**
     * @return array
     */
    public function parsePhoneNumber() : array
    {
        $numb = [];
        $this->getParser()->filter('.htdg-column-per-100 table td')->each(function (Crawler $node) use (&$numb){
            if(preg_match('/((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}/',
                str_replace(' ', '', $node->text()),
                $res)){
                $numb[] = $res[0];
            }
        });

        return $numb;
    }

    /**
     * @return string
     */
    public function parseProduct() : string
    {
        $prod = '';
        $this->getParser()->filter('.htdg-column-per-100 table td div')->each(function (Crawler $node) use (&$prod){
            if(preg_match('/^Новое сообщение по объявлению/', trim($node->text()))){
                $prod = trim($node->text());
            }
        });

        return $prod;
    }
}