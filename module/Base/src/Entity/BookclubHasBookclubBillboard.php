<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 3.0.3 (doctrine2-zf2inputfilterannotation) on 2018-04-19 02:38:07.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace Base\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Base\Entity\BookclubHasBookclubBillboard
 *
 * @ORM\Entity(repositoryClass="BookclubHasBookclubBillboardRepository")
 * @ORM\Table(name="bookclub_has_bookclub_billboard", indexes={@ORM\Index(name="fk_book_club_has_book_club_billboard_book_club_billboard1_idx", columns={"bookclub_billboard_id"}), @ORM\Index(name="fk_book_club_has_book_club_billboard_book_club1_idx", columns={"bookclub_id"})})
 */
class BookclubHasBookclubBillboard implements InputFilterAwareInterface
{
    /**
     * Instance of InputFilterInterface.
     *
     * @var InputFilter
     */
    private $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $bookclub_id;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $bookclub_billboard_id;

    /**
     * @ORM\ManyToOne(targetEntity="Bookclub", inversedBy="bookclubHasBookclubBillboards")
     * @ORM\JoinColumn(name="bookclub_id", referencedColumnName="id", nullable=false)
     */
    protected $bookclub;

    /**
     * @ORM\ManyToOne(targetEntity="BookclubBillboard", inversedBy="bookclubHasBookclubBillboards")
     * @ORM\JoinColumn(name="bookclub_billboard_id", referencedColumnName="id", nullable=false)
     */
    protected $bookclubBillboard;

    public function __construct()
    {
    }

    /**
     * Set the value of bookclub_id.
     *
     * @param integer $bookclub_id
     * @return \Base\Entity\BookclubHasBookclubBillboard
     */
    public function setBookclubId($bookclub_id)
    {
        $this->bookclub_id = $bookclub_id;

        return $this;
    }

    /**
     * Get the value of bookclub_id.
     *
     * @return integer
     */
    public function getBookclubId()
    {
        return $this->bookclub_id;
    }

    /**
     * Set the value of bookclub_billboard_id.
     *
     * @param integer $bookclub_billboard_id
     * @return \Base\Entity\BookclubHasBookclubBillboard
     */
    public function setBookclubBillboardId($bookclub_billboard_id)
    {
        $this->bookclub_billboard_id = $bookclub_billboard_id;

        return $this;
    }

    /**
     * Get the value of bookclub_billboard_id.
     *
     * @return integer
     */
    public function getBookclubBillboardId()
    {
        return $this->bookclub_billboard_id;
    }

    /**
     * Set Bookclub entity (many to one).
     *
     * @param \Base\Entity\Bookclub $bookclub
     * @return \Base\Entity\BookclubHasBookclubBillboard
     */
    public function setBookclub(Bookclub $bookclub = null)
    {
        $this->bookclub = $bookclub;

        return $this;
    }

    /**
     * Get Bookclub entity (many to one).
     *
     * @return \Base\Entity\Bookclub
     */
    public function getBookclub()
    {
        return $this->bookclub;
    }

    /**
     * Set BookclubBillboard entity (many to one).
     *
     * @param \Base\Entity\BookclubBillboard $bookclubBillboard
     * @return \Base\Entity\BookclubHasBookclubBillboard
     */
    public function setBookclubBillboard(BookclubBillboard $bookclubBillboard = null)
    {
        $this->bookclubBillboard = $bookclubBillboard;

        return $this;
    }

    /**
     * Get BookclubBillboard entity (many to one).
     *
     * @return \Base\Entity\BookclubBillboard
     */
    public function getBookclubBillboard()
    {
        return $this->bookclubBillboard;
    }

    /**
     * Not used, Only defined to be compatible with InputFilterAwareInterface.
     * 
     * @param \Zend\InputFilter\InputFilterInterface $inputFilter
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used.");
    }

    /**
     * Return a for this entity configured input filter instance.
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        if ($this->inputFilter instanceof InputFilterInterface) {
            return $this->inputFilter;
        }
        $factory = new InputFactory();
        $filters = array(
            array(
                'name' => 'bookclub_id',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'bookclub_billboard_id',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
        );
        $this->inputFilter = $factory->createInputFilter($filters);

        return $this->inputFilter;
    }

    /**
     * Populate entity with the given data.
     * The set* method will be used to set the data.
     *
     * @param array $data
     * @return boolean
     */
    public function populate(array $data = array())
    {
        foreach ($data as $field => $value) {
            $setter = sprintf('set%s', ucfirst(
                str_replace(' ', '', ucwords(str_replace('_', ' ', $field)))
            ));
            if (method_exists($this, $setter)) {
                $this->{$setter}($value);
            }
        }

        return true;
    }

    /**
     * Return a array with all fields and data.
     * Default the relations will be ignored.
     * 
     * @param array $fields
     * @return array
     */
    public function getArrayCopy(array $fields = array())
    {
        $dataFields = array('bookclub_id', 'bookclub_billboard_id');
        $relationFields = array('bookclub', 'bookclubBillboard');
        $copiedFields = array();
        foreach ($relationFields as $relationField) {
            $map = null;
            if (array_key_exists($relationField, $fields)) {
                $map = $fields[$relationField];
                $fields[] = $relationField;
                unset($fields[$relationField]);
            }
            if (!in_array($relationField, $fields)) {
                continue;
            }
            $getter = sprintf('get%s', ucfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $relationField)))));
            $relationEntity = $this->{$getter}();
            $copiedFields[$relationField] = (!is_null($map))
                ? $relationEntity->getArrayCopy($map)
                : $relationEntity->getArrayCopy();
            $fields = array_diff($fields, array($relationField));
        }
        foreach ($dataFields as $dataField) {
            if (!in_array($dataField, $fields) && !empty($fields)) {
                continue;
            }
            $getter = sprintf('get%s', ucfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $dataField)))));
            $copiedFields[$dataField] = $this->{$getter}();
        }

        return $copiedFields;
    }

    public function __sleep()
    {
        return array('bookclub_id', 'bookclub_billboard_id');
    }
}