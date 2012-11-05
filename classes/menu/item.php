<?php

class Menu_Item
{
	protected $title 		= '';
	protected $link 		= false;
	protected $a_attr 		= array();
	protected $a_pre 		= '';
	protected $a_post 		= '';
	protected $li_attr 		= array();
	protected $ul_attr 		= array();	// Only used for sub-items
	protected $icon 		= null;
	protected $icon_attr 	= array();
	protected $icon_post	= '&nbsp;&nbsp;';
	protected $icon_pre		= '';
	protected $pre_text 	= null;
	protected $post_text 	= null;
	protected $sub_items 	= array();
	protected $active		= false;
	protected $active_class	= 'active';
	protected $permission	= null;
	protected $is_list		= true;

	public static function forge($data = null)
	{
		return new Menu_Item($data);
	}

	public function __construct($data = null)
	{
		if(!empty($data))
		{
			foreach($data as $key=>$row)
			{
				switch($key)
				{
					case 'title':
						$this->set_title($row);
					break;

					case 'link':
						$this->set_link($row);
					break;

					case 'icon':
						$this->set_icon($row);
					break;

					case 'icon_pre':
						$this->set_icon_pre($row);
					break;

					case 'icon_post':
						$this->set_icon_post($row);
					break;

					case 'icon_attr':
						$this->add_icon_attr($row);
					break;

					case 'pre_text':
						$this->set_pre_text($row);
					break;

					case 'post_text':
						$this->set_post_text($row);
					break;

					case 'a_attr':
						$this->add_a_attr($row);
					break;

					case 'a_pre':
						$this->set_a_pre($row);
					break;

					case 'a_post':
						$this->set_a_post($row);
					break;

					case 'li_attr':
						$this->add_li_attr($row);
					break;

					case 'ul_attr':
						$this->add_ul_attr($row);
					break;

					case 'sub_items':
						$this->add_sub_items($row);
					break;

					case 'active':
						$this->set_active($row);
					break;

					case 'permission':
						$this->set_permission($row);
					break;

					default:
						continue;
					break;
				}
			}
		}
	}

	public function __toString()
	{
		if(!is_null($this->permission) and !$this->check_permission())
		{
			return '';
		}

		if($this->icon)
		{
			$current = Arr::get($this->icon_attr, 'class');
			Arr::set($this->icon_attr, 'class',
				(!empty($current)) ? $current . ' icon-' . $this->icon : 'icon-' . $this->icon
				);
			$icon = $this->icon_pre.html_tag('i', $this->icon_attr, '').$this->icon_post;
		}

		$text = (empty($this->icon)) ? '' : $icon;
		$text .= (empty($this->pre_text)) ? '' : $this->pre_text;
		$text .= (empty($this->title) and $this->title !== '') ? $this->link : $this->title;
		$text .= (empty($this->post_text)) ? '' : $this->post_text;

		$subs = '';
		// Sub Items
		if(!empty($this->sub_items))
		{
			$subs = '';
			foreach($this->sub_items as $item)
			{
				$subs .= (string) $item;
			}
			$subs = html_tag(
				'ul',
				$this->ul_attr,
				$subs
				);
		}

		if($this->active)
		{
			if(isset($this->li_attr['class']))
			{
				$this->li_attr['class'] .= ' '.$this->active_class;
			}
			else
			{
				$this->li_attr['class'] = $this->active_class;
			}
		}

		if($this->link !== false)
		{
			$content = Html::anchor(
						Uri::create($this->link),
						$text,
						$this->a_attr
					);
		}
		else
		{
			$content = $text;
		}

		$content = $this->a_pre.$content.$subs.$this->a_post;

		if(!$this->is_list)
		{
			return $content;
		}

		return html_tag(
					'li',
					$this->li_attr,
					$content
				);
	}

	public function set_title($data = null)
	{
		$this->title = (string) $data;
		return $this;
	}

	public function set_link($data = null)
	{
		$this->link = (string) $data;
		return $this;
	}

	public function set_icon($data = null)
	{
		$this->icon = (string) $data;
		return $this;
	}

	public function set_icon_pre($data = null)
	{
		$this->icon_pre = (string) $data;
		return $this;
	}

	public function set_icon_post($data = null)
	{
		$this->icon_post = (string) $data;
		return $this;
	}

	public function set_pre_text($data = null)
	{
		$this->pre_text = (string) $data;
		return $this;
	}

	public function set_post_text($data = null)
	{
		$this->post_text = (string) $data;
		return $this;
	}

	public function set_active($data = false)
	{
		$this->active = (bool) $data;
		return $this;
	}

	public function set_is_list($data = false)
	{
		$this->is_list = (bool) $data;
		return $this;
	}

	public function set_permission($data = false)
	{
		$this->permission = $data;
		return $this;
	}

	public function check_permission()
	{
		return Auth::has_permission($this->permission);
	}

	public function add_li_attr($key = null, $val = null)
	{
		Arr::set($this->li_attr, $key, $val);
		return $this;
	}

	public function add_ul_attr($key = null, $val = null)
	{
		Arr::set($this->ul_attr, $key, $val);
		return $this;
	}

	public function add_a_attr($key = null, $val = null)
	{
		Arr::set($this->a_attr, $key, $val);
		return $this;
	}

	public function set_a_pre($data = null)
	{
		$this->a_pre = (string) $data;
		return $this;
	}

	public function set_a_post($data = null)
	{
		$this->a_post = (string) $data;
		return $this;
	}

	public function add_icon_attr($key = null, $val = null)
	{
		Arr::set($this->icon_attr, $key, $val);
		return $this;
	}

	public function add_sub_items($data = null)
	{
		if(isset($data['title']) or isset($data['link']))
		{
			$data = array($data);
		}

		foreach($data as $row)
		{
			$this->sub_items[] = static::forge($row);
		}

		return $this;
	}
}