<?php
namespace App\Exception\DocumentException;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CannotUpdatePublishedDocumentException extends BadRequestHttpException
{
	
}
