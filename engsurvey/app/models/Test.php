<?php
namespace Engsurvey\Models;

use Phalcon\Mvc\Model as PhalconModel;

/**
 * Виды техники, используемой при производстве инженерных изысканий.
 */
class Test extends PhalconModel
{
    /**
     * @var int
     */
    protected $id;
    
    /**
     * @var string
     */
    protected $name;
    
    
    /**
     * @var int
     */
    protected $code;
    
    
    /**
     * @return int|null
     */
    public static function getLastSequenceNumber()
    {
        $maxSequenceNumber = Test::maximum(
            [
                'column' => 'code'
            ]
        );

        return $maxSequenceNumber;
    }
    
 
}
