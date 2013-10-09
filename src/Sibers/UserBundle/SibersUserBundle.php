<?php

namespace Sibers\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Sibers user bundle
 * 
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
class SibersUserBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}