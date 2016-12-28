<?php

class ValidatorAbstract
{
    protected $errors = array();
    protected $data = array();

    public function isValid($data)
    {
        foreach($this->fields as $field => $rules) {
            foreach($rules as $rule => $ruleData) {
                $params = isset($ruleData['params']) ? $ruleData['params'] : null;
                $value = isset($data[$field]) ? $data[$field] : null;

                if(method_exists($this, $rule)) {
                    if($rule == 'equals') {
                        $params['token'] = $data[$params['token']];
                    }

                    $isValid = $this->{$rule}($value, $params);
                    if($isValid) {
                        $this->data[$field] = $value;
                    } else {
                        $this->errors[$field][] = $rule;
                    }
                }
            }
        }

        return empty($this->errors);
    }

    public function getData()
    {
        return $this->data;
    }

    public function getErrors()
    {
        $errors = array();
        foreach($this->errors as $field => $rules) {
            foreach($rules as $rule) {
                // Getting only 1 error per field
                $rule = current($rules);
                if(isset($this->fields[$field][$rule])) {
                    $errors[$field][$rule] = $this->fields[$field][$rule]['message'];
                }
                if(isset($this->files[$field][$rule])) {
                    $errors[$field][$rule] = $this->files[$field][$rule]['message'];
                }
            }
        }

        return $errors;
    }

    // rules
    protected function required($value)
    {
        // Check if the form value is array (birthdate)
        if(is_array($value)) {
            foreach($value as $val) {
                if(empty($val)) {
                    return false;
                }
            }

            return true;
        } elseif(is_string($value)) {
            $value = trim($value);
            return !!strlen($value);
        } else {
            return !empty($value);
        }
    }

    protected function date($value)
    {
        return preg_match('/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/',$value);
    }

    public function sanitizeText($value)
    {
        return trim(stripslashes(htmlspecialchars($value)));
    }
}