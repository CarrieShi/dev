<?php
/**
 * 系统设置控制器
 */
Class SystemAction extends CommonAction {

	/**
	 * 网站设置
	 */
	public function runEdit () {
		$path = './Index/Conf/system.php';
		$config = include $path;
		$config['WEBNAME'] = $this->_post('webname');
		$config['COPY'] = $this->_post('copy');
		$config['REGIS_ON'] = $this->_post('regis_on');

		$data = "<?php\r\nreturn ". var_export($config, true) .";";

		if(file_put_contents($path, $data)) {
			$this->success('修改成功', U('index'));
		} else {
			$this->error('修改失败，请修改' . $path . '的写入权限');
		}
	}

	/**
	 * 关键设置视图
	 */
	public function filter () {
		$config = include './Index/Conf/system.php';
		$this->filter = implode('|', $config['FILTER']);
		$this->display();
	}

	/**
	 * 执行修改关键字
	 */
	public function runEditFilter() {
		$path = './Index/Conf/system.php';
		$config = include $path;
		$config['FILTER'] = explode('|', $_POST['filter']);

		$data = "<?php\r\nreturn ". var_export($config, true) .";";

		if(file_put_contents($path, $data)) {
			$this->success('修改成功', U('filter'));
		} else {
			$this->error('修改失败，请修改' . $path . '的写入权限');
		}
	}
}