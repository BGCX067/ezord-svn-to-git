package com.ezorder.util;

import java.net.*;
import java.io.*;
import java.lang.ref.*;
import java.security.*;
import javax.net.ssl.*;
import android.os.*;
import android.util.JsonReader;


public class AsyncConnector
{
	public static final int		_HTTP_GET = 0;
	public static final int		_HTTP_POST = 1;
	
	public String					_server_address;
	private AsyncConnectorTask		_connector;
	
	public AsyncConnector(String server_address)
	{
		_server_address = server_address;
		_connector = new AsyncConnectorTask(this);
	}
	
	public void sendGetRequest(String url)
	{
		String full_url = _server_address + url;
		_connector.setMethod(_HTTP_GET);
		
		_connector.execute(full_url);
	}
	
	public void sendPostRequest(String url, byte[] post_data)	//post data != null
	{
		String full_url = _server_address + url;
		_connector.setMethod(_HTTP_POST);
		_connector.setPostData(post_data);
		
		_connector.execute(full_url);
	}

	public void receivedResponse(byte[] response_data)
	{
		
	}
	
	public int connect()
	{
		return 0;
	}
	
	public void disconnect()
	{
	
	}
}

class AsyncConnectorTask extends AsyncTask<String, Void, byte[]>
{
	private final WeakReference<AsyncConnector>			_parent;
	
	private int											_request_method = 0;//GET:0 or POST:1
	private byte[]										_post_data = null;
	
	public AsyncConnectorTask(AsyncConnector parent)
	{
		_parent = new WeakReference<AsyncConnector>(parent);
	}
	
	public void setMethod(int request_method)
	{
		_request_method = request_method;
	}
	
	public void setPostData(byte[] post_data)
	{
		_post_data = post_data;
	}
	
	@Override
    protected byte[] doInBackground(String... params)
	{
		HttpURLConnection url_conn = null;
		InputStream is = null;
		
		try 
		{
			URL url  = new URL(params[0]);
			
			//open connection
			String protocol = url.getProtocol();
			if (protocol.equals("https"))
			{
				//KeyStore trust_keys = KeyStore.getInstance(KeyStore.getDefaultType());
			
				TrustManagerFactory tmf = TrustManagerFactory.getInstance("X509");
				//tmf.init(trust_keys);

				SSLContext context = SSLContext.getInstance("TLS");
				context.init(null, tmf.getTrustManagers(), new SecureRandom());

				HttpsURLConnection secure_url_conn = (HttpsURLConnection) url.openConnection();
				secure_url_conn.setSSLSocketFactory(context.getSocketFactory());
				
				url_conn = secure_url_conn;
			}
			else if (protocol.equals("http"))
			{
				url_conn = (HttpURLConnection) url.openConnection();
			}
			else
			{
				//don't support protocol
				return null;
			}
		
			if (_request_method == AsyncConnector._HTTP_POST && _post_data != null)
			{
				//post data to server
				url_conn.setDoOutput(true);
				url_conn.setFixedLengthStreamingMode(_post_data.length);
				
				OutputStream out = url_conn.getOutputStream();
				out.write(_post_data);
			}
			
			//get reponse
			is = new BufferedInputStream(url_conn.getInputStream());
			
			byte[] buffer = new byte[1024];
			ByteArrayOutputStream baos = new ByteArrayOutputStream();
			
			int len = 0;
			do
			{
				len = is.read(buffer);
				
				if (len > 0)
				{
					baos.write(buffer, 0, len);
				}
			}
			while (len != -1);
			
			return baos.toByteArray();
		}
		catch (Exception ex)
		{
			//log
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
    protected void onPostExecute(byte[] result)
	{
        AsyncConnector parent = _parent.get();
		
		//call response handler
		parent.receivedResponse(result);
    }
}