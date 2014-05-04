<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\Validator;

interface Validatable
{
    public function getData();
    public function getSchema();
    public function validate(Validator $validator);
}