<?php

namespace CommonBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CommonBundle extends Bundle
{
	public function getParent()
	{
		return 'AvanzuAdminThemeBundle';
	}
}
