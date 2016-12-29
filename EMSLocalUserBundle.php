<?php

namespace EMS\LocalUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EMSLocalUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
