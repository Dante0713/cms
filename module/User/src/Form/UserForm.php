<?php
/**
 *
 *
 * @author    sfs teams <zfsfs.team@gmail.com>
 * @copyright 2010-2017 (http://www.sfs.tw)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://www.sfs.tw
 * Date: 2017/8/10
 * Time: 下午 4:58
 */

namespace User\Form;


use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Form\Element\Text;
use Zend\Form\Element\Email;

class UserForm extends Form
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct('user_form');
        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'chinese_name',
            'type' => Text::class,
            'options' => [
                'label' => '中文姓名',
            ],
            'attributes' => [
                'required' => true,
            ]
        ]);

        $this->add([
            'name' => 'english_name',
            'type' => Text::class,
            'options' => [
                'label' => '英文姓名',
            ],
            'attributes' => [
                'required' => true,
            ]
        ]);

        $this->add([
            'name' => 'nick_name',
            'type' => Text::class,
            'options' => [
                'label' => '平台暱稱',
            ],
            'attributes' => [
                'required' => true,
            ]
        ]);

        $this->add([
            'type' => Email::class,
            'name' => 'email',
            'options' => [
                'label' => '聯絡信箱',
            ],
            'attributes' => [
                'placeholder' => '此信箱為平台重要通知使用',
                'required' => true,
            ],
        ]);


        $this->addInputFilter();

    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
        $inputFilter->add([
            'name' => 'chinese_name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 10
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'english_name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 20
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'nick_name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 10
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
                'name' => 'email',
                'required' => true,
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 2,
                            'max' => 100,
                        ],
                    ],
                    [
                        'name' => 'EmailAddress',
                    ],
                ]
            ]
        );

    }


}