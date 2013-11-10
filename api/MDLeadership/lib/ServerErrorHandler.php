<?php
namespace MDLeadership\lib;

class ServerErrorHandler extends \Slim\middleware {
	public function call() {
		try {
			$this->next->call();
		} catch (\InvalidArgumentException $iae) {
			$this->app->halt(400);
		} catch (\ErrorException $ee) {
			// $this->app->halt(500);
			throw $ee;
		} catch(\Exception $e) {
			$this->app->halt($e->getCode());
		}
	}
}