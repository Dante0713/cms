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
 * Base\Entity\BookclubHasStudent
 *
 * @ORM\Entity(repositoryClass="BookclubHasStudentRepository")
 * @ORM\Table(name="bookclub_has_student", indexes={@ORM\Index(name="fk_book_club_has_student_student1_idx", columns={"student_id"}), @ORM\Index(name="fk_book_club_has_student_book_club1_idx", columns={"bookclub_id"}), @ORM\Index(name="fk_book_club_has_student_book1_idx", columns={"book_id"})})
 */
class BookclubHasStudent implements InputFilterAwareInterface
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
     * @ORM\Column(type="integer")
     */
    protected $bookclub_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $student_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $book_id;

    /**
     * 1:加入
     * 2:推薦書
     *
     * @ORM\Column(name="`type`", type="boolean", nullable=true)
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="Bookclub", inversedBy="bookclubHasStudents")
     * @ORM\JoinColumn(name="bookclub_id", referencedColumnName="id", nullable=false)
     */
    protected $bookclub;

    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="bookclubHasStudents")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id", nullable=false)
     */
    protected $student;

    /**
     * @ORM\OneToOne(targetEntity="Book", inversedBy="bookclubHasStudent")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id", nullable=false)
     */
    protected $book;

    public function __construct()
    {
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \Base\Entity\BookclubHasStudent
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
     * Set the value of bookclub_id.
     *
     * @param integer $bookclub_id
     * @return \Base\Entity\BookclubHasStudent
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
     * Set the value of student_id.
     *
     * @param integer $student_id
     * @return \Base\Entity\BookclubHasStudent
     */
    public function setStudentId($student_id)
    {
        $this->student_id = $student_id;

        return $this;
    }

    /**
     * Get the value of student_id.
     *
     * @return integer
     */
    public function getStudentId()
    {
        return $this->student_id;
    }

    /**
     * Set the value of book_id.
     *
     * @param integer $book_id
     * @return \Base\Entity\BookclubHasStudent
     */
    public function setBookId($book_id)
    {
        $this->book_id = $book_id;

        return $this;
    }

    /**
     * Get the value of book_id.
     *
     * @return integer
     */
    public function getBookId()
    {
        return $this->book_id;
    }

    /**
     * Set the value of type.
     *
     * @param boolean $type
     * @return \Base\Entity\BookclubHasStudent
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of type.
     *
     * @return boolean
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set Bookclub entity (many to one).
     *
     * @param \Base\Entity\Bookclub $bookclub
     * @return \Base\Entity\BookclubHasStudent
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
     * Set Student entity (many to one).
     *
     * @param \Base\Entity\Student $student
     * @return \Base\Entity\BookclubHasStudent
     */
    public function setStudent(Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get Student entity (many to one).
     *
     * @return \Base\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Set Book entity (one to one).
     *
     * @param \Base\Entity\Book $book
     * @return \Base\Entity\BookclubHasStudent
     */
    public function setBook(Book $book)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get Book entity (one to one).
     *
     * @return \Base\Entity\Book
     */
    public function getBook()
    {
        return $this->book;
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
                'name' => 'bookclub_id',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'student_id',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'book_id',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'type',
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
        $dataFields = array('id', 'bookclub_id', 'student_id', 'book_id', 'type');
        $relationFields = array('bookclub', 'student', 'book');
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
        return array('id', 'bookclub_id', 'student_id', 'book_id', 'type');
    }
}