<?php

namespace Admin\Form;

use Zend\Form\Element\File;
use Zend\Form\Element\Submit;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;


class UploadForm extends Form
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        $this->add([
            'name' => 'image-file',
            'type' => File::class,
            'options' => [
                'label' => '上傳檔案',
            ],
            'attributes'=>[
                'id' => 'image-file'
            ]
        ]);

        $this->add(array(
            'name' => 'submit',
            'type' => Submit::class,
            'options' => array(
                'label' => '送出',
            ),
            'attributes' => [
                'class'=> 'btn btn-primary'
            ]
        ));
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter();

        // File Input
        $fileInput = new FileInput('image-file');
        $fileInput->setRequired(true);

        // Define validators and filters as if only one file was being uploaded.
        // All files will be run through the same validators and filters
        // automatically.
        $fileInput->getValidatorChain()
            ->attachByName('filesize',      ['max' => 204800])
            ->attachByName('filemimetype',  ['mimeType' => 'image/png,image/x-png,image/jpeg'])
            ->attachByName('fileimagesize', ['maxWidth' => 300, 'maxHeight' => 300]);

        // All files will be renamed, e.g.:
        //   ./data/tmpuploads/avatar_4b3403665fea6.png,
        //   ./data/tmpuploads/avatar_5c45147660fb7.png
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            [
                'target'    => './data/tmpuploads/avatar.png',
                'randomize' => true,
            ]
        );
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }
}