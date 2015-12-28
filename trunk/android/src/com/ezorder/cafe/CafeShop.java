package com.ezorder.cafe;

public class CafeShop
{
	static long				_server_time;
	static long				_local_time;
	
	public String			_name = "Highland Coffee";
	
	public CafeTable[]		_tables = null;
	
	public CafeShop(long server_time, int num_tables)
	{
		setTime(server_time);
		
		_tables = new CafeTable[num_tables];
		
		for (int i = 0; i < num_tables; i++)
		{
			int id = i + 1;
			_tables[i] = new CafeTable(_name, id, "");
		}
	}
	
	public void init(String json_data)
	{
		//cheat
		_tables[3]._is_empty = false;
		
		_tables[8]._is_empty = false;
		
		_tables[35]._is_empty = false;
		//end cheat
	}

	public static void setTime(long server_time)
	{
		_server_time = server_time;
		_local_time = System.currentTimeMillis();
	}
	
	public static long getCurrentTime()
	{
		return _server_time + (System.currentTimeMillis() - _local_time);
	}
}