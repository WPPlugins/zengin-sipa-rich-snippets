<?php
/**
 * Simple Validator Class for php
 * @author Can Geliş <geliscan@gmail.com>
 * @copyright (c) 2013, Can Geliş
 * @license https://github.com/cangelis/simple-validator/blob/master/licence.txt MIT Licence
 * @link https://github.com/cangelis/simple-validator
 */
/**
 * TODO: Exception handling for rules with parameters
 * TODO: unit tests for numeric, float, alpha_numeric, max_length, min_length, exact_length
 * TODO: add protection filters for several input vulnerabilities.
 */
class SipaPlgValidator {
    protected $errors = array();
    protected $namings = array();
    protected $customErrorsWithInputName = array();
    protected $customErrors = array();
    /**
     * Constructor is not allowed because SimpleValidator uses its own
     * static method to instantiate the validaton
     */
    private function __construct($errors, $namings) {
        $this->errors  = (array) $errors;
        $this->namings = (array) $namings;
    }
    /**
     *
     * @return boolean
     */
    public function isSuccess() {
        return (empty($this->errors) == true);
    }
    /**
     *
     * @param Array $errors_array
     */
    public function customErrors($errors_array) {
        foreach ($errors_array as $key => $value) {
            // handle input.rule eg (name.required)
            if (preg_match("#^(.+?)\.(.+?)$#", $key, $matches)) {
                // $this->customErrorsWithInputName[name][required] = error message
                $this->customErrorsWithInputName[(string) $matches[1]][(string) $matches[2]] = $value;
            } else {
                $this->customErrors[(string) $key] = $value;
            }
        }
    }
    protected function getDefaultLang() {
        $lang = SipaPlgSession::get('_sipa__lang',get_locale());
        if( $lang == 'tr_TR' || $lang == 'tr' ) $lang = 'tr'; else $lang = 'en';
        return $lang;
    }

    protected function getErrorFilePath($lang) {
        return null;
    }
    protected function getDefaultErrorTexts($lang = null) {
        /* handle default error text file */
        $default_error_texts = array();
        if (file_exists(GOKSEL__PLUGIN_DIR_INC_SYSTEM . "/lang/validator/" . $lang . ".php")) {
            $default_error_texts = include(GOKSEL__PLUGIN_DIR_INC_SYSTEM . "/lang/validator/" . $lang . ".php");
        }
        return $default_error_texts;
    }
    protected function getCustomErrorTexts($lang = null) {
        /* handle error text file for custom validators */
        $custom_error_texts = array();
        if (file_exists($this->getErrorFilePath($lang)))
            $custom_error_texts = include($this->getErrorFilePath($lang));
        return $custom_error_texts;
    }
    protected function handleNaming($input_name) {
        if (isset($this->namings[(string) $input_name])) {
            $named_input = $this->namings[(string) $input_name];
        } else {
            $named_input = $input_name;
        }
        return $named_input;
    }
    protected function handleParameterNaming($params) {
        foreach ($params as $key => $param) {
            if (preg_match("#^:([a-zA-Z0-9_]+)$#", $param, $param_type)) {
                if (isset($this->namings[(string) $param_type[1]]))
                    $params[$key] = $this->namings[(string) $param_type[1]];
                else
                    $params[$key] = $param_type[1];
            }
        }
        return $params;
    }

    /**
     *
     * @param null $lang
     * @return array
     * @throws SipaPlgValidatorException
     */
    public function getErrors($lang = null) {
        if ($lang == null)
            $lang = $this->getDefaultLang();
        $error_results = array();
        $default_error_texts = $this->getDefaultErrorTexts($lang);
        $custom_error_texts = $this->getCustomErrorTexts($lang);
        foreach ($this->errors as $input_name => $results) {
            foreach ($results as $rule => $result) {
                $named_input = $this->handleNaming($input_name);
                /**
                 * if parameters are input name they should be named as well
                 */
                $result['params'] = $this->handleParameterNaming($result['params']);
                // if there is a custom message with input name, apply it
                if (isset($this->customErrorsWithInputName[(string) $input_name][(string) $rule])) {
                    $error_message = $this->customErrorsWithInputName[(string) $input_name][(string) $rule];
                }
                // if there is a custom message for the rule, apply it
                else if (isset($this->customErrors[(string) $rule])) {
                    $error_message = $this->customErrors[(string) $rule];
                }
                // if there is a custom validator try to fetch from its error file
                else if (isset($custom_error_texts[(string) $rule])) {
                    $error_message = $custom_error_texts[(string) $rule];
                }
                // if none try to fetch from default error file
                else if (isset($default_error_texts[(string) $rule])) {
                    $error_message = $default_error_texts[(string) $rule];
                } else {
                    throw new SipaPlgValidatorException(SipaPlgValidatorException::NO_ERROR_TEXT, $rule);
                }
                /**
                 * handle :params(..)
                 */
                if (preg_match_all("#:params\((.+?)\)#", $error_message, $param_indexes))
                    foreach ($param_indexes[1] as $param_index) {
                        $error_message = str_replace(":params(" . $param_index . ")", $result['params'][$param_index], $error_message);
                    }
                $error_results[] = str_replace(":attribute", $named_input, $error_message);
            }
        }
        return $error_results;
    }
    /**
     *
     * @return boolean
     */
    public function has($input_name, $rule_name = null) {
        if ($rule_name != null)
            return isset($this->errors[$input_name][$rule_name]);
        return isset($this->errors[$input_name]);
    }
    final public function getResults() {
        return $this->errors;
    }
    /**
     * Gets the parameter names of a rule
     * @param string $rule
     * @return mixed
     */
    private static function getParams($rule) {
        if (preg_match("#^([a-zA-Z0-9_]+)\((.+?)\)$#", $rule, $matches)) {
            return array(
                'rule' => $matches[1],
                'params' => explode(",", $matches[2])
            );
        }
        return array(
            'rule' => $rule,
            'params' => array()
        );
    }
    /**
     * Handle parameter with input name
     * eg: equals(:name)
     * @param mixed $params
     * @return mixed
     */
    private static function getParamValues($params, $inputs) {
        foreach ($params as $key => $param) {
            if (preg_match("#^:([a-zA-Z0-9_]+)$#", $param, $param_type)) {
                $params[$key] = @$inputs[(string) $param_type[1]];
            }
        }
        return $params;
    }
    /**
     *
     * @param Array $inputs
     * @param Array $rules
     * @param Array $naming
     * @return SipaPlgValidator
     * @throws SipaPlgValidatorException
     */
    public static function validate($inputs, $rules, $naming = null) {
        $errors = null;
        foreach ($rules as $input => $input_rules) {
            if (is_array($input_rules)) {
                foreach ($input_rules as $rule => $closure) {
                    if (!isset($inputs[(string) $input]))
                        $input_value = null;
                    else
                        $input_value = $inputs[(string) $input];
                    /**
                     * if the key of the $input_rules is numeric that means
                     * it's neither an anonymous nor an user function.
                     */
                    if (is_numeric($rule)) {
                        $rule = $closure;
                    }
                    $rule_and_params = static::getParams($rule);
                    $params = $real_params = $rule_and_params['params'];
                    $rule = $rule_and_params['rule'];
                    $params = static::getParamValues($params, $inputs);
                    array_unshift($params, $input_value);
                    /**
                     * Handle anonymous functions
                     */
                    if(is_object($closure) && get_class($closure) == 'Closure') {
                        $refl_func = new \ReflectionFunction($closure);
                        $validation = $refl_func->invokeArgs($params);
                    }/**
                     * handle class methods
                     */ else if (@method_exists(get_called_class(), $rule)) {
                        $refl = new \ReflectionMethod(get_called_class(), $rule);
                        if ($refl->isStatic()) {
                            $refl->setAccessible(true);
                            $validation = $refl->invokeArgs(null, $params);
                        } else {
                            throw new SipaPlgValidatorException(SipaPlgValidatorException::STATIC_METHOD, $rule);
                        }
                    } else {
                        throw new SipaPlgValidatorException(SipaPlgValidatorException::UNKNOWN_RULE, $rule);
                    }
                    if ($validation == false) {
                        $errors[(string) $input][(string) $rule]['result'] = false;
                        $errors[(string) $input][(string) $rule]['params'] = $real_params;
                    }
                }
            } else {
                throw new SipaPlgValidatorException(SipaPlgValidatorException::ARRAY_EXPECTED, $input);
            }
        }
        return new static($errors, $naming);
    }
    protected static function required($input = null) {
        return ( (!is_null($input) && (trim($input) != '')) || $input === false || $input === true );
    }
    protected static function numeric($input) {
        return is_numeric($input);
    }
    protected static function email($input) {
        return filter_var($input, FILTER_VALIDATE_EMAIL);
    }
    protected static function integer($input) {
        return filter_var($input, FILTER_VALIDATE_INT) !== false;
    }
    protected static function float($input) {
        return is_float($input) || ($input == (string) (float) $input);
    }
    protected static function alpha($input) {
        return (preg_match("#^[a-zA-ZÀ-ÿ]+$#", $input) == 1);
    }
    protected static function alpha_numeric($input) {
        return (preg_match("#^[a-zA-ZÀ-ÿ0-9]+$#", $input) == 1);
    }
    protected static function ip($input) {
        return filter_var($input, FILTER_VALIDATE_IP);
    }

    /*
     * TODO: need improvements for tel and urn urls.
     * check out url.test.php for the test result
     * urn syntax: http://www.faqs.org/rfcs/rfc2141.html
     *
     */
    protected static function url($input) {
        return filter_var($input, FILTER_VALIDATE_URL);
    }
    protected static function max_length($input, $length) {
        return (strlen($input) <= $length);
    }
    protected static function min_length($input, $length) {
        return (strlen($input) >= $length);
    }
    protected static function exact_length($input, $length) {
        return (strlen($input) == $length);
    }
    protected static function equals($input, $param) {
        return ($input == $param);
    }

    /*
     * sonradan eklenenler
     */

    protected static function boolean($input) {
        return (
            (strtolower($input) == 'true' || strtolower($input) == 'false')
            || (strtolower($input) === true || strtolower($input) === false )
            || is_bool($input)
        );
    }

    protected static function max_val($input, $val) {
        return (($input) <= $val);
    }

    protected static function min_val($input, $val) {
        return (($input) >= $val);
    }
}