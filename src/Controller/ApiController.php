<?php
namespace App\Controller;


use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiController extends Controller
{
    public function createResponse($data, $httpStatus = Response::HTTP_OK)
    {
        $data = $this->serializer($data);

        return new JsonResponse($data, $httpStatus, [], true);
    }

    private function serializer($data, $format = 'json')
    {
        $context = new SerializationContext();
        $context->setSerializeNull(true);
        return $this->get('jms_serializer')->serialize($data, $format, $context);
    }
}