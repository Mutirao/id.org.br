<?php
/**
 * This file is part of the login-cidadao project or it's bundles.
 *
 * (c) Guilherme Donato <guilhermednt on github>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LoginCidadao\CoreBundle\Model;

use LoginCidadao\TaskStackBundle\Model\AbstractTask;
use LoginCidadao\TaskStackBundle\Model\TaskTargetInterface;

class InvalidateSessionTask extends AbstractTask
{
    /** @var TaskTargetInterface */
    private $target;

    /**
     * InvalidateSessionTask constructor.
     *
     * @param TaskTargetInterface $target
     */
    public function __construct(TaskTargetInterface $target)
    {
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return 'lc.invalidate_session';
    }

    public function getRoutes()
    {
        return [
            'fos_user_security_logout',
        ];
    }

    /**
     * @return boolean
     */
    public function isMandatory()
    {
        return true;
    }

    /**
     * @return TaskTargetInterface
     */
    public function getTarget()
    {
        return $this->target;
    }
}
