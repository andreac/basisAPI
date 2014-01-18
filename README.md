basisAPI
========

Simple API for myBasis http://mybasis.com/ 
<h4>NOT OFFICIAL</h4>

This is simple API for retrieve data about your basis!!

you need Only your username and password, IT DO NOT KEEP TRACE OF YOUR PERSONAL INFORMATIONS (at the end of request every cookie and personal informations will be delete)


USAGE
=====

Every request it's made with post request. In post request you have to specify username and password

<h2>List of POST parameters</h2>

* username : specify your username [string]
* password : your password [string]
* start_date : start date (YYYY-MM-dd) [string]
* end_date : end date (if you want only one day start_date and end_date keep same value) (YYYY-MM-dd) [string]
* interval : Interval of time (in seconds) [Int]
* steps : false or true if you want it [string]
* calories : false or true if you want it [string]
* heartrate : false or true if you want it [string]
* gsr : false or true if you want it (galvanic skin response)[string]
* skin_temp : false or true if you want it [string]
* bodystates : false or true if you want it [string]


EXAMPLE
=======
Async task to retrieve info
```java
class MyAsyncTask extends AsyncTask<String, Integer, String> {
	static InputStream is = null;
	@Override
	protected String doInBackground(String... params) {
		// TODO Auto-generated method stub
		Log.d("PARAM", params[0]+ " " + params[1]);
		return postData(params[0], params[1]);
	}

	protected void onPostExecute(String result) {
		
		try {
			JSONObject json = new JSONObject(result);
			Log.d("JSON-RESULT", json.getString("status"));
			JSONObject metrics = json.optJSONObject("metrics");
			Log.d("JSON-RESULT", metrics.getString("heartrate"));
			
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

//	protected void onProgressUpdate(Integer... progress) {
//		pb.setProgress(progress[0]);
//	}

	public String postData(String user, String pass) {
		// Create a new HttpClient and Post Header
		HttpClient httpclient = new DefaultHttpClient();
		HttpPost httppost = new HttpPost("http://<YOUR_DOMAIN>/loginMyBasis.php");

		try {
			// Add your data
			List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>();
			nameValuePairs.add(new BasicNameValuePair("username",user));
			nameValuePairs.add(new BasicNameValuePair("password",pass));
			nameValuePairs.add(new BasicNameValuePair("start_date","2014-01-18"));
			nameValuePairs.add(new BasicNameValuePair("end_date","2014-01-18"));
			nameValuePairs.add(new BasicNameValuePair("heartrate","true"));
			nameValuePairs.add(new BasicNameValuePair("gsr","true"));
			httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));

			// Execute HTTP Post Request
			HttpResponse response = httpclient.execute(httppost);
			HttpEntity httpEntity = response.getEntity();
			is = httpEntity.getContent();
			
		} catch (ClientProtocolException e) {
			// TODO Auto-generated catch block
		} catch (IOException e) {
			// TODO Auto-generated catch block
		}
		try {

			BufferedReader reader = new BufferedReader(new InputStreamReader(
					is, "iso-8859-1"), 8);
			StringBuilder sb = new StringBuilder();
			String line = null;
			while ((line = reader.readLine()) != null) {
				sb.append(line + "\n");
			}
			is.close();
			Log.d("RESULT", sb.toString());
			return sb.toString();
		} catch (Exception e) {
			Log.e("Buffer Error", "Error converting result " + e.toString());
		}
		return null;
	}

}
```
start task
```java
new MyAsyncTask().execute(new String[]{"username", "password"});
```
OTHER
=====

If you don't have a webserver to put script file you can use mine
```
http://www.andreacappellotto.com/basis/loginMyBasis.php
```

Special Thanks to:
* Maicol Zenatti http://it.linkedin.com/in/maicolzenatti


Enjoy it !!!


