package com.ezorder;

import java.lang.ref.*;

import android.app.Activity;
import android.os.*;
import android.view.*;
import android.view.View.OnClickListener;
import android.widget.*;
import android.widget.AdapterView.*;
import android.content.Context;

import com.ezorder.cafe.*;


public class EzOrderActivity extends Activity
{
	EditText							_username;
	EditText							_password;
	
	public View[]						_views = null;
	public boolean						_load_all_views_done = false;
	private int							_current_view = 0;
	
	private CafeShop					_cafe_shop = null;
	private CafeMenu					_menu = null;
	private Order						_current_order = null;
	
    /** Called when the activity is first created. */
    @Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
		
        setContentView(R.layout.login);
		
		_username = (EditText) findViewById(R.id.username);
		_password = (EditText) findViewById(R.id.password);
		
		((Button) findViewById(R.id.button_login)).setOnClickListener(_login_button_listener);
		
		//start new thread load all view
		//LoadViewResourceTask load_res_task = new LoadViewResourceTask(this);
		//load_res_task.execute("");
    }
	
	 /**
     * Called when the activity is about to start interacting with the user.
     */
    @Override
    protected void onResume() 
	{
        super.onResume();
    }
	
	private void login()
	{
		onLoginComplete(null);
	}
	
	private void onLoginComplete(byte[] data)
	{
		/*
		if (login_fail)
		{
			Toast.makeText(this, "Login fail" , Toast.LENGTH_SHORT).show();
			return;
		}
		*/
		
		//login ok
		init("");
		
		//change view
		//if (_load_all_views_done)
		{
			setContentView(R.layout.cafe);
			
			GridView gridview = (GridView) findViewById(R.id.cafegridview);
			gridview.setAdapter(new ImageAdapter(EzOrderActivity.this, _cafe_shop._tables));

			gridview.setOnItemClickListener(new OnItemClickListener() 
												{
													public void onItemClick(AdapterView<?> parent, View v, int position, long id) 
													{
														Toast.makeText(EzOrderActivity.this, "" + id, Toast.LENGTH_SHORT).show();
													}
												}
											);
		}
			
		
	}
	
	private void init(String json_data)
	{
		_cafe_shop = new CafeShop(0, 40);
		_cafe_shop.init("");
		
		_menu = new CafeMenu();
		_menu.init("");
	}
	
	
	
	 /**
     * A call-back for when the user presses the back button.
     */
    OnClickListener _login_button_listener = new OnClickListener() 
	{
        public void onClick(View v) 
		{
            //call login function
			login();
			
			//show login anim
			
        }
    };
}

class ImageAdapter extends BaseAdapter 
{
    private Context _context;
	private CafeTable[]	_tables;

    public ImageAdapter(Context c, CafeTable[] tables) 
	{
        _context = c;
		_tables = tables;
    }

    public int getCount() 
	{
        return _tables.length;
    }

    public Object getItem(int position) 
	{
        return _tables[position];
    }

    public long getItemId(int position) 
	{
        return _tables[position]._id;
    }

    // create a new ImageView for each item referenced by the Adapter
    public View getView(int position, View convertView, ViewGroup parent) 
	{
        ImageView _image_view;
		
        if (convertView == null) 
		{  
			// if it's not recycled, initialize some attributes
            _image_view = new ImageView(_context);
            _image_view.setLayoutParams(new GridView.LayoutParams(80, 80));
            _image_view.setScaleType(ImageView.ScaleType.CENTER_CROP);
            _image_view.setPadding(8, 8, 8, 8);
        } 
		else 
		{
            _image_view = (ImageView) convertView;
        }

		if (_tables[position]._is_empty)
		{
			_image_view.setImageResource(R.drawable.table_empty);
		}
		else
		{
			_image_view.setImageResource(R.drawable.table_full);
		}
		
        return _image_view;
    }
}

class LoadViewResourceTask extends AsyncTask<String, Void, String>
{
	private final WeakReference<EzOrderActivity> _parent;
	
	public LoadViewResourceTask(EzOrderActivity parent)
	{
		_parent = new WeakReference<EzOrderActivity>(parent);
	}
	
	@Override
    protected String doInBackground(String... params) 
	{
		//call load view
		EzOrderActivity parent = _parent.get();
		
		try
		{
			parent._views[0] = View.inflate(parent, R.layout.cafe, null);
			parent._views[1] = View.inflate(parent, R.layout.order, null);
			parent._views[2] = View.inflate(parent, R.layout.menu, null);
		}
		catch (Exception ex)
		{
			Toast.makeText(parent, ex.toString(), Toast.LENGTH_SHORT).show();
		}
		
		return "";
    }

	@Override
    protected void onPostExecute(String result)
	{
        EzOrderActivity parent = _parent.get();
		
		parent._load_all_views_done = true;
    }
}
