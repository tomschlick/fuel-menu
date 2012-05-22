<?php

class Test_Menu extends TestCase
{
	public function test_standard_ul_li()
	{
		$menu = Menu::forge();

		$menu->add_item(array(
			'title'		=> 'Google',
			'link'		=> 'http://google.com',
			'a_attr'	=> array('class' => 'btn btn-large'),
			'icon'		=> 'search',
			));

		$this->assertEquals((string) $menu, '<ul><li><a class="btn btn-large" href="http://google.com"><i class="icon-search"></i>&nbsp;&nbsp;Google</a></li></ul>');
	}

	public function test_is_list()
	{
		$menu = Menu::forge();

		$menu->set_is_list(false);

		$menu->add_item(array(
			'title'		=> 'Google',
			'link'		=> 'http://google.com',
			'a_attr'	=> array('class' => 'btn btn-large'),
			));

		$menu->add_item(array(
			'title'		=> 'Yahoo',
			'link'		=> 'http://yahoo.com',
			'a_attr'	=> array('class' => 'btn btn-large'),
			));

		$this->assertEquals((string) $menu, '<a class="btn btn-large" href="http://google.com">Google</a><a class="btn btn-large" href="http://yahoo.com">Yahoo</a>');
	}

	public function test_no_link()
	{
		$menu = Menu::forge();

		$menu->add_item(array(
			'title'		=> 'Google',
			));

		$menu->add_item(array(
			'title'		=> 'Yahoo',
			'link'		=> 'http://yahoo.com',
			'a_attr'	=> array('class' => 'btn btn-large'),
			));

		$this->assertEquals((string) $menu, '<ul><li>Google</li><li><a class="btn btn-large" href="http://yahoo.com">Yahoo</a></li></ul>');
	}
}