package com.ezorder.cafe;

import java.util.*;

public class Order
{
	public String					_cafe_name = "";
	public int						_table_id = 0;
	public String					_table_desc = "";
	
	public long						_order_time = 0;
	
	public ArrayList<OrderItem>		_order_items = null;
	
	public Order(String cafe_name, int table_id, String table_desc)
	{
		_cafe_name = cafe_name;
		_table_id = table_id;
		_table_desc = table_desc;
		
		_order_time = CafeShop.getCurrentTime();
		
		_order_items = new ArrayList<OrderItem>();
	}
	
	public void reset()
	{
		_order_time = CafeShop.getCurrentTime();
		
		_order_items.clear();
	}
	
	public void orderItem(Item item, int num)
	{
		for (OrderItem i : _order_items)
		{
			if (item._id == i._item._id)
			{
				i.increase(num);
				
				return;
			}
		}
		
		_order_items.add(new OrderItem(item, num));
	}
	
	public void removeItem(Item item)
	{
		int len = _order_items.size();
		
		OrderItem order_item = null;
		
		for (int i = 0; i < len; i++)
		{
			order_item = _order_items.get(i);
			
			if (item._id == order_item._item._id)
			{
				_order_items.remove(i);
				
				return;
			}
		}
	}
	
	public void increaseItem(Item item, int num)
	{
		for (OrderItem i : _order_items)
		{
			if (item._id == i._item._id)
			{
				i.increase(num);
				
				return;
			}
		}
	}
	
	public void decreaseItem(Item item, int num)
	{
		for (OrderItem i : _order_items)
		{
			if (item._id == i._item._id)
			{
				i.decrease(num);
				
				return;
			}
		}
	}
	
	
}


class OrderItem
{
	public Item			_item = null;
	public int			_num_items = 0;	
	public long			_order_time = 0;
	
	public OrderItem(Item item, int num)
	{
		_item = item;
		_num_items = num;
		_order_time = CafeShop.getCurrentTime();
	}
	
	public int increase(int num)
	{
		_num_items += num;
		
		_order_time = CafeShop.getCurrentTime();
		
		return _num_items;
	}
	
	public int decrease(int num)
	{
		_num_items -= num;
		
		_order_time = CafeShop.getCurrentTime();
		
		return _num_items;
	}
	
	public double getPrice()
	{
		return _item._price * _num_items;
	}
}