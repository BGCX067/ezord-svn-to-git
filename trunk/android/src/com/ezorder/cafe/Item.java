package com.ezorder.cafe;

public class Item
{
	public int			_id;
	public String		_name;
	public double		_price;
	
	public int			_type;				//drink, food...
	public String		_type_name;
	
	public int			_sub_type;			//cafe, fruit juice...
	public String		_sub_type_name;
	
	public Item(int id, String name, double price, int type, String type_name, int sub_type, String sub_type_name)
	{
		_id = id;
		_name = name;
		_price = price;
		
		_type = type;
		_type_name = type_name;
		
		_sub_type = sub_type;
		_sub_type_name = sub_type_name;
	}
	
	public boolean equals(Item item)
	{
		if (_id == item._id)
		{
			return true;
		}
		
		return false;
	}
}