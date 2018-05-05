<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 05/05/18
 * Time: 10:52
 */

namespace App\Game;


abstract class Cases
{
    /**
     * @var int abscisse de la case
     */
    private $X;
    /**
     * @var int ordonnée de la case
     */
    private $Y;
    /**
     * @var Tablier tableau dans laquelle est situé la cases
     */
    protected $tablier;

    /**
     * Cases constructor.
     * @param $X
     * @param $Y
     * @param Tablier $tablier
     */
    public function __construct(Tablier $tablier,$X, $Y )
    {
        $this->setPosition($X,$Y);
        $this->tablier = $tablier;
    }


    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->X;
    }

    /**
     * @param mixed $X
     */
    private function setX($X): void
    {
        if($X>=0 && $X<10)
        {
            $this->X = $X;
        }else{
            throw new \InvalidArgumentException("X (l'abscisse doit etre compris entre 0 et 9");
        }
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->Y;
    }

    /**
     * @param mixed $Y
     */
    private function setY($Y): void
    {
        if($Y>=0 && $Y<10)
        {
            $this->Y = $Y;
        }else{
            throw new \InvalidArgumentException("Y (l'ordonné doit être compris entre 0 et 9");
        }
    }

    protected function setPosition($X,$Y){
        $this->setX($X);
        $this->setY($Y);
    }

}