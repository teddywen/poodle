<?php
class BreadcrumbsWidget extends CWidget {
    public $links = array();

	public function init() {
        // this method is called by CController::beginWidget()
    }
 
    public function run() {
        $this->render("index");
    }
}