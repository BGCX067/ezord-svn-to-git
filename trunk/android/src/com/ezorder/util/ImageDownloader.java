package com.ezorder.util;

import java.net.*;
import java.io.*;
import java.lang.ref.*;
import javax.net.ssl.*;
import android.os.*;
import android.graphics.*;



public class ImageDownloader
{
	public ImageDownloader()
	{
	
	}
}

class ImageDownloadTask extends AsyncTask<String, Void, Bitmap>
{
	public ImageDownloadTask()
	{
	
	}
	
	@Override
    protected Bitmap doInBackground(String... params) 
	{
		HttpURLConnection url_conn = null;
		InputStream is = null;
		
		try
		{
			URL url  = new URL(params[0]);
			
			url_conn = (HttpURLConnection) url.openConnection();
			
			//get reponse
			is = new BufferedInputStream(url_conn.getInputStream());
			
			//decode and return bitmap
			Bitmap bmp = BitmapFactory.decodeStream(is);
			
			return bmp;
		}
		catch (Exception ex)
		{
			
		}
		finally
		{
			/*
			if (is != null)
			{
				is.close();
			}
			*/
			
			if (url_conn != null)
			{
				url_conn.disconnect();
			}
		}
		
        return null;
    }

	@Override
    protected void onPostExecute(Bitmap img)
	{
        
    }

}