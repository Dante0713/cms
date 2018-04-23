<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class ArticleForm extends Form
{
    /**
     * This form is used to collect user feedback data like user E-mail,
     * message subject and text.
     */
    // Constructor.
    public function __construct()
    {
        // Define form name
        parent::__construct('article_form');

        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    // This method adds elements to form (input fields and
    // submit button).
    private function addElements()
    {


        $this->add(array(
            'name' => 'article_title',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => '標題',
            )
        ));

        $this->add([
            'type' => 'Zend\Form\Element\Select',
            'name' => 'tags_name',
            'attributes' => [
                'id' => 'tags'

            ],
            'options' => [
                'label' => '發文看板',
            ],
        ]);

        $this->add(array(
            'name' => 'content',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => '文章撰寫'
            )
        ));
    }

    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
                'name'     => 'article_title',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                    ['name' => 'StripNewlines'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 128
                        ],
                    ],
                ],
            ]
        );

        $inputFilter->add([
                'name'     => 'content',
                'required' => true,
                'filters'  => [
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 4096
                        ],
                    ],
                ],
            ]
        );
    }
}