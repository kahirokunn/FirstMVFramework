<?php
namespace models;

class Blog
{
    /**
     * @param  mixed
     * @return array
     */
    public function index($params) : array
    {
        return array('str'=>'Hello World!');
    }
}
