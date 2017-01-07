<?php

namespace Pokemon\Resources;

use Doctrine\Common\Inflector\Inflector;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;
use Pokemon\Models\Model;
use Pokemon\Resources\Interfaces\QueriableResourceInterface;
use Psr\Http\Message\ResponseInterface;
use stdClass;

/**
 * Class QueriableResource
 *
 * @package Pokemon\Resources
 */
class CustomQueriableResource extends QueriableResource implements QueriableResourceInterface
{

    /**
     * @param ResponseInterface $response
     *
     * @return array
     */
    protected function getResponseDataArray(ResponseInterface $response)
    {
        return json_decode($response->getBody()->getContents(),true);
    }

    /**
     * @param string $identifier
     *
     * @return Model|null
     * @throws InvalidArgumentException
     */
    public function findToArray($identifier)
    {
        $this->identifier = $identifier;
        try {
            $data = $this->getResponseDataArray($this->client->send($this->prepare()));
        } catch (ClientException $e) {
            throw new InvalidArgumentException('Card not found with identifier: ' . $identifier);
        }
        return array_shift(array_slice($data, 0, 1));
    }

    /**
     * @return array
     */
    public function allToArray()
    {
        $data = $this->getResponseDataArray($this->client->send($this->prepare()));
        $dataSlice = array_slice($data, 0, 1);
        $all = array_shift($dataSlice);
        return $all;
    }

}