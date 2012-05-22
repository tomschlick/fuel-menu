<?php

class Menu
{
	protected $items = array();
	protected $ul_attr = array();
	protected $is_list = true;
	protected $divider = '';

	public static function forge()
	{
		return new Menu();
	}

	public function add_item($data = null, $key = null)
	{
		if(isset($data['title']) or isset($data['link']))
		{
			$data = array($data);
		}

		if($key == null)
		{
			$key = (string) Arr::get($data, 'title', '').'_'.Str::random('numeric', 3);
		}

		if(!empty($data))
		{
			foreach($data as $row)
			{
				$this->items[$key] = Menu_Item::forge($row)->set_is_list($this->is_list);
			}
		}
    
		return $this;
	}

	public function add_ul_attr($key = null, $val = null)
	{
		Arr::set($this->ul_attr, $key, $val);
		return $this;
	}

	public function set_active($key = null)
	{
		if(isset($this->items[$key]))
		{
			$this->items[$key]->set_active(true);
		}
		elseif($key == null)
		{
			foreach($this->items as $row)
			{
				$row->set_active(false);
			}
		}

		return $this;
	}
	
	public function set_divider($data = '')
	{
	  $this->divider = (string) $data;
	  return $this;
	}

	public function set_is_list($data = false)
	{
		$this->is_list = (bool) $data;
		return $this;
	}

	public function __toString()
	{
		$text = '';

		if(!empty($this->items))
		{
			$subs = '';
			$all_items = array();
			
			foreach($this->items as $item)
			{
				$all_items[] = "\n".(string) $item;
			}
			
			$subs = implode($this->divider, $all_items);
			$subs .= "\n";

			if(!$this->is_list)
			{
				$text = $subs;
			}
			else
			{
				$text = html_tag(
					'ul',
					$this->ul_attr,
					$subs
					);
			}
		}

		return $text;
	}
}