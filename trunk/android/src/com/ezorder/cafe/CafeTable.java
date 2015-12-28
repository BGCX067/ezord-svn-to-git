package com.ezorder.cafe;

public class CafeTable
{
	public String	_cafe_name = "";
	
	public int		_id = 0;
	public String	_table_description = "";
	
	public Order 	_order = null;
	public boolean	_is_empty = true;
	
	public CafeTable(String cafe_name, int id, String description)
	{
		_cafe_name = cafe_name;
		_id = id;
		_table_description = description;
		
		_order = new Order(cafe_name, id, description);
		_is_empty = true;
	}
	
	public void init(String json_data)
	{
	
	}
	
	public void newCustomer()
	{
		_order.reset();
		
		_is_empty = false;
	}
	
	public void clearTable()
	{
		_is_empty = true;
	}
	
	public boolean isEmpty()
	{
		return _is_empty;
	}

}