<?php
/**
 *
 *
 * @author    sfs teams <zfsfs.team@gmail.com>
 * @copyright 2010-2018 (http://www.sfs.tw)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://www.sfs.tw
 * Date: 2018/3/15
 * Time: 下午 1:53
 */

namespace Admin\Form;


use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class SchoolForm extends Form
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct('school_form', $options);
       // $this->setAttribute('action','/admin/school/save');
        $this->add([
            'name' => 'chinese_name',
            'type' => Text::class,
            'options' => [
                'label' => '學校名稱'
            ],
            'attributes' => [
                'placeholder' => '請輸入學校名稱'
            ]
        ]);

        $this->add([
            'name' => 'english_name',
            'type' => Text::class,
            'options' => [
                'label' => '英文名稱'
            ]
        ]);

        $this->add([
            'name' => 'note',
            'type' => Textarea::class,
            'options' => [
                'label' => '備註',

            ],
            'attributes' => [
                'rows' => 10,
            ]
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
                ['name' => 'StringToLower'],
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
                'name' => 'english_name',
                'required' => true,
            ]
        );

    }

    /**
     * CREATE TABLE IF NOT EXISTS `test`.`school` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `type` INT NULL,
    `chinese_name` VARCHAR(45) NULL,
    `english_name` VARCHAR(45) NULL,
    `email` VARCHAR(200) NULL,
    `address` VARCHAR(500) NULL,
    `url` VARCHAR(200) NULL COMMENT '學校官網',
    `dialing_code` INT NULL COMMENT '電話區碼\n區碼+ 號碼 = 電話',
    `phone` INT NULL COMMENT '號碼\n區碼+ 號碼 = 電話',
    `fax` VARCHAR(45) NULL,
    `note` VARCHAR(500) NULL,
    `status` INT NULL,
    `updatetime` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC))
    ENGINE = InnoDB
     */
}