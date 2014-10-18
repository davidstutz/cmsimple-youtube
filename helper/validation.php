<?php
/* utf8-marker = äöüß */
/**
 * @author David Stutz
 * @version 1.0.0
 * @package reservations
 */

 /**
 * @class Validation
 * Validation helper.
 * 
 * @author David Stutz
 * @package reservations
 */
class Validation {

	/**
	 * @public
	 * @static
	 * 
	 * Factory.
	 * 
	 * @return <object> Validation
	 */
	public static function factory($array)
	{
		return new Validation($array);
	}
	
	/**
	 * @protected
	 * Storing data array.
	 */
	protected $_array = array();
	
	/**
	 * @protected
	 * Storing rules.
	 */
	protected $_rules = array();
	
	/**
	 * @protected
	 * Storing messages.
	 */
	protected $_messages = array();
	
	/**
	 * @protected
	 * Storing errors.
	 */
	protected $_errors = FALSE;
	
	/**
	 * @public
	 * Constructor
	 * 
	 * @param <array> array to validate
	 */
	public function __construct($array)
	{
		$this->_array = $array;
	}
	
	/**
	 * @public
	 * Add a rule.
	 * 
	 * @param <string> key
	 * @param <string> rule 
	 * @param <string> message
	 */
	public function rule($key, $rule, $message)
	{
		if (!isset($this->_rules[$key]))
		{
			$this->_rules[$key] = array();
		}
		
		$this->_rules[$key][] = $rule;
		
		if (!isset($this->_messages[$key]))
		{
			$this->_messages[$key] = array();
		}
		
		$this->_messages[$key][$rule] = $message;
		
		return $this;
	}
	
	/**
	 * @public
	 * Validate rules on array.
	 * 
	 * @return <boolean> success
	 */
	public function check()
	{
		foreach ($this->_rules as $key => $array)
		{
			foreach ($array as $rule)
			{
				if (!call_user_func_array(array('Validation', $rule), array($this->_array[$key])))
				{
					if (!isset($this->_errors))
					{
						$this->_errors = array();
					}
					
					if (!is_array($this->_errors))
					{
						$this->_errors = array();
					}
					
					$this->_errors[] = $this->_messages[$key][$rule];
				}
			}
		}
		
		return FALSE === $this->_errors;
	}
	
	/**
	 * @public
	 * Gets error messages.
	 * 
	 * @return <array> errors
	 */
	public function errors()
	{
		return FALSE === $this->_errors ? array() : $this->_errors;
	}
	
	/**
	 * @public
	 * @static
	 * Rule to test for emptyness.
	 * 
	 * Taken from Kohana framework.
	 * 
	 * @param <string> value
	 */
	public static function not_empty($value)
	{
		if (is_object($value) AND $value instanceof ArrayObject)
	    {
	        // Get the array from the ArrayObject
	        $value = $value->getArrayCopy();
	    }
	 
	    // Value cannot be NULL, FALSE, '', or an empty array
	    return ! in_array($value, array(NULL, FALSE, '', array()), TRUE);
	}

	/**
	 * @public
	 * @static
	 * Test for date.
	 * 
	 * @param <string> date
	 */
	public static function date($date)
	{
	    return (strtotime($date) !== FALSE);
	}
	
	/** 
	 * @public
	 * @static
	 * Test for numeric.
	 * 
	 * @param <string> number
	 */
	public static function numeric($number)
	{
	    return is_numeric($number) === TRUE;
	}
	
    /**
     * @poublic
     * @static
     * Test for integer.
     * 
     * @param <string> ingeger
     */
    public static function integer($integer) {
        return preg_match('/[0-9]+/', $integer) === 1;
    }
    
	/**
     * Check an email address for correct format.
     *
     * @link    http://www.iamcal.com/publish/articles/php/parsing_email/
     * @link    http://www.w3.org/Protocols/rfc822/
     * @link    http://www.kohanaframework.org
     *
     * @param   string  email
     * @param   boolean strict RFC
     * @return  boolean
     */
    public static function email($email, $strict = FALSE)
    {
        if (strlen($email) > 254)
        {
            return FALSE;
        }

        if ($strict === TRUE)
        {
            $qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';
            $dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';
            $atom  = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';
            $pair  = '\\x5c[\\x00-\\x7f]';

            $domain_literal = "\\x5b($dtext|$pair)*\\x5d";
            $quoted_string  = "\\x22($qtext|$pair)*\\x22";
            $sub_domain     = "($atom|$domain_literal)";
            $word           = "($atom|$quoted_string)";
            $domain         = "$sub_domain(\\x2e$sub_domain)*";
            $local_part     = "$word(\\x2e$word)*";

            $expression     = "/^$local_part\\x40$domain$/D";
        }
        else
        {
            $expression = '/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})$/iD';
        }

        return (bool) preg_match($expression, (string) $email);
    }
}
