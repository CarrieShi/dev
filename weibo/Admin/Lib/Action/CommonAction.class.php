<?php

Class CommonAction extends Action {

	/**
	 * 判断用户是否登陆
	 * @return [type] [description]
	 */
	public function _initialize () {
		if (!isset($_SESSION['uid']) || !isset($_SESSION['username'])) {
			redirect(U('Login/index'));
		}
	}
}