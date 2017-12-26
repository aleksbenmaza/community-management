<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 26/12/2017
 * Time: 21:44
 */

namespace Application\Http\Response;


class Forwarding {

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $request_params;

    /**
     * ForwardingView constructor.
     * @param string $path
     */
    public function __construct(string $path, array $request_params = []) {
        $this->path = $path;
        $this->request_params = $request_params;
    }


    /**
     * @return string
     */
    public function getPath(): string {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getRequestParams(): array {
        return $this->request_params;
    }


}