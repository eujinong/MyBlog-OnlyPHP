<?php
namespace Classes\Contact;

class Contact
{
    private $name;
    private $email;
    private $text;

    /**
     * Contact constructor.
     * @param $name
     * @param $email
     * @param $text
     */
    public function __construct($name='', $email='', $text='')
    {
        $this->name = $name;
        $this->email = $email;
        $this->text = $text;
    }


    public function send()
    {

    }
}