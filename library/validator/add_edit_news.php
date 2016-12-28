<?php
include 'validator_abstract.php';

class AddEditNews extends ValidatorAbstract
{
    protected $fields = array(
        'title' => array(
            'required' => array(
                'message' => 'This field is required'
            )   
        ),
        'text' => array(
            'required' => array(
                'message' => 'This field is required'
            ),
        ),
        'date' => array(
            'required' => array(
                'message' => 'This field is required'
            ),
            'date' => array(
                'message' => 'Invalid date or not a leap-year(YYYY-MM-DD accepted)'
            )
        )
    );
}