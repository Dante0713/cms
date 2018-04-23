<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 3.0.3 (doctrine2-zf2inputfilterannotation) on 2018-04-19 02:38:07.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace Base\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Base\Entity\BookclubBillboard
 *
 * @ORM\Entity(repositoryClass="BookclubBillboardRepository")
 * @ORM\Table(name="bookclub_billboard")
 */
class BookclubBillboard implements InputFilterAwareInterface
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
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    protected $content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $start_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $end_date;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_online;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_limit_member_brower;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatetime;

    /**
     * @ORM\OneToMany(targetEntity="BookclubHasBookclubBillboard", mappedBy="bookclubBillboard")
     * @ORM\JoinColumn(name="id", referencedColumnName="bookclub_billboard_id", nullable=false)
     */
    protected $bookclubHasBookclubBillboards;

    public function __construct()
    {
        $this->bookclubHasBookclubBillboards = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \Base\Entity\BookclubBillboard
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of title.
     *
     * @param string $title
     * @return \Base\Entity\BookclubBillboard
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of content.
     *
     * @param string $content
     * @return \Base\Entity\BookclubBillboard
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of start_date.
     *
     * @param \DateTime $start_date
     * @return \Base\Entity\BookclubBillboard
     */
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;

        return $this;
    }

    /**
     * Get the value of start_date.
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Set the value of end_date.
     *
     * @param \DateTime $end_date
     * @return \Base\Entity\BookclubBillboard
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;

        return $this;
    }

    /**
     * Get the value of end_date.
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * Set the value of is_online.
     *
     * @param boolean $is_online
     * @return \Base\Entity\BookclubBillboard
     */
    public function setIsOnline($is_online)
    {
        $this->is_online = $is_online;

        return $this;
    }

    /**
     * Get the value of is_online.
     *
     * @return boolean
     */
    public function getIsOnline()
    {
        return $this->is_online;
    }

    /**
     * Set the value of is_limit_member_brower.
     *
     * @param boolean $is_limit_member_brower
     * @return \Base\Entity\BookclubBillboard
     */
    public function setIsLimitMemberBrower($is_limit_member_brower)
    {
        $this->is_limit_member_brower = $is_limit_member_brower;

        return $this;
    }

    /**
     * Get the value of is_limit_member_brower.
     *
     * @return boolean
     */
    public function getIsLimitMemberBrower()
    {
        return $this->is_limit_member_brower;
    }

    /**
     * Set the value of updatetime.
     *
     * @param \DateTime $updatetime
     * @return \Base\Entity\BookclubBillboard
     */
    public function setUpdatetime($updatetime)
    {
        $this->updatetime = $updatetime;

        return $this;
    }

    /**
     * Get the value of updatetime.
     *
     * @return \DateTime
     */
    public function getUpdatetime()
    {
        return $this->updatetime;
    }

    /**
     * Add BookclubHasBookclubBillboard entity to collection (one to many).
     *
     * @param \Base\Entity\BookclubHasBookclubBillboard $bookclubHasBookclubBillboard
     * @return \Base\Entity\BookclubBillboard
     */
    public function addBookclubHasBookclubBillboard(BookclubHasBookclubBillboard $bookclubHasBookclubBillboard)
    {
        $this->bookclubHasBookclubBillboards[] = $bookclubHasBookclubBillboard;

        return $this;
    }

    /**
     * Remove BookclubHasBookclubBillboard entity from collection (one to many).
     *
     * @param \Base\Entity\BookclubHasBookclubBillboard $bookclubHasBookclubBillboard
     * @return \Base\Entity\BookclubBillboard
     */
    public function removeBookclubHasBookclubBillboard(BookclubHasBookclubBillboard $bookclubHasBookclubBillboard)
    {
        $this->bookclubHasBookclubBillboards->removeElement($bookclubHasBookclubBillboard);

        return $this;
    }

    /**
     * Get BookclubHasBookclubBillboard entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBookclubHasBookclubBillboards()
    {
        return $this->bookclubHasBookclubBillboards;
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
                'name' => 'id',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'title',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'content',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'start_date',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'end_date',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'is_online',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'is_limit_member_brower',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'updatetime',
                'required' => false,
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
        $dataFields = array('id', 'title', 'content', 'start_date', 'end_date', 'is_online', 'is_limit_member_brower', 'updatetime');
        $relationFields = array();
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
        return array('id', 'title', 'content', 'start_date', 'end_date', 'is_online', 'is_limit_member_brower', 'updatetime');
    }
}