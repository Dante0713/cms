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
 * Base\Entity\SchoolHasStudent
 *
 * @ORM\Entity(repositoryClass="SchoolHasStudentRepository")
 * @ORM\Table(name="school_has_student", indexes={@ORM\Index(name="fk_school_has_student_student1_idx", columns={"student_id"}), @ORM\Index(name="fk_school_has_student_school1_idx", columns={"school_id"})})
 */
class SchoolHasStudent implements InputFilterAwareInterface
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
    protected $school_id;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $student_id;

    /**
     * @ORM\ManyToOne(targetEntity="School", inversedBy="schoolHasStudents")
     * @ORM\JoinColumn(name="school_id", referencedColumnName="id", nullable=false)
     */
    protected $school;

    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="schoolHasStudents")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id", nullable=false)
     */
    protected $student;

    public function __construct()
    {
    }

    /**
     * Set the value of school_id.
     *
     * @param integer $school_id
     * @return \Base\Entity\SchoolHasStudent
     */
    public function setSchoolId($school_id)
    {
        $this->school_id = $school_id;

        return $this;
    }

    /**
     * Get the value of school_id.
     *
     * @return integer
     */
    public function getSchoolId()
    {
        return $this->school_id;
    }

    /**
     * Set the value of student_id.
     *
     * @param integer $student_id
     * @return \Base\Entity\SchoolHasStudent
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
     * Set School entity (many to one).
     *
     * @param \Base\Entity\School $school
     * @return \Base\Entity\SchoolHasStudent
     */
    public function setSchool(School $school = null)
    {
        $this->school = $school;

        return $this;
    }

    /**
     * Get School entity (many to one).
     *
     * @return \Base\Entity\School
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * Set Student entity (many to one).
     *
     * @param \Base\Entity\Student $student
     * @return \Base\Entity\SchoolHasStudent
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
                'name' => 'school_id',
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
        $dataFields = array('school_id', 'student_id');
        $relationFields = array('school', 'student');
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
        return array('school_id', 'student_id');
    }
}