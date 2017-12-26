<?php

namespace Application\Http\Response;

class RedirectView extends Response {
	private 
		$url,
		$status;

	public function __construct(? string $url = NULL, int $status = 302){
		$this->url = $url ?? ROOT;
		$this->status = $status;
	}

	public function __toString(): string{ 
		header('location: '.$this->url, $this->status);
		return '';
	}
}