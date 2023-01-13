<?php
namespace App\Modules;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait RequestDataChecker
{
	private function checkInner(array $inner_params, array $expected_params):void
	{
		$inner_params_keys = array_keys($inner_params);

		foreach ($inner_params_keys as $inner_param_key)
		{
			if (!in_array($inner_param_key, $expected_params))
			{
				throw new BadRequestHttpException("Параметр $inner_param_key не является валидным для данного ресурса");
			}
		}
	} 	


	private function checkRequired(array $inner_params, array $expected_params):void
	{
		$inner_params_keys = array_keys($inner_params);

		foreach($expected_params as $expected_param)
		{
			if (!in_array($expected_param, $inner_params_keys))
			{
				throw new BadRequestHttpException("Ожидаемый параметр $expected_param не был найден в списке входящих параметров");
			}
		}

	}
}