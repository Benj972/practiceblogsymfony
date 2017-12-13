<?php

namespace SnowTricks\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SnowTricksUserBundle extends Bundle
{
	public function getParent()
  	{
    	return 'FOSUserBundle';
  	}
}
