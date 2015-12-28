package com.ezorder.cafe;

import java.util.*;

public class CafeMenu
{
	private ArrayList<Item>									_items = null;
	
	private Map<Integer, ArrayList<Item>>					_items_by_type = null;
	private Map<Integer, Map<Integer, ArrayList<Item>>>		_items_by_type_and_sub = null;
	
	public CafeMenu()
	{
		_items = new ArrayList<Item>();
		
		_items_by_type = new HashMap<Integer, ArrayList<Item>>();
		_items_by_type_and_sub = new HashMap<Integer, Map<Integer, ArrayList<Item>>>();
		
		if (_items != null)
		{
			//sort by id
			sortItems();
			
			//rearrange to type and sub type
			rearrangeItems();
		}
	}
	
	public CafeMenu(String json_data)
	{
	
	}
	
	public void init(String json_data)
	{
	
	}
	
	public int getTotalItems()
	{
		return _items.size();
	}
	
	public Item getItem(int id)
	{
		for (Item item : _items)
		{
			if (item._id == id)
			{
				return item;
			}
		}
		
		return null;
	}
	
	public ArrayList<Item> getItems()
	{
		return _items;
	}
	
	public ArrayList<Item> getItemsByType(Integer type)
	{
		return _items_by_type.get(type);
	}
	
	public ArrayList<Item> getItemsByTypeAndSub(Integer type, Integer sub_type)
	{
		Map<Integer, ArrayList<Item>> items_by_type_map = _items_by_type_and_sub.get(type);
		
		if (items_by_type_map != null)
		{
			return items_by_type_map.get(sub_type);
		}
		
		return null;
	}
	
	private void sortItems()
	{
	
	}
	
	private void rearrangeItems()
	{
		Integer type = null;
		Integer sub_type = null;
		
		ArrayList<Item> items_by_type_list = null;
		Map<Integer, ArrayList<Item>> items_by_type_map = null;
		
		for (Item item : _items)
		{
			type = Integer.valueOf(item._type);
			sub_type = Integer.valueOf(item._sub_type);
			
			//arrange type
			items_by_type_list = _items_by_type.get(type);
			
			if (items_by_type_list == null)
			{
				items_by_type_list = new ArrayList<Item>();
				items_by_type_list.add(item);
				
				_items_by_type.put(type, items_by_type_list);
			}
			else
			{
				items_by_type_list.add(item);
			}
			
			
			//arrange type and sub type
			items_by_type_map = _items_by_type_and_sub.get(type);
			
			if (items_by_type_map == null)
			{
				items_by_type_map = new HashMap<Integer, ArrayList<Item>>();
				items_by_type_list = new ArrayList<Item>();
				
				items_by_type_list.add(item);
				items_by_type_map.put(sub_type, items_by_type_list);
				
				_items_by_type_and_sub.put(type, items_by_type_map);
			}
			else
			{
				items_by_type_list = items_by_type_map.get(sub_type);
				
				if (items_by_type_list == null)
				{
					items_by_type_list = new ArrayList<Item>();
					items_by_type_list.add(item);
					
					items_by_type_map.put(sub_type, items_by_type_list);
				}
				else
				{
					items_by_type_list.add(item);
				}
			}
		}
	}
	
}