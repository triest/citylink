<?php
/**
 * Created by PhpStorm.
 * User: triest
 * Date: 24.07.2019
 * Time: 18:06
 */

class Сarrier
{
    public $name;
    public $rate_type;

    /**
     * @return mixed
     */
    public function getRateType()
    {
        return $this->rate_type;
    }

    /**
     * @param mixed $rate_type
     */
    public function setRateType($rate_type)
    {
        $this->rate_type = $rate_type;
    } //тип оплаты

    public $min_weight;
    public $price_min_weight;
    public $price_max_weight;
    public $rez;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getMinWeight()
    {
        return $this->min_weight;
    }

    /**
     * @param mixed $min_weight
     */
    public function setMinWeight($min_weight)
    {
        $this->min_weight = $min_weight;
    }

    /**
     * @return mixed
     */
    public function getPriceMinWeight()
    {
        return $this->price_min_weight;
    }

    /**
     * @param mixed $price_min_weight
     */
    public function setPriceMinWeight($price_min_weight)
    {
        $this->price_min_weight = $price_min_weight;
    }

    /**
     * @return mixed
     */
    public function getPriceMaxWeight()
    {
        return $this->price_max_weight;
    }

    /**
     * @param mixed $price_max_weight
     */
    public function setPriceMaxWeight($price_max_weight)
    {
        $this->price_max_weight = $price_max_weight;
    }

    /**
     * @return mixed
     */
    public function getRez()
    {
        return $this->rez;
    }

    /**
     * @param mixed $rez
     */
    public function setRez($rez)
    {
        $this->rez = $rez;
    }


    public function calculateCost($weight)
    {
        if ($this->rate_type == "const_every_weight") {
            return $weight * $this->getPriceMinWeight();
        } elseif ($this->rate_type == "price_increase_after_the_limit") {
            if ($weight <= $this->getMinWeight()) {
                return $this->getPriceMinWeight();
            } else {
                return $this->getPriceMinWeight();
            }
        }

        return null;
    }

}