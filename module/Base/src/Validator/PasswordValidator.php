<?php

use Zend\Validator\AbstractValidator;

class PasswordValidator extends AbstractValidator
{
    const ALL_WHITESPACE = 'allWhitespace';
    const NOT_LONG       = 'notLong';
    const NO_NUMERIC     = 'noNumeric';
    const NO_ALPHA       = 'noAlpha';
    const NO_CAPITAL     = 'noCapital';

    protected $_minPasswordLength = 8;
    protected $_requireNumeric    = true;
    protected $_requireAlpha      = true;
    protected $_requireCapital    = false;

    protected $_messageTemplates = [
        self::ALL_WHITESPACE => '密碼不能為空值',
        self::NOT_LONG       => '密碼必須超過 %len% 個字元',
        self::NO_NUMERIC     => '密碼必須至少包含一個數字',
        self::NO_ALPHA       => '密碼必須包含英文字母',
        self::NO_CAPITAL     => '密碼必須至少包含一個大寫英文字母',
    ];

    public function __construct($options = null)
    {
        parent::__construct($options);

        $this->_messageTemplates[self::NOT_LONG] = str_replace(
            '%len%', $this->_minPasswordLength, $this->_messageTemplates[self::NOT_LONG]);
        $isDigit = new Zend\Validator\Digits();
        if (isset($options['minPasswordLength'])
            && $isDigit->isValid($options['minPasswordLength'])
            && (int)$options['minPasswordLength'] > 3)
            $this->_minPasswordLength = $options['minPasswordLength'];

        if (isset($options['requireNumeric'])) $this->_requireNumeric = (bool)$options['requireNumeric'];
        if (isset($options['requireAlpha']))   $this->_requireAlpha   = (bool)$options['requireAlpha'];
        if (isset($options['requireCapital'])) $this->_requireCapital = (bool)$options['requireCapital'];

    }

    /**
     * Validate a password with the set requirements
     *
     * @see Zend_Validate_Interface::isValid()
     * @return bool true if valid, false if not
     */
    public function isValid($value, $context = null)
    {
        $value = (string)$value;
//        $this->_setValue($value);

        if (trim($value) == '') {
            $this->error(self::ALL_WHITESPACE);
        } else if (strlen($value) < $this->_minPasswordLength) {
            $this->error(self::NOT_LONG, $this->_minPasswordLength);
        } else if ($this->_requireNumeric == true && preg_match('/\d/', $value) == false) {
            $this->error(self::NO_NUMERIC);
        } else if ($this->_requireAlpha == true && preg_match('/[a-z]/i', $value) == false) {
            $this->error(self::NO_ALPHA);
        } else if ($this->_requireCapital == true && preg_match('/[A-Z]/', $value) == false) {
            $this->error(self::NO_CAPITAL);
        }

        if (sizeof($this->errors) > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Return a string explaining the current password requirements such as length and character set
     *
     * @return string The printable message explaining password requirements
     */
    public function getRequirementString()
    {
        $parts = array();

        $parts[] = 'Passwords must be at least ' . $this->_minPasswordLength . ' characters long';

        if ($this->_requireNumeric) $parts[] = 'contain one digit';
        if ($this->_requireAlpha)   $parts[] = 'contain one alpha character';
        if ($this->_requireCapital) $parts[] = 'have at least one uppercase letter';

        if (sizeof($parts) == 1) {
            return $parts[0] . '.';
        } else if (sizeof($parts) == 2) {
            return $parts[0] . ' and ' . $parts[1] . '.';
        } else {
            $str = $parts[0];
            for ($i = 1; $i < sizeof($parts) - 1; ++$i) {
                $str .= ', ' . $parts[$i];
            }

            $str .= ' and ' . $parts[$i];

            return $str . '.';
        }
    }
}